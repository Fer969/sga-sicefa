<?php

namespace Modules\SGA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ApzProfileController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.Profile");
        $titleView = trans("sga::menu.Profile");

        // Obtener el usuario autenticado y su persona
        $user = auth()->user();
        $person = $user->person;

        // Cargar departamentos y municipios para los filtros
        $departments = DB::table('departments')->select('id', 'name')->orderBy('name')->get();
        $municipalities = DB::table('municipalities')->select('id', 'name', 'department_id')->orderBy('name')->get();
        
        // Cargar programas para el formulario de formación
        $programs = DB::table('programs')->select('id', 'name')->orderBy('name')->get();
        
        // Cargar EPS para el formulario médico
        $eps = DB::table('e_p_s')->select('id', 'name')->orderBy('name')->get();

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'person' => $person,
            'departments' => $departments,
            'municipalities' => $municipalities,
            'programs' => $programs,
            'eps' => $eps
        ];

        return view('sga::apprentice.profile', $data);
    }

    public function updateProfile(Request $request)
    {
        // Método general para actualizar el perfil
        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente'
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            
            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 400);
            }

            // Actualizar contraseña
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la contraseña: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePersonalInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'personal_info.first_name' => 'required|string|max:255',
            'personal_info.first_last_name' => 'required|string|max:255',
            'personal_info.second_last_name' => 'nullable|string|max:255',
            'personal_info.document_type' => 'required|string|max:50',
            'personal_info.document_number' => 'required|string|max:50',
            'personal_info.date_of_birth' => 'required|date',
            'personal_info.gender' => 'required|string|max:20',
            'personal_info.phone' => 'nullable|string|max:20',
            'personal_info.email' => 'nullable|email|max:255',
            'personal_info.address' => 'nullable|string|max:500',
            'personal_info.department_id' => 'nullable|exists:departments,id',
            'personal_info.municipality_id' => 'nullable|exists:municipalities,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $personalData = $request->input('personal_info');
            
            // Actualizar información personal
            $person->update($personalData);

            return response()->json([
                'success' => true,
                'message' => 'Información personal actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información personal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateFormationInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formation_info.program_id' => 'required|exists:programs,id',
            'formation_info.file_number' => 'required|string|max:50',
            'formation_info.start_date' => 'required|date',
            'formation_info.start_production_date' => 'nullable|date',
            'formation_info.modality' => 'required|string|max:50',
            'formation_info.status' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $formationData = $request->input('formation_info');
            
            // Verificar si ya existe un curso para esta persona
            $existingCourse = \Modules\SICA\Entities\Course::where('person_id', $person->id)->first();
            
            if ($existingCourse) {
                // Actualizar curso existente
                $existingCourse->update($formationData);
            } else {
                // Crear nuevo curso
                $formationData['person_id'] = $person->id;
                \Modules\SICA\Entities\Course::create($formationData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Información de formación actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información de formación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRepresentativeInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'representative_info.first_name' => 'required|string|max:255',
            'representative_info.first_last_name' => 'required|string|max:255',
            'representative_info.second_last_name' => 'nullable|string|max:255',
            'representative_info.document_type' => 'required|string|max:50',
            'representative_info.document_number' => 'required|string|max:50',
            'representative_info.phone' => 'nullable|string|max:20',
            'representative_info.email' => 'nullable|email|max:255',
            'representative_info.relationship' => 'required|string|max:100',
            'representative_info.address' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $representativeData = $request->input('representative_info');
            $representativeData['person_id'] = $person->id;
            
            // Verificar si ya existe información del representante
            $existingRepresentative = \Modules\SGA\Entities\RepresentativeLegal::where('person_id', $person->id)->first();
            
            if ($existingRepresentative) {
                // Actualizar representante existente
                $existingRepresentative->update($representativeData);
            } else {
                // Crear nuevo representante
                \Modules\SGA\Entities\RepresentativeLegal::create($representativeData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Información del representante legal actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información del representante legal: ' . $e->getMessage()
            ], 500);
        }
    }



    public function updateHousingInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'housing_info.stratum' => 'required|in:1,2,3,4,5,6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $housingData = $request->input('housing_info');
            
            // Actualizar solo el campo socioeconomical_status en la tabla people
            if (isset($housingData['stratum'])) {
                $person->update(['socioeconomical_status' => $housingData['stratum']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Información de vivienda (estrato) actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información de vivienda: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMedicalInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medical_info.eps_id' => 'required|exists:e_p_s,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $medicalData = $request->input('medical_info');
            
            // Actualizar solo el campo eps_id en la tabla people
            if (isset($medicalData['eps_id'])) {
                $person->update(['eps_id' => $medicalData['eps_id']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Información médica (EPS) actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información médica: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSocioeconomicInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'socioeconomic_info.family_income' => 'required|string|max:100',
            'socioeconomic_info.income_source' => 'required|string|max:255',
            'socioeconomic_info.has_other_income' => 'required|boolean',
            'socioeconomic_info.other_income_details' => 'nullable|string|max:1000',
            'socioeconomic_info.family_members_count' => 'required|integer|min:1',
            'socioeconomic_info.dependents_count' => 'required|integer|min:0',
            'socioeconomic_info.renta_joven_beneficiary' => 'required|boolean',
            'socioeconomic_info.victim_conflict' => 'required|boolean',
            'socioeconomic_info.displaced_person' => 'required|boolean',
            'socioeconomic_info.ethnic_group' => 'nullable|string|max:100',
            'socioeconomic_info.special_population' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $socioeconomicData = $request->input('socioeconomic_info');
            $socioeconomicData['person_id'] = $person->id;
            
            // Verificar si ya existe información socioeconómica
            $existingSocioeconomic = \Modules\SGA\Entities\SocioeconomicInformation::where('person_id', $person->id)->first();
            
            if ($existingSocioeconomic) {
                // Actualizar información existente
                $existingSocioeconomic->update($socioeconomicData);
            } else {
                // Crear nueva información
                \Modules\SGA\Entities\SocioeconomicInformation::create($socioeconomicData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Información socioeconómica actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información socioeconómica: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateConditionsInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'apprentice_conditions.disability' => 'nullable|string|max:255',
            'apprentice_conditions.disability_type' => 'nullable|string|max:255',
            'apprentice_conditions.research_participation' => 'nullable|string|max:255',
            'apprentice_conditions.research_details' => 'nullable|string|max:1000',
            'apprentice_conditions.special_needs' => 'nullable|string|max:1000',
            'apprentice_conditions.accommodations_required' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $conditionsData = $request->input('apprentice_conditions');
            
            if (!$conditionsData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron datos para actualizar'
                ], 400);
            }

            $conditionsData['person_id'] = $person->id;
            
            // Verificar si ya existe información de condiciones
            $existingConditions = \Modules\SGA\Entities\ApprenticeCondition::where('person_id', $person->id)->first();
            
            if ($existingConditions) {
                // Actualizar información existente
                $existingConditions->update($conditionsData);
            } else {
                // Crear nueva información
                \Modules\SGA\Entities\ApprenticeCondition::create($conditionsData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Información de condiciones del aprendiz actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información de condiciones: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDeclarationInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'declaration_info.attached_sworn_statement' => 'required|boolean',
            'declaration_info.declaration_date' => 'required|date',
            'declaration_info.accepts_terms' => 'required|boolean',
            'declaration_info.accepts_data_processing' => 'required|boolean',
            'declaration_info.additional_comments' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $declarationData = $request->input('declaration_info');
            $declarationData['person_id'] = $person->id;
            
            // Verificar si ya existe declaración jurada
            $existingDeclaration = \Modules\SGA\Entities\SwornStatement::where('person_id', $person->id)->first();
            
            if ($existingDeclaration) {
                // Actualizar declaración existente
                $existingDeclaration->update($declarationData);
            } else {
                // Crear nueva declaración
                \Modules\SGA\Entities\SwornStatement::create($declarationData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Declaración jurada actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la declaración jurada: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveAllSections(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $person = $user->person;

            if (!$person) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la información de la persona'
                ], 404);
            }

            $allData = $request->all();
            $successCount = 0;
            $errors = [];

            // Procesar información personal
            if (isset($allData['personal_info'])) {
                try {
                    $person->update($allData['personal_info']);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors['personal_info'] = $e->getMessage();
                }
            }

            // Procesar información de formación
            if (isset($allData['formation_info'])) {
                try {
                    $formationData = $allData['formation_info'];
                    $existingCourse = \Modules\SICA\Entities\Course::where('person_id', $person->id)->first();
                    
                    if ($existingCourse) {
                        $existingCourse->update($formationData);
                    } else {
                        $formationData['person_id'] = $person->id;
                        \Modules\SICA\Entities\Course::create($formationData);
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errors['formation_info'] = $e->getMessage();
                }
            }

            // Procesar representante legal (solo si es menor de edad)
            if (isset($allData['representative_info']) && isset($allData['is_minor_age']) && $allData['is_minor_age'] == '1') {
                try {
                    $representativeData = $allData['representative_info'];
                    $representativeData['person_id'] = $person->id;
                    
                    $existingRepresentative = \Modules\SGA\Entities\RepresentativeLegal::where('person_id', $person->id)->first();
                    
                    if ($existingRepresentative) {
                        $existingRepresentative->update($representativeData);
                    } else {
                        \Modules\SGA\Entities\RepresentativeLegal::create($representativeData);
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errors['representative_info'] = $e->getMessage();
                }
            }



            // Procesar información de vivienda (solo estrato)
            if (isset($allData['housing_info']) && isset($allData['housing_info']['stratum'])) {
                try {
                    $person->update(['socioeconomical_status' => $allData['housing_info']['stratum']]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors['housing_info'] = $e->getMessage();
                }
            }

            // Procesar información médica (solo EPS)
            if (isset($allData['medical_info']) && isset($allData['medical_info']['eps_id'])) {
                try {
                    $person->update(['eps_id' => $allData['medical_info']['eps_id']]);
                    $successCount++;
                } catch (\Exception $e) {
                    $errors['medical_info'] = $e->getMessage();
                }
            }

            // Procesar información socioeconómica
            if (isset($allData['socioeconomic_info'])) {
                try {
                    $socioeconomicData = $allData['socioeconomic_info'];
                    $socioeconomicData['person_id'] = $person->id;
                    
                    $existingSocioeconomic = \Modules\SGA\Entities\SocioeconomicInformation::where('person_id', $person->id)->first();
                    
                    if ($existingSocioeconomic) {
                        $existingSocioeconomic->update($socioeconomicData);
                    } else {
                        \Modules\SGA\Entities\SocioeconomicInformation::create($socioeconomicData);
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errors['socioeconomic_info'] = $e->getMessage();
                }
            }

            // Procesar condiciones del aprendiz
            if (isset($allData['apprentice_conditions'])) {
                try {
                    $conditionsData = $allData['apprentice_conditions'];
                    $conditionsData['person_id'] = $person->id;
                    
                    $existingConditions = \Modules\SGA\Entities\ApprenticeCondition::where('person_id', $person->id)->first();
                    
                    if ($existingConditions) {
                        $existingConditions->update($conditionsData);
                    } else {
                        \Modules\SGA\Entities\ApprenticeCondition::create($conditionsData);
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errors['apprentice_conditions'] = $e->getMessage();
                }
            }

            // Procesar declaración jurada
            if (isset($allData['declaration_info'])) {
                try {
                    $declarationData = $allData['declaration_info'];
                    $declarationData['person_id'] = $person->id;
                    
                    $existingDeclaration = \Modules\SGA\Entities\SwornStatement::where('person_id', $person->id)->first();
                    
                    if ($existingDeclaration) {
                        $existingDeclaration->update($declarationData);
                    } else {
                        \Modules\SGA\Entities\SwornStatement::create($declarationData);
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errors['declaration_info'] = $e->getMessage();
                }
            }

            if (count($errors) > 0) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Se encontraron errores en algunas secciones',
                    'errors' => $errors,
                    'success_count' => $successCount
                ], 422);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Todas las secciones se guardaron correctamente ({$successCount} secciones)",
                'success_count' => $successCount
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar todas las secciones: ' . $e->getMessage()
            ], 500);
        }
    }
}