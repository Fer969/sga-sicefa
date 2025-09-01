<?php

namespace Modules\SGA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprenticeController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.Home");
        $titleView = trans("sga::menu.Home");

        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Obtener el usuario autenticado y su persona
        $user = auth()->user();
        $person = $user->person;
        
        // Verificar que la persona exista
        if (!$person) {
            return redirect()->back()->with('error', 'No se encontró información de la persona asociada a tu cuenta.');
        }
        
        // Cargar las relaciones necesarias para la vista
        $person->load(['apprentices.course.program']);

        // Inicializar variables
        $benefitStatus = 'No aplicado';
        $benefitData = null;
        $convocatory = null;
        $application = null;
        $dashboardStats = [];

        if ($person) {
            // Obtener la convocatoria más reciente y activa de "Apoyo de Alimentación"
            $convocatory = $this->getActiveConvocatory();
            
            if ($convocatory) {
                // Buscar la aplicación del aprendiz a esta convocatoria
                $application = \Modules\SGA\Entities\CallsApplication::where('person_id', $person->id)
                    ->where('convocatory_selected', $convocatory->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($application) {
                    if ($convocatory->status === 'Active') {
                        $benefitStatus = 'Activo';
                        
                        // Calcular estadísticas del beneficio
                        $benefitData = [
                            'total_points' => $application->total_points,
                            'application_date' => $application->created_at,
                            'convocatory_name' => $convocatory->name,
                            'quarter' => $convocatory->quarter,
                            'year' => $convocatory->year,
                            'registration_start' => $convocatory->registration_start_date,
                            'registration_deadline' => $convocatory->registration_deadline,
                            'coups' => $convocatory->coups
                        ];
                        
                        // Calcular la posición del aprendiz en el cupo
                        $applicationsCount = \Modules\SGA\Entities\CallsApplication::where('convocatory_selected', $convocatory->id)->count();
                        $benefitData['applications_count'] = $applicationsCount;
                        
                        // Calcular posición por puntaje (orden descendente)
                        $positionByPoints = \Modules\SGA\Entities\CallsApplication::where('convocatory_selected', $convocatory->id)
                            ->where('total_points', '>', $application->total_points)
                            ->count();
                        $benefitData['position_by_points'] = $positionByPoints + 1; // +1 porque es posición, no índice
                        
                        // Determinar el nivel del cupo
                        if ($benefitData['position_by_points'] <= $convocatory->coups) {
                            $benefitData['cup_level'] = 'DENTRO DEL CUPO';
                            $benefitData['cup_status'] = 'success';
                        } else {
                            $benefitData['cup_level'] = 'EN LISTA DE ESPERA';
                            $benefitData['cup_status'] = 'warning';
                        }
                    } else {
                        $benefitStatus = 'Inactivo';
                    }
                }
            }

            // Estadísticas del dashboard
            $dashboardStats = [
                'available_calls' => \Modules\SGA\Entities\Convocatory::where('status', 'Active')->count(),
                'total_applications' => $application ? 1 : 0,
                'profile_completion' => $this->calculateProfileCompletion($person),
                'active_convocatory' => $convocatory ? $convocatory->name : 'No disponible'
            ];
        }

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'person' => $person,
            'application' => $application,
            'convocatory' => $convocatory,
            'benefitStatus' => $benefitStatus,
            'benefitData' => $benefitData,
            'dashboardStats' => $dashboardStats
        ];

        return view('sga::apprentice.index', $data);
    }

    /**
     * Obtener la convocatoria más reciente y activa de "Apoyo de Alimentación"
     */
    private function getActiveConvocatory()
    {
        return DB::table('types_convocatories')
            ->join('convocatories', 'types_convocatories.id', '=', 'convocatories.types_convocatories_id')
            ->where('types_convocatories.name', 'Apoyo de Alimentación')
            ->where('convocatories.status', 'Active')
            ->orderBy('convocatories.created_at', 'desc')
            ->select('convocatories.*')
            ->first();
    }

    private function calculateProfileCompletion($person)
    {
        $sections = 0;
        $completedSections = 0;

        // Información personal
        $sections++;
        if ($person->first_name && $person->first_last_name && $person->document_number) {
            $completedSections++;
        }

        // Información de formación
        $sections++;
        if ($person->course && $person->course->program_id) {
            $completedSections++;
        }

        // Representante legal (solo si es menor de edad)
        if ($person->date_of_birth) {
            $age = \Carbon\Carbon::parse($person->date_of_birth)->age;
            if ($age < 18) {
                $sections++;
                if ($person->representativeLegal) {
                    $completedSections++;
                }
            }
        }

        // Información de vivienda
        $sections++;
        if ($person->housingInformation) {
            $completedSections++;
        }

        // Información médica
        $sections++;
        if ($person->medical) {
            $completedSections++;
        }

        // Información socioeconómica
        $sections++;
        if ($person->socioeconomicInformation) {
            $completedSections++;
        }

        // Condiciones del aprendiz
        $sections++;
        if ($person->conditions) {
            $completedSections++;
        }

        // Declaración jurada
        $sections++;
        if ($person->swornStatement) {
            $completedSections++;
        }

        return $sections > 0 ? round(($completedSections / $sections) * 100) : 0;
    }
}