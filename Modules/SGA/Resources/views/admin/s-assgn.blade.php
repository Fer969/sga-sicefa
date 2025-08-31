@extends('sga::layouts.master')

@section('content')
<h3 class="mb-4">Asignación de Subsidio</h3>

{{-- Debug Info (solo si hay error o estás en desarrollo) --}}
@if(isset($error))
<div class="alert alert-warning mb-4">
    <strong>Debug:</strong> {{ $error }}
    @if(isset($debug_info))
    <br><small>Información adicional: {{ json_encode($debug_info) }}</small>
    @endif
</div>
@endif

{{-- Inicio de Información de la consulta --}}
@if(isset($total_postulados))
<div class="alert alert-info mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <strong>Total de postulados encontrados:</strong> {{ $total_postulados }}
            @if(isset($tipo_convocatoria))
            <br><small><strong>Tipo de convocatoria:</strong> {{ $tipo_convocatoria->name }}</small>
            @endif
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-success btn-sm" onclick="exportarExcel()">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </button>
            <button class="btn btn-danger btn-sm" onclick="exportarPDF()">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
        </div>
    </div>
</div>
@endif
{{-- Final de Información de la consulta --}}

{{-- Inicio de Filtros avanzados --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-filter"></i> Filtros de Búsqueda
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label for="searchInput" class="form-label">Búsqueda General</label>
                <input type="text"
                    class="form-control"
                    placeholder="Buscar por nombre, documento o programa..."
                    id="searchInput"
                    onkeyup="filtrarTabla()">
            </div>
            <div class="col-md-3">
                <label for="filtroPrograma" class="form-label">Filtrar por Programa</label>
                <select class="form-select" id="filtroPrograma" onchange="filtrarPorPrograma()">
                    <option value="">Todos los programas</option>
                    @if(isset($postulados) && $postulados->count() > 0)
                    @php
                    $programas = $postulados->pluck('program')->unique()->filter()->sort();
                    @endphp
                    @foreach($programas as $programa)
                    <option value="{{ $programa }}">{{ $programa }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroPuntaje" class="form-label">Filtrar por Puntaje</label>
                <select class="form-select" id="filtroPuntaje" onchange="filtrarPorPuntaje()">
                    <option value="">Todos los puntajes</option>
                    <option value="alto">Puntaje Alto (> 15)</option>
                    <option value="medio">Puntaje Medio (10-15)</option>
                    <option value="bajo">Puntaje Bajo (< 10)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
        {{-- Fin de Filtros avanzados --}}

        {{-- Inicio de Información de filtros activos --}}
        <div class="mt-3">
            <div id="contadorResultados" class="alert alert-secondary mb-0" style="display: none;">
                <strong>Resultados filtrados:</strong> <span id="numeroResultados">0</span> de {{ isset($postulados) ? $postulados->count() : 0 }} postulados
            </div>
        </div>
    </div>
</div>
{{-- Fin de Información de filtros activos --}}

{{-- Instrucciones de uso --}}
@if(isset($postulados) && $postulados->count() > 0)
<div class="alert alert-light border-start border-primary border-4 mb-4">
    <div class="row">
        <div class="col-md-8">
            <small>
                <strong>Instrucciones:</strong>
                <ul class="mb-0 mt-1">
                    <li>Use la búsqueda general para filtrar por nombre, documento o programa</li>
                    <li>Click en los encabezados de la tabla para ordenar</li>
                    <li>Use "Ver más" para información detallada de cada postulado</li>
                </ul>
            </small>
        </div>
        <div class="col-md-4 text-end">
            <small class="text-muted">
                <strong>Máximo de cupos:</strong> 350<br>
                <strong>Última actualización:</strong> {{ date('d/m/Y H:i') }}
            </small>
        </div>
    </div>
</div>
@endif
{{-- Fin de Instrucciones de uso --}}

{{-- Tabla de postulados --}}
<table class="table table-bordered" id="tablaSubsidios">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Documento</th>
            <th>Nombre</th>
            <th>Programa</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Puntaje</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($postulados) && $postulados->count() > 0)
        @foreach($postulados as $index => $postulado)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $postulado->document_number ?? 'N/A' }}</td>
            <td>{{ $postulado->full_name ?? trim(($postulado->first_name ?? '') . ' ' . ($postulado->first_last_name ?? '') . ' ' . ($postulado->second_last_name ?? '')) ?: 'N/A' }}</td>
            <td>{{ $postulado->program ?? $postulado->programa ?? 'N/A' }}</td>
            <td>{{ $postulado->telephone1 ?? $postulado->phone ?? $postulado->telefono ?? 'N/A' }}</td>
            <td>{{ $postulado->personal_email ?? $postulado->email ?? $postulado->correo ?? 'N/A' }}</td>
            <td class="text-center">
                @php
                $puntaje = $postulado->total_points ?? $postulado->score ?? $postulado->puntaje ?? 0;
                @endphp
                <span class="badge bg-{{ $puntaje > 15 ? 'success' : ($puntaje > 10 ? 'warning text-dark' : 'secondary') }}">
                    {{ $puntaje }} puntos
                </span>
            </td>
            <td>
                <button class="btn btn-info btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAprendiz{{ $postulado->id }}">
                    Ver más
                </button>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{-- Fin de Tabla de postulados --}}

