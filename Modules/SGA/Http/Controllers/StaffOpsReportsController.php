<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SGA\Resources\views\exports\AttendanceRegistrationExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

/**
 * Controlador principal para el rol de Staff en el Sistema de Gestión Académica (SGA)
 * 
 * Este controlador maneja todas las funcionalidades disponibles para el personal administrativo
 * del SENA, incluyendo:
 * - Dashboard principal del staff
 * - Generación de reportes operativos
 * - Validación de registros de asistencia
 * - Gestión de perfiles de usuario
 * - Exportación de datos en Excel y PDF
 * - Estadísticas de asistencia
 * 
 * @package Modules\SGA\Http\Controllers
 * @author SENA - Sistema de Gestión Académica
 * @version 1.0
 */
class StaffOpsReportsController extends Controller
{
    /**
     * Muestra la página principal del dashboard para el personal administrativo
     * 
     * Esta vista proporciona acceso a todas las funcionalidades principales
     * del sistema SGA para el rol de staff, incluyendo:
     * - Resumen de actividades del día
     * - Acceso rápido a funciones principales
     * - Estadísticas básicas del sistema
     * 
     * @return \Illuminate\View\View Vista principal del dashboard
     */

    /**
     * Muestra la interfaz de reportes operativos para el personal administrativo
     * 
     * Permite al staff generar y visualizar diferentes tipos de reportes:
     * - Reportes diarios de asistencia
     * - Reportes semanales consolidados
     * - Reportes mensuales con estadísticas
     * - Exportación en múltiples formatos (Excel, PDF)
     * 
     * @return \Illuminate\View\View Vista de reportes operativos
     */
    public function index()
    {
        $titlePage = 'Reportes Operativos';
        $titleView = 'Reportes Operativos';

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView
        ];

