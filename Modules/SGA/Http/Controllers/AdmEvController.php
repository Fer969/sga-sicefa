<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdmEvController extends Controller 
{
    public function index()
    {
        $titlePage = trans("sga::menu.ev-history");
        $titleView = trans("sga::menu.ev-history");

        // Consultar convocatorias de Apoyo de Alimentación con el número de postulados y puntajes
        $convocatories = DB::table('convocatories')
            ->leftJoin('calls_applications', 'convocatories.id', '=', 'calls_applications.convocatory_selected')
            ->leftJoin('convocatories_points', 'convocatories.id', '=', 'convocatories_points.convocatory_selected')
            ->where('convocatories.types_convocatories_id', 4) // Filtrar solo Apoyo de Alimentación
            ->select(
                'convocatories.id',
                'convocatories.name',
                'convocatories.quarter',
                'convocatories.year',
                'convocatories.status',
                'convocatories.coups',
                'convocatories.registration_start_date',
                'convocatories.registration_deadline',
                DB::raw('COUNT(DISTINCT calls_applications.id) as postulados'),
                DB::raw('CASE WHEN convocatories_points.id IS NOT NULL THEN "Configurado" ELSE "Sin configurar" END as puntaje_status'),
                DB::raw('CASE WHEN convocatories_points.id IS NOT NULL THEN 
                    COALESCE(convocatories_points.victim_conflict_score, 0) +
                    COALESCE(convocatories_points.gender_violence_victim_score, 0) +
                    COALESCE(convocatories_points.disability_score, 0) +
                    COALESCE(convocatories_points.head_of_household_score, 0) +
                    COALESCE(convocatories_points.pregnant_or_lactating_score, 0) +
                    COALESCE(convocatories_points.ethnic_group_affiliation_score, 0) +
                    COALESCE(convocatories_points.natural_displacement_score, 0) +
                    COALESCE(convocatories_points.sisben_group_a_score, 0) +
                    COALESCE(convocatories_points.sisben_group_b_score, 0) +
                    COALESCE(convocatories_points.rural_apprentice_score, 0) +
                    COALESCE(convocatories_points.institutional_representative_score, 0) +
                    COALESCE(convocatories_points.lives_in_rural_area_score, 0) +
                    COALESCE(convocatories_points.spokesperson_elected_score, 0) +
                    COALESCE(convocatories_points.research_participation_score, 0) +
                    COALESCE(convocatories_points.previous_boarding_quota_score, 0) +
                    COALESCE(convocatories_points.has_certification_score, 0) +
                    COALESCE(convocatories_points.attached_sworn_statement_score, 0) +
                    COALESCE(convocatories_points.knows_obligations_support_score, 0) +
                    COALESCE(convocatories_points.renta_joven_beneficiary_score, 0) +
                    COALESCE(convocatories_points.has_apprenticeship_contract_score, 0) +
                    COALESCE(convocatories_points.received_fic_support_score, 0) +
                    COALESCE(convocatories_points.received_regular_support_score, 0) +
                    COALESCE(convocatories_points.has_income_contract_score, 0) +
                    COALESCE(convocatories_points.has_sponsored_practice_score, 0) +
                    COALESCE(convocatories_points.receives_food_support_score, 0) +
                    COALESCE(convocatories_points.receives_transport_support_score, 0) +
                    COALESCE(convocatories_points.receives_tech_support_score, 0)
                ELSE 0 END as puntaje_total'),
                // Campos detallados de puntajes
                'convocatories_points.victim_conflict_score',
                'convocatories_points.gender_violence_victim_score',
                'convocatories_points.disability_score',
                'convocatories_points.head_of_household_score',
                'convocatories_points.pregnant_or_lactating_score',
                'convocatories_points.ethnic_group_affiliation_score',
                'convocatories_points.natural_displacement_score',
                'convocatories_points.sisben_group_a_score',
                'convocatories_points.sisben_group_b_score',
                'convocatories_points.rural_apprentice_score',
                'convocatories_points.institutional_representative_score',
                'convocatories_points.lives_in_rural_area_score',
                'convocatories_points.spokesperson_elected_score',
                'convocatories_points.research_participation_score',
                'convocatories_points.previous_boarding_quota_score',
                'convocatories_points.has_certification_score',
                'convocatories_points.attached_sworn_statement_score',
                'convocatories_points.knows_obligations_support_score',
                'convocatories_points.renta_joven_beneficiary_score',
                'convocatories_points.has_apprenticeship_contract_score',
                'convocatories_points.received_fic_support_score',
                'convocatories_points.received_regular_support_score',
                'convocatories_points.has_income_contract_score',
                'convocatories_points.has_sponsored_practice_score',
                'convocatories_points.receives_food_support_score',
                'convocatories_points.receives_transport_support_score',
                'convocatories_points.receives_tech_support_score'
            )
            ->groupBy(
                'convocatories.id',
                'convocatories.name',
                'convocatories.quarter',
                'convocatories.year',
                'convocatories.status',
                'convocatories.coups',
                'convocatories.registration_start_date',
                'convocatories.registration_deadline',
                'convocatories_points.id',
                'convocatories_points.victim_conflict_score',
                'convocatories_points.gender_violence_victim_score',
                'convocatories_points.disability_score',
                'convocatories_points.head_of_household_score',
                'convocatories_points.pregnant_or_lactating_score',
                'convocatories_points.ethnic_group_affiliation_score',
                'convocatories_points.natural_displacement_score',
                'convocatories_points.sisben_group_a_score',
                'convocatories_points.sisben_group_b_score',
                'convocatories_points.rural_apprentice_score',
                'convocatories_points.institutional_representative_score',
                'convocatories_points.lives_in_rural_area_score',
                'convocatories_points.spokesperson_elected_score',
                'convocatories_points.research_participation_score',
                'convocatories_points.previous_boarding_quota_score',
                'convocatories_points.has_certification_score',
                'convocatories_points.attached_sworn_statement_score',
                'convocatories_points.knows_obligations_support_score',
                'convocatories_points.renta_joven_beneficiary_score',
                'convocatories_points.has_apprenticeship_contract_score',
                'convocatories_points.received_fic_support_score',
                'convocatories_points.received_regular_support_score',
                'convocatories_points.has_income_contract_score',
                'convocatories_points.has_sponsored_practice_score',
                'convocatories_points.receives_food_support_score',
                'convocatories_points.receives_transport_support_score',
                'convocatories_points.receives_tech_support_score'
            )
            ->orderBy('convocatories.registration_start_date', 'desc')
            ->get();

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'convocatories' => $convocatories
        ];

        return view('sga::admin.ev-history', $data);
    }

    /**
     * Obtener puntajes detallados de una convocatoria específica
     */
    public function getPuntajesDetallados(Request $request)
    {
        $convocatoriaId = $request->get('convocatoria_id');
        
        $puntajes = DB::table('convocatories_points')
            ->where('convocatory_selected', $convocatoriaId)
            ->first();

        if (!$puntajes) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron puntajes para esta convocatoria'
            ]);
        }

        // Definir los nombres de los campos de puntaje
        $camposPuntaje = [
            'victim_conflict_score' => 'Víctima del Conflicto',
            'gender_violence_victim_score' => 'Víctima de Violencia de Género',
            'disability_score' => 'Persona con Discapacidad',
            'head_of_household_score' => 'Jefe de Hogar',
            'pregnant_or_lactating_score' => 'Embarazada o Lactante',
            'ethnic_group_affiliation_score' => 'Pertenencia a Grupo Étnico',
            'natural_displacement_score' => 'Desplazamiento Natural',
            'sisben_group_a_score' => 'Sisbén Grupo A',
            'sisben_group_b_score' => 'Sisbén Grupo B',
            'rural_apprentice_score' => 'Aprendiz Rural',
            'institutional_representative_score' => 'Representante Institucional',
            'lives_in_rural_area_score' => 'Vive en Zona Rural',
            'spokesperson_elected_score' => 'Vocero Electo',
            'research_participation_score' => 'Participación en Investigación',
            'previous_boarding_quota_score' => 'Cuota de Alimentación Anterior',
            'has_certification_score' => 'Tiene Certificación',
            'attached_sworn_statement_score' => 'Declaración Jurada Adjunta',
            'knows_obligations_support_score' => 'Conoce Obligaciones del Apoyo',
            'renta_joven_beneficiary_score' => 'Beneficiario Renta Joven',
            'has_apprenticeship_contract_score' => 'Tiene Contrato de Aprendizaje',
            'received_fic_support_score' => 'Recibió Apoyo FIC',
            'received_regular_support_score' => 'Recibió Apoyo Regular',
            'has_income_contract_score' => 'Tiene Contrato de Ingresos',
            'has_sponsored_practice_score' => 'Tiene Práctica Patrocinada',
            'receives_food_support_score' => 'Recibe Apoyo de Alimentación',
            'receives_transport_support_score' => 'Recibe Apoyo de Transporte',
            'receives_tech_support_score' => 'Recibe Apoyo Tecnológico'
        ];

        $puntajesDetallados = [];
        $total = 0;

        foreach ($camposPuntaje as $campo => $nombre) {
            $valor = $puntajes->$campo ?? 0;
            $puntajesDetallados[] = [
                'nombre' => $nombre,
                'puntaje' => $valor
            ];
            $total += $valor;
        }

        return response()->json([
            'success' => true,
            'puntajes' => $puntajesDetallados,
            'total' => $total
        ]);
    }
}