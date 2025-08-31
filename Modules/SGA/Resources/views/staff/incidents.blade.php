@extends('sga::layouts.master')

@section('content')
<div class="container mt-4">
    <!-- Header mejorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Manejo de Incidencias
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="{{ route('cefa.sga.staff.index') }}">SGA</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Incidencias</li>
                        </ol>
                    </nav>
                </div>
                <div class="text-right">
                    <a href="{{ route('cefa.sga.staff.incidents-create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nueva Incidencia
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['total'] ?? 0 }}</h4>
                    <small>Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['open'] ?? 0 }}</h4>
                    <small>Abiertas</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['in_progress'] ?? 0 }}</h4>
                    <small>En Progreso</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['resolved'] ?? 0 }}</h4>
                    <small>Resueltas</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4>{{ $stats['overdue'] ?? 0 }}</h4>
                    <small>Vencidas</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4>{{ round($stats['avg_resolution_time'] ?? 0, 1) }}</h4>
                    <small>Promedio (hrs)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('cefa.sga.staff.incidents') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Estado</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Abierta" {{ request('status') == 'Abierta' ? 'selected' : '' }}>Abierta</option>
                                        <option value="En Progreso" {{ request('status') == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                                        <option value="Pendiente" {{ request('status') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="Resuelta" {{ request('status') == 'Resuelta' ? 'selected' : '' }}>Resuelta</option>
                                        <option value="Cerrada" {{ request('status') == 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="priority">Prioridad</label>
                                    <select name="priority" id="priority" class="form-control">
                                        <option value="">Todas</option>
                                        <option value="Baja" {{ request('priority') == 'Baja' ? 'selected' : '' }}>Baja</option>
                                        <option value="Media" {{ request('priority') == 'Media' ? 'selected' : '' }}>Media</option>
                                        <option value="Alta" {{ request('priority') == 'Alta' ? 'selected' : '' }}>Alta</option>
                                        <option value="Crítica" {{ request('priority') == 'Crítica' ? 'selected' : '' }}>Crítica</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="category">Categoría</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Todas</option>
                                        <option value="Sistema" {{ request('category') == 'Sistema' ? 'selected' : '' }}>Sistema</option>
                                        <option value="Asistencia" {{ request('category') == 'Asistencia' ? 'selected' : '' }}>Asistencia</option>
                                        <option value="Cuenta de Usuario" {{ request('category') == 'Cuenta de Usuario' ? 'selected' : '' }}>Cuenta de Usuario</option>
                                        <option value="Reporte" {{ request('category') == 'Reporte' ? 'selected' : '' }}>Reporte</option>
                                        <option value="Técnica" {{ request('category') == 'Técnica' ? 'selected' : '' }}>Técnica</option>
                                        <option value="Otro" {{ request('category') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search">Buscar</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Título o descripción..." value="{{ request('search') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="{{ route('cefa.sga.staff.incidents') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <!-- Tabla de incidencias -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Lista de Incidencias
                        <span class="badge badge-primary ml-2">{{ $incidents->total() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Estado</th>
                                    <th>Prioridad</th>
                                    <th>Categoría</th>
                                    <th>Reportada por</th>
                                    <th>Asignada a</th>
                                    <th>Fecha</th>
                                    <th>Días</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incidents as $incident)
                                <tr class="{{ $incident->is_overdue ? 'table-danger' : '' }}">
                                    <td><strong>#{{ $incident->id }}</strong></td>
                                    <td>
                                        <a href="{{ route('cefa.sga.staff.incidents-show', $incident->id) }}"
                                            class="text-decoration-none">
                                            {{ $incident->title }}
                                        </a>
                                        @if($incident->comments->count() > 0)
                                        <span class="badge badge-info">{{ $incident->comments->count() }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $incident->status_badge }}">
                                            {{ $incident->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $incident->priority_badge }}">
                                            {{ $incident->priority }}
                                        </span>
                                    </td>
                                    <td>{{ $incident->category }}</td>
                                    <td>{{ $incident->user->name ?? 'N/A' }}</td>
                                    <td>{{ $incident->assignedTo->name ?? 'Sin asignar' }}</td>
                                    <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $incident->days_open }}</span>
                                        @if($incident->is_overdue)
                                        <span class="badge badge-danger">Vencida</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($incident->canBeAssigned())
                                            <button type="button" class="btn btn-sm btn-warning"
                                                onclick="assignIncident({{ $incident->id }})" title="Asignar">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                            @endif
                                            @if($incident->canBeResolved())
                                            <button type="button" class="btn btn-sm btn-success"
                                                onclick="resolveIncident({{ $incident->id }})" title="Resolver">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            @if($incident->canBeClosed())
                                            <form action="{{ route('cefa.sga.staff.incidents-close', $incident->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-dark" title="Cerrar">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay incidencias</h5>
                                        <p class="text-muted">No se encontraron incidencias con los filtros aplicados.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($incidents->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $incidents->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function exportIncidents() {
        const form = document.getElementById('filterForm');
        const action = form.action;
        form.action = '{{ route("cefa.sga.staff.incidents-export") }}';
        form.submit();
        form.action = action;
    }

    function assignIncident(incidentId) {
        // Implementar modal de asignación
        alert('Función de asignación en desarrollo');
    }

    function resolveIncident(incidentId) {
        // Implementar modal de resolución
        alert('Función de resolución en desarrollo');
    }
</script>
@endsection