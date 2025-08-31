<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Modules\SGA\Http\Requests\ConvocatoryRequest;

class AdmConvocatoriesController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.convocatories");
        $titleView = trans("sga::menu.convocatories");

        // Obtener solo convocatorias de alimentación (filtrar por types_convocatories_id)
        $convocatorias = DB::table('convocatories as c')
            ->join('types_convocatories as tc', 'c.types_convocatories_id', '=', 'tc.id')
            ->select('c.id', 'c.name', 'c.quarter', 'c.year', 'c.status', 'c.coups', 'c.registration_start_date', 'c.registration_deadline')
            ->where('tc.name', 'Apoyo de Alimentación')
            ->whereNull('c.deleted_at')
            ->orderBy('c.created_at', 'desc')
            ->get();

        // Obtener solo el tipo de convocatoria de alimentación
        $tiposConvocatorias = DB::table('types_convocatories')
            ->select('id', 'name')
            ->where('name', 'Apoyo de Alimentación')
            ->whereNull('deleted_at')
            ->get();

        // Obtener eventos recientes
        $eventos = DB::table('convocatories_events')
            ->select('id', 'name', 'number_lunchs', 'lunchs_discount', 'description', 'created_at')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Obtener configuración de puntajes de la convocatoria más reciente
        $puntajesActuales = DB::table('convocatories_points as cp')
            ->join('convocatories as c', 'cp.convocatory_selected', '=', 'c.id')
            ->select('cp.*', 'c.name as convocatoria_name')
            ->whereNull('c.deleted_at')
            ->orderBy('cp.created_at', 'desc')
            ->first();

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'convocatorias' => $convocatorias,
            'tiposConvocatorias' => $tiposConvocatorias,
            'eventos' => $eventos,
            'puntajesActuales' => $puntajesActuales
        ];

        return view('sga::admin.convocatories', $data);
    }

    /**
     * Crear una nueva convocatoria
     */
    public function store(ConvocatoryRequest $request)
    {
        try {
            // Los datos ya están validados por el Form Request
            $data = $request->validated();

            // Debug: Log de los datos recibidos
            Log::info('Datos de convocatoria recibidos:', $data);

            // Crear la convocatoria
            $convocatoriaId = DB::table('convocatories')->insertGetId([
                'name' => $data['nombre'],
                'types_convocatories_id' => $data['tipo_convocatoria'],
                'quarter' => $data['trimestre'],
                'year' => $data['año'],
                'coups' => $data['cupos'],
                'registration_start_date' => $data['fecha_inicio'] . ' 00:00:00',
                'registration_deadline' => $data['fecha_cierre'] . ' 23:59:59',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Debug: Log de la convocatoria creada
            Log::info('Convocatoria creada con ID:', ['id' => $convocatoriaId]);

            // Log de auditoría
            Log::info('Convocatoria creada exitosamente', [
                'convocatoria_id' => $convocatoriaId,
                'nombre' => $data['nombre'],
                'creado_por' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('cefa.sga.admin.convocatories')
                ->with('success', 'Convocatoria creada exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear convocatoria: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data' => $data ?? null
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la convocatoria: ' . $e->getMessage());
        }
    }

    /**
     * Obtener convocatorias para AJAX
     */
    public function obtenerConvocatorias()
    {
        try {
            $convocatorias = DB::table('convocatories as c')
                ->join('types_convocatories as tc', 'c.types_convocatories_id', '=', 'tc.id')
                ->select('c.id', 'c.name', 'c.quarter', 'c.year', 'c.status', 'c.coups', 'c.registration_start_date', 'c.registration_deadline')
                ->where('tc.name', 'Apoyo de Alimentación')
                ->whereNull('c.deleted_at')
                ->orderBy('c.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'convocatorias' => $convocatorias
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener convocatorias: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las convocatorias'
            ], 500);
        }
    }

    /**
     * Cambiar estado de una convocatoria
     */
    public function cambiarEstadoConvocatoria(Request $request, $id)
    {
        try {
            $nuevoEstado = $request->input('estado');
            
            if (!in_array($nuevoEstado, ['Active', 'Inactive'])) {
                return redirect()->back()
                    ->with('error', 'Estado no válido');
            }

            $convocatoria = DB::table('convocatories')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$convocatoria) {
                return redirect()->back()
                    ->with('error', 'Convocatoria no encontrada');
            }

            DB::table('convocatories')
                ->where('id', $id)
                ->update([
                    'status' => $nuevoEstado,
                    'updated_at' => now()
                ]);

            // Log de auditoría
            Log::info('Estado de convocatoria cambiado', [
                'convocatoria_id' => $id,
                'estado_anterior' => $convocatoria->status,
                'estado_nuevo' => $nuevoEstado,
                'cambiado_por' => auth()->id(),
                'timestamp' => now()
            ]);

            $estadoTexto = $nuevoEstado === 'Active' ? 'activada' : 'desactivada';
            return redirect()->route('cefa.sga.admin.convocatories')
                ->with('success', "Convocatoria {$estadoTexto} exitosamente");

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de convocatoria: ' . $e->getMessage(), [
                'convocatoria_id' => $id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Error al cambiar el estado de la convocatoria: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una convocatoria (soft delete)
     */
    public function destroy($id)
    {
        try {
            $convocatoria = DB::table('convocatories')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$convocatoria) {
                return redirect()->back()
                    ->with('error', 'Convocatoria no encontrada');
            }

            // Verificar si hay postulaciones activas
            $postulacionesActivas = DB::table('calls_applications')
                ->where('convocatory_selected', $id)
                ->exists();

            if ($postulacionesActivas) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar la convocatoria porque tiene postulaciones activas');
            }

            DB::table('convocatories')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            // Log de auditoría
            Log::info('Convocatoria eliminada', [
                'convocatoria_id' => $id,
                'eliminada_por' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('cefa.sga.admin.convocatories')
                ->with('success', 'Convocatoria eliminada exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al eliminar convocatoria: ' . $e->getMessage(), [
                'convocatoria_id' => $id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar la convocatoria: ' . $e->getMessage());
        }
    }
}