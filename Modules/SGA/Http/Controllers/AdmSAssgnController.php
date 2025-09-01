<?php

namespace Modules\SGA\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\CallsApplication;
use App\Models\Convocatory;
use App\Models\TypesConvocatory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmSAssgnController extends Controller
{
    public function index(Request $request)
    {
        try {
            Log::info('=== DEBUG PASO 1: Iniciando consulta de postulados de alimentación ===');
            
            $titlePage = trans("sga::menu.s-assgn");
            $titleView = trans("sga::menu.s-assgn");
            
            // Buscar el tipo de convocatoria de alimentación
            $tipoAlimentacion = DB::table('types_convocatories')
                ->where('name', 'LIKE', '%Alimentación%')
                ->orWhere('name', 'LIKE', '%alimentacion%')
                ->first();
            
            if (!$tipoAlimentacion) {
                Log::warning('No se encontró tipo de convocatoria de alimentación');
                return view('sga::admin.s-assgn', [
                    'titlePage' => $titlePage,
                    'titleView' => $titleView,
                    'postulados' => collect(),
                    'convocatorias' => collect(),
                    'error' => 'No se encontró el tipo de convocatoria de alimentación'
                ]);
            }
            
            // Buscar todas las convocatorias de alimentación para el selector
            $convocatorias = DB::table('convocatories')
                ->where('types_convocatories_id', $tipoAlimentacion->id)
                ->where('status', 'Active')
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($convocatorias->isEmpty()) {
                Log::warning('No se encontraron convocatorias de alimentación');
                return view('sga::admin.s-assgn', [
                    'titlePage' => $titlePage,
                    'titleView' => $titleView,
                    'postulados' => collect(),
                    'convocatorias' => collect(),
                    'error' => 'No se encontraron convocatorias de alimentación'
                ]);
            }
            
            // Obtener la convocatoria seleccionada
            $convocatoriaSeleccionada = $request->get('convocatoria_id');
            
            // Si no hay convocatoria seleccionada, usar la más reciente
            if (!$convocatoriaSeleccionada) {
                $convocatoriaSeleccionada = $convocatorias->first()->id;
            }
            
            // Verificar que la convocatoria seleccionada existe
            $convocatoriaActual = $convocatorias->where('id', $convocatoriaSeleccionada)->first();
            if (!$convocatoriaActual) {
                $convocatoriaSeleccionada = $convocatorias->first()->id;
                $convocatoriaActual = $convocatorias->first();
            }
        
        // Detectar automáticamente el tipo de JOIN necesario
        $camposCallsApplications = DB::getSchemaBuilder()->getColumnListing('calls_applications');
        
        $postulados = collect();
        
        if (in_array('apprentice_id', $camposCallsApplications)) {
            // OPCIÓN 1: Si calls_applications tiene apprentice_id
            Log::info('Usando JOIN con apprentice_id');
            
            $postulados = DB::table('calls_applications as ca')
                ->join('apprentices as a', 'ca.apprentice_id', '=', 'a.id')
                ->join('people as p', 'a.person_id', '=', 'p.id')
                ->join('courses as c', 'a.course_id', '=', 'c.id')
                ->where('ca.convocatory_selected', $convocatoriaSeleccionada)
                ->select([
                    'ca.id',
                    'ca.convocatory_selected',
                    'ca.created_at',
                    'ca.updated_at',
                    'ca.total_points', // ← CAMPO ESPECÍFICO PARA PUNTAJE
                    // Campos de persona
                    'p.document_number',
                    'p.first_name',
                    'p.first_last_name', 
                    'p.second_last_name',
                    'p.telephone1', // ← CAMPO ESPECÍFICO PARA TELÉFONO
                    'p.personal_email', // ← CAMPO ESPECÍFICO PARA EMAIL
                    DB::raw("CONCAT(p.first_name, ' ', p.first_last_name, ' ', COALESCE(p.second_last_name, '')) as full_name"),
                    // Campos de curso
                    'c.code as program',
                    // Otros campos de calls_applications que puedan ser útiles
                    'ca.*'
                ])
                ->get();
                
        } elseif (in_array('person_id', $camposCallsApplications)) {
            // OPCIÓN 2: Si calls_applications tiene person_id directamente
            Log::info('Usando JOIN con person_id');
            
            $postulados = DB::table('calls_applications as ca')
                ->join('people as p', 'ca.person_id', '=', 'p.id')
                ->leftJoin('apprentices as a', 'p.id', '=', 'a.person_id')
                ->leftJoin('courses as c', 'a.course_id', '=', 'c.id')
                ->where('ca.convocatory_selected', $convocatoriaSeleccionada)
                ->select([
                    'ca.id',
                    'ca.convocatory_selected',
                    'ca.created_at',
                    'ca.updated_at',
                    'ca.total_points', // ← CAMPO ESPECÍFICO PARA PUNTAJE
                    // Campos de persona
                    'p.document_number',
                    'p.first_name',
                    'p.first_last_name',
                    'p.second_last_name',
                    'p.telephone1', // ← CAMPO ESPECÍFICO PARA TELÉFONO
                    'p.personal_email', // ← CAMPO ESPECÍFICO PARA EMAIL
                    DB::raw("CONCAT(p.first_name, ' ', p.first_last_name, ' ', COALESCE(p.second_last_name, '')) as full_name"),
                    // Campos de curso
                    'c.code as program',
                    'ca.*'
                ])
                ->get();
                
        } elseif (in_array('document_number', $camposCallsApplications)) {
            // OPCIÓN 3: Si calls_applications tiene document_number
            Log::info('Usando JOIN con document_number');
            
            $postulados = DB::table('calls_applications as ca')
                ->join('people as p', 'ca.document_number', '=', 'p.document_number')
                ->leftJoin('apprentices as a', 'p.id', '=', 'a.person_id')
                ->leftJoin('courses as c', 'a.course_id', '=', 'c.id')
                ->where('ca.convocatory_selected', $convocatoriaSeleccionada)
                ->select([
                    'ca.id',
                    'ca.convocatory_selected', 
                    'ca.created_at',
                    'ca.updated_at',
                    'ca.total_points', // ← CAMPO ESPECÍFICO PARA PUNTAJE
                    // Campos de persona
                    'p.document_number',
                    'p.first_name',
                    'p.first_last_name',
                    'p.second_last_name',
                    'p.telephone1', // ← CAMPO ESPECÍFICO PARA TELÉFONO
                    'p.personal_email', // ← CAMPO ESPECÍFICO PARA EMAIL
                    DB::raw("CONCAT(p.first_name, ' ', p.first_last_name, ' ', COALESCE(p.second_last_name, '')) as full_name"),
                    // Campos de curso
                    'c.code as program',
                    'ca.*'
                ])
                ->get();
        } else {
            // OPCIÓN 4: Sin conexión directa - solo mostrar calls_applications
            Log::warning('No se encontró conexión directa con people/apprentices');
            
            $postulados = DB::table('calls_applications')
                ->where('convocatory_selected', $convocatoriaSeleccionada)
                ->select([
                    'id',
                    'convocatory_selected',
                    'created_at',
                    'updated_at',
                    'total_points', // ← CAMPO ESPECÍFICO PARA PUNTAJE
                    // Agregar campos por defecto para compatibilidad
                    DB::raw("'N/A' as document_number"),
                    DB::raw("'N/A' as full_name"),
                    DB::raw("'N/A' as program"),
                    DB::raw("'N/A' as telephone1"),
                    DB::raw("'N/A' as personal_email")
                ])
                ->get();
        }
        
        // Log para debugging
        if ($postulados->count() > 0) {
            $primerPostulado = $postulados->first();
            Log::info('=== DEBUG CAMPOS OBTENIDOS ===', [
                'total_points' => $primerPostulado->total_points ?? 'NO ENCONTRADO',
                'telephone1' => $primerPostulado->telephone1 ?? 'NO ENCONTRADO',
                'personal_email' => $primerPostulado->personal_email ?? 'NO ENCONTRADO',
                'todos_los_campos' => array_keys((array)$primerPostulado)
            ]);
        }
        
        Log::info('=== CONSULTA COMPLETADA ===', [
            'total_postulados' => $postulados->count(),
            'tipo_join_usado' => $this->getTipoJoinUsado($camposCallsApplications)
        ]);
        
        $data = [
            'titlePage' => $titlePage,
            'titleView' => $titleView,
            'postulados' => $postulados,
            'convocatorias' => $convocatorias,
            'convocatoria_actual' => $convocatoriaActual,
            'total_postulados' => $postulados->count(),
            'tipo_convocatoria' => $tipoAlimentacion
        ];
        
        return view('sga::admin.s-assgn', $data);
            
        } catch (\Exception $e) {
            Log::error('=== ERROR EN CONSULTA DE POSTULADOS ===', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine()
            ]);
            
            return view('sga::admin.s-assgn', [
                'titlePage' => trans("sga::menu.s-assgn"),
                'titleView' => trans("sga::menu.s-assgn"),
                'postulados' => collect(),
                'error' => 'Error al consultar postulados: ' . $e->getMessage()
            ]);
        }
    }
    
    // Método auxiliar para calcular puntaje
    private function calcularPuntaje($postulado)
    {
        $puntaje = 0;
    
        // Aquí implementa tu lógica de puntaje basada en los campos de $postulado
        // Ejemplo básico:
    
        // Si es beneficiario de Renta Joven
        if (isset($postulado->renta_joven) && $postulado->renta_joven == 'Si') {
            $puntaje += 2;
        }
    
        // Si es víctima del conflicto
        if (isset($postulado->victima_conflicto) && $postulado->victima_conflicto == 'Si') {
            $puntaje += 3;
        }
    
        // Si es madre cabeza de familia
        if (isset($postulado->madre_cabeza_familia) && $postulado->madre_cabeza_familia == 'Si') {
            $puntaje += 2;
        }
    
        // Agregar más lógica según tus campos
    
        return $puntaje;
    }
    
    // Método auxiliar para debug
    private function getTipoJoinUsado($campos)
    {
        if (in_array('apprentice_id', $campos)) return 'apprentice_id';
        if (in_array('person_id', $campos)) return 'person_id';
        if (in_array('document_number', $campos)) return 'document_number';
        return 'sin_conexion';
    }
    
    // Método de debug adicional para verificar paso a paso
    public function debug()
    {
        Log::info('=== INICIANDO DEBUG MANUAL DESDE CONTROLADOR ===');

        try {
            // Test 1: Conexión básica
            $count = DB::table('types_convocatories')->count();
            Log::info('TEST 1 - Conexión DB: OK', ['total_types' => $count]);

            // Test 2: Verificar tabla types_convocatories
            $tipos = DB::table('types_convocatories')->select('id', 'name')->get();
            Log::info('TEST 2 - Tipos disponibles', ['tipos' => $tipos->toArray()]);

            // Test 3: Buscar "Apoyo de Alimentación"
            $alimentacion = DB::table('types_convocatories')
                ->where('name', 'LIKE', '%Alimentación%')
                ->orWhere('name', 'LIKE', '%alimentacion%')
                ->get();
            Log::info('TEST 3 - Apoyo de Alimentación', ['encontrados' => $alimentacion->toArray()]);

            // Test 4: Verificar calls_applications - ESTRUCTURA COMPLETA
            $totalPostulados = DB::table('calls_applications')->count();
            $camposPostulados = DB::getSchemaBuilder()->getColumnListing('calls_applications');

            // Obtener algunos registros de ejemplo
            $ejemploPostulados = DB::table('calls_applications')->limit(3)->get();

            Log::info('TEST 4 - Calls Applications', [
                'total' => $totalPostulados,
                'campos' => $camposPostulados,
                'ejemplos' => $ejemploPostulados->toArray()
            ]);

            // Test 5: Verificar si existe relación con apprentices
            $apprenticesCount = DB::table('apprentices')->count();
            $camposApprentices = DB::getSchemaBuilder()->getColumnListing('apprentices');

            Log::info('TEST 5 - Apprentices', [
                'total' => $apprenticesCount,
                'campos' => $camposApprentices
            ]);

            // Test 6: Verificar persons
            $personsCount = DB::table('people')->count(); // Nota: Laravel pluraliza 'person' a 'people'
            $camposPersons = DB::getSchemaBuilder()->getColumnListing('people');

            Log::info('TEST 6 - People', [
                'total' => $personsCount,
                'campos' => $camposPersons
            ]);

            // Test 7: Buscar posibles conexiones entre tablas
            // Verificar si calls_applications tiene apprentice_id o person_id
            $posiblesConexiones = [];

            if (in_array('apprentice_id', $camposPostulados)) {
                $posiblesConexiones[] = 'apprentice_id encontrado en calls_applications';
            }

            if (in_array('person_id', $camposPostulados)) {
                $posiblesConexiones[] = 'person_id encontrado en calls_applications';
            }

            if (in_array('document_number', $camposPostulados)) {
                $posiblesConexiones[] = 'document_number encontrado en calls_applications';
            }

            Log::info('TEST 7 - Posibles conexiones', ['conexiones' => $posiblesConexiones]);

            // Test 8: Intentar hacer una consulta JOIN de prueba
            try {
                $joinTest = null;

                if (in_array('apprentice_id', $camposPostulados)) {
                    $joinTest = DB::table('calls_applications as ca')
                        ->join('apprentices as a', 'ca.apprentice_id', '=', 'a.id')
                        ->join('people as p', 'a.person_id', '=', 'p.id')
                        ->select('ca.id as application_id', 'p.first_name', 'p.first_last_name', 'p.document_number')
                        ->limit(5)
                        ->get();
                } elseif (in_array('person_id', $camposPostulados)) {
                    $joinTest = DB::table('calls_applications as ca')
                        ->join('people as p', 'ca.person_id', '=', 'p.id')
                        ->select('ca.id as application_id', 'p.first_name', 'p.first_last_name', 'p.document_number')
                        ->limit(5)
                        ->get();
                }

                if ($joinTest) {
                    Log::info('TEST 8 - JOIN exitoso', ['resultados' => $joinTest->toArray()]);
                } else {
                    Log::info('TEST 8 - No se pudo hacer JOIN', ['razon' => 'No se encontraron campos de conexión']);
                }
            } catch (\Exception $joinError) {
                Log::error('TEST 8 - Error en JOIN', ['error' => $joinError->getMessage()]);
            }

            return response()->json([
                'message' => 'Debug completado. Revisa los logs de Laravel.',
                'tipos_encontrados' => $tipos->count(),
                'alimentacion_encontrada' => $alimentacion->count(),
                'total_postulados' => $totalPostulados,
                'campos_postulados' => $camposPostulados,
                'total_apprentices' => $apprenticesCount,
                'total_persons' => $personsCount,
                'posibles_conexiones' => $posiblesConexiones
            ]);
        } catch (\Exception $e) {
            Log::error('=== ERROR EN DEBUG ===', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error en debug: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exportar postulados a PDF
     */
    public function exportPDF(Request $request)
    {
        try {
            Log::info('=== INICIANDO EXPORTACIÓN PDF ===');
            
            $convocatoriaId = $request->get('convocatoria_id');
            Log::info('Convocatoria ID recibida:', ['id' => $convocatoriaId]);
            
            if (!$convocatoriaId) {
                Log::warning('No se recibió convocatoria_id');
                return redirect()->back()->with('error', 'Debe seleccionar una convocatoria');
            }

            // Obtener datos de la convocatoria
            $convocatoria = DB::table('convocatories')
                ->join('types_convocatories', 'convocatories.types_convocatories_id', '=', 'types_convocatories.id')
                ->where('convocatories.id', $convocatoriaId)
                ->select('convocatories.*', 'types_convocatories.name as tipo_nombre')
                ->first();

            Log::info('Convocatoria encontrada:', ['convocatoria' => $convocatoria]);

            if (!$convocatoria) {
                Log::warning('Convocatoria no encontrada para ID:', ['id' => $convocatoriaId]);
                return redirect()->back()->with('error', 'Convocatoria no encontrada');
            }

            // Obtener postulados para la convocatoria
            $postulados = $this->obtenerPostulados($convocatoriaId);
            Log::info('Postulados obtenidos:', ['count' => $postulados->count()]);

            // Generar HTML simple
            $html = $this->generarHTMLSimple($postulados, $convocatoria);
            
            Log::info('HTML generado, creando PDF...');
            
            // Crear PDF con HTML simple
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('a4', 'landscape');
            
            $nombreArchivo = 'postulados_convocatoria_' . $convocatoria->name . '_' . date('Y-m-d_H-i-s') . '.pdf';
            Log::info('Descargando archivo:', ['nombre' => $nombreArchivo]);

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            Log::error('Error al exportar PDF:', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Obtener postulados para una convocatoria específica
     */
    private function obtenerPostulados($convocatoriaId)
    {
        $camposCallsApplications = DB::getSchemaBuilder()->getColumnListing('calls_applications');
        
        if (in_array('apprentice_id', $camposCallsApplications)) {
            return DB::table('calls_applications as ca')
                ->join('apprentices as a', 'ca.apprentice_id', '=', 'a.id')
                ->join('people as p', 'a.person_id', '=', 'p.id')
                ->join('courses as c', 'a.course_id', '=', 'c.id')
                ->where('ca.convocatory_selected', $convocatoriaId)
                ->select([
                    'ca.id',
                    'ca.total_points',
                    'p.document_number',
                    'p.first_name',
                    'p.first_last_name',
                    'p.second_last_name',
                    'p.telephone1',
                    'p.personal_email',
                    DB::raw("CONCAT(p.first_name, ' ', p.first_last_name, ' ', COALESCE(p.second_last_name, '')) as full_name"),
                    'c.code as program'
                ])
                ->orderBy('ca.total_points', 'desc')
                ->get();
                
        } elseif (in_array('person_id', $camposCallsApplications)) {
            return DB::table('calls_applications as ca')
                ->join('people as p', 'ca.person_id', '=', 'p.id')
                ->leftJoin('apprentices as a', 'p.id', '=', 'a.person_id')
                ->leftJoin('courses as c', 'a.course_id', '=', 'c.id')
                ->where('ca.convocatory_selected', $convocatoriaId)
                ->select([
                    'ca.id',
                    'ca.total_points',
                    'p.document_number',
                    'p.first_name',
                    'p.first_last_name',
                    'p.second_last_name',
                    'p.telephone1',
                    'p.personal_email',
                    DB::raw("CONCAT(p.first_name, ' ', p.first_last_name, ' ', COALESCE(p.second_last_name, '')) as full_name"),
                    'c.code as program'
                ])
                ->orderBy('ca.total_points', 'desc')
                ->get();
                
        } elseif (in_array('document_number', $camposCallsApplications)) {
            return DB::table('calls_applications as ca')
                ->join('people as p', 'ca.document_number', '=', 'p.document_number')
                ->leftJoin('apprentices as a', 'p.id', '=', 'a.person_id')
                ->leftJoin('courses as c', 'a.course_id', '=', 'c.id')
                ->where('ca.convocatory_selected', $convocatoriaId)
                ->select([
                    'ca.id',
                    'ca.total_points',
                    'p.document_number',
                    'p.first_name',
                    'p.first_last_name',
                    'p.second_last_name',
                    'p.telephone1',
                    'p.personal_email',
                    DB::raw("CONCAT(p.first_name, ' ', p.first_last_name, ' ', COALESCE(p.second_last_name, '')) as full_name"),
                    'c.code as program'
                ])
                ->orderBy('ca.total_points', 'desc')
                ->get();
        } else {
            return DB::table('calls_applications')
                ->where('convocatory_selected', $convocatoriaId)
                ->select([
                    'id',
                    'total_points',
                    DB::raw("'N/A' as document_number"),
                    DB::raw("'N/A' as full_name"),
                    DB::raw("'N/A' as program"),
                    DB::raw("'N/A' as telephone1"),
                    DB::raw("'N/A' as personal_email")
                ])
                ->orderBy('total_points', 'desc')
                ->get();
        }
    }

    /**
     * Generar HTML simple para PDF
     */
    private function generarHTMLSimple($postulados, $convocatoria)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Postulados - ' . $convocatoria->name . '</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .header h1 { margin: 0; color: #333; font-size: 18px; }
                .header h2 { margin: 5px 0; color: #666; font-size: 14px; }
                .info { margin-bottom: 20px; font-size: 10px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 9px; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .puntaje-alto { background-color: #d4edda; }
                .puntaje-medio { background-color: #fff3cd; }
                .puntaje-bajo { background-color: #f8d7da; }
                .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>POSTULADOS A CONVOCATORIA</h1>
                <h2>' . $convocatoria->name . '</h2>
                <p>Tipo: ' . $convocatoria->tipo_nombre . '</p>
            </div>

            <div class="info">
                <p><strong>Total de postulados:</strong> ' . $postulados->count() . '</p>
                <p><strong>Fecha de generación:</strong> ' . date('d/m/Y H:i:s') . '</p>
                <p><strong>Convocatoria:</strong> ' . $convocatoria->name . ' (' . $convocatoria->quarter . '° Trimestre ' . $convocatoria->year . ')</p>
            </div>';

        if ($postulados->count() > 0) {
            $html .= '
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Documento</th>
                        <th>Nombre Completo</th>
                        <th>Programa</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Puntaje</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($postulados as $index => $postulado) {
                $clasePuntaje = '';
                if ($postulado->total_points >= 15) {
                    $clasePuntaje = 'puntaje-alto';
                } elseif ($postulado->total_points >= 10) {
                    $clasePuntaje = 'puntaje-medio';
                } else {
                    $clasePuntaje = 'puntaje-bajo';
                }

                $html .= '
                <tr class="' . $clasePuntaje . '">
                    <td>' . ($index + 1) . '</td>
                    <td>' . $postulado->document_number . '</td>
                    <td>' . $postulado->full_name . '</td>
                    <td>' . ($postulado->program ?? 'N/A') . '</td>
                    <td>' . ($postulado->telephone1 ?? 'N/A') . '</td>
                    <td>' . ($postulado->personal_email ?? 'N/A') . '</td>
                    <td><strong>' . ($postulado->total_points ?? 0) . '</strong></td>
                </tr>';
            }

            $html .= '
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                <h3>Resumen de Puntajes:</h3>
                <ul>
                    <li><strong>Puntaje Alto (≥15):</strong> ' . $postulados->where('total_points', '>=', 15)->count() . ' postulados</li>
                    <li><strong>Puntaje Medio (10-14):</strong> ' . $postulados->whereBetween('total_points', [10, 14])->count() . ' postulados</li>
                    <li><strong>Puntaje Bajo (<10):</strong> ' . $postulados->where('total_points', '<', 10)->count() . ' postulados</li>
                </ul>
            </div>';
        } else {
            $html .= '<p><strong>No hay postulados registrados para esta convocatoria.</strong></p>';
        }

        $html .= '
            <div class="footer">
                <p>Documento generado automáticamente el ' . date('d/m/Y') . ' a las ' . date('H:i:s') . '</p>
                <p>Sistema de Gestión Académica - SGA</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}
