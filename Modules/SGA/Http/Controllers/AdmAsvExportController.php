<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdmAsvExportController extends Controller
{
    /**
     * Exportar asistencias a PDF
     */
    public function exportPDF(Request $request)
    {
        try {
            // Obtener los datos usando la misma lógica del controlador original
            $asistencias = $this->getAsistenciasData($request);
            
            // Verificar si hay datos para exportar
            if ($asistencias->count() === 0) {
                return redirect()->back()->with('warning', 'No hay datos para exportar con los filtros aplicados.');
            }
            
            // Preparar datos para el PDF
            $data = [
                'asistencias' => $asistencias,
                'filters' => [
                    'name' => $request->name,
                    'document_number' => $request->document_number,
                    'date' => $request->date,
                    'course_id' => $request->course_id
                ],
                'total' => $asistencias->count(),
                'generated_at' => Carbon::now()->format('d/m/Y H:i:s')
            ];
            
            // Log para debug
            Log::info('Generando PDF con ' . $asistencias->count() . ' registros');
            
            // Generar PDF
            $pdf = PDF::loadView('sga::exports.asv-pdf', $data);
            $pdf->setPaper('A4', 'landscape');
            
            $filename = 'asistencias_convocatorias_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
            
            // Log para debug
            Log::info('PDF generado: ' . $filename);
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Error generando PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Exportar asistencias a Word
     */
    public function exportWord(Request $request)
    {
        try {
            // Obtener los datos usando la misma lógica del controlador original
            $asistencias = $this->getAsistenciasData($request);
            
            // Verificar si hay datos para exportar
            if ($asistencias->count() === 0) {
                return redirect()->back()->with('warning', 'No hay datos para exportar con los filtros aplicados.');
            }
            
            // Log para debug
            Log::info('Generando Word con ' . $asistencias->count() . ' registros');
            
            // Crear documento Word
            $phpWord = new PhpWord();
            
            // Configurar estilos
            $phpWord->addFontStyle('titleStyle', array('bold' => true, 'size' => 16, 'name' => 'Arial'));
            $phpWord->addFontStyle('headerStyle', array('bold' => true, 'size' => 12, 'name' => 'Arial'));
            $phpWord->addFontStyle('normalStyle', array('size' => 10, 'name' => 'Arial'));
            
            // Agregar sección
            $section = $phpWord->addSection([
                'orientation' => 'landscape',
                'marginLeft' => 600,
                'marginRight' => 600,
                'marginTop' => 600,
                'marginBottom' => 600
            ]);
            
            // Título
            $section->addText(
                trans('sga::contents.AdmAsvTitle'),
                'titleStyle',
                array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
            );
            
            $section->addTextBreak(1);
            
            // Información de filtros aplicados
            if ($request->filled('name') || $request->filled('document_number') || 
                $request->filled('date') || $request->filled('course_id')) {
                
                $section->addText('Filtros aplicados:', 'headerStyle');
                
                if ($request->filled('name')) {
                    $section->addText('• Aprendiz: ' . $request->name, 'normalStyle');
                }
                if ($request->filled('document_number')) {
                    $section->addText('• Documento: ' . $request->document_number, 'normalStyle');
                }
                if ($request->filled('date')) {
                    $section->addText('• Fecha: ' . Carbon::parse($request->date)->format('d/m/Y'), 'normalStyle');
                }
                if ($request->filled('course_id')) {
                    $curso = DB::table('courses')->where('id', $request->course_id)->first();
                    if ($curso) {
                        $section->addText('• Curso: ' . $curso->code, 'normalStyle');
                    }
                }
                
                $section->addTextBreak(1);
            }
            
            // Total de registros
            $section->addText(
                'Total de asistencias: ' . $asistencias->count(),
                'headerStyle'
            );
            
            $section->addTextBreak(1);
            
            // Crear tabla
            $tableStyle = array(
                'borderSize' => 6,
                'borderColor' => '999999',
                'cellMargin' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER
            );
            
            $table = $section->addTable($tableStyle);
            
            // Encabezados de tabla
            $table->addRow(500);
            $table->addCell(800, array('bgColor' => 'CCCCCC'))->addText('ID', 'headerStyle');
            $table->addCell(3000, array('bgColor' => 'CCCCCC'))->addText('Aprendiz', 'headerStyle');
            $table->addCell(1500, array('bgColor' => 'CCCCCC'))->addText('Documento', 'headerStyle');
            $table->addCell(2000, array('bgColor' => 'CCCCCC'))->addText('Curso', 'headerStyle');
            $table->addCell(1500, array('bgColor' => 'CCCCCC'))->addText('Fecha', 'headerStyle');
            $table->addCell(2000, array('bgColor' => 'CCCCCC'))->addText('Estado', 'headerStyle');
            
            // Datos de la tabla
            $counter = 1;
            foreach ($asistencias as $apprentice) {
                foreach ($apprentice->asistencias as $asistencia) {
                    $table->addRow();
                    $table->addCell(800)->addText($counter++, 'normalStyle');
                    $table->addCell(3000)->addText($apprentice->person->name, 'normalStyle');
                    $table->addCell(1500)->addText($apprentice->person->document_number, 'normalStyle');
                    $table->addCell(2000)->addText($apprentice->course->code, 'normalStyle');
                    $table->addCell(1500)->addText(
                        Carbon::parse($asistencia->asistance->date)->format('d/m/Y'),
                        'normalStyle'
                    );
                    $table->addCell(2000)->addText($asistencia->asistance->type_asistance, 'normalStyle');
                }
            }
            
            // Pie de página con fecha de generación
            $section->addTextBreak(2);
            $section->addText(
                'Documento generado el: ' . Carbon::now()->format('d/m/Y H:i:s'),
                'normalStyle',
                array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT)
            );
            
            // Crear el archivo en storage temporal
            $filename = 'asistencias_convocatorias_' . Carbon::now()->format('Y-m-d_H-i-s') . '.docx';
            $tempPath = storage_path('app/temp/' . $filename);
            
            // Crear directorio si no existe
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempPath);
            
            // Log para debug
            Log::info('Word generado: ' . $filename . ' en ' . $tempPath);
            
            // Verificar que el archivo se creó
            if (!file_exists($tempPath)) {
                throw new \Exception('No se pudo crear el archivo Word');
            }
            
            return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error generando Word: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el archivo Word: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener datos de asistencias (misma lógica del controlador original)
     */
    private function getAsistenciasData(Request $request)
    {
        // Construir la consulta SQL directa (copiada del controlador original)
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
        
        // Obtener asistencias para cada aprendiz
        $asistencias = collect();
        
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
                
                // Crear objeto similar a Eloquent para compatibilidad
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
            }
        }

        return $asistencias;
    }
}