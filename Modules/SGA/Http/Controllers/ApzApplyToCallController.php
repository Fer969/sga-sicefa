<?php

namespace Modules\SGA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SGA\Entities\CallsApplication;
use Modules\SGA\Entities\Convocatory;
use Modules\SGA\Entities\ConvocatoryPoints;
use Carbon\Carbon;

class ApzApplyToCallController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.apply-to-call");
        $titleView = trans("sga::menu.apply-to-call");

        // Obtener la convocatoria más reciente y activa de alimentación
        $convocatory = $this->getActiveConvocatory();

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'convocatory' => $convocatory
        ];

        return view('sga::apprentice.apply-to-call', $data);
    }

    /**
     * Obtener la convocatoria más reciente y activa de alimentación
     */
    private function getActiveConvocatory()
    {
        // Buscar el tipo de convocatoria de alimentación
        $tipoAlimentacion = DB::table('types_convocatories')
            ->where('name', 'LIKE', '%Alimentación%')
            ->orWhere('name', 'LIKE', '%alimentacion%')
            ->first();

        if (!$tipoAlimentacion) {
            return null;
        }

        // Obtener la convocatoria más reciente y activa
        $convocatory = DB::table('convocatories')
            ->where('types_convocatories_id', $tipoAlimentacion->id)
            ->where('status', 'Active')
            ->orderBy('created_at', 'desc')
            ->first();

        return $convocatory;
    }

    /**
     * Validar si la convocatoria está en período de registro
     */
    private function validateRegistrationPeriod($convocatory)
    {
        if (!$convocatory) {
            return [
                'valid' => false,
                'message' => 'No hay convocatorias activas disponibles'
            ];
        }

        $now = Carbon::now();

        // Verificar fecha de inicio
        if ($convocatory->registration_start_date) {
            $startDate = Carbon::parse($convocatory->registration_start_date);
            if ($now->lt($startDate)) {
                return [
                    'valid' => false,
                    'message' => 'El período de registro aún no ha comenzado. Inicia el ' . $startDate->format('d/m/Y')
                ];
            }
        }

        // Verificar fecha de cierre
        if ($convocatory->registration_deadline) {
            $deadline = Carbon::parse($convocatory->registration_deadline);
            if ($now->gt($deadline)) {
                return [
                    'valid' => false,
                    'message' => 'El período de registro ha finalizado. Terminó el ' . $deadline->format('d/m/Y')
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Período de registro válido'
        ];
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
            $convocatory = $this->getActiveConvocatory();
            
            if (!$convocatory) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay convocatorias activas disponibles'
                ], 400);
            }

            // Validar período de registro
            $periodValidation = $this->validateRegistrationPeriod($convocatory);
            if (!$periodValidation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $periodValidation['message']
                ], 400);
            }

            // Verificar si ya existe una aplicación para esta persona y convocatoria
            $existingApplication = CallsApplication::where('person_id', $person->id)
                ->where('convocatory_selected', $convocatory->id)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes una aplicación activa para esta convocatoria'
                ], 400);
            }

            // Obtener los puntos de la convocatoria
            $convocatoryPoints = ConvocatoryPoints::where('convocatory_selected', $convocatory->id)->first();
            
            if (!$convocatoryPoints) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron los puntos de la convocatoria'
                ], 500);
            }

            // Calcular el puntaje total con desglose
            $pointsData = $this->calculatePointsWithBreakdown($person, $convocatoryPoints);
            $totalPoints = $pointsData['total_points'];
            $pointsBreakdown = $pointsData['breakdown'];

            // Crear la aplicación con todos los puntos detallados
            $applicationData = [
                'person_id' => $person->id,
                'convocatory_selected' => $convocatory->id,
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
            ];

            // Agregar los puntos detallados según el breakdown
            foreach ($pointsBreakdown as $field => $data) {
                if ($data['applied']) {
                    // Mapear el campo del breakdown al campo de la tabla calls_applications
                    $pointsField = $this->mapFieldToPointsField($field);
                    if ($pointsField) {
                        $applicationData[$pointsField] = $data['points'];
                        \Log::info("Guardando punto individual: {$field} -> {$pointsField} = {$data['points']}");
                    }
                } else {
                    // Si no se aplicó, guardar 0 en el campo correspondiente
                    $pointsField = $this->mapFieldToPointsField($field);
                    if ($pointsField) {
                        $applicationData[$pointsField] = 0;
                        \Log::info("Guardando punto individual: {$field} -> {$pointsField} = 0 (no aplicó)");
                    }
                }
            }

            \Log::info('Datos de aplicación a guardar:', $applicationData);

            $application = CallsApplication::create($applicationData);

            // Preparar respuesta con información detallada
            $appliedPoints = array_filter($pointsBreakdown, function($item) {
                return $item['applied'];
            });

            $notAppliedPoints = array_filter($pointsBreakdown, function($item) {
                return !$item['applied'];
            });

            return response()->json([
                'success' => true,
                'message' => 'Aplicación procesada correctamente',
                'total_points' => $totalPoints,
                'application_id' => $application->id,
                'convocatory_name' => $convocatory->name,
                'points_summary' => [
                    'total_applied' => count($appliedPoints),
                    'total_not_applied' => count($notAppliedPoints),
                    'applied_points' => $appliedPoints,
                    'not_applied_points' => $notAppliedPoints
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en processApplication: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la aplicación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcular puntos con desglose detallado
     */
    private function calculatePointsWithBreakdown($person, $convocatoryPoints)
    {
        $totalPoints = 0;
        $pointsBreakdown = [];

        // 1. Puntos por condiciones del aprendiz (ApprenticeCondition)
        $apprenticeConditions = $person->conditions;
        if ($apprenticeConditions) {
            // Mapeo de campos de ApprenticeCondition a ConvocatoryPoints
            $conditionMappings = [
                'victim_conflict' => 'victim_conflict_score',
                'gender_violence_victim' => 'gender_violence_victim_score',
                'disability' => 'disability_score',
                'head_of_household' => 'head_of_household_score',
                'pregnant_or_lactating' => 'pregnant_or_lactating_score',
                'ethnic_group_affiliation' => 'ethnic_group_affiliation_score',
                'natural_displacement' => 'natural_displacement_score',
                'sisben_group_a' => 'sisben_group_a_score',
                'sisben_group_b' => 'sisben_group_b_score',
                'rural_apprentice' => 'rural_apprentice_score',
                'institutional_representative' => 'institutional_representative_score',
                'lives_in_rural_area' => 'lives_in_rural_area_score',
                'spokesperson_elected' => 'spokesperson_elected_score',
                'research_participation' => 'research_participation_score',
                'previous_boarding_quota' => 'previous_boarding_quota_score',
                'has_certification' => 'has_certification_score',
                'attached_sworn_statement' => 'attached_sworn_statement_score',
                'knows_obligations_support' => 'knows_obligations_support_score'
            ];

            foreach ($conditionMappings as $conditionField => $pointsField) {
                $conditionValue = $apprenticeConditions->$conditionField;
                $pointsValue = $convocatoryPoints->$pointsField ?? 0;
                
                // Si el campo es "si", aplicar el puntaje; si es "no", aplicar 0
                if (strtolower($conditionValue) === 'si' || $conditionValue === true || $conditionValue === 1) {
                    $totalPoints += $pointsValue;
                    $pointsBreakdown[$conditionField] = [
                        'value' => $conditionValue,
                        'points' => $pointsValue,
                        'points_field' => $pointsField,
                        'applied' => true,
                        'category' => 'conditions'
                    ];
                } else {
                    $pointsBreakdown[$conditionField] = [
                        'value' => $conditionValue,
                        'points' => $pointsValue,
                        'points_field' => $pointsField,
                        'applied' => false,
                        'category' => 'conditions'
                    ];
                }
            }
        }

        // 2. Puntos por información socioeconómica (SocioeconomicInformation)
        $socioeconomicInfo = $person->socioeconomic;
        if ($socioeconomicInfo) {
            // Mapeo de campos de SocioeconomicInformation a ConvocatoryPoints
            $socioeconomicMappings = [
                'renta_joven_beneficiary' => 'renta_joven_beneficiary_score',
                'has_apprenticeship_contract' => 'has_apprenticeship_contract_score',
                'received_fic_support' => 'received_fic_support_score',
                'received_regular_support' => 'received_regular_support_score',
                'has_income_contract' => 'has_income_contract_score',
                'has_sponsored_practice' => 'has_sponsored_practice_score',
                'receives_food_support' => 'receives_food_support_score',
                'receives_transport_support' => 'receives_transport_support_score',
                'receives_tech_support' => 'receives_tech_support_score'
            ];

            foreach ($socioeconomicMappings as $socioField => $pointsField) {
                $socioValue = $socioeconomicInfo->$socioField;
                $pointsValue = $convocatoryPoints->$pointsField ?? 0;
                
                // Si el campo es "si", aplicar el puntaje; si es "no", aplicar 0
                if (strtolower($socioValue) === 'si' || $socioValue === true || $socioValue === 1) {
                    $totalPoints += $pointsValue;
                    $pointsBreakdown[$socioField] = [
                        'value' => $socioValue,
                        'points' => $pointsValue,
                        'points_field' => $pointsField,
                        'applied' => true,
                        'category' => 'socioeconomic'
                    ];
                } else {
                    $pointsBreakdown[$socioField] = [
                        'value' => $socioValue,
                        'points' => $pointsValue,
                        'points_field' => $pointsField,
                        'applied' => false,
                        'category' => 'socioeconomic'
                    ];
                }
            }
        }

        // 3. Puntos por declaración jurada (SwornStatement)
        $swornStatements = $person->swornStatements;
        if ($swornStatements && $swornStatements->count() > 0) {
            // Si tiene declaraciones juradas, aplicar el puntaje
            $pointsValue = $convocatoryPoints->attached_sworn_statement_score ?? 0;
            $totalPoints += $pointsValue;
            $pointsBreakdown['attached_sworn_statement'] = [
                'value' => 'Tiene declaraciones juradas',
                'points' => $pointsValue,
                'points_field' => 'attached_sworn_statement_score',
                'applied' => true,
                'category' => 'sworn_statement'
            ];
        } else {
            $pointsBreakdown['attached_sworn_statement'] = [
                'value' => 'No tiene declaraciones juradas',
                'points' => $convocatoryPoints->attached_sworn_statement_score ?? 0,
                'points_field' => 'attached_sworn_statement_score',
                'applied' => false,
                'category' => 'sworn_statement'
            ];
        }

        // Log para debugging
        \Log::info('Cálculo de puntos para persona ID: ' . $person->id, [
            'total_points' => $totalPoints,
            'breakdown' => $pointsBreakdown,
            'convocatory_points_id' => $convocatoryPoints->id
        ]);

        return [
            'total_points' => $totalPoints,
            'breakdown' => $pointsBreakdown
        ];
    }

    /**
     * Mapear el campo del breakdown al campo de la tabla calls_applications
     */
    private function mapFieldToPointsField($field)
    {
        // Mapeo directo de campos a sus campos de puntos correspondientes
        $fieldMappings = [
            // Campos de condiciones del aprendiz
            'victim_conflict' => 'victim_conflict_points',
            'gender_violence_victim' => 'gender_violence_victim_points',
            'disability' => 'disability_points',
            'head_of_household' => 'head_of_household_points',
            'pregnant_or_lactating' => 'pregnant_or_lactating_points',
            'ethnic_group_affiliation' => 'ethnic_group_affiliation_points',
            'natural_displacement' => 'natural_displacement_points',
            'sisben_group_a' => 'sisben_group_a_points',
            'sisben_group_b' => 'sisben_group_b_points',
            'rural_apprentice' => 'rural_apprentice_points',
            'institutional_representative' => 'institutional_representative_points',
            'lives_in_rural_area' => 'lives_in_rural_area_points',
            'spokesperson_elected' => 'spokesperson_elected_points',
            'research_participation' => 'research_participation_points',
            'previous_boarding_quota' => 'previous_boarding_quota_points',
            'has_certification' => 'has_certification_points',
            'attached_sworn_statement' => 'attached_sworn_statement_points',
            'knows_obligations_support' => 'knows_obligations_support_points',
            
            // Campos de información socioeconómica
            'renta_joven_beneficiary' => 'renta_joven_beneficiary_points',
            'has_apprenticeship_contract' => 'has_apprenticeship_contract_points',
            'received_fic_support' => 'received_fic_support_points',
            'received_regular_support' => 'received_regular_support_points',
            'has_income_contract' => 'has_income_contract_points',
            'has_sponsored_practice' => 'has_sponsored_practice_points',
            'receives_food_support' => 'receives_food_support_points',
            'receives_transport_support' => 'receives_transport_support_points',
            'receives_tech_support' => 'receives_tech_support_points'
        ];

        return $fieldMappings[$field] ?? null;
    }
}