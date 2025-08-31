@extends('sga::layouts.master')

@section('content')
<h3 class="mb-4">{{ trans('sga::contents.AdmAsvTitle') }}</h3>

{{-- Mostrar mensajes de éxito, error o advertencia --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<form action="{{ route('cefa.sga.admin.asv.asistencias.jornada') }}" method="POST" class="mb-4" id="filterForm">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <input type="text" 
                   name="name" 
                   class="form-control" 
                   placeholder="{{ trans('sga::contents.AdmAsvFilter-apprentice') }}"
                   value="{{ old('name', request('name')) }}">
        </div>
        <div class="col-md-3">
            <input type="text" 
                   name="document_number" 
                   class="form-control" 
                   placeholder="{{ trans('sga::contents.AdmAsvFilter-documentnumber') }}"
                   value="{{ old('document_number', request('document_number')) }}">
        </div>
        <div class="col-md-3">
            <input type="date" 
                   name="date" 
                   class="form-control"
                   value="{{ old('date', request('date')) }}">
        </div>
        <div class="col-md-3">
            <select name="course_id" class="form-control">
                <option value="">{{ trans('sga::contents.AdmAsvFilter-course') }}</option>
                @if(isset($cursos) && count($cursos) > 0)
                    @foreach ($cursos as $curso)
                        <option value="{{ $curso->id }}" 
                                {{ old('course_id', request('course_id')) == $curso->id ? 'selected' : '' }}>
                            {{ $curso->code ?? 'Sin código' }}
                        </option>
                    @endforeach
                @else
                    <option value="">{{ trans('sga::contents.AdmAsvFilter-coursedanger') }}</option>
                @endif
            </select>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-search"></i> {{ trans('sga::menu.Search') }}
            </button>
            <a href="{{ route('cefa.sga.admin.asv') }}" class="btn btn-secondary ml-2">
                <i class="fas fa-retweet"></i> {{ trans('sga::menu.Clear') }}
            </a>
            
            {{-- Botones de exportación --}}
            @if(isset($asistencias) && count($asistencias) > 0)
                <div class="btn-group ml-3" role="group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                    <div class="dropdown-menu">
                        <button type="button" class="dropdown-item export-btn" data-format="pdf">
                            <i class="fas fa-file-pdf text-danger"></i> Exportar a PDF
                        </button>
                        <button type="button" class="dropdown-item export-btn" data-format="word">
                            <i class="fas fa-file-word text-primary"></i> Exportar a Word
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>

@if(isset($asistencias) && count($asistencias) > 0)
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                {{ trans('sga::contents.AdmAsvTable-title') }}
                <span class="badge badge-primary ml-2">{{ count($asistencias) }} {{ trans('sga::contents.AdmAsvTable-title-apprentice') }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>{{ trans('sga::contents.AdmAsvTable-apprentice') }}</th>
                            <th>{{ trans('sga::contents.AdmAsvTable-document') }}</th>
                            <th>{{ trans('sga::contents.AdmAsvTable-course') }}</th>
                            <th>{{ trans('sga::contents.AdmAsvTable-date') }}</th>
                            <th>{{ trans('sga::contents.AdmAsvTable-status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp
                        @foreach($asistencias as $apprentice)
                            @foreach($apprentice->asistencias as $asistencia)
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td>{{ $apprentice->person->name }}</td>
                                    <td class="text-center">{{ $apprentice->person->document_number }}</td>
                                    <td>{{ $apprentice->course->code }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($asistencia->asistance->date)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ $asistencia->asistance->type_asistance }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@elseif(isset($asistencias))
    <div class="alert alert-warning mt-4">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Sin resultados:</strong> No se encontraron asistencias de "Convocatorias de Alimentación" con los criterios especificados.
    </div>
@endif

{{-- Formulario oculto para exportaciones --}}
<form id="exportForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="name" value="{{ request('name') }}">
    <input type="hidden" name="document_number" value="{{ request('document_number') }}">
    <input type="hidden" name="date" value="{{ request('date') }}">
    <input type="hidden" name="course_id" value="{{ request('course_id') }}">
</form>

{{-- Scripts inline para evitar problemas de timing --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funciones principales
    function exportData(format) {
        const exportForm = document.getElementById('exportForm');
        
        // Establecer la acción según el formato
        if (format === 'pdf') {
            exportForm.action = '{{ route("cefa.sga.admin.asv.export.pdf") }}';
        } else if (format === 'word') {
            exportForm.action = '{{ route("cefa.sga.admin.asv.export.word") }}';
        }
        
        // Mostrar un mensaje de carga
        const loadingToast = showLoadingMessage(format);
        
        // Enviar el formulario
        exportForm.submit();
        
        // Ocultar el mensaje después de un tiempo
        setTimeout(() => {
            hideLoadingMessage(loadingToast);
        }, 3000);
    }

    function testExport(format) {
        // Crear formulario temporal para testing
        const testForm = document.createElement('form');
        testForm.method = 'POST';
        testForm.style.display = 'none';
        
        // Agregar token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        testForm.appendChild(csrfToken);
        
        // Establecer la acción según el formato
        if (format === 'pdf') {
            testForm.action = '{{ route("debug.test.pdf") }}';
        } else if (format === 'word') {
            testForm.action = '{{ route("debug.test.word") }}';
        }
        
        // Agregar al DOM y enviar
        document.body.appendChild(testForm);
        testForm.submit();
        
        // Limpiar
        setTimeout(() => {
            document.body.removeChild(testForm);
        }, 100);
    }

    function showLoadingMessage(format) {
        const formatText = format === 'pdf' ? 'PDF' : 'Word';
        
        // Crear elemento de notificación
        const toast = document.createElement('div');
        toast.className = 'alert alert-info alert-dismissible fade show position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="spinner-border spinner-border-sm mr-2" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <div>
                    <strong>Generando archivo ${formatText}...</strong><br>
                    <small>Por favor espere mientras se procesa la información.</small>
                </div>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        return toast;
    }

    function hideLoadingMessage(toast) {
        if (toast && toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }

    // Event listeners para los botones de exportación
    const exportButtons = document.querySelectorAll('.export-btn');
    exportButtons.forEach(button => {
        button.addEventListener('click', function() {
            const format = this.getAttribute('data-format');
            exportData(format);
            
            // Deshabilitar temporalmente el botón
            this.disabled = true;
            setTimeout(() => {
                this.disabled = false;
            }, 5000);
        });
    });

    // Event listeners para los botones de test
    const testButtons = document.querySelectorAll('.test-btn');
    testButtons.forEach(button => {
        button.addEventListener('click', function() {
            const format = this.getAttribute('data-format');
            testExport(format);
            
            // Deshabilitar temporalmente el botón
            this.disabled = true;
            setTimeout(() => {
                this.disabled = false;
            }, 5000);
        });
    });
});
</script>

@endsection