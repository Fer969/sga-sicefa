@extends('sga::layouts.master')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary"><i class="fas fa-cogs me-2"></i>Configuración del Sistema - Convocatorias y Eventos</h3>
        <div class="btn-group">
            <button class="btn btn-outline-primary" onclick="actualizarDatos()">
                <i class="fas fa-sync-alt me-1"></i>Actualizar
            </button>
        </div>
    </div>

    <!-- Alertas -->
    <div id="alertContainer"></div>

    <!-- Tabs de navegación -->
    <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="convocatorias-tab" data-bs-toggle="tab" data-bs-target="#convocatorias" type="button">
                <i class="fas fa-bullhorn me-2"></i>Convocatorias
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="puntajes-tab" data-bs-toggle="tab" data-bs-target="#puntajes" type="button">
                <i class="fas fa-star me-2"></i>Puntajes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="eventos-tab" data-bs-toggle="tab" data-bs-target="#eventos" type="button">
                <i class="fas fa-calendar-alt me-2"></i>Eventos Externos
            </button>
        </li>
    </ul>

    <div class="tab-content" id="configTabContent">
        <!-- TAB CONVOCATORIAS -->
        <div class="tab-pane fade show active" id="convocatorias" role="tabpanel">
            <div class="row">
                <!-- Formulario Crear Convocatoria -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Crear Nueva Convocatoria</h5>
                        </div>
                        <div class="card-body">
                            <form id="formCrearConvocatoria">
                                @csrf
                                <div class="mb-3">
                                    <label for="nombreConvocatoria" class="form-label">Nombre de la Convocatoria</label>
                                    <input type="text" class="form-control" id="nombreConvocatoria" name="nombre" 
                                           placeholder="Ej. Convocatoria Alimentación I - 2025" maxlength="255" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tipoConvocatoria" class="form-label">Tipo de Convocatoria</label>
                                        <select class="form-select" id="tipoConvocatoria" name="tipo_convocatoria" required>
                                            <option value="">Seleccione un tipo...</option>
                                            @if(isset($tiposConvocatorias))
                                                @foreach($tiposConvocatorias as $tipo)
                                                    <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="trimestre" class="form-label">Trimestre</label>
                                        <select class="form-select" id="trimestre" name="trimestre" required>
                                            <option value="">Seleccione...</option>
                                            <option value="1">I Trimestre</option>
                                            <option value="2">II Trimestre</option>
                                            <option value="3">III Trimestre</option>
                                            <option value="4">IV Trimestre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                                        <input type="date" class="form-control" id="fechaInicio" name="fecha_inicio" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fechaCierre" class="form-label">Fecha de Cierre</label>
                                        <input type="date" class="form-control" id="fechaCierre" name="fecha_cierre" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cuposDisponibles" class="form-label">Cantidad de Cupos</label>
                                        <input type="number" class="form-control" id="cuposDisponibles" name="cupos" 
                                               placeholder="Ej. 350" min="1" max="1000" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="año" class="form-label">Año</label>
                                        <input type="number" class="form-control" id="año" name="año" 
                                               value="{{ date('Y') }}" min="2024" max="2030" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Crear Convocatoria
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lista de Convocatorias -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Convocatorias Existentes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-sm" id="tablaConvocatorias">
                                    <thead class="table-dark sticky-top">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Año</th>
                                            <th>Cupos</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($convocatorias) && count($convocatorias) > 0)
                                            @foreach($convocatorias as $conv)
                                            <tr>
                                                <td>{{ $conv->name }}</td>
                                                <td>{{ $conv->year }}</td>
                                                <td>{{ $conv->coups ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $conv->status == 'Active' ? 'success' : 'secondary' }}">
                                                        {{ $conv->status == 'Active' ? 'Activa' : 'Inactiva' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="cambiarEstado({{ $conv->id }}, '{{ $conv->status == 'Active' ? 'Inactive' : 'Active' }}')">
                                                        <i class="fas fa-toggle-{{ $conv->status == 'Active' ? 'off' : 'on' }}"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No hay convocatorias registradas</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB PUNTAJES -->
        <div class="tab-pane fade" id="puntajes" role="tabpanel">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-star me-2"></i>Configuración de Puntajes por Ítem</h5>
                        </div>
                        <div class="card-body">
                            <form id="formPuntajes">
                                @csrf
                                <div class="mb-3">
                                    <label for="convocatoriaSelect" class="form-label">Seleccionar Convocatoria</label>
                                    <select class="form-select" id="convocatoriaSelect" name="convocatoria_id" required>
                                        <option value="">Seleccione una convocatoria...</option>
                                        @if(isset($convocatorias))
                                            @foreach($convocatorias as $conv)
                                                <option value="{{ $conv->id }}">{{ $conv->name }} ({{ $conv->year }})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Criterios Principales</h6>
                                        
                                        <div class="mb-2">
                                            <label for="victimaConflicto" class="form-label">Víctima Conflicto Armado</label>
                                            <input type="number" class="form-control form-control-sm" id="victimaConflicto" 
                                                   name="puntajes[victima_conflicto]" min="0" max="10" value="3">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label for="cabezaFamilia" class="form-label">Madre o Padre Cabeza de Familia</label>
                                            <input type="number" class="form-control form-control-sm" id="cabezaFamilia" 
                                                   name="puntajes[cabeza_familia]" min="0" max="10" value="2">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label for="sisbenGrupoA" class="form-label">Sisben Grupo A</label>
                                            <input type="number" class="form-control form-control-sm" id="sisbenGrupoA" 
                                                   name="puntajes[sisben_grupo_a]" min="0" max="10" value="2">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label for="rentaJoven" class="form-label">Renta Joven</label>
                                            <input type="number" class="form-control form-control-sm" id="rentaJoven" 
                                                   name="puntajes[renta_joven]" min="0" max="10" value="2">
                                        </div>

                                        <div class="mb-2">
                                            <label for="discapacidad" class="form-label">Discapacidad</label>
                                            <input type="number" class="form-control form-control-sm" id="discapacidad" 
                                                   name="puntajes[discapacidad]" min="0" max="10" value="2">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Criterios Adicionales</h6>
                                        
                                        <div class="mb-2">
                                            <label for="violenciaGenero" class="form-label">Víctima Violencia de Género</label>
                                            <input type="number" class="form-control form-control-sm" id="violenciaGenero" 
                                                   name="puntajes[violencia_genero]" min="0" max="10" value="2">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label for="embarazadaLactante" class="form-label">Embarazada o Lactante</label>
                                            <input type="number" class="form-control form-control-sm" id="embarazadaLactante" 
                                                   name="puntajes[embarazada_lactante]" min="0" max="10" value="2">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label for="grupoEtnico" class="form-label">Grupo Étnico</label>
                                            <input type="number" class="form-control form-control-sm" id="grupoEtnico" 
                                                   name="puntajes[grupo_etnico]" min="0" max="10" value="1">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label for="sisbenGrupoB" class="form-label">Sisben Grupo B</label>
                                            <input type="number" class="form-control form-control-sm" id="sisbenGrupoB" 
                                                   name="puntajes[sisben_grupo_b]" min="0" max="10" value="1">
                                        </div>

                                        <div class="mb-2">
                                            <label for="aprendizRural" class="form-label">Aprendiz Rural</label>
                                            <input type="number" class="form-control form-control-sm" id="aprendizRural" 
                                                   name="puntajes[aprendiz_rural]" min="0" max="10" value="1">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Guardar Puntajes
                                    </button>
                                    <button type="button" class="btn btn-secondary ms-2" onclick="cargarPuntajes()">
                                        <i class="fas fa-sync-alt me-2"></i>Cargar Existentes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB EVENTOS -->
        <div class="tab-pane fade" id="eventos" role="tabpanel">
            <div class="row">
                <!-- Formulario Crear Evento -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Crear Evento Externo</h5>
                        </div>
                        <div class="card-body">
                            <form id="formCrearEvento">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombreEvento" class="form-label">Nombre del Evento</label>
                                        <input type="text" class="form-control" id="nombreEvento" name="nombre_evento" 
                                               placeholder="Ej. Encuentro Regional de Emprendimiento" maxlength="150" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cantidadAlmuerzos" class="form-label">Cantidad de Almuerzos</label>
                                        <input type="number" class="form-control" id="cantidadAlmuerzos" name="cantidad_almuerzos" 
                                               placeholder="Ej. 150" min="1" max="999" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="tieneDescuento" class="form-label">¿Tiene Descuento?</label>
                                        <select class="form-select" id="tieneDescuento" name="descuento" required>
                                            <option value="">Seleccione...</option>
                                            <option value="50">50% de descuento</option>
                                            <option value="30">30% de descuento</option>
                                            <option value="0">Sin descuento</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcionEvento" class="form-label">Descripción del Evento</label>
                                    <textarea class="form-control" id="descripcionEvento" name="descripcion" rows="3" 
                                              placeholder="Detalle del evento, fecha, responsables, etc." maxlength="250"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="elementosRequeridos" class="form-label">Elementos Requeridos (Opcional)</label>
                                    <textarea class="form-control" id="elementosRequeridos" name="elementos_requeridos" rows="2" 
                                              placeholder="Ej. Carpas, sillas, sonido, etc." maxlength="250"></textarea>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-calendar-check me-2"></i>Registrar Evento
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lista de Eventos Recientes -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Eventos Recientes</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group" id="listaEventos">
                                @if(isset($eventos) && count($eventos) > 0)
                                    @foreach($eventos as $evento)
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $evento->name }}</h6>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($evento->created_at)->format('d/m/Y') }}</small>
                                        </div>
                                        <p class="mb-1 small">{{ $evento->description ?? 'Sin descripción' }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-utensils me-1"></i>{{ $evento->number_lunchs }} almuerzos
                                            @if($evento->lunchs_discount > 0)
                                                <span class="badge bg-info ms-1">{{ $evento->lunchs_discount }}% desc.</span>
                                            @endif
                                        </small>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                        <p>No hay eventos registrados</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Acción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="mensajeConfirmacion"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmar">Confirmar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 12px;
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    
    .nav-tabs .nav-link {
        border-radius: 8px 8px 0 0;
        border: none;
        color: #6c757d;
        margin-right: 5px;
    }
    
    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border: none;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 500;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .list-group-item {
        border-radius: 8px;
        margin-bottom: 8px;
        border: 1px solid #e9ecef;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .text-primary {
        color: #007bff !important;
    }
    
    .alert {
        border-radius: 8px;
        border: none;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Configurar fechas mínimas
    const today = new Date().toISOString().split('T')[0];
    $('#fechaInicio, #fechaCierre').attr('min', today);
    
    // Validar fechas en tiempo real
    $('#fechaInicio').on('change', function() {
        $('#fechaCierre').attr('min', $(this).val());
    });
});

