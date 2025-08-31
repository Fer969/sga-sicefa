<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\SGA\Entities\Incident;
use Modules\SGA\Entities\IncidentComment;
use Modules\SGA\Entities\IncidentActivity;
use App\Models\User;

/**
 * Controlador para la gestión de incidencias en el Sistema de Gestión Académica (SGA)
 * 
 * Este controlador permite al personal administrativo (staff) del SENA:
 * - Registrar y gestionar incidencias del sistema
 * - Asignar incidencias a técnicos o administradores
 * - Realizar seguimiento del estado de las incidencias
 * - Comentar y actualizar el progreso de las incidencias
 * - Generar reportes y estadísticas de incidencias
 * - Exportar datos para análisis y auditoría
 * 
 * Tipos de incidencias soportadas:
 * - Problemas del sistema (errores, fallos)
 * - Problemas de asistencia (registros incorrectos)
 * - Problemas de cuentas de usuario (accesos, permisos)
 * - Problemas con reportes (generación, formato)
 * - Problemas técnicos (hardware, software)
 * - Otros problemas operativos
 * 
 * Prioridades disponibles:
 * - Baja: Problemas menores que no afectan operaciones
 * - Media: Problemas que afectan algunas funcionalidades
 * - Alta: Problemas que afectan operaciones críticas
 * - Crítica: Problemas que paralizan el sistema
 * 
 * @package Modules\SGA\Http\Controllers
 * @author SENA - Sistema de Gestión Académica
 * @version 1.0
 */
class StaffIncidentController extends Controller
{
    /**
     * Muestra la lista principal de incidencias con filtros y paginación
     * 
     * Esta función proporciona una vista completa de todas las incidencias
     * del sistema, permitiendo al staff:
     * - Ver incidencias activas y cerradas
     * - Filtrar por estado, prioridad y categoría
     * - Buscar incidencias por título o descripción
     * - Ordenar por diferentes criterios
     * - Acceder a estadísticas generales
     * 
     * Los filtros disponibles incluyen:
     * - Estado: Abierta, En progreso, Resuelta, Cerrada
     * - Prioridad: Baja, Media, Alta, Crítica
     * - Categoría: Sistema, Asistencia, Usuario, Reporte, Técnica, Otro
     * - Búsqueda de texto libre
     * 
     * @param Request $request Solicitud HTTP con filtros y parámetros de paginación
     * @return \Illuminate\View\View Vista principal de gestión de incidencias
     */
    public function index(Request $request)
    {
        $query = Incident::with(['user', 'assignedTo', 'comments']);

        // Aplicar filtros según los parámetros de la solicitud
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Búsqueda de texto en título y descripción
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Configurar ordenamiento de resultados
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Obtener incidencias paginadas y estadísticas
        $incidents = $query->paginate(15);
        $stats = Incident::getStats();
        $priorityStats = Incident::getPriorityStats();
        $categoryStats = Incident::getCategoryStats();

        $titlePage = 'Manejo de Incidencias';
        $titleView = 'Manejo de Incidencias';

        return view('sga::staff.incidents', compact(
            'incidents', 
            'titlePage', 
            'titleView', 
            'stats', 
            'priorityStats', 
            'categoryStats'
        ));
    }

    /**
     * Muestra el formulario para crear una nueva incidencia
     * 
     * Esta función proporciona la interfaz para que el staff pueda:
     * - Registrar nuevos problemas o solicitudes
     * - Seleccionar la categoría apropiada
     * - Asignar prioridad según el impacto
     * - Describir detalladamente el problema
     * - Adjuntar información adicional si es necesario
     * 
     * Las opciones disponibles incluyen:
     * - Categorías predefinidas del sistema
     * - Niveles de prioridad estándar
     * - Campos obligatorios y opcionales
     * 
     * @return \Illuminate\View\View Formulario de creación de incidencia
     */
    public function create()
    {
        $titlePage = 'Registrar Nueva Incidencia';
        $titleView = 'Registrar Nueva Incidencia';
        
        // Definir categorías disponibles para clasificar incidencias
        $categories = [
            Incident::CATEGORY_SYSTEM => 'Sistema',
            Incident::CATEGORY_ATTENDANCE => 'Asistencia',
            Incident::CATEGORY_USER_ACCOUNT => 'Cuenta de Usuario',
            Incident::CATEGORY_REPORT => 'Reporte',
            Incident::CATEGORY_TECHNICAL => 'Técnica',
            Incident::CATEGORY_OTHER => 'Otro'
        ];

        // Definir niveles de prioridad disponibles
        $priorities = [
            Incident::PRIORITY_LOW => 'Baja',
            Incident::PRIORITY_MEDIUM => 'Media',
            Incident::PRIORITY_HIGH => 'Alta',
            Incident::PRIORITY_CRITICAL => 'Crítica'
        ];

        return view('sga::staff.incidents-create', compact('titlePage', 'titleView', 'categories', 'priorities'));
    }

