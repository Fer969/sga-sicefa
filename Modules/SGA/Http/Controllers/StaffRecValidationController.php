<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Modules\SGA\Entities\AttendanceRegistration;
use Modules\SGA\Entities\Asistance;
use Modules\SGA\Http\Requests\AttendanceRegistrationRequest;
use Carbon\Carbon;

/**
 * Controlador para la validación y registro de asistencias en el Sistema de Gestión Académica (SGA)
 * 
 * Este controlador es el núcleo del sistema de gestión de asistencias, permitiendo al personal
 * administrativo (staff) del SENA:
 * - Registrar asistencias de aprendices en tiempo real
 * - Validar y gestionar registros existentes
 * - Obtener estadísticas de asistencia del día
 * - Realizar búsquedas rápidas de aprendices
 * - Exportar datos de asistencia
 * - Gestionar el ciclo completo de registro de asistencia
 * 
 * Funcionalidades principales:
 * - Dashboard de validación con estadísticas en tiempo real
 * - Registro de asistencia por número de documento
 * - Validación de duplicados y consistencia de datos
 * - Gestión de estados de asistencia (registrado, asistió, no asistió, tardanza)
 * - Sistema de auditoría completo con logs de todas las operaciones
 * 
 * @package Modules\SGA\Http\Controllers
 * @author SENA - Sistema de Gestión Académica
 * @version 1.0
 */
class StaffRecValidationController extends Controller
{
    /**
     * Constructor del controlador
     * 
     * Aplica middleware de autenticación para asegurar que solo
     * usuarios autenticados puedan acceder a las funcionalidades
     * del sistema de gestión de asistencias.
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:sga.staff'); // Eliminado porque no existe
    }
    
    /**
     * Muestra la interfaz principal de validación de asistencias
     * 
     * Esta es la función central del sistema que proporciona:
     * - Vista principal para el registro de asistencias
     * - Estadísticas en tiempo real del día actual
     * - Lista de registros de asistencia del día
     * - Interfaz para nuevas validaciones
     * 
     * El sistema obtiene automáticamente:
     * - Estadísticas del día (total, asistencias, ausencias, tardanzas)
     * - Lista de aprendices que ya han sido registrados
     * - Información detallada de cada registro
     * 
     * @return \Illuminate\View\View Vista principal de validación de asistencias
     */
    public function index()
    {
        // Obtener estadísticas del día para mostrar en el dashboard
        $stats = $this->getTodayStats();
        
        // Obtener asistencias del día actual usando la nueva estructura
        $attendances = AttendanceRegistration::with(['apprentice.person', 'apprentice.course.program', 'asistance'])
            ->whereHas('asistance', function ($q) { $q->whereDate('date', now()->toDateString()); })
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($attendance) {
                return (object) [
                    'id' => $attendance->id,
                    'document_number' => $attendance->apprentice->person->document_number,
                    'first_name' => $attendance->apprentice->person->first_name,
                    'first_last_name' => $attendance->apprentice->person->first_last_name,
                    'second_last_name' => $attendance->apprentice->person->second_last_name,
                    'course_code' => $attendance->apprentice->course->code,
                    'program_name' => $attendance->apprentice->course->program->name,
                    'attendance_time' => optional($attendance->created_at)->format('H:i:s'),
                    'status' => $attendance->asistencia,
                    'porcentaje' => null,
                    'notes' => null,
                ];
            });

