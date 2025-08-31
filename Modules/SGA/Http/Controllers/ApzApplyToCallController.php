<?php

namespace Modules\SGA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SGA\Entities\CallsApplication;
use Modules\SGA\Entities\Convocatory;
use Modules\SGA\Entities\ConvocatoryPoints;

class ApzApplyToCallController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.apply-to-call");
        $titleView = trans("sga::menu.apply-to-call");

        // Obtener la convocatoria de alimentación
        $convocatory = \Modules\SGA\Entities\Convocatory::find(4);

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'convocatory' => $convocatory
        ];

        return view('sga::apprentice.apply-to-call', $data);
    }

    public function processApplication(Request $request)
    {
        try {
            // Obtener el usuario autenticado y su persona
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            // Buscar la convocatoria activa de alimentación
            $convocatory = Convocatory::find(4);
            
            if (!$convocatory || $convocatory->status !== 'Active') {
                return response()->json([
                    'success' => false,
                    'message' => 'La convocatoria no está activa'
                ], 400);
            }

            // Verificar si ya existe una aplicación para esta persona y convocatoria
            $existingApplication = CallsApplication::where('person_id', $person->id)
                ->where('convocatory_selected', 4)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes una aplicación activa para esta convocatoria'
                ], 400);
            }

            // Obtener los puntos de la convocatoria
            $convocatoryPoints = ConvocatoryPoints::where('convocatory_id', 4)->first();
            
            if (!$convocatoryPoints) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron los puntos de la convocatoria'
                ], 500);
            }

            // Calcular el puntaje total
            $totalPoints = $this->calculatePoints($person, $convocatoryPoints);

            // Crear la aplicación
            $application = CallsApplication::create([
                'person_id' => $person->id,
                'convocatory_selected' => 4,
                'total_points' => $totalPoints,
                'personal_points' => $totalPoints,
                'formation_points' => 0,
                'representative_points' => 0,
                'housing_points' => 0,
                'medical_points' => 0,
                'socioeconomic_points' => 0,
                'conditions_points' => 0,
                'declaration_points' => 0,
                'status' => 'Active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Aplicación procesada correctamente',
                'total_points' => $totalPoints,
                'application_id' => $application->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la aplicación: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculatePoints($person, $convocatoryPoints)
    {
        $totalPoints = 0;

        // Puntos por información socioeconómica
        $socioeconomicInfo = $person->socioeconomicInformation;
        if ($socioeconomicInfo) {
            if ($socioeconomicInfo->renta_joven_beneficiary) {
                $totalPoints += $convocatoryPoints->renta_joven_beneficiary ?? 0;
            }
            if ($socioeconomicInfo->victim_conflict) {
                $totalPoints += $convocatoryPoints->victim_conflict ?? 0;
            }
            if ($socioeconomicInfo->displaced_person) {
                $totalPoints += $convocatoryPoints->displaced_person ?? 0;
            }
        }

        // Puntos por condiciones del aprendiz
        $apprenticeConditions = $person->conditions;
        if ($apprenticeConditions) {
            if ($apprenticeConditions->disability) {
                $totalPoints += $convocatoryPoints->disability ?? 0;
            }
            if ($apprenticeConditions->research_participation) {
                $totalPoints += $convocatoryPoints->research_participation ?? 0;
            }
        }

        // Puntos por declaración jurada
        $swornStatement = $person->swornStatement;
        if ($swornStatement && $swornStatement->attached_sworn_statement) {
            $totalPoints += $convocatoryPoints->attached_sworn_statement ?? 0;
        }

        return $totalPoints;
    }
}