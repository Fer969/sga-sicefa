@extends('sga::layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Historial de Aplicaciones - SGA</h2>
            
            @if($activeConvocatory)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Convocatoria Activa:</strong> {{ $activeConvocatory->name }} - Q{{ $activeConvocatory->quarter }} {{ $activeConvocatory->year }}
                    <br>
                    <small class="text-muted">
                        Periodo: {{ \Carbon\Carbon::parse($activeConvocatory->registration_start_date)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($activeConvocatory->registration_deadline)->format('d/m/Y') }}
                    </small>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>No hay convocatorias activas.</strong> Actualmente no hay convocatorias de alimentación disponibles.
                </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas -->
    @if(count($applicationsHistory) > 0)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistics['total_applications'] }}</h3>
                        <p class="mb-0">Total Aplicaciones</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistics['active_applications'] }}</h3>
                        <p class="mb-0">Aplicaciones Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistics['total_points_earned'] }}</h3>
                        <p class="mb-0">Puntos Totales</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistics['average_points'] }}</h3>
                        <p class="mb-0">Promedio Puntos</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-chart-bar text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted">No hay aplicaciones registradas</h5>
                        <p class="text-muted mb-0">Aplica a una convocatoria para ver tu historial</p>
                        @if($activeConvocatory)
                            <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-edit"></i> Aplicar a Convocatoria
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabla de historial de aplicaciones -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-table"></i> Historial de Aplicaciones</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Convocatoria</th>
                                    <th>Trimestre</th>
                                    <th>Puntos</th>
                                    <th>Posición</th>
                                    <th>Estado</th>
                                    <th>Fecha Aplicación</th>
                                    <th>Período</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($applicationsHistory) > 0)
                                    @foreach($applicationsHistory as $application)
                                        <tr class="{{ $application['is_active'] ? 'table-success' : '' }}">
                                            <td>
                                                <strong>{{ $application['convocatory_name'] }}</strong>
                                                @if($application['is_active'])
                                                    <span class="badge bg-success ms-2">Activa</span>
                                                @endif
                                            </td>
                                            <td>Q{{ $application['quarter'] }} - {{ $application['year'] }}</td>
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $application['total_points'] }} pts</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">#{{ $application['position'] }}</span>
                                                <br>
                                                <small class="text-muted">{{ $application['applications_count'] }} aplicaciones</small>
                                            </td>
                                            <td>
                                                @if($application['application_status'] === 'Activa')
                                                    <span class="badge bg-success">{{ $application['application_status'] }}</span>
                                                @elseif($application['application_status'] === 'Próximamente')
                                                    <span class="badge bg-warning">{{ $application['application_status'] }}</span>
                                                @elseif($application['application_status'] === 'Finalizada')
                                                    <span class="badge bg-secondary">{{ $application['application_status'] }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $application['application_status'] }}</span>
                                                @endif
                                                <br>
                                                <span class="badge bg-{{ $application['cup_status'] }}">{{ $application['cup_level'] }}</span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($application['application_date'])->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="small">
                                                    <div><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($application['registration_start'])->format('d/m/Y') }}</div>
                                                    <div><strong>Cierre:</strong> {{ \Carbon\Carbon::parse($application['registration_deadline'])->format('d/m/Y') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-info" title="Ver detalles" onclick="verDetalles({{ $application['id'] }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($application['is_active'] && $application['application_status'] === 'Activa')
                                                        <a href="{{ route('cefa.sga.apprentice.my-benefit') }}" class="btn btn-sm btn-outline-success" title="Mi beneficio">
                                                            <i class="fas fa-gift"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0">No hay historial de aplicaciones disponible</p>
                                            @if($activeConvocatory)
                                                <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-primary btn-sm mt-2">
                                                    <i class="fas fa-edit"></i> Aplicar a Convocatoria
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('cefa.sga.apprentice.my-benefit') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-gift"></i> Mi Beneficio
            </a>
            <a href="{{ route('cefa.sga.apprentice.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalDetallesLabel">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalles de la Aplicación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetallesBody">
                <!-- El contenido se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verDetalles(applicationId) {
    // Aquí puedes implementar la lógica para cargar los detalles de la aplicación
    // Por ahora mostraremos un mensaje de ejemplo
    const modalBody = document.getElementById('modalDetallesBody');
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
            <p>Cargando detalles de la aplicación...</p>
        </div>
    `;
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    modal.show();
    
    // Simular carga de datos (reemplazar con llamada AJAX real)
    setTimeout(() => {
        modalBody.innerHTML = `
            <div class="alert alert-info">
                <h6><strong>Funcionalidad en desarrollo</strong></h6>
                <p class="mb-0">Los detalles detallados de cada aplicación estarán disponibles próximamente.</p>
            </div>
        `;
    }, 1000);
}
</script>
@endpush
@endsection