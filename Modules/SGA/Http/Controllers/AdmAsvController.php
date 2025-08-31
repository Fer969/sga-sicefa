<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SGA\Entities\Course;
use Modules\SGA\Entities\Apprentice;
use Modules\SGA\Entities\Asistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmAsvController extends Controller
{
    public function index()
    {
        $titlePage = trans("sga::menu.Attendance");
        $titleView = trans("sga::menu.Attendance");

        $cursos = Course::all();
        // Renderiza la vista de asistencias con los cursos disponibles
        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'cursos' => $cursos,
        ];
        // Obtener cursos usando consulta SQL directa
        $cursos = DB::table('courses')
                    ->select('id', 'code')
                    ->where('status', 'Activo')
                    ->where('deschooling', 'Presencial')
                    ->orderBy('code', 'asc')
                    ->get();
        return view('sga::admin.asv', $data, compact('cursos'));
    }

    public function asistenciasJornada(Request $request)
    {
        $titlePage = trans("sga::menu.Attendance");
        $titleView = trans("sga::menu.Attendance");
        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
        ];

        // Obtener cursos para el select
        $cursos = DB::table('courses')
                    ->select('id', 'code')
                    ->where('status', 'Activo')
                    ->where('deschooling', 'Presencial')
                    ->orderBy('code', 'asc')
                    ->get();

        // Construir la consulta SQL directa
        $query = DB::table('apprentices')
                   ->join('people', 'apprentices.person_id', '=', 'people.id')
                   ->join('courses', 'apprentices.course_id', '=', 'courses.id')
                   ->select(
                       'apprentices.id as apprentice_id',
                       'apprentices.person_id',
                       'apprentices.course_id',
                       'people.first_name',
                       'people.first_last_name',
                       'people.second_last_name',
                       'people.document_number',
                       'courses.code as course_code'
                   )
                   ->where('courses.status', 'Activo')
                   ->where('courses.deschooling', 'Presencial');

        // Aplicar filtros
        if ($request->filled('name')) {
            $searchName = '%' . $request->name . '%';
            $query->where(function($q) use ($searchName) {
                $q->where('people.first_name', 'LIKE', $searchName)
                  ->orWhere('people.first_last_name', 'LIKE', $searchName)
                  ->orWhere('people.second_last_name', 'LIKE', $searchName)
                  ->orWhereRaw("CONCAT(people.first_name, ' ', people.first_last_name) LIKE ?", [$searchName])
                  ->orWhereRaw("CONCAT(people.first_name, ' ', people.first_last_name, ' ', people.second_last_name) LIKE ?", [$searchName]);
            });
        }

        if ($request->filled('document_number')) {
            $query->where('people.document_number', 'LIKE', '%' . $request->document_number . '%');
        }

        if ($request->filled('course_id')) {
            $query->where('apprentices.course_id', $request->course_id);
        }

        $apprentices = $query->get();
        
        // Para debug: contar aprendices encontrados
        $totalApprenticesFound = $apprentices->count();
        
        // Obtener asistencias para cada aprendiz
        $asistencias = collect();
        $apprenticesWithOtherAsistencias = collect();
        
        foreach($apprentices as $apprentice) {
            // Verificar si tiene asistencias de "Convocatoria de Alimentación"
            $asistenciasQuery = DB::table('apprentice_asistencia')
                                  ->join('asistances', 'apprentice_asistencia.asistance_id', '=', 'asistances.id')
                                  ->select('asistances.date', 'asistances.type_asistance')
                                  ->where('apprentice_asistencia.apprentice_id', $apprentice->apprentice_id)
                                  ->where('asistances.type_asistance', 'Convocatoria de Alimentación');
            
            // Filtrar por fecha si se especifica
            if ($request->filled('date')) {
                $asistenciasQuery->whereDate('asistances.date', $request->date);
            }
            
            $asistenciasData = $asistenciasQuery->get();
            
            if ($asistenciasData->count() > 0) {
                // Concatenar el nombre completo
                $fullName = trim($apprentice->first_name . ' ' . $apprentice->first_last_name . ' ' . $apprentice->second_last_name);
                
                // Crear objeto similar a Eloquent para compatibilidad con la vista
                $apprenticeObj = (object)[
                    'person' => (object)[
                        'name' => $fullName,
                        'document_number' => $apprentice->document_number
                    ],
                    'course' => (object)[
                        'code' => $apprentice->course_code
                    ],
                    'asistencias' => $asistenciasData->map(function($asistencia) {
                        return (object)[
                            'asistance' => (object)[
                                'date' => $asistencia->date,
                                'type_asistance' => $asistencia->type_asistance
                            ]
                        ];
                    })
                ];
                
                $asistencias->push($apprenticeObj);
            } else {
                // Verificar si tiene otro tipo de asistencias (para debug)
                $otherAsistencias = DB::table('apprentice_asistencia')
                                     ->join('asistances', 'apprentice_asistencia.asistance_id', '=', 'asistances.id')
                                     ->select('asistances.type_asistance')
                                     ->where('apprentice_asistencia.apprentice_id', $apprentice->apprentice_id)
                                     ->distinct()
                                     ->pluck('type_asistance');
                
                if ($otherAsistencias->count() > 0) {
                    $fullName = trim($apprentice->first_name . ' ' . $apprentice->first_last_name . ' ' . $apprentice->second_last_name);
                    $apprenticesWithOtherAsistencias->push((object)[
                        'name' => $fullName,
                        'document' => $apprentice->document_number,
                        'course' => $apprentice->course_code,
                        'asistencia_types' => $otherAsistencias->toArray()
                    ]);
                }
            }
        }

        // Pasar información de debug a la vista
        $debugInfo = (object)[
            'total_apprentices_found' => $totalApprenticesFound,
            'apprentices_with_convocatorias' => $asistencias->count(),
            'apprentices_with_other_asistencias' => $apprenticesWithOtherAsistencias
        ];

        return view('sga::admin.asv', compact('cursos', 'asistencias', 'debugInfo'), $data);
    }
}