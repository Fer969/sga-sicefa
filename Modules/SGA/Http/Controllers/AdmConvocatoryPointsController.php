<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\SGA\Http\Requests\ConvocatoryPointsRequest;

class AdmConvocatoryPointsController extends Controller
{
    /**
     * Mostrar la vista de puntos de convocatorias
     */
    public function index()
    {
        $titlePage = trans("sga::menu.points");
        $titleView = trans("sga::menu.points");

        // Obtener convocatorias de alimentación activas
        $convocatorias = DB::table('convocatories as c')
            ->join('types_convocatories as tc', 'c.types_convocatories_id', '=', 'tc.id')
            ->select('c.id', 'c.name', 'c.quarter', 'c.year', 'c.status')
            ->where('tc.name', 'Apoyo de Alimentación')
            ->whereNull('c.deleted_at')
            ->orderBy('c.created_at', 'desc')
            ->get();

        // Obtener puntos de la convocatoria más reciente (si existe)
        $puntajesActuales = DB::table('convocatories_points as cp')
            ->join('convocatories as c', 'cp.convocatory_selected', '=', 'c.id')
            ->select('cp.*', 'c.name as convocatoria_name')
            ->whereNull('c.deleted_at')
            ->orderBy('cp.created_at', 'desc')
            ->first();

        // Obtener convocatorias con puntos para copiar
        $convocatoriasConPuntos = DB::table('convocatories_points as cp')
            ->join('convocatories as c', 'cp.convocatory_selected', '=', 'c.id')
            ->select('cp.convocatory_selected', 'c.name', 'c.quarter', 'c.year', 'cp.created_at')
            ->whereNull('c.deleted_at')
            ->orderBy('cp.created_at', 'desc')
            ->get();

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'convocatorias' => $convocatorias,
            'puntajesActuales' => $puntajesActuales,
            'convocatoriasConPuntos' => $convocatoriasConPuntos
        ];