// Función para mostrar alertas
function mostrarAlerta(tipo, mensaje) {
    const alertContainer = $('#alertContainer');
    const alertId = 'alert-' + Date.now();
    
    const alertHtml = `
        <div class="alert alert-${tipo} alert-dismissible fade show" id="${alertId}" role="alert">
            <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' || tipo === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    alertContainer.append(alertHtml);
    
    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        $('#' + alertId).fadeOut(() => {
            $('#' + alertId).remove();
        });
    }, 5000);
}

// Crear Convocatoria
$('#formCrearConvocatoria').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    // Validar que todos los campos estén llenos
    const formData = $(this).serializeArray();
    const requiredFields = ['nombre', 'tipo_convocatoria', 'trimestre', 'fecha_inicio', 'fecha_cierre', 'cupos', 'año'];
    
    for (let field of requiredFields) {
        const fieldValue = formData.find(item => item.name === field);
        if (!fieldValue || !fieldValue.value) {
            mostrarAlerta('warning', `El campo ${field.replace('_', ' ')} es obligatorio`);
            return;
        }
    }
    
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creando...').prop('disabled', true);
    
    // Debug: mostrar datos que se envían
    console.log('Datos del formulario:', $(this).serialize());
    
    $.ajax({
        url: '{{ route("cefa.sga.admin.sys-params.crear-convocatoria") }}',
        type: 'POST',
        data: $(this).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Respuesta exitosa:', response);
            if (response.success) {
                mostrarAlerta('success', response.message);
                $('#formCrearConvocatoria')[0].reset();
                actualizarTablaConvocatorias();
                actualizarSelectConvocatorias();
            } else {
                mostrarAlerta('danger', 'Error al crear la convocatoria');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en AJAX:', {xhr, status, error});
            let mensaje = 'Error al crear la convocatoria';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errores = Object.values(xhr.responseJSON.errors).flat();
                mensaje = errores.join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                mensaje = xhr.responseJSON.message;
            } else if (xhr.status === 0) {
                mensaje = 'Error de conexión. Verifique su conexión a internet.';
            } else if (xhr.status === 500) {
                mensaje = 'Error interno del servidor. Contacte al administrador.';
            }
            mostrarAlerta('danger', mensaje);
        },
        complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

// Actualizar Puntajes
$('#formPuntajes').on('submit', function(e) {
    e.preventDefault();
    
    const convocatoriaId = $('#convocatoriaSelect').val();
    if (!convocatoriaId) {
        mostrarAlerta('warning', 'Debe seleccionar una convocatoria');
        return;
    }
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...').prop('disabled', true);
    
    $.ajax({
        url: '{{ route("cefa.sga.admin.sys-params.actualizar-puntajes") }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                mostrarAlerta('success', response.message);
            } else {
                mostrarAlerta('danger', 'Error al actualizar puntajes');
            }
        },
        error: function(xhr) {
            let mensaje = 'Error al actualizar puntajes';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errores = Object.values(xhr.responseJSON.errors).flat();
                mensaje = errores.join('<br>');
            }
            mostrarAlerta('danger', mensaje);
        },
        complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

// Crear Evento
$('#formCrearEvento').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Registrando...').prop('disabled', true);
    
    $.ajax({
        url: '{{ route("cefa.sga.admin.sys-params.crear-evento") }}',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                mostrarAlerta('success', response.message);
                $('#formCrearEvento')[0].reset();
                actualizarListaEventos();
            } else {
                mostrarAlerta('danger', 'Error al registrar evento');
            }
        },
        error: function(xhr) {
            let mensaje = 'Error al registrar evento';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errores = Object.values(xhr.responseJSON.errors).flat();
                mensaje = errores.join('<br>');
            }
            mostrarAlerta('danger', mensaje);
        },
        complete: function() {
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

// Cargar puntajes existentes
function cargarPuntajes() {
    const convocatoriaId = $('#convocatoriaSelect').val();
    if (!convocatoriaId) {
        mostrarAlerta('warning', 'Debe seleccionar una convocatoria');
        return;
    }
    
    $.ajax({
        url: '{{ route("cefa.sga.admin.sys-params.obtener-puntajes", ":id") }}'.replace(':id', convocatoriaId),
        type: 'GET',
        success: function(response) {
            if (response.success && response.puntajes) {
                const puntajes = response.puntajes;
                $('#victimaConflicto').val(puntajes.victim_conflict_score || 0);
                $('#cabezaFamilia').val(puntajes.head_of_household_score || 0);
                $('#sisbenGrupoA').val(puntajes.sisben_group_a_score || 0);
                $('#rentaJoven').val(puntajes.renta_joven_beneficiary_score || 0);
                $('#discapacidad').val(puntajes.disability_score || 0);
                $('#violenciaGenero').val(puntajes.gender_violence_victim_score || 0);
                $('#embarazadaLactante').val(puntajes.pregnant_or_lactating_score || 0);
                $('#grupoEtnico').val(puntajes.ethnic_group_affiliation_score || 0);
                $('#sisbenGrupoB').val(puntajes.sisben_group_b_score || 0);
                $('#aprendizRural').val(puntajes.rural_apprentice_score || 0);
                
                mostrarAlerta('success', 'Puntajes cargados exitosamente');
            } else {
                mostrarAlerta('info', 'No se encontraron puntajes para esta convocatoria');
            }
        },
        error: function() {
            mostrarAlerta('danger', 'Error al cargar puntajes');
        }
    });
}

// Cambiar estado de convocatoria
function cambiarEstado(id, nuevoEstado) {
    const mensaje = nuevoEstado === 'Active' ? 'activar' : 'desactivar';
    $('#mensajeConfirmacion').text(`¿Está seguro de que desea ${mensaje} esta convocatoria?`);
    
    $('#btnConfirmar').off('click').on('click', function() {
        $.ajax({
            url: '{{ route("cefa.sga.admin.sys-params.cambiar-estado", ":id") }}'.replace(':id', id),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                estado: nuevoEstado
            },
            success: function(response) {
                if (response.success) {
                    mostrarAlerta('success', response.message);
                    actualizarTablaConvocatorias();
                } else {
                    mostrarAlerta('danger', 'Error al cambiar estado');
                }
            },
            error: function() {
                mostrarAlerta('danger', 'Error al cambiar estado de la convocatoria');
            }
        });
        
        $('#modalConfirmacion').modal('hide');
    });
    
    $('#modalConfirmacion').modal('show');
}

// Actualizar datos
function actualizarDatos() {
    location.reload();
}

// Funciones auxiliares para actualizar contenido
function actualizarTablaConvocatorias() {
    $.ajax({
        url: '{{ route("cefa.sga.admin.sys-params.obtener-convocatorias") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                // Actualizar tabla (implementar según necesidad)
                console.log('Convocatorias actualizadas');
            }
        }
    });
}

function actualizarSelectConvocatorias() {
    $.ajax({
        url: '{{ route("cefa.sga.admin.sys-params.obtener-convocatorias") }}',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Seleccione una convocatoria...</option>';
                response.convocatorias.forEach(conv => {
                    options += `<option value="${conv.id}">${conv.name} (${conv.year})</option>`;
                });
                $('#convocatoriaSelect').html(options);
            }
        }
    });
}

function actualizarListaEventos() {
    // Implementar actualización de lista de eventos
    console.log('Actualizando lista de eventos...');
}
</script>
@endpush