@extends('sga::layouts.master')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Mensajes de Sesión con SweetAlert -->
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Entendido'
            });
        });
    </script>
@endif

<!-- TAB EVENTOS EXTERNOS -->
<div class="tab-pane fade show active" id="external_events" role="tabpanel">
    <div class="row g-4">
        <!-- Formulario Crear/Editar Evento -->
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0" id="formTitle">
                        <i class="fas fa-plus me-2"></i>Crear Nuevo Evento Externo
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cefa.sga.admin.external-events.store') }}" id="formEventoExterno">
                        @csrf
                        <input type="hidden" id="evento_id" name="evento_id">
                        
                        <!-- Nombre del Evento -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nombre del Evento</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   maxlength="150" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Máximo 150 caracteres</small>
                        </div>

                        <!-- Número de Almuerzos -->
                        <div class="mb-3">
                            <label for="number_lunchs" class="form-label fw-bold">Número de Almuerzos</label>
                            <input type="number" class="form-control @error('number_lunchs') is-invalid @enderror" 
                                   id="number_lunchs" name="number_lunchs" 
                                   min="0" max="999" value="{{ old('number_lunchs', 0) }}" required>
                            @error('number_lunchs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Cantidad de almuerzos disponibles (0-999)</small>
                        </div>

                        <!-- Descuento de Almuerzos -->
                        <div class="mb-3">
                            <label for="lunchs_discount" class="form-label fw-bold">Descuento de Almuerzos</label>
                            <input type="number" class="form-control @error('lunchs_discount') is-invalid @enderror" 
                                   id="lunchs_discount" name="lunchs_discount" 
                                   min="0" max="999" value="{{ old('lunchs_discount', 0) }}" required>
                            @error('lunchs_discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Porcentaje de descuento aplicado (0-999)</small>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" 
                                      rows="2" maxlength="250" 
                                      style="resize: none; height: 60px;">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Descripción opcional del evento (máximo 250 caracteres)</small>
                        </div>

                        <!-- Elementos Requeridos -->
                        <div class="mb-4">
                            <label for="required_elements" class="form-label fw-bold">Elementos Requeridos</label>
                            <textarea class="form-control @error('required_elements') is-invalid @enderror" 
                                      id="required_elements" name="required_elements" 
                                      rows="2" maxlength="250" 
                                      style="resize: none; height: 60px;">{{ old('required_elements') }}</textarea>
                            @error('required_elements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Elementos o documentos requeridos (máximo 250 caracteres)</small>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-success flex-fill" id="btnGuardarEvento">
                                <i class="fas fa-save me-2"></i>Guardar Evento
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                                <i class="fas fa-eraser me-2"></i>Limpiar
                            </button>
                            <button type="button" class="btn btn-warning" id="btnCancelarEdicion" 
                                    onclick="cancelarEdicion()" style="display: none;">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                            <!-- Botón de prueba temporal -->
                            <button type="button" class="btn btn-info" onclick="probarFunciones()">
                                <i class="fas fa-bug me-2"></i>Probar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Eventos Existentes -->
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Eventos Existentes</h5>
                    @if(isset($eventos) && count($eventos) > 0)
                        <span class="badge bg-light text-dark">{{ count($eventos) }} eventos</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if(isset($eventos) && count($eventos) > 0)
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light sticky-top" style="background: #f8f9fa;">
                                    <tr>
                                        <th style="min-width: 200px;">Evento</th>
                                        <th style="min-width: 100px; text-align: center;">Almuerzos</th>
                                        <th style="min-width: 100px; text-align: center;">Descuento</th>
                                        <th style="min-width: 120px; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eventos as $evento)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong class="text-primary">{{ $evento->name }}</strong>
                                                @if($evento->description)
                                                    <small class="text-muted mt-1">{{ Str::limit($evento->description, 60) }}</small>
                                                @endif
                                                @if($evento->required_elements)
                                                    <small class="text-info mt-1">
                                                        <i class="fas fa-clipboard-list me-1"></i>
                                                        {{ Str::limit($evento->required_elements, 50) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary fs-6">{{ $evento->number_lunchs }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success fs-6">{{ $evento->lunchs_discount }}%</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick="editarEvento({{ $evento->id }})" 
                                                        title="Editar evento">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmarEliminacion({{ $evento->id }}, '{{ $evento->name }}')" 
                                                        title="Eliminar evento">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-2">No hay eventos externos configurados</p>
                            <p class="text-muted small">Crea el primer evento usando el formulario</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CARGADO - EXTERNAL EVENTS ===');
    
    // Limpiar formulario si hay mensaje de éxito
    @if(session('success'))
        limpiarFormulario();
    @endif
});

/**
 * Editar un evento existente
 */
function editarEvento(eventoId) {
    console.log('Editando evento con ID:', eventoId);
    
    // Cambiar el formulario a modo edición
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Evento Externo';
    document.getElementById('btnGuardarEvento').innerHTML = '<i class="fas fa-save me-2"></i>Actualizar Evento';
    document.getElementById('btnCancelarEdicion').style.display = 'inline-block';
    
    // Cambiar la acción del formulario
    const form = document.getElementById('formEventoExterno');
    form.action = `/sga/admin/external-events/${eventoId}`;
    form.method = 'POST';
    
    // Agregar método PUT
    if (!document.querySelector('input[name="_method"]')) {
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    } else {
        document.querySelector('input[name="_method"]').value = 'PUT';
    }
    
    console.log('Formulario configurado para edición:', form.action, form.method);
    
    // Obtener datos del evento
    const url = `/sga/admin/external-events/${eventoId}`;
    console.log('Fetching URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            if (data.success) {
                const evento = data.evento;
                document.getElementById('evento_id').value = evento.id;
                document.getElementById('name').value = evento.name;
                document.getElementById('number_lunchs').value = evento.number_lunchs;
                document.getElementById('lunchs_discount').value = evento.lunchs_discount;
                document.getElementById('description').value = evento.description || '';
                document.getElementById('required_elements').value = evento.required_elements || '';
                
                console.log('Formulario llenado con datos del evento:', evento);
                
                // Hacer scroll al formulario
                document.getElementById('formEventoExterno').scrollIntoView({ behavior: 'smooth' });
            } else {
                mostrarError('Error al cargar los datos del evento: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error en fetch:', error);
            mostrarError('Error al cargar los datos del evento: ' + error.message);
        });
}

/**
 * Cancelar edición y volver al modo creación
 */
function cancelarEdicion() {
    // Restaurar formulario a modo creación
    document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus me-2"></i>Crear Nuevo Evento Externo';
    document.getElementById('btnGuardarEvento').innerHTML = '<i class="fas fa-save me-2"></i>Guardar Evento';
    document.getElementById('btnCancelarEdicion').style.display = 'none';
    
    // Restaurar acción del formulario
    const form = document.getElementById('formEventoExterno');
    form.action = '{{ route("cefa.sga.admin.external-events.store") }}';
    form.method = 'POST';
    
    // Remover método PUT
    const methodInput = document.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }
    
    // Limpiar formulario
    limpiarFormulario();
}

/**
 * Confirmar eliminación de evento con SweetAlert
 */
function confirmarEliminacion(eventoId, nombreEvento) {
    console.log('Confirmando eliminación del evento:', eventoId, nombreEvento);
    
    Swal.fire({
        title: '¿Eliminar evento?',
        text: `¿Está seguro de que desea eliminar el evento "${nombreEvento}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Usuario confirmó eliminación, creando formulario...');
            
            // Crear formulario de eliminación
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/sga/admin/external-events/${eventoId}`;
            
            // Agregar CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Agregar método DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            console.log('Formulario de eliminación creado:', form.action, form.method);
            
            // Enviar formulario
            document.body.appendChild(form);
            form.submit();
        }
    });
}