        return view('sga::admin.convocatory_points', $data);
    }

    /**
     * Crear nuevos puntos para una convocatoria
     */
    public function store(ConvocatoryPointsRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Verificar si ya existen puntos para esta convocatoria
            $puntosExistentes = DB::table('convocatories_points')
                ->where('convocatory_selected', $data['convocatory_selected'])
                ->first();

            if ($puntosExistentes) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Ya existen puntos configurados para esta convocatoria. Use la función de editar.');
            }

            // Crear los puntos
            $puntosId = DB::table('convocatories_points')->insertGetId([
                'convocatory_selected' => $data['convocatory_selected'],
                'victim_conflict_score' => $data['victim_conflict_score'],
                'gender_violence_victim_score' => $data['gender_violence_victim_score'],
                'disability_score' => $data['disability_score'],
                'head_of_household_score' => $data['head_of_household_score'],
                'pregnant_or_lactating_score' => $data['pregnant_or_lactating_score'],
                'ethnic_group_affiliation_score' => $data['ethnic_group_affiliation_score'],
                'natural_displacement_score' => $data['natural_displacement_score'],
                'sisben_group_a_score' => $data['sisben_group_a_score'],
                'sisben_group_b_score' => $data['sisben_group_b_score'],
                'rural_apprentice_score' => $data['rural_apprentice_score'],
                'institutional_representative_score' => $data['institutional_representative_score'],
                'lives_in_rural_area_score' => $data['lives_in_rural_area_score'],
                'spokesperson_elected_score' => $data['spokesperson_elected_score'],
                'research_participation_score' => $data['research_participation_score'],
                'previous_boarding_quota_score' => $data['previous_boarding_quota_score'],
                'has_certification_score' => $data['has_certification_score'],
                'attached_sworn_statement_score' => $data['attached_sworn_statement_score'],
                'knows_obligations_support_score' => $data['knows_obligations_support_score'],
                'renta_joven_beneficiary_score' => $data['renta_joven_beneficiary_score'],
                'has_apprenticeship_contract_score' => $data['has_apprenticeship_contract_score'],
                'received_fic_support_score' => $data['received_fic_support_score'],
                'received_regular_support_score' => $data['received_regular_support_score'],
                'has_income_contract_score' => $data['has_income_contract_score'],
                'has_sponsored_practice_score' => $data['has_sponsored_practice_score'],
                'receives_food_support_score' => $data['receives_food_support_score'],
                'receives_transport_support_score' => $data['receives_transport_support_score'],
                'receives_tech_support_score' => $data['receives_tech_support_score'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log de auditoría
            Log::info('Puntos de convocatoria creados exitosamente', [
                'puntos_id' => $puntosId,
                'convocatoria_id' => $data['convocatory_selected'],
                'creado_por' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('cefa.sga.admin.convocatory_points')
                ->with('success', 'Puntos de convocatoria creados exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear puntos de convocatoria: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data' => $data ?? null
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear los puntos de convocatoria: ' . $e->getMessage());
        }
    }

    /**
     * Copiar puntos de una convocatoria anterior
     */
    public function copyPoints(Request $request)
    {
        try {
            $request->validate([
                'convocatory_selected' => 'required|integer|exists:convocatories,id',
                'convocatory_source' => 'required|integer|exists:convocatories,id'
            ]);

            $convocatoriaDestino = $request->input('convocatory_selected');
            $convocatoriaOrigen = $request->input('convocatory_source');

            // Verificar que la convocatoria destino sea de tipo "Apoyo de Alimentación"
            $convocatoriaDestinoValida = DB::table('convocatories as c')
                ->join('types_convocatories as tc', 'c.types_convocatories_id', '=', 'tc.id')
                ->where('c.id', $convocatoriaDestino)
                ->where('tc.name', 'Apoyo de Alimentación')
                ->whereNull('c.deleted_at')
                ->first();

            if (!$convocatoriaDestinoValida) {
                return redirect()->back()
                    ->with('error', 'La convocatoria destino no es válida o no es de tipo "Apoyo de Alimentación".');
            }

            // Verificar que no existan puntos para la convocatoria destino
            $puntosExistentes = DB::table('convocatories_points')
                ->where('convocatory_selected', $convocatoriaDestino)
                ->first();

            if ($puntosExistentes) {
                return redirect()->back()
                    ->with('error', 'Ya existen puntos configurados para esta convocatoria. Use la función de editar.');
            }

            // Obtener puntos de la convocatoria origen
            $puntosOrigen = DB::table('convocatories_points')
                ->where('convocatory_selected', $convocatoriaOrigen)
                ->first();

            if (!$puntosOrigen) {
                return redirect()->back()
                    ->with('error', 'La convocatoria origen no tiene puntos configurados.');
            }

            // Copiar los puntos
            $puntosId = DB::table('convocatories_points')->insertGetId([
                'convocatory_selected' => $convocatoriaDestino,
                'victim_conflict_score' => $puntosOrigen->victim_conflict_score,
                'gender_violence_victim_score' => $puntosOrigen->gender_violence_victim_score,
                'disability_score' => $puntosOrigen->disability_score,
                'head_of_household_score' => $puntosOrigen->head_of_household_score,
                'pregnant_or_lactating_score' => $puntosOrigen->pregnant_or_lactating_score,
                'ethnic_group_affiliation_score' => $puntosOrigen->ethnic_group_affiliation_score,
                'natural_displacement_score' => $puntosOrigen->natural_displacement_score,
                'sisben_group_a_score' => $puntosOrigen->sisben_group_a_score,
                'sisben_group_b_score' => $puntosOrigen->sisben_group_b_score,
                'rural_apprentice_score' => $puntosOrigen->rural_apprentice_score,
                'institutional_representative_score' => $puntosOrigen->institutional_representative_score,
                'lives_in_rural_area_score' => $puntosOrigen->lives_in_rural_area_score,
                'spokesperson_elected_score' => $puntosOrigen->spokesperson_elected_score,
                'research_participation_score' => $puntosOrigen->research_participation_score,
                'previous_boarding_quota_score' => $puntosOrigen->previous_boarding_quota_score,
                'has_certification_score' => $puntosOrigen->has_certification_score,
                'attached_sworn_statement_score' => $puntosOrigen->attached_sworn_statement_score,
                'knows_obligations_support_score' => $puntosOrigen->knows_obligations_support_score,
                'renta_joven_beneficiary_score' => $puntosOrigen->renta_joven_beneficiary_score,
                'has_apprenticeship_contract_score' => $puntosOrigen->has_apprenticeship_contract_score,
                'received_fic_support_score' => $puntosOrigen->received_fic_support_score,
                'received_regular_support_score' => $puntosOrigen->received_regular_support_score,
                'has_income_contract_score' => $puntosOrigen->has_income_contract_score,
                'has_sponsored_practice_score' => $puntosOrigen->has_sponsored_practice_score,
                'receives_food_support_score' => $puntosOrigen->receives_food_support_score,
                'receives_transport_support_score' => $puntosOrigen->receives_transport_support_score,
                'receives_tech_support_score' => $puntosOrigen->receives_tech_support_score,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log de auditoría
            Log::info('Puntos de convocatoria copiados exitosamente', [
                'puntos_id' => $puntosId,
                'convocatoria_destino' => $convocatoriaDestino,
                'convocatoria_origen' => $convocatoriaOrigen,
                'copiado_por' => auth()->id(),
                'timestamp' => now()
            ]);

            return redirect()->route('cefa.sga.admin.convocatory_points')
                ->with('success', 'Puntos de convocatoria copiados exitosamente desde la convocatoria anterior');

        } catch (\Exception $e) {
            Log::error('Error al copiar puntos de convocatoria: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error al copiar los puntos de convocatoria: ' . $e->getMessage());
        }
    }

    /**
     * Obtener puntos de una convocatoria específica para AJAX
     */
    public function getPoints($convocatoryId)
    {
        try {
            $puntos = DB::table('convocatories_points')
                ->where('convocatory_selected', $convocatoryId)
                ->first();

            if (!$puntos) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron puntos para esta convocatoria'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'puntos' => $puntos
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener puntos de convocatoria: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los puntos de la convocatoria'
            ], 500);
        }
    }
}
