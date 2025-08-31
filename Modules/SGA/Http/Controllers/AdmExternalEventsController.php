<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\SGA\Http\Requests\ExternalEventRequest;

class AdmExternalEventsController extends Controller
{
    /**
     * Mostrar la vista de eventos externos
     */
    public function index()
    {
        $titlePage = trans("sga::menu.external-events");
        $titleView = trans("sga::menu.external-events");

        // Obtener eventos recientes para mostrar en la vista
        $eventos = DB::table('convocatories_events')
            ->select('id', 'name', 'number_lunchs', 'lunchs_discount', 'description', 'required_elements', 'created_at')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'eventos' => $eventos
        ];

        return view('sga::admin.external-events', $data);
    }

    /**
     * Crear un nuevo evento externo
     */
    public function store(ExternalEventRequest $request)
    {
        try {
            $data = $request->validated();

            // Crear el evento
            $eventoId = DB::table('convocatories_events')->insertGetId([
                'name' => $data['name'],
                'number_lunchs' => $data['number_lunchs'],
                'lunchs_discount' => $data['lunchs_discount'],
                'description' => $data['description'],
                'required_elements' => $data['required_elements'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log de auditoría
            Log::info('Evento externo creado exitosamente', [
                'evento_id' => $eventoId,
                'nombre' => $data['name'],
                'creado_por' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('cefa.sga.admin.external-events')
                ->with('success', 'Evento externo creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear evento externo: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data' => $data ?? null
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el evento externo: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un evento externo existente
     */
    public function update(ExternalEventRequest $request, $id)
    {
        try {
            $data = $request->validated();

            // Verificar que el evento existe
            $evento = DB::table('convocatories_events')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$evento) {
                return redirect()->back()
                    ->with('error', 'Evento no encontrado');
            }

            // Actualizar el evento
            DB::table('convocatories_events')
                ->where('id', $id)
                ->update([
                    'name' => $data['name'],
                    'number_lunchs' => $data['number_lunchs'],
                    'lunchs_discount' => $data['lunchs_discount'],
                    'description' => $data['description'],
                    'required_elements' => $data['required_elements'],
                    'updated_at' => now()
                ]);

            // Log de auditoría
            Log::info('Evento externo actualizado exitosamente', [
                'evento_id' => $id,
                'nombre' => $data['name'],
                'actualizado_por' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('cefa.sga.admin.external-events')
                ->with('success', 'Evento externo actualizado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al actualizar evento externo: ' . $e->getMessage(), [
                'evento_id' => $id,
                'user_id' => auth()->id(),
                'data' => $data ?? null
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el evento externo: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un evento externo (soft delete)
     */
    public function destroy($id)
    {
        try {
            $evento = DB::table('convocatories_events')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$evento) {
                return redirect()->back()
                    ->with('error', 'Evento no encontrado');
            }

            // Soft delete
            DB::table('convocatories_events')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now()
                ]);

            // Log de auditoría
            Log::info('Evento externo eliminado', [
                'evento_id' => $id,
                'nombre' => $evento->name,
                'eliminado_por' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('cefa.sga.admin.external-events')
                ->with('success', 'Evento externo eliminado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al eliminar evento externo: ' . $e->getMessage(), [
                'evento_id' => $id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Error al eliminar el evento externo: ' . $e->getMessage());
        }
    }

    /**
     * Obtener eventos para AJAX (mantener compatibilidad)
     */
    public function obtenerEventos()
    {
        try {
            $eventos = DB::table('convocatories_events')
                ->select('id', 'name', 'number_lunchs', 'lunchs_discount', 'description', 'required_elements', 'created_at')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'eventos' => $eventos
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener eventos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener eventos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un evento específico para edición
     */
    public function getEvent($id)
    {
        try {
            $evento = DB::table('convocatories_events')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$evento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evento no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'evento' => $evento
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener evento: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el evento'
            ], 500);
        }
    }
}