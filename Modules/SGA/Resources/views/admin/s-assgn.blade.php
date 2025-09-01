@extends('sga::layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark-green">
                <i class="fas fa-users text-olive-green me-2"></i>
                Asignación de Subsidio
            </h1>
            <p class="text-muted">Gestionar postulados a convocatorias de Apoyo de Alimentación</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="badge bg-olive-green fs-6">
                <i class="fas fa-calendar-alt me-1"></i>
                {{ isset($convocatoria_actual) ? $convocatoria_actual->quarter . '° Trimestre ' . $convocatoria_actual->year : 'Sin convocatoria' }}
            </div>
        </div>
    </div>

    <!-- Mensajes de sesión -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Selector de Convocatoria -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>
                Seleccionar Convocatoria
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('cefa.sga.admin.s-assgn') }}" id="convocatoriaForm">
                <div class="row align-items-end">
                    <div class="col-lg-6">
                        <label for="convocatoria_id" class="form-label fw-bold">
                            <i class="fas fa-filter me-1"></i>
                            Convocatoria de Apoyo de Alimentación
                        </label>
                        <select class="form-select border-olive-green" id="convocatoria_id" name="convocatoria_id" onchange="this.form.submit()">
                            @if(isset($convocatorias) && $convocatorias->count() > 0)
                                @foreach($convocatorias as $convocatoria)
                                    <option value="{{ $convocatoria->id }}" 
                                            {{ isset($convocatoria_actual) && $convocatoria_actual->id == $convocatoria->id ? 'selected' : '' }}>
                                        {{ $convocatoria->name }} ({{ $convocatoria->quarter }}° Trimestre {{ $convocatoria->year }})
                                    </option>
                                @endforeach
                            @else
                                <option value="">No hay convocatorias disponibles</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>
                            Buscar Postulados
                        </button>
                    </div>
                    <div class="col-lg-3">
                        @if(isset($convocatoria_actual))
                            <button type="button" class="btn btn-success w-100" onclick="exportarPDF()">
                                <i class="fas fa-file-pdf me-1"></i>
                                Exportar PDF
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Información de la Convocatoria Seleccionada -->
    @if(isset($convocatoria_actual))
    <div class="alert alert-info border-0 shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h6 class="mb-1">
                    <i class="fas fa-info-circle me-2"></i>
                    Convocatoria Seleccionada
                </h6>
                <p class="mb-0">
                    <strong>{{ $convocatoria_actual->name }}</strong> 
                    ({{ $convocatoria_actual->quarter }}° Trimestre {{ $convocatoria_actual->year }})
                </p>
                <small class="text-muted">
                    <strong>Total de postulados:</strong> {{ $total_postulados }}
                </small>
            </div>
            <div class="col-lg-4 text-end">
                <button class="btn btn-outline-danger btn-sm" onclick="exportarPDF()">
                    <i class="fas fa-file-pdf me-1"></i>
                    Exportar PDF
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtros de Búsqueda -->
    @if(isset($postulados) && $postulados->count() > 0)
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>
                Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-4">
                    <label for="searchInput" class="form-label fw-bold">Búsqueda General</label>
                    <input type="text"
                        class="form-control border-olive-green"
                        placeholder="Buscar por nombre, documento o programa..."
                        id="searchInput"
                        onkeyup="filtrarTabla()">
                </div>
                <div class="col-lg-3">
                    <label for="filtroPrograma" class="form-label fw-bold">Filtrar por Programa</label>
                    <select class="form-select border-olive-green" id="filtroPrograma" onchange="filtrarPorPrograma()">
                        <option value="">Todos los programas</option>
                        @php
                        $programas = $postulados->pluck('program')->unique()->filter()->sort();
                        @endphp
                        @foreach($programas as $programa)
                        <option value="{{ $programa }}">{{ $programa }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="filtroPuntaje" class="form-label fw-bold">Filtrar por Puntaje</label>
                    <select class="form-select border-olive-green" id="filtroPuntaje" onchange="filtrarPorPuntaje()">
                        <option value="">Todos los puntajes</option>
                        <option value="alto">Puntaje Alto (≥15)</option>
                        <option value="medio">Puntaje Medio (10-14)</option>
                        <option value="bajo">Puntaje Bajo (<10)</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                            <i class="fas fa-times me-1"></i>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Contador de resultados -->
            <div class="mt-3">
                <div id="contadorResultados" class="alert alert-secondary mb-0" style="display: none;">
                    <strong>Resultados filtrados:</strong> 
                    <span id="numeroResultados">0</span> de {{ $postulados->count() }} postulados
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de Postulados -->
    @if(isset($postulados) && $postulados->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-table me-2"></i>
                Lista de Postulados
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-hover mb-0" id="tablaSubsidios">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th class="text-center" style="min-width: 50px;">#</th>
                            <th style="min-width: 120px;">Documento</th>
                            <th style="min-width: 200px;">Nombre Completo</th>
                            <th style="min-width: 150px;">Programa</th>
                            <th style="min-width: 120px;">Teléfono</th>
                            <th style="min-width: 180px;">Email</th>
                            <th class="text-center" style="min-width: 100px;">
                                <i class="fas fa-star me-1"></i>Puntaje
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($postulados as $index => $postulado)
                        <tr class="
                            @if($postulado->total_points >= 15) table-success
                            @elseif($postulado->total_points >= 10) table-warning
                            @else table-danger
                            @endif
                        ">
                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $postulado->document_number }}</td>
                            <td>{{ $postulado->full_name }}</td>
                            <td>{{ $postulado->program ?? 'N/A' }}</td>
                            <td>{{ $postulado->telephone1 ?? 'N/A' }}</td>
                            <td>
                                @if($postulado->personal_email)
                                    <a href="mailto:{{ $postulado->personal_email }}" class="text-decoration-none">
                                        {{ $postulado->personal_email }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge 
                                    @if($postulado->total_points >= 15) bg-success
                                    @elseif($postulado->total_points >= 10) bg-warning text-dark
                                    @else bg-danger
                                    @endif
                                    fs-6">
                                    {{ $postulado->total_points ?? 0 }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @elseif(isset($convocatoria_actual))
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No hay postulados registrados</h5>
            <p class="text-muted">No se encontraron postulados para la convocatoria seleccionada.</p>
        </div>
    </div>
    @else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Selecciona una convocatoria</h5>
            <p class="text-muted">Elige una convocatoria de la lista para ver los postulados.</p>
        </div>
    </div>
    @endif
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Función para exportar PDF usando DomPDF
function exportarPDF() {
    const convocatoriaId = document.getElementById('convocatoria_id').value;
    
    if (!convocatoriaId) {
        Swal.fire({
            icon: 'warning',
            title: 'Selecciona una convocatoria',
            text: 'Debe seleccionar una convocatoria para exportar',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    // Mostrar indicador de carga
    const btnPDF = event.target;
    const originalText = btnPDF.innerHTML;
    btnPDF.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Generando PDF...';
    btnPDF.disabled = true;
    
    // Crear formulario temporal para enviar la solicitud POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("cefa.sga.admin.s-assgn.export-pdf") }}';
    
    // Agregar token CSRF
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    // Agregar ID de convocatoria
    const convocatoriaInput = document.createElement('input');
    convocatoriaInput.type = 'hidden';
    convocatoriaInput.name = 'convocatoria_id';
    convocatoriaInput.value = convocatoriaId;
    form.appendChild(convocatoriaInput);
    
    // Agregar formulario al DOM y enviarlo
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    // Restaurar botón después de un tiempo
    setTimeout(() => {
        btnPDF.innerHTML = originalText;
        btnPDF.disabled = false;
    }, 3000);
}



// Función para filtrar tabla
function filtrarTabla() {
    const input = document.getElementById('searchInput');
    const filtro = input.value.toLowerCase();
    const tabla = document.getElementById('tablaSubsidios');
    const filas = tabla.querySelectorAll('tbody tr');
    let contador = 0;
    
    filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        if (texto.includes(filtro)) {
            fila.style.display = '';
            contador++;
        } else {
            fila.style.display = 'none';
        }
    });
    
    mostrarContador(contador);
}

// Función para filtrar por programa
function filtrarPorPrograma() {
    const select = document.getElementById('filtroPrograma');
    const filtro = select.value.toLowerCase();
    const tabla = document.getElementById('tablaSubsidios');
    const filas = tabla.querySelectorAll('tbody tr');
    let contador = 0;
    
    filas.forEach(fila => {
        const programa = fila.cells[3].textContent.toLowerCase();
        if (filtro === '' || programa.includes(filtro)) {
            fila.style.display = '';
            contador++;
        } else {
            fila.style.display = 'none';
        }
    });
    
    mostrarContador(contador);
}

// Función para filtrar por puntaje
function filtrarPorPuntaje() {
    const select = document.getElementById('filtroPuntaje');
    const filtro = select.value;
    const tabla = document.getElementById('tablaSubsidios');
    const filas = tabla.querySelectorAll('tbody tr');
    let contador = 0;
    
    filas.forEach(fila => {
        const puntaje = parseInt(fila.cells[6].textContent.trim());
        
        let mostrar = false;
        switch(filtro) {
            case 'alto':
                mostrar = puntaje >= 15;
                break;
            case 'medio':
                mostrar = puntaje >= 10 && puntaje <= 14;
                break;
            case 'bajo':
                mostrar = puntaje < 10;
                break;
            default:
                mostrar = true;
        }
        
        if (mostrar) {
            fila.style.display = '';
            contador++;
        } else {
            fila.style.display = 'none';
        }
    });
    
    mostrarContador(contador);
}

// Función para limpiar filtros
function limpiarFiltros() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filtroPrograma').value = '';
    document.getElementById('filtroPuntaje').value = '';
    
    const tabla = document.getElementById('tablaSubsidios');
    const filas = tabla.querySelectorAll('tbody tr');
    
    filas.forEach(fila => {
        fila.style.display = '';
    });
    
    ocultarContador();
}

// Función para mostrar contador
function mostrarContador(contador) {
    const contadorElement = document.getElementById('contadorResultados');
    const numeroElement = document.getElementById('numeroResultados');
    
    numeroElement.textContent = contador;
    contadorElement.style.display = 'block';
}

// Función para ocultar contador
function ocultarContador() {
    document.getElementById('contadorResultados').style.display = 'none';
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vista s-assgn cargada correctamente');
});
</script>

@push('styles')
<style>
    .border-olive-green {
        border-color: #6b8e23 !important;
    }
    
    .text-dark-green {
        color: #2d5a3d !important;
    }
    
    .text-olive-green {
        color: #6b8e23 !important;
    }
    
    .bg-olive-green {
        background-color: #6b8e23 !important;
    }
    
    .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
    }
    
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 1020;
    }
</style>
@endpush
@endsection