@extends('sga::layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Historial de Beneficios - SGA</h2>
            
            @if($application && $convocatory)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Convocatoria Activa:</strong> {{ $convocatory->name }} - {{ $convocatory->quarter }} {{ $convocatory->year }}
                    <br>
                    <small class="text-muted">
                        Periodo: {{ \Carbon\Carbon::parse($convocatory->registration_start_date)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($convocatory->registration_deadline)->format('d/m/Y') }}
                    </small>
                </div>
            @elseif(!$application)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>No has aplicado a ninguna convocatoria.</strong>
                    <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-primary btn-sm ms-2">
                        <i class="fas fa-edit"></i> Aplicar Ahora
                    </a>
                </div>
            @else
                <div class="alert alert-secondary">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Convocatoria Inactiva:</strong> No hay convocatorias activas en este momento.
                </div>
            @endif
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-filter"></i> Filtros</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="month" class="form-label">Mes:</label>
                            <select class="form-select" id="month">
                                <option value="">Todos los meses</option>
                                @if($convocatory)
                                    @php
                                        $startDate = \Carbon\Carbon::parse($convocatory->registration_start_date);
                                        $endDate = \Carbon\Carbon::parse($convocatory->registration_deadline);
                                        $currentDate = $startDate->copy();
                                    @endphp
                                    @while($currentDate <= $endDate)
                                        <option value="{{ $currentDate->format('Y-m') }}" 
                                                {{ $currentDate->format('Y-m') === now()->format('Y-m') ? 'selected' : '' }}>
                                            {{ $currentDate->format('F Y') }}
                                        </option>
                                        @php
                                            $currentDate->addMonth();
                                        @endphp
                                    @endwhile
                                @else
                                    <option value="{{ now()->format('Y-m') }}" selected>{{ now()->format('F Y') }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Estado:</label>
                            <select class="form-select" id="status">
                                <option value="">Todos</option>
                                <option value="received" selected>Recibido</option>
                                <option value="missed">No Recibido</option>
                                <option value="justified">Justificado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Desde:</label>
                            <input type="date" class="form-control" id="date_from" 
                                   value="{{ $convocatory ? \Carbon\Carbon::parse($convocatory->registration_start_date)->format('Y-m-d') : now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Hasta:</label>
                            <input type="date" class="form-control" id="date_to" 
                                   value="{{ $convocatory ? \Carbon\Carbon::parse($convocatory->registration_deadline)->format('Y-m-d') : now()->addMonths(3)->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    @if(count($benefitHistory) > 0)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistics['total_received'] ?? 0 }}</h3>
                        <p class="mb-0">Almuerzos Recibidos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistics['total_missed'] ?? 0 }}</h3>
                        <p class="mb-0">No Recibidos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistics['total_justified'] ?? 0 }}</h3>
                        <p class="mb-0">Justificados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3>{{ $statistics['attendance_percentage'] ?? 0 }}%</h3>
                        <p class="mb-0">Asistencia</p>
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
                        <h5 class="text-muted">No hay estadísticas disponibles</h5>
                        <p class="text-muted mb-0">Completa tu perfil y aplica a una convocatoria para ver estadísticas</p>
                        @if(!$application)
                            <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-edit"></i> Aplicar a Convocatoria
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabla de historial -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-table"></i> Historial Detallado</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Día</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($benefitHistory) > 0)
                                    @foreach($benefitHistory as $benefit)
                                        <tr>
                                            <td>{{ $benefit['date']->format('d/m/Y') }}</td>
                                            <td>{{ $benefit['day_spanish'] }}</td>
                                            <td>
                                                @if($benefit['time'])
                                                    {{ $benefit['time']->format('h:i A') }}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>
                                                @if($benefit['status'] === 'received')
                                                    <span class="badge bg-success">Recibido</span>
                                                @elseif($benefit['status'] === 'missed')
                                                    <span class="badge bg-danger">No Recibido</span>
                                                @elseif($benefit['status'] === 'justified')
                                                    <span class="badge bg-warning">Justificado</span>
                                                @endif
                                            </td>
                                            <td>{{ $benefit['observations'] }}</td>
                                            <td>
                                                @if($benefit['status'] === 'received' || $benefit['status'] === 'justified')
                                                    <button class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @elseif($benefit['status'] === 'missed')
                                                    <button class="btn btn-sm btn-outline-warning" title="Justificar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0">No hay historial de beneficios disponible</p>
                                            @if(!$application)
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

    <!-- Paginación -->
    @if(count($benefitHistory) > 10)
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Navegación de páginas">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Anterior</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        @if(count($benefitHistory) > 20)
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                        @endif
                        @if(count($benefitHistory) > 30)
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="#">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @endif

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtros
    const monthFilter = document.getElementById('month');
    const statusFilter = document.getElementById('status');
    const dateFromFilter = document.getElementById('date_from');
    const dateToFilter = document.getElementById('date_to');
    
    // Función para aplicar filtros
    function applyFilters() {
        const selectedMonth = monthFilter.value;
        const selectedStatus = statusFilter.value;
        const selectedDateFrom = dateFromFilter.value;
        const selectedDateTo = dateToFilter.value;
        
        // Aquí puedes implementar la lógica de filtrado
        // Por ahora solo mostraremos un mensaje
        console.log('Filtros aplicados:', {
            month: selectedMonth,
            status: selectedStatus,
            dateFrom: selectedDateFrom,
            dateTo: selectedDateTo
        });
        
        // Mostrar mensaje de filtros aplicados
        showFilterMessage();
    }
    
    // Función para mostrar mensaje de filtros aplicados
    function showFilterMessage() {
        const filterInfo = [];
        
        if (monthFilter.value) {
            const monthText = monthFilter.options[monthFilter.selectedIndex].text;
            filterInfo.push(`Mes: ${monthText}`);
        }
        
        if (statusFilter.value) {
            const statusText = statusFilter.options[statusFilter.selectedIndex].text;
            filterInfo.push(`Estado: ${statusText}`);
        }
        
        if (dateFromFilter.value || dateToFilter.value) {
            const dateRange = [];
            if (dateFromFilter.value) dateRange.push(`Desde: ${dateFromFilter.value}`);
            if (dateToFilter.value) dateRange.push(`Hasta: ${dateToFilter.value}`);
            filterInfo.push(`Rango: ${dateRange.join(' - ')}`);
        }
        
        if (filterInfo.length > 0) {
            // Crear o actualizar mensaje de filtros
            let filterMessage = document.getElementById('filterMessage');
            if (!filterMessage) {
                filterMessage = document.createElement('div');
                filterMessage.id = 'filterMessage';
                filterMessage.className = 'alert alert-secondary mt-3';
                document.querySelector('.card-body').appendChild(filterMessage);
            }
            
            filterMessage.innerHTML = `
                <i class="fas fa-filter me-2"></i>
                <strong>Filtros aplicados:</strong> ${filterInfo.join(' | ')}
                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Limpiar Filtros
                </button>
            `;
        }
    }
    
    // Función para limpiar filtros
    window.clearFilters = function() {
        monthFilter.value = '';
        statusFilter.value = '';
        dateFromFilter.value = '';
        dateToFilter.value = '';
        
        // Remover mensaje de filtros
        const filterMessage = document.getElementById('filterMessage');
        if (filterMessage) {
            filterMessage.remove();
        }
        
        // Recargar la página para mostrar todos los datos
        location.reload();
    };
    
    // Event listeners para filtros
    monthFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    dateFromFilter.addEventListener('change', applyFilters);
    dateToFilter.addEventListener('change', applyFilters);
    
    // Botón para aplicar filtros manualmente
    const applyFiltersBtn = document.createElement('button');
    applyFiltersBtn.className = 'btn btn-primary mt-3';
    applyFiltersBtn.innerHTML = '<i class="fas fa-filter"></i> Aplicar Filtros';
    applyFiltersBtn.onclick = applyFilters;
    
    // Insertar botón después de los filtros
    const filterRow = document.querySelector('.card-body .row');
    filterRow.appendChild(document.createElement('div')).className = 'col-12';
    filterRow.lastElementChild.appendChild(applyFiltersBtn);
});
</script>
@endpush
@endsection