{{-- Modales dinámicos para postulados reales --}}
@if(isset($postulados) && $postulados->count() > 0)
@foreach($postulados as $postulado)
<div class="modal fade" id="modalAprendiz{{ $postulado->id }}" tabindex="-1" aria-labelledby="labelAprendiz{{ $postulado->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="labelAprendiz{{ $postulado->id }}">
                    Información de {{ $postulado->full_name ?? trim(($postulado->first_name ?? '') . ' ' . ($postulado->first_last_name ?? '')) }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                {{-- Información Personal --}}
                <h6 class="text-secondary">Información Personal</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Documento:</strong> {{ $postulado->document_number ?? 'N/A' }}</li>
                            <li><strong>Primer Nombre:</strong> {{ $postulado->first_name ?? 'N/A' }}</li>
                            <li><strong>Primer Apellido:</strong> {{ $postulado->first_last_name ?? 'N/A' }}</li>
                            <li><strong>Segundo Apellido:</strong> {{ $postulado->second_last_name ?? 'N/A' }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Teléfono:</strong> {{ $postulado->telephone1 ?? $postulado->phone ?? $postulado->telefono ?? 'N/A' }}</li>
                            <li><strong>Email:</strong> {{ $postulado->personal_email ?? $postulado->email ?? $postulado->correo ?? 'N/A' }}</li>
                            <li><strong>Programa:</strong> {{ $postulado->program ?? $postulado->programa ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>

                {{-- Información de Postulación --}}
                <h6 class="text-secondary mt-3">Información de Postulación</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>ID de Postulación:</strong> {{ $postulado->id }}</li>
                            <li><strong>Convocatoria Seleccionada:</strong> {{ $postulado->convocatory_selected }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Fecha de Creación:</strong> {{ isset($postulado->created_at) ? date('d/m/Y H:i', strtotime($postulado->created_at)) : 'N/A' }}</li>
                            <li><strong>Fecha de Actualización:</strong> {{ isset($postulado->updated_at) ? date('d/m/Y H:i', strtotime($postulado->updated_at)) : 'N/A' }}</li>
                        </ul>
                    </div>
                </div>

                {{-- Información Socioeconómica (campos comunes en convocatorias) --}}
                <h6 class="text-secondary mt-3">Información Socioeconómica</h6>
                <div class="row">
                    @php
                    $camposSocioeconomicos = [
                    'renta_joven' => 'Beneficiario Renta Joven',
                    'contrato_aprendizaje' => 'Contrato de Aprendizaje',
                    'apoyo_fic' => 'Apoyo FIC Anterior',
                    'vinculo_laboral' => 'Vínculo Laboral',
                    'apoyo_alimentacion' => 'Apoyo Alimentación SENA',
                    'apoyo_transporte' => 'Apoyo Transporte SENA',
                    'victima_conflicto' => 'Víctima del Conflicto',
                    'madre_cabeza_familia' => 'Madre Cabeza de Familia',
                    'sisben_a' => 'SISBEN Grupo A',
                    'sisben_b' => 'SISBEN Grupo B',
                    'discapacidad' => 'Situación de Discapacidad',
                    'embarazada_lactante' => 'Embarazada o Lactante',
                    'comunidades_narp' => 'Comunidades NARP/ROM/Indígenas',
                    'desplazado_fenomenos' => 'Desplazado por Fenómenos Naturales',
                    'aprendiz_campesino' => 'Aprendiz Campesino',
                    'zona_rural' => 'Vive en Zona Rural'
                    ];
                    @endphp

                    @foreach($camposSocioeconomicos as $campo => $etiqueta)
                    @if(isset($postulado->$campo) && !is_null($postulado->$campo) && $postulado->$campo !== '')
                    <div class="col-md-6 mb-2">
                        <small>
                            <strong>{{ $etiqueta }}:</strong>
                            <span class="badge bg-{{ $postulado->$campo == 'Si' || $postulado->$campo == '1' || $postulado->$campo === 1 ? 'success' : 'secondary' }}">
                                {{ $postulado->$campo == 'Si' || $postulado->$campo == '1' || $postulado->$campo === 1 ? 'Sí' : 'No' }}
                            </span>
                        </small>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Otros Datos (campos que no están en las categorías anteriores) --}}
                <h6 class="text-secondary mt-3">Otros Datos</h6>
                <div class="row">
                    @foreach((array)$postulado as $campo => $valor)
                    @if(!in_array($campo, [
                    'id', 'document_number', 'full_name', 'first_name', 'first_last_name', 'second_last_name',
                    'telephone1', 'personal_email', 'program', 'convocatory_selected', 'created_at', 'updated_at',
                    'telefono', 'correo', 'programa', 'phone', 'email', 'total_points', 'score', 'puntaje',
                    'renta_joven', 'contrato_aprendizaje', 'apoyo_fic', 'vinculo_laboral', 'apoyo_alimentacion',
                    'apoyo_transporte', 'victima_conflicto', 'madre_cabeza_familia', 'sisben_a', 'sisben_b',
                    'discapacidad', 'embarazada_lactante', 'comunidades_narp', 'desplazado_fenomenos',
                    'aprendiz_campesino', 'zona_rural'
                    ]) && !is_null($valor) && $valor !== '')
                    <div class="col-md-6 mb-1">
                        <small><strong>{{ ucwords(str_replace(['_', '-'], ' ', $campo)) }}:</strong> {{ $valor }}</small>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Footer del modal con PUNTAJE EN LA ESQUINA INFERIOR DERECHA --}}
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="imprimirPostulado({{ $postulado->id }})">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>

                {{-- PUNTAJE TOTAL EN LA ESQUINA INFERIOR DERECHA --}}
                <div>
                    @php
                    $puntajeTotal = $postulado->total_points ?? $postulado->score ?? $postulado->puntaje ?? 0;
                    @endphp
                    <h5 class="mb-0">
                        <span class="badge bg-{{ $puntajeTotal > 15 ? 'success' : ($puntajeTotal > 10 ? 'warning text-dark' : 'secondary') }} fs-6 px-3 py-2">
                            <i class="fas fa-star me-1"></i>{{ $puntajeTotal }} puntos
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
{{-- Fin de Modales dinámicos para postulados reales --}}

{{-- Modal de ejemplo (actualizado para consistencia) --}}
<div class="modal fade" id="modalAprendiz1" tabindex="-1" aria-labelledby="labelAprendiz1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="labelAprendiz1">Información de Laura Marcela Torres Nieto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-secondary">Información Personal</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Documento:</strong> 1000123456</li>
                            <li><strong>Primer Nombre:</strong> Laura Marcela</li>
                            <li><strong>Primer Apellido:</strong> Torres</li>
                            <li><strong>Segundo Apellido:</strong> Nieto</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Teléfono:</strong> 3114567890</li>
                            <li><strong>Email:</strong> laura.torres@example.com</li>
                            <li><strong>Programa:</strong> Técnico en Enfermería</li>
                        </ul>
                    </div>
                </div>

                <h6 class="text-secondary mt-3">Información Socioeconómica</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small><strong>Beneficiario Renta Joven:</strong> <span class="badge bg-success">Sí</span></small>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small><strong>Víctima del Conflicto:</strong> <span class="badge bg-success">Sí</span></small>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small><strong>Madre Cabeza de Familia:</strong> <span class="badge bg-success">Sí</span></small>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small><strong>SISBEN Grupo A:</strong> <span class="badge bg-success">Sí</span></small>
                    </div>
                </div>
            </div>

            {{-- Footer con puntaje en la esquina derecha --}}
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="imprimirPostulado(1)">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
                <div>
                    <h5 class="mb-0">
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="fas fa-star me-1"></i>22 puntos
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>                   
{{-- Fin de Modal de ejemplo --}}

{{-- Modal de ejemplo --}}
<div class="modal fade" id="modalAprendiz1" tabindex="-1" aria-labelledby="labelAprendiz1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="labelAprendiz1">Información de Laura Marcela Torres Nieto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-secondary">Información Personal</h6>
                <ul>
                    <li><strong>Documento:</strong> Cédula de ciudadanía - 1000123456</li>
                    <li><strong>Dirección:</strong> Carrera 12 # 45-78</li>
                    <li><strong>Teléfono:</strong> 3114567890</li>
                    <li><strong>Email:</strong> laura.torres@example.com</li>
                    <li><strong>Programa:</strong> Técnico en Enfermería</li>
                </ul>

                <h6 class="text-secondary mt-3">Información Socioeconómica</h6>
                <ul>
                    <li>¿Es beneficiario del Programa Renta Joven? <strong>Sí (2 ptos)</strong></li>
                    <li>¿Tiene contrato de aprendizaje? <strong>No</strong></li>
                    <li>¿Ha recibido apoyo FIC antes? <strong>No</strong></li>
                    <li>¿Apoyo REGULAR anterior? <strong>Sí (1 pto)</strong></li>
                    <li>¿Tiene vínculo laboral o contrato de servicios? <strong>No</strong></li>
                    <li>¿Tiene patrocinio o prácticas con ingreso? <strong>No</strong></li>
                    <li>¿Recibe apoyo de alimentación SENA? <strong>No</strong></li>
                    <li>¿Recibe apoyo de transporte SENA? <strong>Sí (1 pto)</strong></li>
                    <li>¿Recibe medios tecnológicos del SENA? <strong>No</strong></li>
                </ul>

                <h6 class="text-secondary mt-3">Condiciones del Aprendiz</h6>
                <ul>
                    <li>¿Víctima del conflicto armado? <strong>Sí (3 ptos)</strong></li>
                    <li>¿Víctima violencia de género? <strong>No</strong></li>
                    <li>¿Situación de discapacidad? <strong>No</strong></li>
                    <li>¿Madre cabeza de familia? <strong>Sí (2 ptos)</strong></li>
                    <li>¿Embarazada o lactante? <strong>No</strong></li>
                    <li>¿Pertenece a comunidades NARP, ROM o indígenas? <strong>Sí (1 pto)</strong></li>
                    <li>¿Desplazado por fenómenos naturales? <strong>No</strong></li>
                    <li>¿SISBEN grupo A 1-5? <strong>Sí (2 ptos)</strong></li>
                    <li>¿SISBEN grupo B 1-7? <strong>No</strong></li>
                    <li>¿Aprendiz campesino? <strong>No</strong></li>
                    <li>¿Representante institucional? <strong>No</strong></li>
                    <li>¿Vive en zona rural? <strong>No</strong></li>
                    <li>¿Vocero elegido? <strong>No</strong></li>
                    <li>¿Participa en eventos institucionales? <strong>No</strong></li>
                    <li>¿Tuvo internado anterior? <strong>No</strong></li>
                    <li>¿Certificado tecnólogo o profesional? <strong>No</strong></li>
                    <li>¿Adjunta declaración juramentada? <strong>Sí</strong></li>
                    <li>¿Conoce sus obligaciones? <strong>Sí</strong></li>
                </ul>

                <h6 class="text-end text-success">Puntaje total: <strong>22</strong></h6>
            </div>
        </div>
    </div>
</div>
{{-- Fin de Información de la consulta --}}
@endsection

@section('scripts')
<style>
/* Paleta de colores verde aplicada */
:root {
    --forest-green: #1e3a2e;
    --dark-green: #2d5a3d;
    --emerald-green: #3d6b4a;
    --sage-green: #4a7c59;
    --mint-green: #5a9268;
    --light-green: #8fbc8f;
    --ocean-green: #2e5266;
}

/* Estilos personalizados con paleta verde */
.table-dark {
    background-color: var(--forest-green) !important;
    color: white !important;
}

.table-dark th {
    border-color: var(--dark-green) !important;
    background-color: var(--forest-green) !important;
}

.btn-success {
    background-color: var(--sage-green) !important;
    border-color: var(--sage-green) !important;
}

.btn-success:hover {
    background-color: var(--emerald-green) !important;
    border-color: var(--emerald-green) !important;
}

.btn-primary {
    background-color: var(--ocean-green) !important;
    border-color: var(--ocean-green) !important;
}

.btn-primary:hover {
    background-color: var(--dark-green) !important;
    border-color: var(--dark-green) !important;
}

.btn-info {
    background-color: var(--mint-green) !important;
    border-color: var(--mint-green) !important;
    color: white !important;
}

.btn-info:hover {
    background-color: var(--sage-green) !important;
    border-color: var(--sage-green) !important;
}

.btn-outline-secondary {
    color: var(--emerald-green) !important;
    border-color: var(--emerald-green) !important;
}

.btn-outline-secondary:hover {
    background-color: var(--emerald-green) !important;
    border-color: var(--emerald-green) !important;
    color: white !important;
}

.card-header {
    background-color: var(--light-green) !important;
    border-bottom: 1px solid var(--sage-green) !important;
    color: var(--forest-green) !important;
}

.alert-info {
    background-color: rgba(143, 188, 143, 0.2) !important;
    border-color: var(--light-green) !important;
    color: var(--forest-green) !important;
}

.alert-secondary {
    background-color: rgba(90, 146, 104, 0.15) !important;
    border-color: var(--mint-green) !important;
    color: var(--dark-green) !important;
}

.alert-light {
    background-color: rgba(143, 188, 143, 0.1) !important;
    border-left: 4px solid var(--sage-green) !important;
    color: var(--forest-green) !important;
}

.border-primary {
    border-color: var(--sage-green) !important;
}

.modal-header.bg-primary {
    background-color: var(--ocean-green) !important;
}

.badge.bg-success {
    background-color: var(--sage-green) !important;
}

.badge.bg-warning {
    background-color: var(--mint-green) !important;
    color: white !important;
}

.badge.bg-secondary {
    background-color: var(--emerald-green) !important;
}

.text-secondary {
    color: var(--dark-green) !important;
}

.text-success {
    color: var(--sage-green) !important;
}

.text-primary {
    color: var(--ocean-green) !important;
}

/* Estilos para ordenamiento de tabla */
#tablaSubsidios thead th.asc { 
    background-color: rgba(143, 188, 143, 0.3) !important;
    color: var(--forest-green) !important;
}

