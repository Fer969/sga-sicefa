<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias - Convocatorias de Alimentación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin: 0;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        
        .filters h3 {
            margin-top: 0;
            color: #007bff;
            font-size: 14px;
        }
        
        .filters ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        
        .filters li {
            margin: 5px 0;
        }
        
        .summary {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            border: 2px solid #007bff;
        }
        
        .summary h3 {
            margin: 0;
            color: #007bff;
            font-size: 16px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }
        
        table thead {
            background-color: #007bff;
            color: white;
        }
        
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        table th {
            font-weight: bold;
            text-align: center;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        table tbody tr:hover {
            background-color: #e7f3ff;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            background-color: #28a745;
            color: white;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        @page {
            margin: 2cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ trans('sga::contents.AdmAsvTitle') }}</h1>
        <p>Reporte de Asistencias - Convocatorias de Alimentación</p>
    </div>
    
    @if($filters['name'] || $filters['document_number'] || $filters['date'] || $filters['course_id'])
    <div class="filters">
        <h3>Filtros Aplicados:</h3>
        <ul>
            @if($filters['name'])
                <li><strong>Aprendiz:</strong> {{ $filters['name'] }}</li>
            @endif
            @if($filters['document_number'])
                <li><strong>Número de Documento:</strong> {{ $filters['document_number'] }}</li>
            @endif
            @if($filters['date'])
                <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($filters['date'])->format('d/m/Y') }}</li>
            @endif
            @if($filters['course_id'])
                @php
                    $curso = DB::table('courses')->where('id', $filters['course_id'])->first();
                @endphp
                @if($curso)
                    <li><strong>Curso:</strong> {{ $curso->code }}</li>
                @endif
            @endif
        </ul>
    </div>
    @endif
    
    <div class="summary">
        <h3>Total de Asistencias Encontradas: {{ $total }}</h3>
    </div>
    
    @if($total > 0)
        <table>
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="25%">{{ trans('sga::contents.AdmAsvTable-apprentice') }}</th>
                    <th width="15%">{{ trans('sga::contents.AdmAsvTable-document') }}</th>
                    <th width="20%">{{ trans('sga::contents.AdmAsvTable-course') }}</th>
                    <th width="15%">{{ trans('sga::contents.AdmAsvTable-date') }}</th>
                    <th width="17%">{{ trans('sga::contents.AdmAsvTable-status') }}</th>
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
                            <td class="text-center">
                                <span class="badge">
                                    {{ $asistencia->asistance->type_asistance }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>Sin Resultados</h3>
            <p>No se encontraron asistencias de "Convocatorias de Alimentación" con los criterios especificados.</p>
        </div>
    @endif
    
    <div class="footer">
        <p>Documento generado el: {{ $generated_at }}</p>
        <p>Sistema de Gestión de Almuerzos (SGA)</p>
    </div>
</body>
</html>