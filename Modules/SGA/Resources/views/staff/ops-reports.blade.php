@extends('sga::layouts.master')

@section('content')
<div class="container mt-4">
    <!-- Header mejorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-chart-bar text-primary"></i> Reportes Operativos
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="{{ route('cefa.sga.staff.index') }}">SGA</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reportes Operativos</li>
                        </ol>
                    </nav>
                </div>
                <div class="text-right">
                    <div class="badge badge-info p-2">
                        <i class="fas fa-calendar"></i> {{ now()->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Hoy</h6>
                            <h4 class="mb-0" id="todayCount">-</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Esta Semana</h6>
                            <h4 class="mb-0" id="weekCount">-</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-week fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Este Mes</h6>
                            <h4 class="mb-0" id="monthCount">-</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Asistencia</h6>
                            <h4 class="mb-0" id="attendanceRate">-</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formularios de exportación -->
    <div class="row">
        <!-- Reporte por Día -->
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-day"></i> Reporte por Día
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('cefa.sga.staff.ops-reports.export-day') }}" id="dayForm">
                        <div class="form-group">
                            <label for="date">
                                <i class="fas fa-calendar"></i> Selecciona un día:
                            </label>
                            <input type="date" class="form-control" name="date" id="date" 
                                   value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger" onclick="exportDayPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte por Semana -->
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-week"></i> Reporte por Semana
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('cefa.sga.staff.ops-reports.export-week') }}" id="weekForm">
                        <div class="form-group">
                            <label for="start">
                                <i class="fas fa-play"></i> Fecha inicio:
                            </label>
                            <input type="date" class="form-control" name="start" id="start" 
                                   value="{{ now()->startOfWeek()->toDateString() }}" required>
                        </div>
                        <div class="form-group">
                            <label for="end">
                                <i class="fas fa-stop"></i> Fecha fin:
                            </label>
                            <input type="date" class="form-control" name="end" id="end" 
                                   value="{{ now()->endOfWeek()->toDateString() }}" required>
                        </div>
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger" onclick="exportWeekPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte por Mes -->
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt"></i> Reporte por Mes
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('cefa.sga.staff.ops-reports.export-month') }}" id="monthForm">
                        <div class="form-group">
                            <label for="month">
                                <i class="fas fa-calendar"></i> Selecciona un mes:
                            </label>
                            <input type="month" class="form-control" name="month" id="month" 
                                   value="{{ now()->format('Y-m') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="year">
                                <i class="fas fa-calendar-year"></i> Año:
                            </label>
                            <input type="number" class="form-control" name="year" id="year" 
                                   min="2000" max="2100" value="{{ now()->year }}" required>
                        </div>
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger" onclick="exportMonthPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6><i class="fas fa-info-circle text-info"></i> Información sobre los reportes:</h6>
                    <ul class="mb-0">
                        <li><strong>Excel:</strong> Descarga directa del archivo .xlsx con todos los datos</li>
                        <li><strong>PDF:</strong> Vista previa en el navegador con formato profesional</li>
                        <li>Los reportes incluyen: Número de documento, nombre completo, ficha, programa, fecha, hora y estado</li>
                        <li>Los PDF incluyen el logo del SENA y la fecha/hora de generación</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar estadísticas al iniciar
    loadStats();
    
    // Validar fechas de semana
    document.getElementById('start').addEventListener('change', function() {
        const start = this.value;
        const end = document.getElementById('end').value;
        if (start && end && start > end) {
            alert('La fecha de inicio no puede ser mayor a la fecha de fin');
            this.value = '';
        }
    });
    
    document.getElementById('end').addEventListener('change', function() {
        const start = document.getElementById('start').value;
        const end = this.value;
        if (start && end && start > end) {
            alert('La fecha de fin no puede ser menor a la fecha de inicio');
            this.value = '';
        }
    });
});

function loadStats() {
    // Cargar estadísticas reales desde el servidor
    fetch('{{ route("cefa.sga.staff.stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error al cargar estadísticas:', data.error);
                return;
            }
            
            document.getElementById('todayCount').textContent = data.today || '0';
            document.getElementById('weekCount').textContent = data.week || '0';
            document.getElementById('monthCount').textContent = data.month || '0';
            document.getElementById('attendanceRate').textContent = (data.attendance_rate || 0) + '%';
        })
        .catch(error => {
            console.error('Error al cargar estadísticas:', error);
            // Mantener valores por defecto en caso de error
            document.getElementById('todayCount').textContent = '0';
            document.getElementById('weekCount').textContent = '0';
            document.getElementById('monthCount').textContent = '0';
            document.getElementById('attendanceRate').textContent = '0%';
        });
}

function exportDayPDF() {
    const date = document.getElementById('date').value;
    if (!date) {
        alert('Por favor selecciona una fecha');
        return;
    }
    window.open(`{{ route('cefa.sga.staff.ops-reports.export-day-pdf') }}?date=${date}`, '_blank');
}

function exportWeekPDF() {
    const start = document.getElementById('start').value;
    const end = document.getElementById('end').value;
    if (!start || !end) {
        alert('Por favor completa las fechas de inicio y fin');
        return;
    }
    if (start > end) {
        alert('La fecha de inicio no puede ser mayor a la fecha de fin');
        return;
    }
    window.open(`{{ route('cefa.sga.staff.ops-reports.export-week-pdf') }}?start=${start}&end=${end}`, '_blank');
}

function exportMonthPDF() {
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    if (!month || !year) {
        alert('Por favor completa el mes y año');
        return;
    }
    const [yearInput, monthInput] = month.split('-');
    window.open(`{{ route('cefa.sga.staff.ops-reports.export-month-pdf') }}?month=${monthInput}&year=${yearInput}`, '_blank');
}
</script>
@endsection