#tablaSubsidios thead th.desc { 
    background-color: rgba(90, 146, 104, 0.3) !important;
    color: var(--forest-green) !important;
}

#tablaSubsidios thead th:hover { 
    background-color: rgba(74, 124, 89, 0.2) !important;
    color: var(--forest-green) !important;
}

/* Estilos para formularios */
.form-control:focus {
    border-color: var(--mint-green) !important;
    box-shadow: 0 0 0 0.2rem rgba(90, 146, 104, 0.25) !important;
}

.form-select:focus {
    border-color: var(--mint-green) !important;
    box-shadow: 0 0 0 0.2rem rgba(90, 146, 104, 0.25) !important;
}

/* Hover effects para filas de tabla */
#tablaSubsidios tbody tr:hover {
    background-color: rgba(143, 188, 143, 0.1) !important;
}

/* Estilos para badges en modales */
.modal-body .badge.bg-success {
    background-color: var(--sage-green) !important;
}

.modal-body .badge.bg-secondary {
    background-color: var(--emerald-green) !important;
}

.modal-body .badge.bg-warning {
    background-color: var(--mint-green) !important;
    color: white !important;
}
</style>

<h3 class="mb-4" style="color: var(--forest-green);">Asignación de Subsidio</h3>

{{-- Debug Info (solo si hay error o estás en desarrollo) --}}
@if(isset($error))
<div class="alert alert-warning mb-4">
    <strong>Debug:</strong> {{ $error }}
    @if(isset($debug_info))
    <br><small>Información adicional: {{ json_encode($debug_info) }}</small>
    @endif
