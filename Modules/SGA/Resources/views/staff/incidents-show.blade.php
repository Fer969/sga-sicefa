@extends('sga::layouts.master')

@section('content')
<div class="container mt-4" style="max-width: 600px;">
    <h3>Detalle de Incidencia</h3>
    <div class="card mt-3">
        <div class="card-body">
            <h4>{{ $incident->title }}</h4>
            <p><strong>Descripción:</strong><br>{{ $incident->description }}</p>
            <p><strong>Estado:</strong> {{ $incident->status }}</p>
            <p><strong>Fecha de creación:</strong> {{ $incident->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Reportada por:</strong> {{ $incident->user->person->first_name ?? $incident->user->name }}</p>
            @if($incident->status === 'Abierta')
            <form action="{{ route('cefa.sga.staff.incidents.close', $incident->id) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-warning">Cerrar Incidencia</button>
            </form>
            @endif
            <a href="{{ route('cefa.sga.staff.incidents') }}" class="btn btn-secondary mt-3">Volver al listado</a>
        </div>
    </div>
</div>
@endsection 