/**
 * Limpiar formulario
 */
function limpiarFormulario() {
    document.getElementById('formEventoExterno').reset();
    document.getElementById('evento_id').value = '';
    
    // Restaurar valores por defecto
    document.getElementById('number_lunchs').value = '0';
    document.getElementById('lunchs_discount').value = '0';
    
    // Cancelar edición si está en modo edición
    if (document.getElementById('btnCancelarEdicion').style.display !== 'none') {
        cancelarEdicion();
    }
}

/**
 * Mostrar notificación de éxito con SweetAlert
 */
function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: mensaje,
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

/**
 * Mostrar notificación de error con SweetAlert
 */
function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: mensaje,
        confirmButtonText: 'Entendido'
    });
}

/**
 * Función de prueba para verificar que el JavaScript funciona
 */
function probarFunciones() {
    console.log('=== PRUEBA DE FUNCIONES ===');
    console.log('SweetAlert disponible:', typeof Swal !== 'undefined');
    console.log('Formulario encontrado:', document.getElementById('formEventoExterno'));
    console.log('Botón editar encontrado:', document.querySelector('.btn-outline-primary'));
    console.log('Botón eliminar encontrado:', document.querySelector('.btn-outline-danger'));
    
    // Probar SweetAlert
    Swal.fire({
        title: '¡Prueba exitosa!',
        text: 'El JavaScript está funcionando correctamente',
        icon: 'success',
        confirmButtonText: 'Perfecto'
    });
}
</script>