        $data = [
            'lunchRegistrations' => $attendances, // Para reutilizar la vista existente
            'titlePage' => 'Validación de Asistencia',
            'titleView' => 'Validación de Asistencia',
            'stats' => $stats,
        ];
        return view('sga::staff.rec-validation', $data);
    }

    /**
     * Obtiene estadísticas detalladas del día actual para el dashboard
     * 
     * Esta función implementa un sistema de caché inteligente que:
     * - Almacena estadísticas por 5 minutos para optimizar performance
     * - Calcula métricas en tiempo real del día actual
     * - Proporciona información para toma de decisiones operativas
     * 
     * Las estadísticas incluyen:
     * - Total de registros del día
     * - Conteo por estado (asistió, no asistió, tardanza, cancelado)
     * - Tasa de asistencia calculada automáticamente
     * 
     * @return array Array asociativo con todas las estadísticas del día
     */
    private function getTodayStats()
    {
        // Clave de caché única para el día actual
        $cacheKey = 'attendance_stats_' . now()->toDateString();
        
        // Implementar caché con expiración de 5 minutos para optimizar consultas
        return Cache::remember($cacheKey, 300, function () {
            // Usar el método del modelo que ya mapea a la nueva estructura
            $stats = AttendanceRegistration::getTodayStats();
            // Compatibilidad con claves antiguas usadas en vistas
            $stats['total_registrations'] = $stats['total'] ?? 0;
            
            // Calcular tasa de asistencia como porcentaje
            $total = $stats['total'] ?? 0;
            $attended = $stats['attended'] ?? 0;
            $stats['attendance_rate'] = $total > 0
                ? round(($attended / $total) * 100, 1)
                : 0;
                
            return $stats;
        });
    }

    /**
     * Registra una nueva asistencia de un aprendiz en el sistema
     * 
     * Esta es la función principal del sistema que permite:
     * - Registrar asistencia por número de documento
     * - Validar que el aprendiz existe y está activo
     * - Prevenir registros duplicados del mismo día
     * - Asignar estado inicial de 'registered'
     * - Registrar auditoría completa de la operación
     * 
     * El sistema valida:
     * - Existencia del número de documento
     * - Estado activo del aprendiz
     * - Ausencia de registros previos del día
     * - Datos obligatorios del formulario
     * 
     * @param Request $request Solicitud HTTP con datos de la asistencia
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con resultado de la operación
     */
    public function registerAttendance(Request $request)
    {
        $documentNumber = $request->input('documentNumber');
        $status = $request->input('status', 'asistio'); // Por defecto, asistió

        // Validaciones básicas de seguridad y consistencia
        if (empty($documentNumber)) {
            return response()->json(['error' => 'El número de documento es requerido.'], 400);
        }

        // Validar formato del número de documento (CC, CE, TI)
        if (strlen($documentNumber) < 8 || strlen($documentNumber) > 10) {
            return response()->json(['error' => 'El número de documento debe tener entre 8 y 10 dígitos.'], 400);
        }

        // Mapear los valores del frontend a los valores de la base de datos
        // Esto asegura consistencia entre la interfaz de usuario y el almacenamiento
        $statusMap = [
            'asistio' => 'si',
            'no_asistio' => 'no',
        ];
        $statusDB = $statusMap[$status] ?? 'si';

        try {
            // Buscar el aprendiz activo por número de documento
            // La consulta incluye join con la tabla people para obtener información personal
            $apprentice = DB::table('apprentices')
                ->select('apprentices.id as apprentice_id', 'people.first_name', 'people.first_last_name', 'people.second_last_name', 'people.document_number')
                ->join('people', 'apprentices.person_id', '=', 'people.id')
                ->where('people.document_number', $documentNumber)
                ->whereNull('apprentices.deleted_at')
                ->first();

            // Validar que el aprendiz existe y está activo en el sistema
            if (!$apprentice) {
                return response()->json(['error' => 'No se encontró un aprendiz activo con ese número de documento.'], 404);
            }

            // Obtener/crear cabecera de asistencia del día
            $asistance = Asistance::firstOrCreate(
                ['date' => now()->toDateString()],
                ['type_asistance' => 'Escolar', 'description' => 'Generado automáticamente', 'guardado' => 1]
            );

            // Verificar si ya existe un registro para el aprendiz en la fecha
            $existingRecord = AttendanceRegistration::where('apprentice_id', $apprentice->apprentice_id)
                ->where('asistance_id', $asistance->id)
                ->first();

            if ($existingRecord) {
                return response()->json(['error' => 'El aprendiz ya tiene una asistencia registrada para hoy.'], 409);
            }

            // Crear el registro de asistencia usando el modelo Eloquent
            $attendance = new AttendanceRegistration();
            $attendance->apprentice_id = $apprentice->apprentice_id;
            $attendance->asistance_id = $asistance->id;
            $attendance->asistencia = $statusDB;
            $attendance->save();

            // Limpiar cache de estadísticas
            Cache::forget('attendance_stats_' . now()->toDateString());

            // Log de auditoría
            Log::info('Asistencia registrada', [
                'apprentice_id' => $apprentice->apprentice_id,
                'document_number' => $documentNumber,
                'registered_by' => auth()->id(),
                'status' => $statusDB,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => 'Asistencia registrada correctamente para ' . $apprentice->first_name . ' ' . $apprentice->first_last_name . ' ' . $apprentice->second_last_name
            ]);

        } catch (\Exception $e) {
            Log::error('Error al registrar asistencia: ' . $e->getMessage(), [
                'document_number' => $documentNumber,
                'user_id' => auth()->id()
            ]);
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Cancela un registro de asistencia existente
     * 
     * Esta función permite al staff cancelar registros de asistencia
     * que han sido creados por error o que necesitan ser anulados.
     * 
     * Restricciones de seguridad:
     * - Solo se pueden cancelar registros del día actual
     * - No se pueden cancelar registros ya cancelados
     * - Se requiere autenticación del usuario
     * 
     * @param Request $request Solicitud HTTP con ID del registro
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con resultado de la operación
     */
    public function cancelAttendance(Request $request)
    {
        $registrationId = $request->input('registrationId');
        
        // Validar que se proporcione un ID de registro válido
        if (empty($registrationId)) {
            return response()->json(['error' => 'El ID del registro es requerido.'], 400);
        }

        try {
            $registration = AttendanceRegistration::with('asistance')->find($registrationId);
            
            if (!$registration) {
                return response()->json(['error' => 'Registro de asistencia no encontrado.'], 404);
            }

            // Verificar que el registro sea del día actual
            if (optional($registration->asistance)->date != now()->toDateString()) {
                return response()->json(['error' => 'Solo se pueden cancelar registros del día actual.'], 403);
            }

            // Verificar que el registro no esté ya cancelado
            if ($registration->status === 'cancelled') {
                return response()->json(['error' => 'El registro ya está cancelado.'], 409);
            }

            // En el nuevo esquema no hay estado cancelado: se elimina (soft delete)
            $registration->delete();

            // Limpiar cache de estadísticas
            Cache::forget('attendance_stats_' . now()->toDateString());

            // Log de auditoría
            Log::info('Asistencia cancelada', [
                'registration_id' => $registrationId,
                'apprentice_id' => $registration->apprentice_id,
                'cancelled_by' => auth()->id(),
                'timestamp' => now()
            ]);

            return response()->json(['success' => 'Registro de asistencia cancelado correctamente.']);

        } catch (\Exception $e) {
            Log::error('Error al cancelar registro: ' . $e->getMessage(), [
                'registration_id' => $registrationId,
                'user_id' => auth()->id()
            ]);
            return response()->json(['error' => 'Error interno del servidor al procesar la solicitud.'], 500);
        }
    }

    /**
     * Obtiene todas las asistencias registradas del día actual
     * 
     * Esta función proporciona una vista completa de todos los registros
     * de asistencia del día, incluyendo información detallada de:
     * - Datos personales del aprendiz
     * - Información del curso y programa
     * - Estado y hora de registro
     * - Notas adicionales
     * 
     * Los datos se ordenan por hora de registro (más reciente primero)
     * y se optimizan con eager loading para evitar consultas N+1.
     * 
     * @return \Illuminate\Http\JsonResponse Lista de asistencias del día en formato JSON
     */
    public function getTodayAttendances()
    {
        try {
            // Consulta optimizada con relaciones para evitar múltiples queries
            $attendances = AttendanceRegistration::with(['apprentice.person', 'apprentice.course.program', 'asistance'])
                ->whereHas('asistance', function ($q) { $q->whereDate('date', now()->toDateString()); })
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($attendance) {
                    return [
                        'id' => $attendance->id,
                        'document_number' => $attendance->apprentice->person->document_number,
                        'first_name' => $attendance->apprentice->person->first_name,
                        'first_last_name' => $attendance->apprentice->person->first_last_name,
                        'second_last_name' => $attendance->apprentice->person->second_last_name,
                        'course_code' => $attendance->apprentice->course->code,
                        'program_name' => $attendance->apprentice->course->program->name,
                        'attendance_time' => optional($attendance->created_at)->format('H:i:s'),
                        'status' => $attendance->asistencia,
                        'porcentaje' => null,
                        'notes' => null,
                    ];
                });

        return response()->json(['registrations' => $attendances]);
        } catch (\Exception $e) {
            Log::error('Error al obtener asistencias del día: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los registros de asistencia.'], 500);
        }
    }

    /**
     * Obtener información del aprendiz y porcentaje de alimentación por documento
     */
    public function getApprenticeInfo(Request $request)
    {
        $documentNumber = $request->input('documentNumber');
        
        // Validación del número de documento
        if (empty($documentNumber)) {
            return response()->json(['error' => 'El número de documento es requerido.'], 400);
        }

        if (strlen($documentNumber) < 8 || strlen($documentNumber) > 10) {
            return response()->json(['error' => 'El número de documento debe tener entre 8 y 10 dígitos.'], 400);
        }

        try {
            // Buscar aprendiz y datos personales
            $apprentice = DB::table('apprentices')
                ->select(
                    'apprentices.id as apprentice_id',
                    'people.first_name',
                    'people.first_last_name',
                    'people.second_last_name',
                    'people.document_number',
                    'courses.code as course_code',
                    'programs.name as program_name'
                )
                ->join('people', 'apprentices.person_id', '=', 'people.id')
                ->join('courses', 'apprentices.course_id', '=', 'courses.id')
                ->join('programs', 'courses.program_id', '=', 'programs.id')
                ->where('people.document_number', $documentNumber)
                ->whereNull('apprentices.deleted_at')
                ->first();

            if (!$apprentice) {
                return response()->json(['error' => 'No se encontró un aprendiz activo con ese número de documento.'], 404);
            }

            // Verificar si ya tiene asistencia registrada hoy
            $existingAttendance = AttendanceRegistration::where('apprentice_id', $apprentice->apprentice_id)
                ->whereHas('asistance', function ($q) { $q->whereDate('date', now()->toDateString()); })
                ->first();

            $hasAttendanceToday = $existingAttendance ? true : false;

            // Buscar porcentaje de alimentación vigente (opcional)
            // $benefit = DB::table('postulations_benefits')
            //     ->select('benefits.porcentege')
            //     ->join('postulations', 'postulations_benefits.postulation_id', '=', 'postulations.id')
            //     ->join('benefits', 'postulations_benefits.benefit_id', '=', 'benefits.id')
            //     ->join('apprentices', 'postulations.apprentice_id', '=', 'apprentices.id')
            //     ->join('people', 'apprentices.person_id', '=', 'people.id')
            //     ->where('people.document_number', $documentNumber)
            //     ->where('postulations_benefits.state', 'Beneficiario')
            //     ->where('benefits.name', 'Alimentacion')
            //     ->orderByDesc('postulations_benefits.created_at')
            //     ->first();

            // $porcentaje = $benefit ? $benefit->porcentege : null;
            $porcentaje = null;

            return response()->json([
                'apprentice' => $apprentice,
                'porcentaje_alimentacion' => $porcentaje,
                'has_attendance_today' => $hasAttendanceToday,
                'existing_attendance' => $existingAttendance
            ]);
        } catch (\Exception $e) {
            // Mostrar el error exacto en la respuesta para depuración
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene estadísticas del día actual para el dashboard
     * 
     * Esta función proporciona acceso público a las estadísticas
     * de asistencia del día, incluyendo:
     * - Total de registros del día
     * - Conteo por estado (asistió, no asistió, tardanza, cancelado)
     * - Tasa de asistencia calculada
     * 
     * Se utiliza para:
     * - Actualizar el dashboard en tiempo real
     * - Mostrar métricas a los usuarios
     * - Generar reportes operativos
     * 
     * @return \Illuminate\Http\JsonResponse Estadísticas del día en formato JSON
     */
    public function getStats()
    {
        try {
            // Obtener estadísticas del día usando la función privada con caché
            $stats = $this->getTodayStats();
            return response()->json($stats);
        } catch (\Exception $e) {
            // Registrar error en logs para auditoría
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener estadísticas.'], 500);
        }
    }

    /**
     * Realiza búsqueda rápida de aprendices por nombre o documento
     * 
     * Esta función permite al staff buscar aprendices de forma eficiente
     * para agilizar el proceso de registro de asistencia.
     * 
     * Características de la búsqueda:
     * - Búsqueda por nombre completo o parcial
     * - Búsqueda por número de documento
     * - Mínimo 3 caracteres para activar la búsqueda
     * - Resultados limitados para optimizar performance
     * - Incluye información básica del aprendiz
     * 
     * @param Request $request Solicitud HTTP con término de búsqueda
     * @return \Illuminate\Http\JsonResponse Resultados de la búsqueda en formato JSON
     */
    public function quickSearch(Request $request)
    {
        $query = $request->input('query');
        
        // Validar que la búsqueda tenga al menos 3 caracteres para evitar consultas muy amplias
        if (empty($query) || strlen($query) < 3) {
            return response()->json(['error' => 'La búsqueda debe tener al menos 3 caracteres.'], 400);
        }

        try {
            $apprentices = DB::table('apprentices')
                ->select(
                    'apprentices.id as apprentice_id',
                    'people.first_name',
                    'people.first_last_name',
                    'people.second_last_name',
                    'people.document_number',
                    'courses.code as course_code',
                    'programs.name as program_name'
                )
                ->join('people', 'apprentices.person_id', '=', 'people.id')
                ->join('courses', 'apprentices.course_id', '=', 'courses.id')
                ->join('programs', 'courses.program_id', '=', 'programs.id')
                ->where(function($q) use ($query) {
                    $q->where('people.document_number', 'LIKE', "%{$query}%")
                      ->orWhere('people.first_name', 'LIKE', "%{$query}%")
                      ->orWhere('people.first_last_name', 'LIKE', "%{$query}%")
                      ->orWhere('people.second_last_name', 'LIKE', "%{$query}%");
                })
                ->whereNull('apprentices.deleted_at')
                ->limit(10)
                ->get();

            return response()->json(['apprentices' => $apprentices]);
        } catch (\Exception $e) {
            Log::error('Error en búsqueda rápida: ' . $e->getMessage());
            return response()->json(['error' => 'Error en la búsqueda.'], 500);
        }
    }

    /**
     * Exporta los registros de asistencia del día en diferentes formatos
     * 
     * Esta función permite al staff exportar todos los registros de asistencia
     * del día para análisis, reportes o respaldo de información.
     * 
     * Formatos soportados:
     * - Excel (.xlsx): Para análisis en hojas de cálculo
     * - PDF: Para presentaciones y archivo oficial
     * 
     * Características:
     * - Exportación de todos los registros del día especificado
     * - Incluye información completa del aprendiz y asistencia
     * - Formato profesional para uso institucional
     * - Manejo de errores con logs de auditoría
     * 
     * @param Request $request Solicitud HTTP con formato y fecha
     * @return \Illuminate\Http\JsonResponse Archivo exportado o datos en JSON
     */
    public function exportToday(Request $request)
    {
        try {
            // Obtener formato de exportación (Excel por defecto) y fecha
            $format = $request->input('format', 'excel');
            $date = $request->input('date', now()->toDateString());

            $attendances = AttendanceRegistration::with(['apprentice.person', 'apprentice.course.program'])
                ->whereDate('attendance_date', $date)
                ->orderBy('attendance_time', 'desc')
                ->get();

            if ($format === 'pdf') {
                return $this->exportToPDF($attendances, $date);
            } else {
                return $this->exportToExcel($attendances, $date);
            }
        } catch (\Exception $e) {
            Log::error('Error al exportar registros: ' . $e->getMessage());
            return response()->json(['error' => 'Error al exportar los registros.'], 500);
        }
    }

    /**
     * Exporta los registros de asistencia a formato Excel
     * 
     * Esta función privada prepara los datos para exportación a Excel,
     * transformando la información del modelo a un formato estructurado
     * adecuado para hojas de cálculo.
     * 
     * Los datos exportados incluyen:
     * - Información personal del aprendiz (documento, nombre completo)
     * - Datos académicos (código de curso, nombre del programa)
     * - Detalles de asistencia (hora, estado, porcentaje, notas)
     * 
     * @param Collection $attendances Colección de registros de asistencia
     * @param string $date Fecha de los registros para el nombre del archivo
     * @return \Illuminate\Http\JsonResponse Datos preparados para exportación
     */
    private function exportToExcel($attendances, $date)
    {
        // Preparar datos para exportación a Excel
        // Por ahora retornamos un JSON con los datos estructurados
        $data = $attendances->map(function ($attendance) {
            return [
                'document_number' => $attendance->apprentice->person->document_number,
                'full_name' => $attendance->apprentice->person->first_name . ' ' . 
                              $attendance->apprentice->person->first_last_name . ' ' . 
                              $attendance->apprentice->person->second_last_name,
                'course_code' => $attendance->apprentice->course->code,
                'program_name' => $attendance->apprentice->course->program->name,
                'attendance_time' => $attendance->attendance_time,
                'status' => $attendance->status,
                'porcentaje' => $attendance->porcentaje,
                'notes' => $attendance->notes
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => 'asistencias_' . $date . '.xlsx'
        ]);
    }

    /**
     * Exporta los registros de asistencia a formato PDF
     * 
     * Esta función privada prepara los datos para exportación a PDF,
     * transformando la información del modelo a un formato estructurado
     * adecuado para documentos oficiales.
     * 
     * Los datos exportados incluyen:
     * - Información personal del aprendiz (documento, nombre completo)
     * - Datos académicos (código de curso, nombre del programa)
     * - Detalles de asistencia (hora, estado, porcentaje, notas)
     * 
     * @param Collection $attendances Colección de registros de asistencia
     * @param string $date Fecha de los registros para el nombre del archivo
     * @return \Illuminate\Http\JsonResponse Datos preparados para exportación
     */
    private function exportToPDF($attendances, $date)
    {
        // Preparar datos para exportación a PDF
        // Por ahora retornamos un JSON con los datos estructurados
        $data = $attendances->map(function ($attendance) {
            return [
                'document_number' => $attendance->apprentice->person->document_number,
                'full_name' => $attendance->apprentice->person->first_name . ' ' . 
                              $attendance->apprentice->person->first_last_name . ' ' . 
                              $attendance->apprentice->person->second_last_name,
                'course_code' => $attendance->apprentice->course->code,
                'program_name' => $attendance->apprentice->course->program->name,
                'attendance_time' => $attendance->attendance_time,
                'status' => $attendance->status,
                'porcentaje' => $attendance->porcentaje,
                'notes' => $attendance->notes
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => 'asistencias_' . $date . '.pdf'
        ]);
    }

    /**
     * Método temporal para obtener números de documento (solo para desarrollo)
     */
    public function getDocumentNumbers(Request $request)
    {
        $type = $request->input('type', 'all');
        $limit = $request->input('limit', 10);

        try {
            switch ($type) {
                case 'available':
                    $documents = DB::table('people')
                        ->select(
                            'people.document_number',
                            'people.first_name',
                            'people.first_last_name',
                            'people.second_last_name',
                            'courses.code as course_code',
                            'programs.name as program_name'
                        )
                        ->join('apprentices', 'people.id', '=', 'apprentices.person_id')
                        ->join('courses', 'apprentices.course_id', '=', 'courses.id')
                        ->join('programs', 'courses.program_id', '=', 'programs.id')
                        ->whereNull('apprentices.deleted_at')
                        ->whereNotExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('attendance_registrations')
                                ->join('apprentices as a2', 'attendance_registrations.apprentice_id', '=', 'a2.id')
                                ->join('people as p2', 'a2.person_id', '=', 'p2.id')
                                ->whereRaw('p2.document_number = people.document_number')
                                ->whereDate('attendance_registrations.attendance_date', now()->toDateString())
                                ->where('attendance_registrations.status', '!=', 'cancelled');
                        })
                        ->orderBy('people.document_number')
                        ->limit($limit)
                        ->get();
                    break;

                case 'registered':
                    $documents = DB::table('people')
                        ->select(
                            'people.document_number',
                            'people.first_name',
                            'people.first_last_name',
                            'people.second_last_name',
                            'courses.code as course_code',
                            'programs.name as program_name',
                            'attendance_registrations.status',
                            'attendance_registrations.attendance_time'
                        )
                        ->join('apprentices', 'people.id', '=', 'apprentices.person_id')
                        ->join('courses', 'apprentices.course_id', '=', 'courses.id')
                        ->join('programs', 'courses.program_id', '=', 'programs.id')
                        ->join('attendance_registrations', 'apprentices.id', '=', 'attendance_registrations.apprentice_id')
                        ->whereNull('apprentices.deleted_at')
                        ->whereDate('attendance_registrations.attendance_date', now()->toDateString())
                        ->orderBy('attendance_registrations.attendance_time', 'desc')
                        ->limit($limit)
                        ->get();
                    break;

                default:
                    $documents = DB::table('people')
                        ->select(
                            'people.document_number',
                            'people.first_name',
                            'people.first_last_name',
                            'people.second_last_name',
                            'courses.code as course_code',
                            'programs.name as program_name'
                        )
                        ->join('apprentices', 'people.id', '=', 'apprentices.person_id')
                        ->join('courses', 'apprentices.course_id', '=', 'courses.id')
                        ->join('programs', 'courses.program_id', '=', 'programs.id')
                        ->whereNull('apprentices.deleted_at')
                        ->orderBy('people.document_number')
                        ->limit($limit)
                        ->get();
                    break;
            }

            return response()->json([
                'success' => true,
                'type' => $type,
                'count' => $documents->count(),
                'documents' => $documents,
                'numbers_only' => $documents->pluck('document_number')->toArray()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener números de documento: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los números de documento.'], 500);
        }
    }
}