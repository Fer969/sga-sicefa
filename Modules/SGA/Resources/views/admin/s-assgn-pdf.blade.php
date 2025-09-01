<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulados - {{ $convocatoria->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .info {
            margin-bottom: 20px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 9px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .puntaje-alto {
            background-color: #d4edda;
        }
        .puntaje-medio {
            background-color: #fff3cd;
        }
        .puntaje-bajo {
            background-color: #f8d7da;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>POSTULADOS A CONVOCATORIA</h1>
        <h2>{{ $convocatoria->name }}</h2>
        <p>Tipo: {{ $convocatoria->tipo_nombre }}</p>
    </div>

    <div class="info">
        <p><strong>Total de postulados:</strong> {{ $total_postulados }}</p>
        <p><strong>Fecha de generación:</strong> {{ date('d/m/Y H:i:s') }}</p>
        <p><strong>Convocatoria:</strong> {{ $convocatoria->name }} ({{ $convocatoria->quarter }}° Trimestre {{ $convocatoria->year }})</p>
    </div>

    @if($postulados->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Documento</th>
                    <th>Nombre Completo</th>
                    <th>Programa</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($postulados as $index => $postulado)
                    <tr class="
                        @if($postulado->total_points >= 15) puntaje-alto
                        @elseif($postulado->total_points >= 10) puntaje-medio
                        @else puntaje-bajo
                        @endif
                    ">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $postulado->document_number }}</td>
                        <td>{{ $postulado->full_name }}</td>
                        <td>{{ $postulado->program ?? 'N/A' }}</td>
                        <td>{{ $postulado->telephone1 ?? 'N/A' }}</td>
                        <td>{{ $postulado->personal_email ?? 'N/A' }}</td>
                        <td><strong>{{ $postulado->total_points ?? 0 }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <h3>Resumen de Puntajes:</h3>
            <ul>
                <li><strong>Puntaje Alto (≥15):</strong> {{ $postulados->where('total_points', '>=', 15)->count() }} postulados</li>
                <li><strong>Puntaje Medio (10-14):</strong> {{ $postulados->whereBetween('total_points', [10, 14])->count() }} postulados</li>
                <li><strong>Puntaje Bajo (<10):</strong> {{ $postulados->where('total_points', '<', 10)->count() }} postulados</li>
            </ul>
        </div>
    @else
        <p><strong>No hay postulados registrados para esta convocatoria.</strong></p>
    @endif

    <div class="footer">
        <p>Documento generado automáticamente el {{ date('d/m/Y') }} a las {{ date('H:i:s') }}</p>
        <p>Sistema de Gestión Académica - SGA</p>
    </div>
</body>
</html>
