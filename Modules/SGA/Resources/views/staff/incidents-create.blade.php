@extends('sga::layouts.master')

@section('content')
<div class="container mt-4" style="max-width: 600px;">
    <h3>Registrar Nueva Incidencia</h3>
    <form action="{{ route('cefa.sga.staff.incidents-store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" name="title" id="title" class="form-control" required maxlength="255" value="{{ old('title') }}">
            @error('title')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        
        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
            @error('description')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        
        <div class="form-group">
            <label for="priority">Prioridad</label>
            <select name="priority" id="priority" class="form-control" required>
                <option value="">Seleccione una prioridad</option>
                <option value="Baja" {{ old('priority') == 'Baja' ? 'selected' : '' }}>Baja</option>
                <option value="Media" {{ old('priority') == 'Media' ? 'selected' : '' }}>Media</option>
                <option value="Alta" {{ old('priority') == 'Alta' ? 'selected' : '' }}>Alta</option>
                <option value="Crítica" {{ old('priority') == 'Crítica' ? 'selected' : '' }}>Crítica</option>
            </select>
            @error('priority')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        
        <div class="form-group">
            <label for="category">Categoría</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">Seleccione una categoría</option>
                <option value="Sistema" {{ old('category') == 'Sistema' ? 'selected' : '' }}>Sistema</option>
                <option value="Asistencia" {{ old('category') == 'Asistencia' ? 'selected' : '' }}>Asistencia</option>
                <option value="Cuenta de Usuario" {{ old('category') == 'Cuenta de Usuario' ? 'selected' : '' }}>Cuenta de Usuario</option>
                <option value="Reporte" {{ old('category') == 'Reporte' ? 'selected' : '' }}>Reporte</option>
                <option value="Técnica" {{ old('category') == 'Técnica' ? 'selected' : '' }}>Técnica</option>
                <option value="Otro" {{ old('category') == 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
            @error('category')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        
        <div class="form-group">
            <label for="estimated_resolution_date">Fecha Estimada de Resolución</label>
            <input type="date" name="estimated_resolution_date" id="estimated_resolution_date" class="form-control" value="{{ old('estimated_resolution_date') }}">
            @error('estimated_resolution_date')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        
        <div class="form-group">
            <label for="location">Ubicación</label>
            <input type="text" name="location" id="location" class="form-control" maxlength="255" value="{{ old('location') }}">
            @error('location')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        
        <div class="form-group">
            <label for="contact_info">Información de Contacto</label>
            <input type="text" name="contact_info" id="contact_info" class="form-control" maxlength="255" value="{{ old('contact_info') }}">
            @error('contact_info')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        
        <div class="form-group">
            <label for="tags">Etiquetas (separadas por comas)</label>
            <input type="text" name="tags" id="tags" class="form-control" placeholder="ej: urgente, sistema, usuario" value="{{ old('tags') }}">
            @error('tags')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Registrar Incidencia
            </button>
            <a href="{{ route('cefa.sga.staff.incidents') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>
@endsection 