</div>
@endif

{{-- Inicio de Información de la consulta --}}
@if(isset($total_postulados))
<div class="alert alert-info mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <strong>Total de postulados encontrados:</strong> {{ $total_postulados }}
            @if(isset($tipo_convocatoria))
            <br><small><strong>Tipo de convocatoria:</strong> {{ $tipo_convocatoria->name }}</small>
            @endif
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-success btn-sm" onclick="exportarExcel()">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </button>
            <button class="btn btn-danger btn-sm" onclick="exportarPDF()">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
        </div>
    </div>
</div>
@endif
{{-- Final de Información de la consulta --}}

{{-- Inicio de Filtros avanzados --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="fas fa-filter"></i> Filtros de Búsqueda
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label for="searchInput" class="form-label">Búsqueda General</label>
                <input type="text"
                    class="form-control"
                    placeholder="Buscar por nombre, documento o programa..."
                    id="searchInput"
                    onkeyup="filtrarTabla()">
            </div>
            <div class="col-md-3">
                <label for="filtroPrograma" class="form-label">Filtrar por Programa</label>
                <select class="form-select" id="filtroPrograma" onchange="filtrarPorPrograma()">
                    <option value="">Todos los programas</option>
                    @if(isset($postulados) && $postulados->count() > 0)
                    @php
                    $programas = $postulados->pluck('program')->unique()->filter()->sort();
                    @endphp
                    @foreach($programas as $programa)
                    <option value="{{ $programa }}">{{ $programa }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroPuntaje" class="form-label">Filtrar por Puntaje</label>
                <select class="form-select" id="filtroPuntaje" onchange="filtrarPorPuntaje()">
                    <option value="">Todos los puntajes</option>
                    <option value="alto">Puntaje Alto (> 15)</option>
                    <option value="medio">Puntaje Medio (10-15)</option>
                    <option value="bajo">Puntaje Bajo (< 10)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
            </div>
        </div>
        {{-- Fin de Filtros avanzados --}}

        {{-- Inicio de Información de filtros activos --}}
        <div class="mt-3">
            <div id="contadorResultados" class="alert alert-secondary mb-0" style="display: none;">
                <strong>Resultados filtrados:</strong> <span id="numeroResultados">0</span> de {{ isset($postulados) ? $postulados->count() : 0 }} postulados
            </div>
        </div>
    </div>
</div>
{{-- Fin de Información de filtros activos --}}

{{-- Instrucciones de uso --}}
@if(isset($postulados) && $postulados->count() > 0)
<div class="alert alert-light border-start border-primary border-4 mb-4">
    <div class="row">
        <div class="col-md-8">
            <small>
                <strong>Instrucciones:</strong>
                <ul class="mb-0 mt-1">
                    <li>Use la búsqueda general para filtrar por nombre, documento o programa</li>
                    <li>Click en los encabezados de la tabla para ordenar</li>
                    <li>Use "Ver más" para información detallada de cada postulado</li>
                </ul>
            </small>
        </div>
        <div class="col-md-4 text-end">
            <small class="text-muted">
                <strong>Máximo de cupos:</strong> 350<br>
                <strong>Última actualización:</strong> {{ date('d/m/Y H:i') }}
            </small>
        </div>
    </div>
</div>
@endif
{{-- Fin de Instrucciones de uso --}}

{{-- Tabla de postulados --}}
<table class="table table-bordered" id="tablaSubsidios">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Documento</th>
            <th>Nombre</th>
            <th>Programa</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Puntaje</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($postulados) && $postulados->count() > 0)
        @foreach($postulados as $index => $postulado)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $postulado->document_number ?? 'N/A' }}</td>
            <td>{{ $postulado->full_name ?? trim(($postulado->first_name ?? '') . ' ' . ($postulado->first_last_name ?? '') . ' ' . ($postulado->second_last_name ?? '')) ?: 'N/A' }}</td>
            <td>{{ $postulado->program ?? $postulado->programa ?? 'N/A' }}</td>
            <td>{{ $postulado->telephone1 ?? $postulado->phone ?? $postulado->telefono ?? 'N/A' }}</td>
            <td>{{ $postulado->personal_email ?? $postulado->email ?? $postulado->correo ?? 'N/A' }}</td>
            <td class="text-center">
                @php
                $puntaje = $postulado->total_points ?? $postulado->score ?? $postulado->puntaje ?? 0;
                @endphp
                <span class="badge bg-{{ $puntaje > 15 ? 'success' : ($puntaje > 10 ? 'warning text-dark' : 'secondary') }}">
                    {{ $puntaje }} puntos
                </span>
            </td>
            <td>
                <button class="btn btn-info btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAprendiz{{ $postulado->id }}">
                    Ver más
                </button>
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
{{-- Fin de Tabla de postulados --}}

