<?php

namespace Modules\SGA\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApzMyBenefitController extends Controller
{
    public function myBenefit()
    {
        $titlePage = trans("sga::menu.my-benefit");
        $titleView = trans("sga::menu.my-benefit");

        // Obtener el usuario autenticado y su persona
        $user = auth()->user();
        $person = $user->person;

        // Buscar la aplicación activa del aprendiz a la convocatoria de alimentación
        $application = null;
        $convocatory = null;
        $benefitStatus = 'No aplicado';
        $benefitData = null;

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
                    // Determinar el estado del beneficio
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
        }

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'application' => $application,
            'convocatory' => $convocatory,
            'benefitStatus' => $benefitStatus,
            'benefitData' => $benefitData,
            'person' => $person
        ];

        return view('sga::apprentice.my-benefit', $data);
    }

    public function benHistory()
    {
        $titlePage = trans("sga::menu.history-benefit");
        $titleView = trans("sga::menu.history-benefit");

        // Obtener el usuario autenticado y su persona
        $user = auth()->user();
        $person = $user->person;

        // Inicializar variables
        $applicationsHistory = [];
        $activeConvocatory = null;
        $activeApplication = null;
        $statistics = [
            'total_applications' => 0,
            'active_applications' => 0,
            'total_points_earned' => 0,
            'average_points' => 0
        ];

        if ($person) {
            // Obtener la convocatoria más reciente y activa de "Apoyo de Alimentación"
            $activeConvocatory = $this->getActiveConvocatory();
            
            // Obtener todas las aplicaciones del aprendiz a convocatorias de alimentación
            $allApplications = \Modules\SGA\Entities\CallsApplication::where('person_id', $person->id)
                ->join('convocatories', 'calls_applications.convocatory_selected', '=', 'convocatories.id')
                ->join('types_convocatories', 'convocatories.types_convocatories_id', '=', 'types_convocatories.id')
                ->where('types_convocatories.name', 'Apoyo de Alimentación')
                ->select('calls_applications.*', 'convocatories.name as convocatory_name', 'convocatories.quarter', 'convocatories.year', 'convocatories.status as convocatory_status', 'convocatories.registration_start_date', 'convocatories.registration_deadline', 'convocatories.coups')
                ->orderBy('calls_applications.created_at', 'desc')
                ->get();

            if ($allApplications->count() > 0) {
                foreach ($allApplications as $application) {
                    // Calcular posición en el cupo para esta convocatoria
                    $applicationsCount = \Modules\SGA\Entities\CallsApplication::where('convocatory_selected', $application->convocatory_selected)->count();
                    $positionByPoints = \Modules\SGA\Entities\CallsApplication::where('convocatory_selected', $application->convocatory_selected)
                        ->where('total_points', '>', $application->total_points)
                        ->count();
                    $position = $positionByPoints + 1;

                    // Determinar el nivel del cupo
                    $cupLevel = $position <= $application->coups ? 'DENTRO DEL CUPO' : 'EN LISTA DE ESPERA';
                    $cupStatus = $position <= $application->coups ? 'success' : 'warning';

                    // Determinar el estado de la aplicación
                    $applicationStatus = 'Inactiva';
                    if ($application->convocatory_status === 'Active') {
                        $now = Carbon::now();
                        $startDate = Carbon::parse($application->registration_start_date);
                        $deadline = Carbon::parse($application->registration_deadline);
                        
                        if ($now->between($startDate, $deadline)) {
                            $applicationStatus = 'Activa';
                        } elseif ($now->lt($startDate)) {
                            $applicationStatus = 'Próximamente';
                        } else {
                            $applicationStatus = 'Finalizada';
                        }
                    }

                    $applicationsHistory[] = [
                        'id' => $application->id,
                        'convocatory_name' => $application->convocatory_name,
                        'quarter' => $application->quarter,
                        'year' => $application->year,
                        'total_points' => $application->total_points,
                        'application_date' => $application->created_at,
                        'convocatory_status' => $application->convocatory_status,
                        'application_status' => $applicationStatus,
                        'registration_start' => $application->registration_start_date,
                        'registration_deadline' => $application->registration_deadline,
                        'coups' => $application->coups,
                        'applications_count' => $applicationsCount,
                        'position' => $position,
                        'cup_level' => $cupLevel,
                        'cup_status' => $cupStatus,
                        'is_active' => $activeConvocatory && $application->convocatory_selected == $activeConvocatory->id
                    ];

                    // Actualizar estadísticas
                    $statistics['total_applications']++;
                    $statistics['total_points_earned'] += $application->total_points;
                    
                    if ($applicationStatus === 'Activa') {
                        $statistics['active_applications']++;
                    }
                }

                // Calcular promedio de puntos
                $statistics['average_points'] = round($statistics['total_points_earned'] / $statistics['total_applications']);

                // Obtener la aplicación activa si existe
                $activeApplication = $allApplications->where('convocatory_selected', $activeConvocatory ? $activeConvocatory->id : null)->first();
            }
        }

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'person' => $person,
            'activeConvocatory' => $activeConvocatory,
            'activeApplication' => $activeApplication,
            'applicationsHistory' => $applicationsHistory,
            'statistics' => $statistics
        ];

        return view('sga::apprentice.ben-history', $data);
    }



    private function getDayInSpanish($dayOfWeek)
    {
        $days = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        
        return $days[$dayOfWeek] ?? 'N/A';
    }

    private function getObservationsByStatus($status)
    {
        $observations = [
            'received' => 'Entrega normal',
            'missed' => 'Ausencia sin justificar',
            'justified' => 'Ausencia justificada'
        ];
        
        return $observations[$status] ?? 'N/A';
    }

    /**
     * Mapea el estado de asistencia de la base de datos al formato de la vista
     */
    private function mapAttendanceState($state)
    {
        $stateMap = [
            'P' => 'received',      // Presente = Recibido
            'MF' => 'missed',       // Falta = No Recibido
            'FJ' => 'justified',    // Falta Justificada = Justificado
            'FI' => 'missed'        // Falta Injustificada = No Recibido
        ];
        
        return $stateMap[$state] ?? 'missed';
    }

    /**
     * Obtiene la hora de asistencia basada en el estado
     */
    private function getAttendanceTime($state)
    {
        if ($state === 'P') {
            // Para asistencias, simular hora entre 11:30 AM y 12:30 PM
            return Carbon::createFromTime(11, rand(30, 59), rand(0, 59));
        }
        
        return null;
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
}