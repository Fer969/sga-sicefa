<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdmSysParamsController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.sys-params");
        $titleView = trans("sga::menu.sys-params");

        // Obtener convocatorias activas
        $convocatorias = DB::table('convocatories')
            ->select('id', 'name', 'quarter', 'year', 'status', 'coups', 'registration_start_date', 'registration_deadline')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener tipos de convocatorias
        $tiposConvocatorias = DB::table('types_convocatories')
            ->select('id', 'name')
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

        return view('sga::admin.sys-params', $data);
    }

    public function crearConvocatoria(Request $request)
    {
        // Log de datos recibidos para debugging
        Log::info('Datos recibidos en crearConvocatoria:', $request->all());
        
        // Validación con mensajes personalizados
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'tipo_convocatoria' => 'required|integer|exists:types_convocatories,id',
            'trimestre' => 'required|in:1,2,3,4',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_cierre' => 'required|date|after:fecha_inicio',
            'cupos' => 'required|integer|min:1|max:1000',
            'año' => 'required|integer|min:2024|max:2030'
        ], [
            'nombre.required' => 'El nombre de la convocatoria es obligatorio',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres',
            'tipo_convocatoria.required' => 'Debe seleccionar un tipo de convocatoria',
            'tipo_convocatoria.exists' => 'El tipo de convocatoria seleccionado no existe',
            'trimestre.required' => 'El trimestre es obligatorio',
            'trimestre.in' => 'El trimestre debe ser 1, 2, 3 o 4',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy',
            'fecha_cierre.required' => 'La fecha de cierre es obligatoria',
            'fecha_cierre.after' => 'La fecha de cierre debe ser posterior a la fecha de inicio',
            'cupos.required' => 'La cantidad de cupos es obligatoria',
            'cupos.min' => 'Debe haber al menos 1 cupo disponible',
            'cupos.max' => 'No puede haber más de 1000 cupos',
            'año.required' => 'El año es obligatorio',
            'año.min' => 'El año debe ser 2024 o posterior',
            'año.max' => 'El año no puede ser posterior a 2030'
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida en crearConvocatoria:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Datos de validación incorrectos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Iniciar transacción de base de datos
            DB::beginTransaction();

            // Verificar que el tipo de convocatoria existe
            $tipoConvocatoria = DB::table('types_convocatories')
                ->where('id', $request->tipo_convocatoria)
                ->whereNull('deleted_at')
                ->first();
            
            if (!$tipoConvocatoria) {
                Log::error('Tipo de convocatoria no encontrado:', ['id' => $request->tipo_convocatoria]);
                return response()->json([
                    'success' => false,
                    'message' => 'El tipo de convocatoria seleccionado no existe.'
                ], 422);
            }

            // Verificar si ya existe una convocatoria activa para el mismo tipo, trimestre y año
            $existeConvocatoria = DB::table('convocatories')
                ->where('types_convocatories_id', $request->tipo_convocatoria)
                ->where('quarter', $request->trimestre)
                ->where('year', $request->año)
                ->where('status', 'Active')
                ->whereNull('deleted_at')
                ->exists();

            if ($existeConvocatoria) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una convocatoria activa para este tipo, trimestre y año.'
                ], 422);
            }

            // Crear la convocatoria
            $convocatoriaId = DB::table('convocatories')->insertGetId([
                'types_convocatories_id' => $request->tipo_convocatoria,
                'name' => trim($request->nombre),
                'quarter' => (int)$request->trimestre,
                'year' => (int)$request->año,
                'coups' => (int)$request->cupos,
                'registration_start_date' => $request->fecha_inicio,
                'registration_deadline' => $request->fecha_cierre,
                'status' => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null
            ]);

            if (!$convocatoriaId) {
                throw new \Exception('No se pudo insertar la convocatoria en la base de datos');
            }

            Log::info('Convocatoria creada exitosamente:', ['id' => $convocatoriaId]);

            // Crear puntajes por defecto para la nueva convocatoria
            $puntajesInsertados = DB::table('convocatories_points')->insert([
                'convocatory_selected' => $convocatoriaId,
                'victim_conflict_score' => 3,
                'gender_violence_victim_score' => 2,
                'disability_score' => 2,
                'head_of_household_score' => 2,
                'pregnant_or_lactating_score' => 2,
                'ethnic_group_affiliation_score' => 1,
                'natural_displacement_score' => 2,
                'sisben_group_a_score' => 2,
                'sisben_group_b_score' => 1,
                'rural_apprentice_score' => 1,
                'institutional_representative_score' => 1,
                'lives_in_rural_area_score' => 1,
                'spokesperson_elected_score' => 1,
                'research_participation_score' => 1,
                'previous_boarding_quota_score' => 1,
                'has_certification_score' => 1,
                'attached_sworn_statement_score' => 1,
                'knows_obligations_support_score' => 1,
                'renta_joven_beneficiary_score' => 2,
                'has_apprenticeship_contract_score' => 1,
                'received_fic_support_score' => 1,
                'received_regular_support_score' => 1,
                'has_income_contract_score' => 1,
                'has_sponsored_practice_score' => 1,
                'receives_food_support_score' => 1,
                'receives_transport_support_score' => 1,
                'receives_tech_support_score' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            if (!$puntajesInsertados) {
                Log::warning('No se pudieron crear los puntajes por defecto, pero la convocatoria se creó correctamente');
            } else {
                Log::info('Puntajes por defecto creados para convocatoria:', ['convocatoria_id' => $convocatoriaId]);
            }

            // Confirmar transacción
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Convocatoria creada exitosamente con ID: ' . $convocatoriaId,
                'convocatoria_id' => $convocatoriaId,
                'data' => [
                    'id' => $convocatoriaId,
                    'name' => $request->nombre,
                    'year' => $request->año,
                    'quarter' => $request->trimestre,
                    'coups' => $request->cupos,
                    'status' => 'Active'
                ]
            ]);

        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            
            Log::error('Error al crear convocatoria:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la convocatoria: ' . $e->getMessage(),
                'debug_info' => [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]
            ], 500);
        }
    }

    // Resto de métodos sin cambios...
    public function actualizarPuntajes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'convocatoria_id' => 'required|integer|exists:convocatories,id',
            'puntajes' => 'required|array',
            'puntajes.*' => 'integer|min:0|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $puntajes = $request->puntajes;

            // Buscar si ya existe configuración de puntajes para esta convocatoria
            $existingPoints = DB::table('convocatories_points')
                ->where('convocatory_selected', $request->convocatoria_id)
                ->first();

            $updateData = [
                'victim_conflict_score' => $puntajes['victima_conflicto'] ?? 0,
                'gender_violence_victim_score' => $puntajes['violencia_genero'] ?? 0,
                'disability_score' => $puntajes['discapacidad'] ?? 0,
                'head_of_household_score' => $puntajes['cabeza_familia'] ?? 0,
                'pregnant_or_lactating_score' => $puntajes['embarazada_lactante'] ?? 0,
                'ethnic_group_affiliation_score' => $puntajes['grupo_etnico'] ?? 0,
                'natural_displacement_score' => $puntajes['desplazamiento'] ?? 0,
                'sisben_group_a_score' => $puntajes['sisben_grupo_a'] ?? 0,
                'sisben_group_b_score' => $puntajes['sisben_grupo_b'] ?? 0,
                'rural_apprentice_score' => $puntajes['aprendiz_rural'] ?? 0,
                'renta_joven_beneficiary_score' => $puntajes['renta_joven'] ?? 0,
                'updated_at' => Carbon::now()
            ];

            if ($existingPoints) {
                DB::table('convocatories_points')
                    ->where('convocatory_selected', $request->convocatoria_id)
                    ->update($updateData);
            } else {
                $updateData['convocatory_selected'] = $request->convocatoria_id;
                $updateData['created_at'] = Carbon::now();
                DB::table('convocatories_points')->insert($updateData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Puntajes actualizados exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar puntajes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function crearEvento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_evento' => 'required|string|max:150',
            'cantidad_almuerzos' => 'required|integer|min:1|max:999',
            'descuento' => 'required|integer|in:0,30,50',
            'descripcion' => 'nullable|string|max:250',
            'elementos_requeridos' => 'nullable|string|max:250'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $eventoId = DB::table('convocatories_events')->insertGetId([
                'name' => $request->nombre_evento,
                'number_lunchs' => $request->cantidad_almuerzos,
                'lunchs_discount' => $request->descuento,
                'description' => $request->descripcion,
                'required_elements' => $request->elementos_requeridos,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Evento registrado exitosamente',
                'evento_id' => $eventoId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar evento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerConvocatorias()
    {
        try {
            $convocatorias = DB::table('convocatories as c')
                ->join('types_convocatories as tc', 'c.types_convocatories_id', '=', 'tc.id')
                ->select('c.*', 'tc.name as tipo_nombre')
                ->whereNull('c.deleted_at')
                ->orderBy('c.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'convocatorias' => $convocatorias
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener convocatorias: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerPuntajes($convocatoriaId)
    {
        try {
            $puntajes = DB::table('convocatories_points')
                ->where('convocatory_selected', $convocatoriaId)
                ->first();

            return response()->json([
                'success' => true,
                'puntajes' => $puntajes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener puntajes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cambiarEstadoConvocatoria(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::table('convocatories')
                ->where('id', $id)
                ->update([
                    'status' => $request->estado,
                    'updated_at' => Carbon::now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado de convocatoria actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar estado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerTiposConvocatorias()
    {
        try {
            $tipos = DB::table('types_convocatories')
                ->select('id', 'name', 'description')
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'tipos' => $tipos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de convocatorias: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerEventos()
    {
        try {
            $eventos = DB::table('convocatories_events')
                ->select('id', 'name', 'number_lunchs', 'lunchs_discount', 'description', 'created_at')
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'eventos' => $eventos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener eventos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerEstadisticas()
    {
        try {
            $stats = [
                'convocatorias_activas' => DB::table('convocatories')->where('status', 'Active')->whereNull('deleted_at')->count(),
                'convocatorias_totales' => DB::table('convocatories')->whereNull('deleted_at')->count(),
                'eventos_mes_actual' => DB::table('convocatories_events')
                    ->whereNull('deleted_at')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count(),
                'total_almuerzos_eventos' => DB::table('convocatories_events')
                    ->whereNull('deleted_at')
                    ->sum('number_lunchs')
            ];

            return response()->json([
                'success' => true,
                'estadisticas' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function debug()
    {
        try {
            // Verificar conexión a base de datos
            $connection = DB::connection()->getPdo();
            echo "✅ Conexión a BD: OK<br>";

            // Verificar si existen las tablas
            $tables = ['convocatories', 'convocatories_points', 'convocatories_events', 'types_convocatories'];

            foreach ($tables as $table) {
                $exists = DB::select("SHOW TABLES LIKE '$table'");
                if ($exists) {
                    echo "✅ Tabla $table: Existe<br>";
                    $count = DB::table($table)->count();
                    echo "📊 Registros en $table: $count<br>";
                } else {
                    echo "❌ Tabla $table: NO EXISTE<br>";
                }
            }

            // Verificar estructura de convocatories
            echo "<br><h3>Estructura de tabla convocatories:</h3>";
            $columns = DB::select("DESCRIBE convocatories");
            foreach ($columns as $column) {
                echo "- {$column->Field} ({$column->Type})<br>";
            }

            // Verificar si existe types_convocatories y mostrar datos
            echo "<br><h3>Tipos de Convocatorias:</h3>";
            try {
                $tipos = DB::table('types_convocatories')->get();
                foreach ($tipos as $tipo) {
                    echo "- ID: {$tipo->id}, Nombre: {$tipo->name}<br>";
                }
            } catch (\Exception $e) {
                echo "❌ Error: " . $e->getMessage() . "<br>";
            }
        } catch (\Exception $e) {
            echo "❌ Error general: " . $e->getMessage();
        }
    }
}