        return view('sga::staff.ops-reports', $data);
    }

    /**
     * Muestra la interfaz de validación de registros de asistencia
     * 
     * Esta función permite al staff:
     * - Registrar asistencias de aprendices en tiempo real
     * - Validar registros existentes
     * - Gestionar estados de asistencia
     * - Ver estadísticas del día actual
     * 
     * @return \Illuminate\View\View Vista de validación de registros
     */

    /**
     * Muestra y permite editar el perfil del usuario autenticado
     * 
     * El staff puede:
     * - Ver su información personal actualizada
     * - Modificar datos de contacto
     * - Cambiar contraseña
     * - Actualizar preferencias del sistema
     * 
     * @return \Illuminate\View\View Vista de perfil del usuario
     */
    /**
     * Exporta el reporte de asistencia del día en formato Excel
     * 
     * Genera un archivo Excel con todos los registros de asistencia
     * del día especificado, incluyendo:
     * - Información del aprendiz (documento, nombre, programa)
     * - Detalles de asistencia (fecha, hora, estado)
     * - Estadísticas del día
     * - Formato profesional para presentaciones
     * 
     * @param Request $request Solicitud HTTP con la fecha deseada
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Archivo Excel descargable
     */
    public function exportAttendanceDay(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $export = new AttendanceRegistrationExport('day', $date);
        return Excel::download($export, 'asistencias_dia_' . $date . '.xlsx');
    }

    /**
     * Exporta el reporte de asistencia de la semana en formato Excel
     * 
     * Genera un archivo Excel consolidado con todos los registros
     * de asistencia del rango de fechas especificado, incluyendo:
     * - Resumen diario de asistencias
     * - Estadísticas semanales
     * - Comparativas entre días
     * - Análisis de tendencias
     * 
     * @param Request $request Solicitud HTTP con fechas de inicio y fin
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Archivo Excel descargable
     */
    public function exportAttendanceWeek(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $export = new AttendanceRegistrationExport('week', ['start' => $start, 'end' => $end]);
        return Excel::download($export, 'asistencias_semana_' . $start . '_' . $end . '.xlsx');
    }

    /**
     * Exporta el reporte de asistencia del mes en formato Excel
     * 
     * Genera un archivo Excel completo con todos los registros
     * de asistencia del mes especificado, incluyendo:
     * - Resumen semanal y diario
     * - Estadísticas mensuales detalladas
     * - Análisis de patrones de asistencia
     * - Comparativas con meses anteriores
     * 
     * @param Request $request Solicitud HTTP con mes y año
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse Archivo Excel descargable
     */
    public function exportAttendanceMonth(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $export = new AttendanceRegistrationExport('month', ['month' => $month, 'year' => $year]);
        return Excel::download($export, 'asistencias_mes_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.xlsx');
    }

    /**
     * Exporta el reporte de asistencia del día en formato PDF
     * 
     * Genera un documento PDF profesional con todos los registros
     * de asistencia del día, incluyendo:
     * - Encabezado institucional del SENA
     * - Tabla detallada de asistencias
     * - Estadísticas del día
     * - Formato oficial para archivo y presentación
     * 
     * @param Request $request Solicitud HTTP con la fecha deseada
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response PDF o error
     */
    public function exportAttendanceDayPDF(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $export = new AttendanceRegistrationExport('day', $date);
        $data = $export->collection();
        
        // Validar que hay datos para generar el reporte
        if ($data->isEmpty()) {
            return response()->json(['error' => 'No hay datos para la fecha seleccionada'], 404);
        }
        
        return $this->generatePDF($data, $export->getReportTitle(), $export->getGeneratedAt());
    }

    /**
     * Exporta el reporte de asistencia de la semana en formato PDF
     * 
     * Genera un documento PDF consolidado con todos los registros
     * de asistencia de la semana, incluyendo:
     * - Resumen semanal detallado
     * - Estadísticas comparativas
     * - Formato oficial institucional
     * 
     * @param Request $request Solicitud HTTP con fechas de inicio y fin
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response PDF o error
     */
    public function exportAttendanceWeekPDF(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $export = new AttendanceRegistrationExport('week', ['start' => $start, 'end' => $end]);
        $data = $export->collection();
        
        // Validar que hay datos para generar el reporte
        if ($data->isEmpty()) {
            return response()->json(['error' => 'No hay datos para el período seleccionado'], 404);
        }
        
        return $this->generatePDF($data, $export->getReportTitle(), $export->getGeneratedAt());
    }

    /**
     * Exporta el reporte de asistencia del mes en formato PDF
     * 
     * Genera un documento PDF completo con todos los registros
     * de asistencia del mes, incluyendo:
     * - Resumen mensual detallado
     * - Análisis de tendencias
     * - Estadísticas comparativas
     * - Formato oficial para archivo
     * 
     * @param Request $request Solicitud HTTP con mes y año
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response PDF o error
     */
    public function exportAttendanceMonthPDF(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $export = new AttendanceRegistrationExport('month', ['month' => $month, 'year' => $year]);
        $data = $export->collection();
        
        // Validar que hay datos para generar el reporte
        if ($data->isEmpty()) {
            return response()->json(['error' => 'No hay datos para el mes seleccionado'], 404);
        }
        
        return $this->generatePDF($data, $export->getReportTitle(), $export->getGeneratedAt());
    }

    /**
     * Obtiene estadísticas de asistencia para el dashboard del staff
     * 
     * Proporciona métricas en tiempo real sobre:
     * - Total de registros del día actual
     * - Registros de la semana actual
     * - Registros del mes actual
     * - Tasa de asistencia general
     * 
     * Estas estadísticas se utilizan para:
     * - Mostrar resúmenes en el dashboard
     * - Generar alertas de rendimiento
     * - Tomar decisiones operativas
     * 
     * @return \Illuminate\Http\JsonResponse Estadísticas en formato JSON
     */
    public function getStats()
    {
        try {
            $today = now()->toDateString();
            $startOfWeek = now()->startOfWeek()->toDateString();
            $endOfWeek = now()->endOfWeek()->toDateString();
            $startOfMonth = now()->startOfMonth()->toDateString();
            $endOfMonth = now()->endOfMonth()->toDateString();

            $stats = [
                'today' => \Modules\SGA\Entities\AttendanceRegistration::byDate($today)->count(),
                'week' => \Modules\SGA\Entities\AttendanceRegistration::byDateRange($startOfWeek, $endOfWeek)->count(),
                'month' => \Modules\SGA\Entities\AttendanceRegistration::byDateRange($startOfMonth, $endOfMonth)->count(),
                'attendance_rate' => \Modules\SGA\Entities\AttendanceRegistration::getAttendanceRate($today)
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener estadísticas'], 500);
        }
    }

    /**
     * Genera un documento PDF profesional usando la librería TCPDF
     * 
     * Esta función privada se encarga de:
     * - Configurar el documento PDF con estándares institucionales
     * - Aplicar formato y estilos corporativos del SENA
     * - Generar contenido HTML estructurado
     * - Manejar errores de generación de PDF
     * 
     * @param Collection $data Colección de datos de asistencia
     * @param string $title Título del reporte
     * @param Carbon $generatedAt Fecha y hora de generación
     * @return \Symfony\Component\HttpFoundation\Response PDF generado
     */
    private function generatePDF($data, $title, $generatedAt)
    {
        try {
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            
            // Configurar metadatos del documento PDF
            $pdf->SetCreator('SGA - Sistema de Gestión de Asistencias');
            $pdf->SetAuthor('SENA');
            $pdf->SetTitle($title);
            $pdf->SetSubject('Reporte de Asistencias');
            $pdf->SetKeywords('SENA, Asistencias, Reporte');
            
            // Configurar márgenes y formato del documento
            $pdf->SetMargins(15, 15, 15);
            $pdf->SetHeaderMargin(5);
            $pdf->SetFooterMargin(10);
            
            // Configurar salto automático de página
            $pdf->SetAutoPageBreak(TRUE, 25);
            
            // Configurar fuente y tamaño del texto
            $pdf->SetFont('helvetica', '', 10);
            
            // Agregar nueva página al documento
            $pdf->AddPage();
            
            // Generar contenido HTML estructurado para el PDF
            $html = $this->generateHTML($data, $title, $generatedAt);
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            return response($pdf->Output($title . '.pdf', 'S'))
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . $title . '.pdf"');
                    
        } catch (\Exception $e) {
            // Registrar error en logs del sistema para auditoría
            \Log::error('Error al generar PDF: ' . $e->getMessage(), [
                'title' => $title,
                'data_count' => $data->count(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'error' => 'Error al generar el PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera contenido HTML estructurado para el PDF
     * 
     * Crea una tabla HTML con formato profesional que incluye:
     * - Encabezado con título y fecha de generación
     * - Tabla detallada con información de asistencia
     * - Pie de página institucional
     * - Formato compatible con TCPDF
     * 
     * @param Collection $data Colección de registros de asistencia
     * @param string $title Título del reporte
     * @param Carbon $generatedAt Fecha y hora de generación
     * @return string HTML estructurado para el PDF
     */
    private function generateHTML($data, $title, $generatedAt)
    {
        // Generar encabezado del documento
        $html = '<h1>' . $title . '</h1>';
        $html .= '<p>Fecha: ' . $generatedAt->format('d/m/Y H:i:s') . '</p>';
        $html .= '<p>Total: ' . $data->count() . ' registros</p>';
        
        // Generar tabla principal con datos de asistencia
        $html .= '<table border="1">';
        $html .= '<tr><th>Documento</th><th>Nombre</th><th>Ficha</th><th>Programa</th><th>Fecha</th><th>Hora</th><th>Estado</th></tr>';
        
        // Iterar sobre cada registro de asistencia
        foreach ($data as $attendance) {
            $person = $attendance->apprentice->person;
            $course = $attendance->apprentice->course;
            $program = $course->program ?? null;
            
            // Construir nombre completo del aprendiz
            $fullName = trim(($person->first_name ?? '') . ' ' . 
                           ($person->first_last_name ?? '') . ' ' . 
                           ($person->second_last_name ?? ''));
            
            // Obtener etiqueta legible del estado de asistencia
            $statusLabel = $this->getStatusLabel($attendance->asistencia ?? $attendance->status);
            
            // Generar fila de la tabla con datos del aprendiz
            $html .= '<tr>';
            $html .= '<td>' . ($person->document_number ?? '') . '</td>';
            $html .= '<td>' . $fullName . '</td>';
            $html .= '<td>' . ($course->code ?? '') . '</td>';
            $html .= '<td>' . ($program->name ?? '') . '</td>';
            $html .= '<td>' . (method_exists($attendance, 'getFormattedDateAttribute') ? $attendance->formatted_date : (optional($attendance->asistance)->date ? Carbon::parse($attendance->asistance->date)->format('d/m/Y') : '')) . '</td>';
            $html .= '<td>' . ($attendance->created_at ? Carbon::parse($attendance->created_at)->format('H:i') : '') . '</td>';
            $html .= '<td>' . $statusLabel . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $html .= '<p>SENA - Sistema de Gestión de Asistencias</p>';
        
        return $html;
    }

    /**
     * Convierte nombres de días en inglés a español
     * 
     * Utilizada para formatear fechas en reportes y vistas
     * del sistema, proporcionando una experiencia localizada
     * para usuarios hispanohablantes.
     * 
     * @param string $englishDay Nombre del día en inglés
     * @return string Nombre del día en español
     */
    private function getDayName($englishDay)
    {
        $days = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        
        return $days[$englishDay] ?? $englishDay;
    }

    /**
     * Convierte códigos de estado de asistencia a etiquetas legibles
     * 
     * Traduce los códigos internos del sistema a texto comprensible
     * para el usuario final, mejorando la experiencia de usuario
     * en reportes y vistas del sistema.
     * 
     * @param string $status Código de estado del sistema
     * @return string Etiqueta legible del estado
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'registered' => 'Registrado',
            'attended' => 'Asistió',
            'absent' => 'No asistió',
            'late' => 'Tardanza',
            'cancelled' => 'Cancelado'
        ];

        return $labels[$status] ?? $status;
    }
}