    /**
     * Almacena una nueva incidencia en el sistema
     * 
     * Esta función procesa la creación de una nueva incidencia,
     * validando todos los datos requeridos y creando el registro
     * en la base de datos con auditoría completa.
     * 
     * El sistema valida:
     * - Título obligatorio (máximo 255 caracteres)
     * - Descripción detallada (mínimo 10 caracteres)
     * - Prioridad válida (baja, media, alta, crítica)
     * - Categoría válida (sistema, asistencia, usuario, etc.)
     * 
     * Se crea automáticamente:
     * - Registro principal de la incidencia
     * - Actividad de creación
     * - Log de auditoría
     * 
     * @param Request $request Solicitud HTTP con datos de la incidencia
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Validar datos de entrada según reglas de negocio
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:' . implode(',', [
                Incident::PRIORITY_LOW,
                Incident::PRIORITY_MEDIUM,
                Incident::PRIORITY_HIGH,
                Incident::PRIORITY_CRITICAL
            ]),
            'category' => 'required|in:' . implode(',', [
                Incident::CATEGORY_SYSTEM,
                Incident::CATEGORY_ATTENDANCE,
                Incident::CATEGORY_USER_ACCOUNT,
                Incident::CATEGORY_REPORT,
                Incident::CATEGORY_TECHNICAL,
                Incident::CATEGORY_OTHER
            ]),
            'estimated_resolution_date' => 'nullable|date|after:today',
            'location' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'tags' => 'nullable|string'
        ]);

        try {
            $incident = Incident::create([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'category' => $request->category,
                'user_id' => auth()->id(),
                'status' => Incident::STATUS_OPEN,
                'estimated_resolution_date' => $request->estimated_resolution_date,
                'location' => $request->location,
                'contact_info' => $request->contact_info,
                'tags' => $request->tags ? explode(',', $request->tags) : []
            ]);

            Log::info('Incidencia creada', [
                'incident_id' => $incident->id,
                'user_id' => auth()->id(),
                'title' => $incident->title
            ]);

            return redirect()->route('cefa.sga.staff.incidents')
                ->with('success', 'Incidencia registrada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al crear incidencia: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al registrar la incidencia.');
        }
    }

    public function show($id)
    {
        $incident = Incident::with(['user', 'assignedTo', 'comments.user', 'activities.user'])
            ->findOrFail($id);

        $titlePage = 'Detalle de Incidencia';
        $titleView = 'Detalle de Incidencia';

        $users = User::where('id', '!=', auth()->id())->get();

        return view('sga::staff.incidents-show', compact('incident', 'titlePage', 'titleView', 'users'));
    }

    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:' . implode(',', [
                Incident::PRIORITY_LOW,
                Incident::PRIORITY_MEDIUM,
                Incident::PRIORITY_HIGH,
                Incident::PRIORITY_CRITICAL
            ]),
            'category' => 'required|in:' . implode(',', [
                Incident::CATEGORY_SYSTEM,
                Incident::CATEGORY_ATTENDANCE,
                Incident::CATEGORY_USER_ACCOUNT,
                Incident::CATEGORY_REPORT,
                Incident::CATEGORY_TECHNICAL,
                Incident::CATEGORY_OTHER
            ]),
            'status' => 'required|in:' . implode(',', [
                Incident::STATUS_OPEN,
                Incident::STATUS_IN_PROGRESS,
                Incident::STATUS_PENDING,
                Incident::STATUS_RESOLVED,
                Incident::STATUS_CLOSED,
                Incident::STATUS_CANCELLED
            ]),
            'assigned_to' => 'nullable|exists:users,id',
            'estimated_resolution_date' => 'nullable|date',
            'resolution_notes' => 'nullable|string'
        ]);

        try {
            $oldValues = $incident->toArray();
            
            $incident->update([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'category' => $request->category,
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
                'estimated_resolution_date' => $request->estimated_resolution_date,
                'resolution_notes' => $request->resolution_notes
            ]);

            // Registrar actividad
            $incident->activities()->create([
                'action' => 'Actualizada',
                'description' => 'Incidencia actualizada',
                'user_id' => auth()->id(),
                'old_values' => $oldValues,
                'new_values' => $incident->toArray()
            ]);

            return redirect()->route('cefa.sga.staff.incidents-show', $incident->id)
                ->with('success', 'Incidencia actualizada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar incidencia: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al actualizar la incidencia.');
        }
    }

    public function assign(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $request->validate(['assigned_to' => 'required|exists:users,id']);

        try {
            $incident->assignTo($request->assigned_to);

            return redirect()->route('cefa.sga.staff.incidents-show', $incident->id)
                ->with('success', 'Incidencia asignada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al asignar incidencia: ' . $e->getMessage());
            return back()->with('error', 'Error al asignar la incidencia.');
        }
    }

    public function resolve(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $request->validate(['resolution_notes' => 'required|string|min:10']);

        try {
            $incident->resolve($request->resolution_notes);

            return redirect()->route('cefa.sga.staff.incidents-show', $incident->id)
                ->with('success', 'Incidencia marcada como resuelta.');

        } catch (\Exception $e) {
            Log::error('Error al resolver incidencia: ' . $e->getMessage());
            return back()->with('error', 'Error al resolver la incidencia.');
        }
    }

    public function close($id)
    {
        $incident = Incident::findOrFail($id);

        try {
            $incident->close();

            return redirect()->route('cefa.sga.staff.incidents')
                ->with('success', 'Incidencia cerrada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al cerrar incidencia: ' . $e->getMessage());
            return back()->with('error', 'Error al cerrar la incidencia.');
        }
    }

    public function addComment(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        
        $request->validate([
            'comment' => 'required|string|min:5',
            'is_internal' => 'boolean',
            'is_resolution_note' => 'boolean'
        ]);

        try {
            $comment = $incident->comments()->create([
                'user_id' => auth()->id(),
                'comment' => $request->comment,
                'is_internal' => $request->boolean('is_internal'),
                'is_resolution_note' => $request->boolean('is_resolution_note')
            ]);

            // Registrar actividad
            $incident->activities()->create([
                'action' => 'Comentario',
                'description' => 'Comentario agregado',
                'user_id' => auth()->id()
            ]);

            return redirect()->route('cefa.sga.staff.incidents-show', $incident->id)
                ->with('success', 'Comentario agregado correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al agregar comentario: ' . $e->getMessage());
            return back()->with('error', 'Error al agregar el comentario.');
        }
    }

    public function getStats()
    {
        try {
            $stats = Incident::getStats();
            $priorityStats = Incident::getPriorityStats();
            $categoryStats = Incident::getCategoryStats();

            return response()->json([
                'stats' => $stats,
                'priorityStats' => $priorityStats,
                'categoryStats' => $categoryStats
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener estadísticas'], 500);
        }
    }

    public function export(Request $request)
    {
        $query = Incident::with(['user', 'assignedTo']);

        // Aplicar filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $incidents = $query->orderBy('created_at', 'desc')->get();

        $filename = 'incidencias_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($incidents) {
            $file = fopen('php://output', 'w');
            
            // Headers del CSV
            fputcsv($file, [
                'ID', 'Título', 'Descripción', 'Estado', 'Prioridad', 'Categoría',
                'Reportada por', 'Asignada a', 'Fecha de creación', 'Fecha estimada de resolución',
                'Fecha de resolución', 'Días abierta', 'Notas de resolución'
            ]);

            foreach ($incidents as $incident) {
                fputcsv($file, [
                    $incident->id,
                    $incident->title,
                    $incident->description,
                    $incident->status,
                    $incident->priority,
                    $incident->category,
                    $incident->user->name ?? 'N/A',
                    $incident->assignedTo->name ?? 'N/A',
                    $incident->created_at->format('d/m/Y H:i'),
                    $incident->estimated_resolution_date ? $incident->estimated_resolution_date->format('d/m/Y') : 'N/A',
                    $incident->resolved_at ? $incident->resolved_at->format('d/m/Y H:i') : 'N/A',
                    $incident->days_open,
                    $incident->resolution_notes ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
