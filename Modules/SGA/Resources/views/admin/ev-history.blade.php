@extends('sga::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="text-dark-green mb-1">
                        <i class="fas fa-history me-2 text-forest-green"></i>
                        Historial de Evaluaciones
                    </h3>
                    <p class="text-secondary mb-0">Convocatorias de Apoyo de Alimentación</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-gradient-forest fs-6 px-3 py-2 text-white">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <!-- Card principal -->
            <div class="card bg-light border-0 shadow-lg">
                <div class="card-header bg-gradient-emerald text-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-utensils me-2 text-white"></i>
                                Registro de Convocatorias Alimentarias
                            </h5>
                        </div>
                        <div class="col-auto">
                            @if($convocatories->isNotEmpty())
                                <span class="badge bg-white text-dark-green">{{ $convocatories->count() }} Convocatorias</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-light table-hover mb-0">
                            <thead style="background: linear-gradient(135deg, #2d5a3d 0%, #1e3a2e 100%);">
                                <tr>
                                    <th class="text-center border-0 py-3 text-sage-green">
                                        <i class="fas fa-hashtag text-light-green"></i>
                                    </th>
                                    <th class="border-0 py-3 text-sage-green">
                                        <i class="fas fa-bullhorn me-2 text-mint-green"></i>Convocatoria
                                    </th>
                                    <th class="text-center border-0 py-3 text-sage-green">
                                        <i class="fas fa-calendar-week me-2 text-sage-green"></i>Trimestre
                                    </th>
                                    <th class="text-center border-0 py-3 text-sage-green">
                                        <i class="fas fa-calendar-alt me-2 text-mint-green"></i>Año
                                    </th>
                                    <th class="text-center border-0 py-3 text-sage-green">
                                        <i class="fas fa-traffic-light me-2 text-light-green"></i>Estado
                                    </th>
                                    <th class="text-center border-0 py-3 text-sage-green">
                                        <i class="fas fa-play-circle me-2 text-sage-green"></i>F. Apertura
                                    </th>
                                    <th class="text-center border-0 py-3 text-sage-green">
                                        <i class="fas fa-stop-circle me-2 text-coral"></i>F. Cierre
                                    </th>
                                    <th class="text-center border-0 py-3 text-sage-green">
                                        <i class="fas fa-chair me-2 text-ocean-green"></i>Cupos
                                    </th>
                                    <th class="text-center border-0 py-3 text-sage-green">
                                        <i class="fas fa-users me-2 text-light-green"></i>Postulados
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($convocatories as $index => $convocatory)
                                    <tr class="border-bottom border-light-green">
                                        <td class="text-center py-3">
                                            <span class="badge bg-gradient-forest rounded-pill text-white">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-mint-green rounded-circle me-3" style="width: 8px; height: 8px;"></div>
                                                <strong class="text-dark-green">{{ $convocatory->name }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-gradient-sage text-white">{{ $convocatory->quarter }}</span>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="bg-mint-green bg-opacity-25 rounded-pill px-3 py-2 d-inline-block">
                                                <span class="text-white fw-bold fs-6">{{ $convocatory->year }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            @switch($convocatory->status)
                                                @case('active')
                                                    <span class="badge bg-gradient-sage text-white">
                                                        <i class="fas fa-check-circle me-1"></i>Activa
                                                    </span>
                                                    @break
                                                @case('closed')
                                                    <span class="badge bg-gradient-secondary text-white">
                                                        <i class="fas fa-lock me-1"></i>Cerrada
                                                    </span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-gradient-amber text-dark">
                                                        <i class="fas fa-clock me-1"></i>Pendiente
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-gradient-ocean text-white">
                                                        <i class="fas fa-question-circle me-1"></i>{{ $convocatory->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="text-center py-3">
                                            @if($convocatory->registration_start_date)
                                                <div class="text-sage-green fw-bold">
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
                                            <div class="bg-ocean-green bg-opacity-25 rounded-pill px-3 py-2 d-inline-block">
                                                <span class="text-white fw-bold fs-6">{{ $convocatory->coups ?? 0 }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="bg-forest-green bg-opacity-25 rounded-pill px-3 py-2 d-inline-block">
                                                <span class="text-white fw-bold fs-6">{{ $convocatory->postulados }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-search fs-1 mb-3 d-block text-sage-green"></i>
                                                <h5 class="text-dark-green">No se encontraron convocatorias</h5>
                                                <p class="mb-0 text-secondary">No hay convocatorias de alimentación registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($convocatories->isNotEmpty())
                    <div class="card-footer bg-gradient-emerald border-0">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="fas fa-list-alt text-white fs-4 mb-2 d-block"></i>
                                    <h5 class="text-white mb-1">{{ $convocatories->count() }}</h5>
                                    <small class="text-white">Total Convocatorias</small>
                                </div>
                            </div>
                            <div class="col-md-4 border-start border-end border-white border-opacity-50">
                                <div class="p-3">
                                    <i class="fas fa-users text-white fs-4 mb-2 d-block"></i>
                                    <h5 class="text-white mb-1">{{ $convocatories->sum('postulados') }}</h5>
                                    <small class="text-white">Total Postulaciones</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="fas fa-chair text-white fs-4 mb-2 d-block"></i>
                                    <h5 class="text-white mb-1">{{ $convocatories->sum('coups') }}</h5>
                                    <small class="text-white">Total Cupos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Fondo principal con gradiente verde claro */
    body {
        background: linear-gradient(135deg, #e8f5e8 0%, #d4e6d4 100%);
        min-height: 100vh;
    }
    
    /* Definición de colores verdes personalizados */
    :root {
        --forest-green: #1e3a2e;
        --dark-green: #2d5a3d; 
        --emerald-green: #3d6b4a;
        --sage-green: #4a7c59;
        --mint-green: #5a9268;
        --light-green: #8fbc8f;
        --ocean-green: #2e5266;
        --amber: #d4a574;
        --coral: #e57373;
    }
    
    /* Clases de colores personalizadas */
    .text-forest-green { color: var(--forest-green) !important; }
    .text-dark-green { color: var(--dark-green) !important; }
    .text-emerald-green { color: var(--emerald-green) !important; }
    .text-sage-green { color: var(--sage-green) !important; }
    .text-mint-green { color: var(--mint-green) !important; }
    .text-light-green { color: var(--light-green) !important; }
    .text-ocean-green { color: var(--ocean-green) !important; }
    .text-coral { color: var(--coral) !important; }
    
    /* Fondos de colores */
    .bg-forest-green { background-color: var(--forest-green) !important; }
    .bg-dark-green { background-color: var(--dark-green) !important; }
    .bg-emerald-green { background-color: var(--emerald-green) !important; }
    .bg-sage-green { background-color: var(--sage-green) !important; }
    .bg-mint-green { background-color: var(--mint-green) !important; }
    .bg-light-green { background-color: var(--light-green) !important; }
    .bg-ocean-green { background-color: var(--ocean-green) !important; }
    
    /* Bordes de colores */
    .border-light-green { border-color: var(--light-green) !important; }
    .border-sage-green { border-color: var(--sage-green) !important; }
    
    /* Gradientes verdes */
    .bg-gradient-forest {
        background: linear-gradient(45deg, var(--forest-green), var(--dark-green)) !important;
    }
    
    .bg-gradient-emerald {
        background: linear-gradient(135deg, var(--emerald-green) 0%, var(--dark-green) 100%) !important;
    }
    
    .bg-gradient-sage {
        background: linear-gradient(45deg, var(--sage-green), var(--emerald-green)) !important;
    }
    
    .bg-gradient-mint {
        background: linear-gradient(45deg, var(--mint-green), var(--sage-green)) !important;
    }
    
    .bg-gradient-ocean {
        background: linear-gradient(45deg, var(--ocean-green), var(--sage-green)) !important;
    }
    
    .bg-gradient-amber {
        background: linear-gradient(45deg, var(--amber), #c49363) !important;
    }
    
    .bg-gradient-secondary {
        background: linear-gradient(45deg, #6c757d, #495057) !important;
    }
    
    .bg-success-light {
        background-color: rgba(255, 255, 255, 0.9) !important;
        border: 1px solid var(--sage-green);
    }
    
    /* Efectos hover mejorados */
    .table-light tr:hover {
        background-color: rgba(138, 188, 143, 0.1) !important;
        transition: all 0.3s ease;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(46, 90, 61, 0.15);
    }
    
    /* Estilos de tarjeta mejorados */
    .card {
        backdrop-filter: blur(10px);
        border-radius: 15px !important;
        box-shadow: 0 10px 30px rgba(46, 90, 61, 0.2) !important;
    }
    
    /* Badges mejorados */
    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        box-shadow: 0 2px 4px rgba(46, 90, 61, 0.2);
    }
    
    /* Efectos de sombra suaves */
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(46, 90, 61, 0.175) !important;
    }
    
    /* Animaciones sutiles */
    .badge, .rounded-pill {
        transition: all 0.3s ease;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }
    
    /* Mejoras en iconos */
    i.fas {
        transition: all 0.3s ease;
    }
    
    thead tr:hover i.fas {
        transform: scale(1.1);
    }
    
    /* Tabla con mejor contraste */
    .table-light {
        background-color: #ffffff !important;
        color: var(--dark-green) !important;
    }
    
    .table-light tbody tr {
        background-color: #ffffff;
        color: var(--dark-green);
    }
    
    .table-light tbody td {
        color: var(--dark-green) !important;
    }
    
    /* Espaciado mejorado */
    .py-3 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
</style>

@endsection