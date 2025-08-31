<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SGA\Entities\Apprentice;
use Illuminate\Support\Facades\DB;

class AdmBSummaryController extends Controller 
{
    public function index()
    {
        $titlePage = trans("sga::menu.b-summary");
        $titleView = trans("sga::menu.b-summary");

        // Consultar aprendices con sus asistencias de alimentación (igual que en AdmAsvController)
        $apprentices = DB::table('apprentices')
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
            ->where('courses.deschooling', 'Presencial')
            ->get();

        // Obtener asistencias (almuerzos) para cada aprendiz
        $aprendices = collect();
        
        foreach($apprentices as $apprentice) {
            // Contar asistencias de "Convocatoria de Alimentación" (almuerzos consumidos)
            $totalAlmuerzos = DB::table('apprentice_asistencia')
                ->join('asistances', 'apprentice_asistencia.asistance_id', '=', 'asistances.id')
                ->where('apprentice_asistencia.apprentice_id', $apprentice->apprentice_id)
                ->where('asistances.type_asistance', 'Convocatoria de Alimentación')
                ->count();
            
            // Solo incluir aprendices que tienen almuerzos
            if ($totalAlmuerzos > 0) {
                // Concatenar el nombre completo
                $fullName = trim($apprentice->first_name . ' ' . $apprentice->first_last_name . ' ' . $apprentice->second_last_name);
                
                // Crear objeto con los datos necesarios
                $aprendices->push((object)[
                    'id' => $apprentice->apprentice_id,
                    'full_name' => $fullName,
                    'document_number' => $apprentice->document_number,
                    'course_code' => $apprentice->course_code,
                    'total_almuerzos' => $totalAlmuerzos
                ]);
            }
        }

        // Ordenar por número de almuerzos descendente
        $aprendices = $aprendices->sortByDesc('total_almuerzos');

        // Valor por almuerzo
        $valorAlmuerzo = 12000;

        // Calcular totales
        $totalAlmuerzosCount = $aprendices->sum('total_almuerzos');
        $totalValorOriginal = $totalAlmuerzosCount * $valorAlmuerzo;
        $totalDescuento = $totalValorOriginal * 0.5;

        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'aprendices' => $aprendices,
            'valorAlmuerzo' => $valorAlmuerzo,
            'totalAlmuerzosCount' => $totalAlmuerzosCount,
            'totalValorOriginal' => $totalValorOriginal,
            'totalDescuento' => $totalDescuento
        ];

        return view('sga::admin.b-summary', $data);
    }
}