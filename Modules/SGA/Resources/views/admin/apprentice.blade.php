@extends('sga::layouts.master')

@section('content')
<div class="sga-wrap py-4">
    <div class="container">

        {{-- Header --}}
        <div class="mb-4">
            <h1 class="sga-title">
                <i class="fas fa-users"></i> Gestión de Aprendices
            </h1>
            <p class="sga-subtitle mb-0">
                Consulta aprendices por <strong>nombre</strong>, <strong>documento</strong> o <strong>código de curso</strong>.
            </p>
        </div>

        {{-- Filtros --}}
        <div class="sga-card p-3 p-md-4 mb-4">
            <form method="GET" action="{{ route('cefa.sga.admin.apprentice') }}" id="searchForm">
                <div class="row sga-h-row">
                    <div class="col-md-4 position-relative">
                        <i class="fas fa-search icon-in"></i>
                        <input type="text"
                            name="name"
                            value="{{ request('name') }}"
                            class="form-control sga-input with-icon"
                            placeholder="Nombre o Apellido">
                    </div>
                    <div class="col-md-4 position-relative">
                        <i class="fas fa-id-card icon-in"></i>
                        <input type="text"
                            name="document"
                            value="{{ request('document') }}"
                            class="form-control sga-input with-icon"
                            placeholder="Documento">
                    </div>
                    <div class="col-md-4 position-relative">
                        <i class="fas fa-book icon-in"></i>
                        <input type="text"
                            name="course_code"
                            value="{{ request('course_code') }}"
                            class="form-control sga-input with-icon"
                            placeholder="Código de curso (ej. 2898694)">
                    </div>
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn sga-btn">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('cefa.sga.admin.apprentice') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo"></i> Limpiar
                        </a>
                        @if(request()->anyFilled(['name','document','course_code']))
                        <span class="sga-chip">
                            <i class="fas fa-filter"></i> Filtros activos
                        </span>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Estadísticas rápidas --}}
        @if($apprentices->total() > 0)
        <div class="filter-summary">
            <div class="row text-center">
                <div class="col-md-12">
                    <strong>{{ $apprentices->total() }}</strong> aprendices encontrados
                    @if(request()->anyFilled(['name','document','course_code']))
                    con los filtros aplicados
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Resultados --}}
        <div class="sga-card">
            <div class="table-responsive">
                <table class="table sga-table mb-0">
                    <thead class="sga-sticky">
                        <tr>
                            <th style="width:60px">#</th>
                            <th><i class="fas fa-user"></i> Aprendiz</th>
                            <th><i class="fas fa-id-badge"></i> Documento</th>
                            <th><i class="fas fa-chalkboard-teacher"></i> Curso / Programa</th>
                            <th><i class="fas fa-clipboard-list"></i> Condiciones</th>
                            <th><i class="fas fa-hand-holding-heart"></i> Socioeconómico</th>
                            <th><i class="fas fa-file-alt"></i> Declaraciones</th>
                            <th><i class="fas fa-user-friends"></i> Representante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($apprentices as $index => $apprentice)
                        @php
                        $p = $apprentice->person;
                        $c = $apprentice->course;
                        $program = optional($c)->program;
                        $cond = optional($p->conditions);
                        $soc = optional($p->socioeconomic);
                        @endphp
                        <tr>
                            <td>{{ $apprentices->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $p->first_name }} {{ $p->first_last_name }} {{ $p->second_last_name }}
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-envelope"></i>
                                    {{ $p->personal_email ?? $p->misena_email ?? $p->sena_email ?? 'Sin correo' }}
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $p->document_number }}</span>
                                <div class="text-muted small">
                                    <i class="far fa-id-card"></i> {{ $p->document_type }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $c->code ?? 'N/A' }}
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-graduation-cap"></i>
                                    {{ $program->name ?? 'Programa no asociado' }}
                                </div>
                            </td>
                            <td style="min-width:220px">
                                @if($cond)
                                @if($cond->victim_conflict === 'SI')
                                <span class="sga-badge">
                                    <i class="fas fa-shield-alt"></i> Víctima conflicto
                                </span>
                                @endif
                                @if($cond->head_of_household === 'SI')
                                <span class="sga-badge">
                                    <i class="fas fa-home"></i> Cabeza de hogar
                                </span>
                                @endif
                                @if($cond->pregnant_or_lactating === 'SI')
                                <span class="sga-badge">
                                    <i class="fas fa-baby"></i> Gestante/Lactante
                                </span>
                                @endif
                                @if($cond->rural_apprentice === 'SI')
                                <span class="sga-badge">
                                    <i class="fas fa-tractor"></i> Rural
                                </span>
                                @endif
                                @if($cond->sisben_group_a === 'SI')
                                <span class="sga-badge sga-badge--info">
                                    <i class="fas fa-layer-group"></i> SISBEN A
                                </span>
                                @endif
                                @if($cond->sisben_group_b === 'SI')
                                <span class="sga-badge sga-badge--info">
                                    <i class="fas fa-layer-group"></i> SISBEN B
                                </span>
                                @endif
                                @if(!$cond->victim_conflict && !$cond->head_of_household && !$cond->pregnant_or_lactating && !$cond->rural_apprentice && !$cond->sisben_group_a && !$cond->sisben_group_b)
                                <em class="text-muted">Sin condiciones especiales</em>
                                @endif
                                @else
                                <em class="text-muted">No registra</em>
                                @endif
                            </td>
                            <td style="min-width:220px">
                                @if($soc)
                                @if($soc->renta_joven_beneficiary === 'SI')
                                <span class="sga-badge">
                                    <i class="fas fa-hand-holding-usd"></i> Renta Joven
                                </span>
                                @endif
                                @if($soc->receives_food_support === 'SI')
                                <span class="sga-badge">
                                    <i class="fas fa-utensils"></i> Apoyo alimentación
                                </span>
                                @endif
                                @if($soc->receives_transport_support === 'SI')
                                <span class="sga-badge">
                                    <i class="fas fa-bus"></i> Apoyo transporte
                                </span>
                                @endif
                                @if($soc->receives_tech_support === 'SI')
                                <span class="sga-badge">
                                    <i class="fas fa-laptop"></i> Apoyo tecnológico
                                </span>
                                @endif
                                @if(!$soc->renta_joven_beneficiary && !$soc->receives_food_support && !$soc->receives_transport_support && !$soc->receives_tech_support)
                                <em class="text-muted">Sin apoyos registrados</em>
                                @endif
                                @else
                                <em class="text-muted">No registra</em>
                                @endif
                            </td>
                            <td style="min-width:220px">
                                @if($p->swornStatements->count())
                                @foreach($p->swornStatements as $sw)
                                <div class="mb-2">
                                    <span class="sga-badge sga-badge--info">
                                        <i class="fas fa-user"></i> {{ $sw->responsible_name }}
                                    </span>
                                    <div class="text-muted small mt-1">
                                        <i class="far fa-id-card"></i> {{ $sw->responsible_document }}
                                        @if($sw->live_with)
                                        <br><i class="fas fa-users"></i> Convive: {{ $sw->live_with }}
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <em class="text-muted">No registra</em>
                                @endif
                            </td>
                            <td style="min-width:200px">
                                @if($p->representativeLegal)
                                <div class="fw-semibold">
                                    <i class="fas fa-user"></i> {{ $p->representativeLegal->name }}
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="fas fa-user-tag"></i> {{ $p->representativeLegal->relationship }}
                                    <br><i class="fas fa-phone"></i> {{ $p->representativeLegal->telephone1 }}
                                </div>
                                @else
                                <em class="text-muted">No registra</em>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="no-results">
                                    <i class="fas fa-search"></i>
                                    <h4>No se encontraron aprendices</h4>
                                    <p class="mb-0">Intenta ajustar los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($apprentices->hasPages())
            <div class="pagination-container p-3 mt-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <!-- Información de registros -->
                    <div class="text-muted small">
                        Mostrando {{ $apprentices->firstItem() ?? 0 }}—{{ $apprentices->lastItem() ?? 0 }}
                        de {{ number_format($apprentices->total()) }} registros
                    </div>

                    <!-- Enlaces de paginación -->
                    <nav aria-label="Paginación de aprendices">
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            <!-- Botón Anterior -->
                            <li class="page-item {{ $apprentices->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $apprentices->previousPageUrl() }}" aria-label="Anterior">
                                    <span aria-hidden="true">&laquo; Anterior</span>
                                </a>
                            </li>

                            <!-- Números de página -->
                            @foreach($apprentices->getUrlRange(max(1, $apprentices->currentPage() - 2), min($apprentices->lastPage(), $apprentices->currentPage() + 2)) as $page => $url)
                            <li class="page-item {{ $page == $apprentices->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @endforeach

                            <!-- Botón Siguiente -->
                            <li class="page-item {{ $apprentices->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $apprentices->nextPageUrl() }}" aria-label="Siguiente">
                                    <span aria-hidden="true">Siguiente &raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            @else
            <div class="text-muted small text-center p-3">
                No hay suficientes registros para paginar.
            </div>
            @endif
        </div>
    </div>
</div>

@endsection