{{-- Modales dinámicos para postulados reales --}}
@if(isset($postulados) && $postulados->count() > 0)
@foreach($postulados as $postulado)
<div class="modal fade" id="modalAprendiz{{ $postulado->id }}" tabindex="-1" aria-labelledby="labelAprendiz{{ $postulado->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="labelAprendiz{{ $postulado->id }}">
                    Información de {{ $postulado->full_name ?? trim(($postulado->first_name ?? '') . ' ' . ($postulado->first_last_name ?? '')) }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                {{-- Información Personal --}}
                <h6 class="text-secondary">Información Personal</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Documento:</strong> {{ $postulado->document_number ?? 'N/A' }}</li>
                            <li><strong>Primer Nombre:</strong> {{ $postulado->first_name ?? 'N/A' }}</li>
                            <li><strong>Primer Apellido:</strong> {{ $postulado->first_last_name ?? 'N/A' }}</li>
                            <li><strong>Segundo Apellido:</strong> {{ $postulado->second_last_name ?? 'N/A' }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Teléfono:</strong> {{ $postulado->telephone1 ?? $postulado->phone ?? $postulado->telefono ?? 'N/A' }}</li>
                            <li><strong>Email:</strong> {{ $postulado->personal_email ?? $postulado->email ?? $postulado->correo ?? 'N/A' }}</li>
                            <li><strong>Programa:</strong> {{ $postulado->program ?? $postulado->programa ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>

                {{-- Información de Postulación --}}
                <h6 class="text-secondary mt-3">Información de Postulación</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>ID de Postulación:</strong> {{ $postulado->id }}</li>
                            <li><strong>Convocatoria Seleccionada:</strong> {{ $postulado->convocatory_selected }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Fecha de Creación:</strong> {{ isset($postulado->created_at) ? date('d/m/Y H:i', strtotime($postulado->created_at)) : 'N/A' }}</li>
                            <li><strong>Fecha de Actualización:</strong> {{ isset($postulado->updated_at) ? date('d/m/Y H:i', strtotime($postulado->updated_at)) : 'N/A' }}</li>
                        </ul>
                    </div>
                </div>

                {{-- Información Socioeconómica (campos comunes en convocatorias) --}}
                <h6 class="text-secondary mt-3">Información Socioeconómica</h6>
                <div class="row">
                    @php
                    $camposSocioeconomicos = [
                    'renta_joven' => 'Beneficiario Renta Joven',
                    'contrato_aprendizaje' => 'Contrato de Aprendizaje',
                    'apoyo_fic' => 'Apoyo FIC Anterior',
                    'vinculo_laboral' => 'Vínculo Laboral',
                    'apoyo_alimentacion' => 'Apoyo Alimentación SENA',
                    'apoyo_transporte' => 'Apoyo Transporte SENA',
                    'victima_conflicto' => 'Víctima del Conflicto',
                    'madre_cabeza_familia' => 'Madre Cabeza de Familia',
                    'sisben_a' => 'SISBEN Grupo A',
                    'sisben_b' => 'SISBEN Grupo B',
                    'discapacidad' => 'Situación de Discapacidad',
                    'embarazada_lactante' => 'Embarazada o Lactante',
                    'comunidades_narp' => 'Comunidades NARP/ROM/Indígenas',
                    'desplazado_fenomenos' => 'Desplazado por Fenómenos Naturales',
                    'aprendiz_campesino' => 'Aprendiz Campesino',
                    'zona_rural' => 'Vive en Zona Rural'
                    ];
                    @endphp

                    @foreach($camposSocioeconomicos as $campo => $etiqueta)
                    @if(isset($postulado->$campo) && !is_null($postulado->$campo) && $postulado->$campo !== '')
                    <div class="col-md-6 mb-2">
                        <small>
                            <strong>{{ $etiqueta }}:</strong>
                            <span class="badge bg-{{ $postulado->$campo == 'Si' || $postulado->$campo == '1' || $postulado->$campo === 1 ? 'success' : 'secondary' }}">
                                {{ $postulado->$campo == 'Si' || $postulado->$campo == '1' || $postulado->$campo === 1 ? 'SÍ' : 'No' }}
                            </span>
                        </small>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Otros Datos (campos que no están en las categorías anteriores) --}}
                <h6 class="text-secondary mt-3">Otros Datos</h6>
                <div class="row">
                    @foreach((array)$postulado as $campo => $valor)
                    @if(!in_array($campo, [
                    'id', 'document_number', 'full_name', 'first_name', 'first_last_name', 'second_last_name',
                    'telephone1', 'personal_email', 'program', 'convocatory_selected', 'created_at', 'updated_at',
                    'telefono', 'correo', 'programa', 'phone', 'email', 'total_points', 'score', 'puntaje',
                    'renta_joven', 'contrato_aprendizaje', 'apoyo_fic', 'vinculo_laboral', 'apoyo_alimentacion',
                    'apoyo_transporte', 'victima_conflicto', 'madre_cabeza_familia', 'sisben_a', 'sisben_b',
                    'discapacidad', 'embarazada_lactante', 'comunidades_narp', 'desplazado_fenomenos',
                    'aprendiz_campesino', 'zona_rural'
                    ]) && !is_null($valor) && $valor !== '')
                    <div class="col-md-6 mb-1">
                        <small><strong>{{ ucwords(str_replace(['_', '-'], ' ', $campo)) }}:</strong> {{ $valor }}</small>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Footer del modal con PUNTAJE EN LA ESQUINA INFERIOR DERECHA --}}
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="imprimirPostulado({{ $postulado->id }})">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>

                {{-- PUNTAJE TOTAL EN LA ESQUINA INFERIOR DERECHA --}}
                <div>
                    @php
                    $puntajeTotal = $postulado->total_points ?? $postulado->score ?? $postulado->puntaje ?? 0;
                    @endphp
                    <h5 class="mb-0">
                        <span class="badge bg-{{ $puntajeTotal > 15 ? 'success' : ($puntajeTotal > 10 ? 'warning text-dark' : 'secondary') }} fs-6 px-3 py-2">
                            <i class="fas fa-star me-1"></i>{{ $puntajeTotal }} puntos
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
{{-- Fin de Modales dinámicos para postulados reales --}}

{{-- Modal de ejemplo (actualizado para consistencia) --}}
<div class="modal fade" id="modalAprendiz1" tabindex="-1" aria-labelledby="labelAprendiz1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="labelAprendiz1">Información de Laura Marcela Torres Nieto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-secondary">Información Personal</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Documento:</strong> 1000123456</li>
                            <li><strong>Primer Nombre:</strong> Laura Marcela</li>
                            <li><strong>Primer Apellido:</strong> Torres</li>
                            <li><strong>Segundo Apellido:</strong> Nieto</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li><strong>Teléfono:</strong> 3114567890</li>
                            <li><strong>Email:</strong> laura.torres@example.com</li>
                            <li><strong>Programa:</strong> Técnico en Enfermería</li>
                        </ul>
                    </div>
                </div>

                <h6 class="text-secondary mt-3">Información Socioeconómica</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small><strong>Beneficiario Renta Joven:</strong> <span class="badge bg-success">SÍ</span></small>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small><strong>Víctima del Conflicto:</strong> <span class="badge bg-success">SÍ</span></small>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small><strong>Madre Cabeza de Familia:</strong> <span class="badge bg-success">SÍ</span></small>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small><strong>SISBEN Grupo A:</strong> <span class="badge bg-success">SÍ</span></small>
                    </div>
                </div>
            </div>

            {{-- Footer con puntaje en la esquina derecha --}}
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="imprimirPostulado(1)">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
                <div>
                    <h5 class="mb-0">
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="fas fa-star me-1"></i>22 puntos
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>                   
{{-- Fin de Modal de ejemplo --}}
@endsection

@section('scripts')
<script>
// Función original mejorada para filtrar tabla
function filtrarTabla() {
    aplicarFiltrosCombinados();
}

// Nueva función para aplicar todos los filtros combinados
function aplicarFiltrosCombinados() {
    let input = document.getElementById("searchInput").value.toUpperCase();
    let programaSeleccionado = document.getElementById('filtroPrograma') ? document.getElementById('filtroPrograma').value.toUpperCase() : '';
    let puntajeSeleccionado = document.getElementById('filtroPuntaje') ? document.getElementById('filtroPuntaje').value : '';
    let filas = document.querySelector("#tablaSubsidios tbody").rows;
    let contador = 0;
    
    for (let i = 0; i < filas.length; i++) {
        // Verificar que la fila tiene datos (no es la fila de "no hay datos")
        if (filas[i].cells.length <= 1 || filas[i].cells[1].textContent.trim() === '' || filas[i].textContent.includes('No se encontraron postulados')) {
            continue;
        }
        
        let doc = filas[i].cells[1].textContent.toUpperCase();
        let nombre = filas[i].cells[2].textContent.toUpperCase();
        let programa = filas[i].cells[3].textContent.toUpperCase();
        let puntajeTexto = filas[i].cells[6].textContent.trim().replace(/[^\d]/g, ''); // Remover "puntos" y otros caracteres
        let puntaje = parseInt(puntajeTexto) || 0;
        
        // Filtro de búsqueda general
        let coincideBusqueda = input === '' || doc.includes(input) || nombre.includes(input) || programa.includes(input);
        
        // Filtro de programa
        let coincidePrograma = programaSeleccionado === '' || programa.includes(programaSeleccionado);
        
        // Filtro de puntaje
        let coincidePuntaje = true;
        switch(puntajeSeleccionado) {
            case 'alto':
                coincidePuntaje = puntaje > 15;
                break;
            case 'medio':
                coincidePuntaje = puntaje >= 10 && puntaje <= 15;
                break;
            case 'bajo':
                coincidePuntaje = puntaje < 10;
                break;
            default:
                coincidePuntaje = true;
        }
        
        let mostrar = coincideBusqueda && coincidePrograma && coincidePuntaje;
        filas[i].style.display = mostrar ? "" : "none";
        
        if (mostrar) {
            contador++;
            filas[i].cells[0].textContent = contador;
        }
    }
    
    actualizarContadorResultados(contador);
}

// Función para filtrar solo por programa
function filtrarPorPrograma() {
    aplicarFiltrosCombinados();
}

// Función para filtrar solo por puntaje
function filtrarPorPuntaje() {
    aplicarFiltrosCombinados();
}

// Función para limpiar todos los filtros
function limpiarFiltros() {
    document.getElementById("searchInput").value = '';
    
    if (document.getElementById("filtroPrograma")) {
        document.getElementById("filtroPrograma").value = '';
    }
    
    if (document.getElementById("filtroPuntaje")) {
        document.getElementById("filtroPuntaje").value = '';
    }
    
    let filas = document.querySelector("#tablaSubsidios tbody").rows;
    let contador = 0;
    
    for (let i = 0; i < filas.length; i++) {
        if (filas[i].cells.length > 1 && !filas[i].textContent.includes('No se encontraron postulados')) {
            filas[i].style.display = "";
            contador++;
            filas[i].cells[0].textContent = contador;
        }
    }
    
    actualizarContadorResultados(contador);
}

// Función para actualizar el contador de resultados
function actualizarContadorResultados(contador) {
    let contadorElement = document.getElementById("contadorResultados");
    let numeroElement = document.getElementById("numeroResultados");
    
    if (!contadorElement) {
        // Crear el elemento contador si no existe
        let alertInfo = document.querySelector(".alert-info");
        if (alertInfo) {
            contadorElement = document.createElement("div");
            contadorElement.id = "contadorResultados";
            contadorElement.className = "alert alert-secondary mt-2";
            contadorElement.innerHTML = '<strong>Resultados filtrados:</strong> <span id="numeroResultados">0</span> de {{ isset($postulados) ? $postulados->count() : 0 }} postulados';
            alertInfo.parentNode.insertBefore(contadorElement, alertInfo.nextSibling);
            numeroElement = document.getElementById("numeroResultados");
        }
    }
    
    let totalPostulados = {{ isset($postulados) ? $postulados->count() : 0 }};
    
    if (contadorElement) {
        contadorElement.style.display = contador < totalPostulados ? 'block' : 'none';
    }
    
    if (numeroElement) {
        numeroElement.textContent = contador;
    }
}

// Función para imprimir información de un postulado
function imprimirPostulado(postulacionId) {
    let modal = document.getElementById(`modalAprendiz${postulacionId}`);
    if (!modal) {
        alert('No se encontró la información del postulado');
        return;
    }
    
    let modalBody = modal.querySelector('.modal-body').innerHTML;
    let modalTitle = modal.querySelector('.modal-title').textContent;
    
    let ventanaImpresion = window.open('', '_blank');
    ventanaImpresion.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Información del Postulado - ${modalTitle}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 20px;
                    line-height: 1.4;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #2d5a3d;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                }
                .section {
                    margin-bottom: 15px;
                }
                .section h6 {
                    color: #2d5a3d;
                    border-bottom: 1px solid #8fbc8f;
                    padding-bottom: 5px;
                    margin-bottom: 10px;
                }
                ul {
                    margin: 0;
                    padding-left: 20px;
                }
                .row {
                    display: flex;
                    flex-wrap: wrap;
                }
                .col-md-6 {
                    width: 50%;
                    padding-right: 10px;
                }
                .badge {
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-size: 11px;
                }
                .bg-success { background-color: #4a7c59; color: white; }
                .bg-secondary { background-color: #3d6b4a; color: white; }
                .bg-warning { background-color: #5a9268; color: white; }
                .bg-light { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }
                .text-end { text-align: right; }
                .fs-6 { font-size: 1.1em; }
                @media print {
                    .no-print { display: none; }
                    body { margin: 0; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>SENA - Sistema de Gestión de Aprendices</h2>
                <h3>Información del Postulado</h3>
                <h4>${modalTitle}</h4>
                <p><strong>Fecha de impresión:</strong> ${new Date().toLocaleDateString('es-CO')} ${new Date().toLocaleTimeString('es-CO')}</p>
            </div>
            <div class="content">
                ${modalBody}
            </div>
        </body>
        </html>
    `);
    
    ventanaImpresion.document.close();
    
    setTimeout(() => {
        ventanaImpresion.print();
        ventanaImpresion.close();
    }, 250);
}

// Función original de descarga (mantenida por compatibilidad)
function descargar(tipo) {
    if (tipo === 'excel') {
        exportarExcel();
    } else if (tipo === 'pdf') {
        exportarPDF();
    } else {
        // Método original
        const data = document.getElementById('tablaSubsidios').outerHTML;
        const contenido = `<html><head><meta charset='UTF-8'></head><body>${data}</body></html>`;
        const blob = new Blob([contenido], { type: tipo === 'pdf' ? 'application/pdf' : 'application/msword' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = tipo === 'pdf' ? 'subsidios-asignados.pdf' : 'subsidios-asignados.doc';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
}

// Nueva función para exportar a Excel
function exportarExcel() {
    let tabla = document.getElementById('tablaSubsidios');
    let filas = tabla.querySelectorAll('tbody tr:not([style*="display: none"])');
    
    let csvContent = "data:text/csv;charset=utf-8,\uFEFF"; // BOM para UTF-8
    
    // Agregar encabezados
    let encabezados = ['#', 'Documento', 'Nombre', 'Programa', 'Teléfono', 'Email', 'Puntaje'];
    csvContent += encabezados.join(',') + "\n";
    
    // Agregar filas de datos
    filas.forEach(fila => {
        if (fila.cells.length > 1 && !fila.textContent.includes('No se encontraron postulados')) {
            let rowData = [];
            for (let i = 0; i < 7; i++) {
                let cellText = fila.cells[i].textContent.trim().replace(/"/g, '""');
                rowData.push('"' + cellText + '"');
            }
            csvContent += rowData.join(',') + "\n";
        }
    });
    
    // Crear y descargar el archivo
    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `postulados_alimentacion_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Nueva función para exportar a PDF
function exportarPDF() {
    // Obtener datos visibles de la tabla
    let tabla = document.getElementById('tablaSubsidios');
    let filas = tabla.querySelectorAll('tbody tr:not([style*="display: none"])');
    
    let contenido = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Postulados de Alimentación</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { 
                    text-align: center; 
                    margin-bottom: 20px; 
                    color: #1e3a2e;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    font-size: 12px; 
                }
                th, td { 
                    border: 1px solid #2d5a3d; 
                    padding: 8px; 
                    text-align: left; 
                }
                th { 
                    background-color: #8fbc8f; 
                    font-weight: bold; 
                    color: #1e3a2e;
                }
                .text-center { text-align: center; }
                @media print { body { margin: 0; } }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>SENA - Sistema de Gestión de Aprendices</h2>
                <h3>Postulados - Convocatoria de Alimentación</h3>
                <p>Fecha: ${new Date().toLocaleDateString('es-CO')} - Hora: ${new Date().toLocaleTimeString('es-CO')}</p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Programa</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Puntaje</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    let contador = 0;
    filas.forEach(fila => {
        if (fila.cells.length > 1 && !fila.textContent.includes('No se encontraron postulados')) {
            contador++;
            contenido += '<tr>';
            for (let i = 0; i < 7; i++) {
                let cellText = fila.cells[i].textContent.trim();
                contenido += `<td>${cellText}</td>`;
            }
            contenido += '</tr>';
        }
    });
    
    contenido += `
                </tbody>
            </table>
            <div style="margin-top: 20px; text-align: center; color: #2d5a3d;">
                <p><strong>Total de registros:</strong> ${contador}</p>
            </div>
        </body>
        </html>
    `;
    
    let ventanaPDF = window.open('', '_blank');
    ventanaPDF.document.write(contenido);
    ventanaPDF.document.close();
    
    setTimeout(() => {
        ventanaPDF.print();
    }, 250);
}

// Función para ordenar tabla
function ordenarTabla(columna) {
    let tabla = document.getElementById('tablaSubsidios');
    let tbody = tabla.querySelector('tbody');
    let filas = Array.from(tbody.querySelectorAll('tr'));
    
    // Filtrar solo las filas visibles con datos
    filas = filas.filter(fila => 
        fila.style.display !== 'none' && 
        fila.cells.length > 1 && 
        !fila.textContent.includes('No se encontraron postulados')
    );
    
    // Determinar el tipo de ordenamiento
    let esNumerico = columna === 0 || columna === 6; // # y Puntaje
    
    // Obtener el estado actual de ordenamiento
    let th = tabla.querySelectorAll('thead th')[columna];
    let esAscendente = !th.classList.contains('desc');
    
    // Limpiar clases de ordenamiento anteriores
    tabla.querySelectorAll('thead th').forEach(header => {
        header.classList.remove('asc', 'desc');
        let icon = header.querySelector('.fa-sort, .fa-sort-up, .fa-sort-down');
        if (icon) {
            icon.className = 'fas fa-sort text-muted';
        }
    });
    
    // Ordenar filas
    filas.sort((a, b) => {
        let valorA = a.cells[columna].textContent.trim();
        let valorB = b.cells[columna].textContent.trim();
        
        if (esNumerico) {
            valorA = parseInt(valorA) || 0;
            valorB = parseInt(valorB) || 0;
            return esAscendente ? valorA - valorB : valorB - valorA;
        } else {
            let comparacion = valorA.localeCompare(valorB);
            return esAscendente ? comparacion : -comparacion;
        }
    });
    
    // Aplicar clase y icono de ordenamiento
    th.classList.add(esAscendente ? 'asc' : 'desc');
    let icon = th.querySelector('.fa-sort, .fa-sort-up, .fa-sort-down');
    if (icon) {
        icon.className = esAscendente ? 'fas fa-sort-up text-primary' : 'fas fa-sort-down text-primary';
    }
    
    // Reordenar las filas en el DOM
    filas.forEach(fila => tbody.appendChild(fila));
    
    // Actualizar numeración
    filas.forEach((fila, index) => {
        fila.cells[0].textContent = index + 1;
    });
}

// Debug e información en consola
@if(isset($postulados))
console.log('Total postulados cargados:', {{ $postulados->count() }});
console.log('Campos disponibles:', {!! json_encode(isset($postulados) && $postulados->count() > 0 ? array_keys((array)$postulados->first()) : []) !!});
@endif

// Inicialización cuando la página está lista
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando sistema de filtros y ordenamiento...');
    
    // Agregar eventos de ordenamiento a los encabezados
    let encabezados = document.querySelectorAll('#tablaSubsidios thead th');
    encabezados.forEach((th, index) => {
        if (index < 7) { // Solo para las columnas ordenables
            th.style.cursor = 'pointer';
            th.title = 'Click para ordenar';
            
            // Agregar icono de ordenamiento si no existe
            if (!th.querySelector('.fa-sort')) {
                th.innerHTML += ' <i class="fas fa-sort text-muted" style="font-size: 0.8em;"></i>';
            }
            
            // Agregar evento de click
            th.addEventListener('click', () => ordenarTabla(index));
        }
    });
    
    // Inicializar contador de resultados
    let totalPostulados = {{ isset($postulados) ? $postulados->count() : 0 }};
    actualizarContadorResultados(totalPostulados);
    
    // Agregar estilos adicionales para ordenamiento con paleta verde
    let style = document.createElement('style');
    style.textContent = `
        #tablaSubsidios thead th.asc { 
            background-color: rgba(143, 188, 143, 0.3) !important;
            color: #1e3a2e !important;
        }
        #tablaSubsidios thead th.desc { 
            background-color: rgba(90, 146, 104, 0.3) !important;
            color: #1e3a2e !important;
        }
        #tablaSubsidios thead th:hover { 
            background-color: rgba(74, 124, 89, 0.2) !important;
            color: #1e3a2e !important;
        }
    `;
    document.head.appendChild(style);
    
    console.log('Sistema inicializado correctamente. Total de postulados:', totalPostulados);
});
</script>
@endsection