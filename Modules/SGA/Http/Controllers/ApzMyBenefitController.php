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
        $benefitHistory = [];
        $statistics = [
            'total_received' => 0,
            'total_missed' => 0,
            'total_justified' => 0,
            'attendance_percentage' => 0
        ];

        if ($person) {
            // Buscar la aplicación activa del aprendiz
            $application = \Modules\SGA\Entities\CallsApplication::where('person_id', $person->id)
                ->where('convocatory_selected', 4) // Convocatoria de Alimentación
                ->orderBy('created_at', 'desc')
                ->first();

            if ($application) {
                // Obtener información de la convocatoria
                $convocatory = \Modules\SGA\Entities\Convocatory::find(4);
                
                if ($convocatory) {
                    // Consultar datos reales de asistencia del aprendiz
                    $attendanceRecords = DB::table('attendance_apprentices')
                        ->where('person_id', $person->id)
                        ->orderBy('date', 'desc')
                        ->get();

                    // Procesar registros de asistencia reales
                    foreach ($attendanceRecords as $record) {
                        $date = Carbon::parse($record->date);
                        $status = $this->mapAttendanceState($record->state);
                        
                        $benefitHistory[] = [
                            'date' => $date,
                            'day_name' => $date->format('l'),
                            'day_spanish' => $this->getDayInSpanish($date->dayOfWeek),
                            'time' => $this->getAttendanceTime($record->state),
                            'status' => $status,
                            'observations' => $this->getObservationsByStatus($status),
                            'can_justify' => $status === 'missed',
                            'original_state' => $record->state
                        ];
                        
                        // Actualizar estadísticas
                        if ($status === 'received') {
                            $statistics['total_received']++;
                        } elseif ($status === 'missed') {
                            $statistics['total_missed']++;
                        } elseif ($status === 'justified') {
                            $statistics['total_justified']++;
                        }
                    }
                    
                    // Si no hay registros de asistencia, mostrar mensaje informativo
                    if (count($benefitHistory) === 0) {
                        $benefitHistory = [];
                    }
                    
                    // Calcular porcentaje de asistencia
                    $totalDays = count($benefitHistory);
                    if ($totalDays > 0) {
                        $statistics['attendance_percentage'] = round(($statistics['total_received'] / $totalDays) * 100);
                    }
                }
            }
        }

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'person' => $person,
            'application' => $application,
            'convocatory' => $convocatory,
            'benefitHistory' => $benefitHistory,
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