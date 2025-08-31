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
                    <form method="POST" action="{{ route('cefa.sga.admin.convocatories.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nombreConvocatoria" class="form-label">Nombre de la Convocatoria</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombreConvocatoria" name="nombre"
                                placeholder="Ej. Convocatoria Alimentación I - 2025" maxlength="255" required value="{{ old('nombre') }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipoConvocatoria" class="form-label">Tipo de Convocatoria</label>
                                <select class="form-select @error('tipo_convocatoria') is-invalid @enderror" id="tipoConvocatoria" name="tipo_convocatoria" required>
                                    @if(isset($tiposConvocatorias) && count($tiposConvocatorias) > 0)
                                        @foreach($tiposConvocatorias as $tipo)
                                        <option value="{{ $tipo->id }}" {{ old('tipo_convocatoria', $tipo->id) == $tipo->id ? 'selected' : '' }}>{{ $tipo->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="">No hay tipos de convocatoria disponibles</option>
                                    @endif
                                </select>
                                @error('tipo_convocatoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Solo se permiten convocatorias de "Apoyo de Alimentación"</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="trimestre" class="form-label">Trimestre</label>
                                <select class="form-select @error('trimestre') is-invalid @enderror" id="trimestre" name="trimestre" required>
                                    <option value="">Seleccione...</option>
                                    <option value="1" {{ old('trimestre') == '1' ? 'selected' : '' }}>I Trimestre</option>
                                    <option value="2" {{ old('trimestre') == '2' ? 'selected' : '' }}>II Trimestre</option>
                                    <option value="3" {{ old('trimestre') == '3' ? 'selected' : '' }}>III Trimestre</option>
                                    <option value="4" {{ old('trimestre') == '4' ? 'selected' : '' }}>IV Trimestre</option>
                                </select>
                                @error('trimestre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fechaInicio" name="fecha_inicio" required value="{{ old('fecha_inicio') }}">
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fechaCierre" class="form-label">Fecha de Cierre</label>
                                <input type="date" class="form-control @error('fecha_cierre') is-invalid @enderror" id="fechaCierre" name="fecha_cierre" required value="{{ old('fecha_cierre') }}">
                                @error('fecha_cierre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cuposDisponibles" class="form-label">Cantidad de Cupos</label>
                                <input type="number" class="form-control @error('cupos') is-invalid @enderror" id="cuposDisponibles" name="cupos"
                                    placeholder="Ej. 350" min="1" max="1000" required value="{{ old('cupos') }}">
                                @error('cupos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="año" class="form-label">Año</label>
                                <input type="number" class="form-control @error('año') is-invalid @enderror" id="año" name="año"
                                    value="{{ old('año', date('Y')) }}" min="2024" max="2030" required>
                                @error('año')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success" id="btnCrearConvocatoria">
                            <i class="fas fa-save me-2"></i>Crear Convocatoria
                        </button>
                        <button type="button" class="btn btn-secondary ms-2" onclick="confirmarLimpiarFormulario()">
                            <i class="fas fa-eraser me-2"></i>Limpiar
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
                                        <div class="btn-group" role="group">
                                            <form method="POST" action="{{ route('cefa.sga.admin.convocatories.cambiar-estado', $conv->id) }}" style="display: inline;" class="me-1">
                                                @csrf
                                                <input type="hidden" name="estado" value="{{ $conv->status == 'Active' ? 'Inactive' : 'Active' }}">
                                                <button type="submit" class="btn btn-sm btn-outline-primary" 
                                                        onclick="return confirmarCambioEstado('{{ $conv->status == 'Active' ? 'desactivar' : 'activar' }}')" 
                                                        title="Cambiar Estado">
                                                    <i class="fas fa-toggle-{{ $conv->status == 'Active' ? 'off' : 'on' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('cefa.sga.admin.convocatories.destroy', $conv->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirmarEliminacion()" 
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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



@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Limpiar formulario si hay mensaje de éxito
    @if(session('success'))
        limpiarFormulario();
    @endif
});

/**
 * Confirmar cambio de estado con SweetAlert
 */
function confirmarCambioEstado(accion) {
    return Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas ${accion} esta convocatoria?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        return result.isConfirmed;
    });
}

/**
 * Confirmar eliminación con SweetAlert
 */
function confirmarEliminacion() {
    return Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer. ¿Deseas continuar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        return result.isConfirmed;
    });
}

/**
 * Confirmar limpieza del formulario con SweetAlert
 */
function confirmarLimpiarFormulario() {
    Swal.fire({
        title: '¿Limpiar formulario?',
        text: '¿Estás seguro de que deseas limpiar todos los campos del formulario?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            limpiarFormulario();
            Swal.fire({
                icon: 'success',
                title: '¡Formulario limpiado!',
                text: 'Todos los campos han sido limpiados',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

/**
 * Limpiar formulario
 */
function limpiarFormulario() {
    document.getElementById('formCrearConvocatoria').reset();
    document.getElementById('año').value = new Date().getFullYear();
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
 * Mostrar notificación de advertencia con SweetAlert
 */
function mostrarAdvertencia(mensaje) {
    Swal.fire({
        icon: 'warning',
        title: '¡Advertencia!',
        text: mensaje,
        confirmButtonText: 'Entendido'
    });
}
</script>
@endpush