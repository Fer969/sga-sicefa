@extends('sga::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="text-dark mb-1">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Resumen de Beneficios
                    </h3>
                    <p class="text-secondary mb-0">Convocatorias de Alimentación - Aprendices Presenciales</p>
                </div>
                <div class="text-end">
                    <div class="badge bg-primary fs-6 px-3 py-2 mb-2">
                        <i class="fas fa-utensils me-1"></i>
                        Valor por almuerzo: ${{ number_format($valorAlmuerzo, 0, ',', '.') }} COP
                    </div>
                    <div class="d-block">
                        <span class="badge bg-secondary fs-6 px-3 py-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas generales -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white border-0 shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-users fs-2 mb-2"></i>
                            <h4 class="mb-1">{{ $aprendices->count() }}</h4>
                            <small>Aprendices Beneficiados</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white border-0 shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-utensils fs-2 mb-2"></i>
                            <h4 class="mb-1">{{ number_format($totalAlmuerzosCount) }}</h4>
                            <small>Total Almuerzos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white border-0 shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-hand-holding-usd fs-2 mb-2"></i>
                            <h4 class="mb-1">${{ number_format($totalDescuento, 0, ',', '.') }}</h4>
                            <small>Total Descuentos (50%)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white border-0 shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-dollar-sign fs-2 mb-2"></i>
                            <h4 class="mb-1">${{ number_format($totalValorOriginal, 0, ',', '.') }}</h4>
                            <small>Valor Original</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen Financiero y Estadísticas -->
            @if($aprendices->isNotEmpty())
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-calculator me-2"></i>
                                Resumen Financiero
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h5 class="text-primary mb-1">${{ number_format($totalValorOriginal, 0, ',', '.') }}</h5>
                                        <small class="text-muted">Valor Original</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div>
                                        <h5 class="text-success mb-1">${{ number_format($totalDescuento, 0, ',', '.') }}</h5>
                                        <small class="text-muted">Total Descuentos</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Estadísticas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h5 class="text-success mb-1">{{ number_format($aprendices->avg('total_almuerzos'), 1) }}</h5>
                                        <small class="text-muted">Promedio/Aprendiz</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div>
                                        <h5 class="text-info mb-1">{{ $aprendices->max('total_almuerzos') }}</h5>
                                        <small class="text-muted">Máximo Almuerzos</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tabla principal -->
            <div class="card border-0 shadow">
                <div class="card-header bg-dark text-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>
                                Detalle de Beneficios por Aprendiz
                            </h5>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-light text-dark">{{ $aprendices->count() }} Registros</span>
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
                                        <i class="fas fa-user-graduate me-2"></i>Nombre del Aprendiz
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-id-card me-2"></i>Documento
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-book me-2"></i>Curso
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-utensils me-2"></i>Almuerzos
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-dollar-sign me-2"></i>Valor Total
                                    </th>
                                    <th class="text-center border-0 py-3">
                                        <i class="fas fa-percentage me-2"></i>Descuento 50%
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($aprendices as $index => $aprendiz)
                                    @php
                                        $valorTotal = $aprendiz->total_almuerzos * $valorAlmuerzo;
                                        $descuento = $valorTotal * 0.5;
                                    @endphp
                                    <tr>
                                        <td class="text-center py-3">
                                            <span class="badge bg-primary rounded-pill">{{ $index + 1 }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded-circle me-3" style="width: 10px; height: 10px;"></div>
                                                <div>
                                                    <strong class="text-dark d-block">{{ $aprendiz->full_name }}</strong>
                                                    <small class="text-muted">ID: {{ $aprendiz->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="bg-light rounded px-2 py-1 d-inline-block">
                                                <span class="text-dark fw-bold">{{ $aprendiz->document_number }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="bg-info bg-opacity-25 rounded px-2 py-1 d-inline-block">
                                                <span class="text-info fw-bold">{{ $aprendiz->course_code ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="bg-success bg-opacity-25 rounded-pill px-3 py-2 d-inline-block">
                                                <span class="text-success fw-bold fs-5">{{ $aprendiz->total_almuerzos }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="text-primary fw-bold fs-6">
                                                <i class="fas fa-dollar-sign me-1"></i>
                                                {{ number_format($valorTotal, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            <div class="text-success fw-bold fs-6">
                                                <i class="fas fa-hand-holding-usd me-1"></i>
                                                {{ number_format($descuento, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-search fs-1 mb-3 d-block text-secondary"></i>
                                                <h5 class="text-dark">No se encontraron beneficiarios</h5>
                                                <p class="mb-0 text-secondary">No hay aprendices con almuerzos registrados</p>
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
@endsection