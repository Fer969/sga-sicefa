@extends('sga::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Estadísticas en la parte superior -->
            @if($convocatories->isNotEmpty())
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white border-0 shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-list-alt fs-2 mb-2"></i>
                            <h3 class="mb-1">{{ $convocatories->count() }}</h3>
                            <small>Total Convocatorias</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white border-0 shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-star fs-2 mb-2"></i>
                            <h3 class="mb-1">{{ $convocatories->where('puntaje_total', '>', 0)->count() }}</h3>
                            <small>Con Puntajes</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white border-0 shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-users fs-2 mb-2"></i>
                            <h3 class="mb-1">{{ $convocatories->sum('postulados') }}</h3>
                            <small>Total Postulaciones</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white border-0 shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-chair fs-2 mb-2"></i>
                            <h3 class="mb-1">{{ $convocatories->sum('coups') }}</h3>
                            <small>Total Cupos</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="text-dark mb-1">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Historial de Evaluaciones
                    </h3>
                    <p class="text-secondary mb-0">Convocatorias de Apoyo de Alimentación</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <!-- Card principal -->
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-utensils me-2"></i>
                                Registro de Convocatorias Alimentarias
                            </h5>
                        </div>
                        <div class="col-auto">
                            @if($convocatories->isNotEmpty())
                                <span class="badge bg-light text-dark">{{ $convocatories->count() }} Convocatorias</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-hashtag"></i>
                                    </th>
                                    <th class="border-0 py-3">
                                        <i class="fas fa-bullhorn me-2"></i>Convocatoria
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-calendar-week me-2"></i>Trimestre
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-calendar-alt me-2"></i>Año
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-traffic-light me-2"></i>Estado
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-star me-2"></i>Puntaje Total
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-play-circle me-2"></i>F. Apertura
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-stop-circle me-2"></i>F. Cierre
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-chair me-2"></i>Cupos
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-users me-2"></i>Postulados
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($convocatories as $index => $convocatory)
                                    <tr>
                                        <td class="text-center py-3">
                                            <span class="badge bg-primary rounded-pill">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded-circle me-3" style="width: 8px; height: 8px;"></div>
                                                <strong class="text-dark">{{ $convocatory->name }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-info text-white">{{ $convocatory->quarter }}</span>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="bg-light rounded-pill px-3 py-2 d-inline-block">
                                                <span class="text-dark fw-bold fs-6">{{ $convocatory->year }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            @switch($convocatory->status)
                                                @case('Active')
                                                    <span class="badge bg-success text-white">
                                                        <i class="fas fa-check-circle me-1"></i>Activa
                                                    </span>
                                                    @break
                                                @case('Inactive')
                                                    <span class="badge bg-secondary text-white">
                                                        <i class="fas fa-lock me-1"></i>Inactiva
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-question-circle me-1"></i>{{ $convocatory->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="text-center py-3">
                                            @if($convocatory->puntaje_total > 0)
                                                <div class="bg-success bg-opacity-25 rounded-pill px-3 py-2 d-inline-block">
                                                    <span class="text-success fw-bold fs-6">{{ $convocatory->puntaje_total }}</span>
                                                    <small class="d-block text-muted">{{ $convocatory->puntaje_status }}</small>
                                                    <button class="btn btn-sm btn-outline-success mt-1" 
                                                            onclick="verPuntajesDetallados({{ $convocatory->id }}, '{{ $convocatory->name }}')">
                                                        <i class="fas fa-eye me-1"></i>Ver Puntajes
                                                    </button>
                                                </div>
                                            @else
                                                <div class="bg-warning bg-opacity-25 rounded-pill px-3 py-2 d-inline-block">
                                                    <span class="text-warning fw-bold fs-6">0</span>
                                                    <small class="d-block text-muted">{{ $convocatory->puntaje_status }}</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center py-3">
                                            @if($convocatory->registration_start_date)
                                                <div class="text-primary fw-bold">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($convocatory->registration_start_date)->format('d/m/Y') }}
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-minus-circle me-1"></i>No definida
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3">
                                            @if($convocatory->registration_deadline)
                                                <div class="text-danger fw-bold">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($convocatory->registration_deadline)->format('d/m/Y') }}
                                                </div>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-minus-circle me-1"></i>No definida
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="bg-info bg-opacity-25 rounded-pill px-3 py-2 d-inline-block">
                                                <span class="text-info fw-bold fs-6">{{ $convocatory->coups ?? 0 }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="bg-primary bg-opacity-25 rounded-pill px-3 py-2 d-inline-block">
                                                <span class="text-primary fw-bold fs-6">{{ $convocatory->postulados }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-search fs-1 mb-3 d-block text-secondary"></i>
                                                <h5 class="text-dark">No se encontraron convocatorias</h5>
                                                <p class="mb-0 text-secondary">No hay convocatorias de alimentación registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Puntajes Detallados -->
<div class="modal fade" id="modalPuntajesDetallados" tabindex="-1" aria-labelledby="modalPuntajesDetalladosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalPuntajesDetalladosLabel">
                    <i class="fas fa-star me-2"></i>
                    Puntajes Detallados - <span id="nombreConvocatoria"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loadingPuntajes" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando puntajes detallados...</p>
                </div>
                
                <div id="contenidoPuntajes" style="display: none;">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Total de Puntaje:</strong> <span id="totalPuntaje" class="badge bg-success fs-6"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="listaPuntajes">
                        <!-- Los puntajes se cargarán dinámicamente aquí -->
                    </div>
                </div>
                
                <div id="errorPuntajes" class="alert alert-danger" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="mensajeError"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function verPuntajesDetallados(convocatoriaId, nombreConvocatoria) {
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalPuntajesDetallados'));
    modal.show();
    
    // Actualizar título del modal
    document.getElementById('nombreConvocatoria').textContent = nombreConvocatoria;
    
    // Mostrar loading
    document.getElementById('loadingPuntajes').style.display = 'block';
    document.getElementById('contenidoPuntajes').style.display = 'none';
    document.getElementById('errorPuntajes').style.display = 'none';
    
    // Realizar petición AJAX
    fetch('{{ route("cefa.sga.admin.ev-history.puntajes-detallados") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            convocatoria_id: convocatoriaId
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingPuntajes').style.display = 'none';
        
        if (data.success) {
            mostrarPuntajesDetallados(data.puntajes, data.total);
        } else {
            mostrarError(data.message);
        }
    })
    .catch(error => {
        document.getElementById('loadingPuntajes').style.display = 'none';
        mostrarError('Error al cargar los puntajes: ' + error.message);
    });
}

function mostrarPuntajesDetallados(puntajes, total) {
    const listaPuntajes = document.getElementById('listaPuntajes');
    const totalPuntaje = document.getElementById('totalPuntaje');
    
    // Actualizar total
    totalPuntaje.textContent = total;
    
    // Limpiar lista anterior
    listaPuntajes.innerHTML = '';
    
    // Agrupar puntajes por categorías
    const categorias = {
        'Vulnerabilidad': [
            'Víctima del Conflicto', 'Víctima de Violencia de Género', 'Persona con Discapacidad',
            'Jefe de Hogar', 'Embarazada o Lactante', 'Pertenencia a Grupo Étnico',
            'Desplazamiento Natural'
        ],
        'Sisbén': [
            'Sisbén Grupo A', 'Sisbén Grupo B'
        ],
        'Características Rurales': [
            'Aprendiz Rural', 'Vive en Zona Rural'
        ],
        'Participación': [
            'Representante Institucional', 'Vocero Electo', 'Participación en Investigación'
        ],
        'Historial': [
            'Cuota de Alimentación Anterior', 'Tiene Certificación', 'Declaración Jurada Adjunta',
            'Conoce Obligaciones del Apoyo'
        ],
        'Beneficios': [
            'Beneficiario Renta Joven', 'Tiene Contrato de Aprendizaje', 'Recibió Apoyo FIC',
            'Recibió Apoyo Regular', 'Tiene Contrato de Ingresos', 'Tiene Práctica Patrocinada',
            'Recibe Apoyo de Alimentación', 'Recibe Apoyo de Transporte', 'Recibe Apoyo Tecnológico'
        ]
    };
    
    // Crear contenido por categorías
    Object.keys(categorias).forEach(categoria => {
        const puntajesCategoria = puntajes.filter(p => categorias[categoria].includes(p.nombre));
        
        if (puntajesCategoria.length > 0) {
            const colDiv = document.createElement('div');
            colDiv.className = 'col-md-6 mb-3';
            
            let html = `
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-folder me-2"></i>${categoria}
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
            `;
            
            puntajesCategoria.forEach(puntaje => {
                const colorClase = puntaje.puntaje > 0 ? 'text-success' : 'text-muted';
                const icono = puntaje.puntaje > 0 ? 'fas fa-check-circle' : 'fas fa-times-circle';
                
                html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="${colorClase}">
                            <i class="${icono} me-2"></i>${puntaje.nombre}
                        </span>
                        <span class="badge bg-primary rounded-pill">${puntaje.puntaje}</span>
                    </div>
                `;
            });
            
            html += `
                        </div>
                    </div>
                </div>
            `;
            
            colDiv.innerHTML = html;
            listaPuntajes.appendChild(colDiv);
        }
    });
    
    // Mostrar contenido
    document.getElementById('contenidoPuntajes').style.display = 'block';
}

function mostrarError(mensaje) {
    document.getElementById('mensajeError').textContent = mensaje;
    document.getElementById('errorPuntajes').style.display = 'block';
}
</script>
@endsection