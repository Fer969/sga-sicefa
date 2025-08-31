<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formato Socioeconómico - {{ $apprentice->person->first_name }} {{ $apprentice->person->first_last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .logo {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
            text-align: center;
        }
        .section {
            margin: 15px 0;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .section-title {
            font-weight: bold;
            font-size: 13px;
            background-color: #f5f5f5;
            padding: 5px;
            margin: -10px -10px 10px -10px;
            border-bottom: 1px solid #ccc;
        }
        .row {
            display: table;
            width: 100%;
            margin: 5px 0;
        }
        .col {
            display: table-cell;
            padding: 5px;
            vertical-align: top;
        }
        .col-2 { width: 50%; }
        .col-3 { width: 33.33%; }
        .col-4 { width: 25%; }
        .label {
            font-weight: bold;
            margin-right: 5px;
        }
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            text-align: center;
            line-height: 12px;
        }
        .checkbox.checked {
            background-color: #000;
            color: white;
        }
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 150px;
            padding: 2px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            background-color: #007bff;
            color: white;
            border-radius: 3px;
            font-size: 10px;
            margin: 2px;
        }
        .badge.success { background-color: #28a745; }
        .badge.warning { background-color: #ffc107; color: #000; }
        .badge.danger { background-color: #dc3545; }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    @php
        $person = $apprentice->person;
        $course = $apprentice->course;
        $program = optional($course)->program;
        $conditions = optional($person->conditions);
        $socioeconomic = optional($person->socioeconomic);
        $representative = optional($person->representativeLegal);
    @endphp

    <div class="header">
        <div class="logo">SERVICIO NACIONAL DE APRENDIZAJE - SENA</div>
        <div>CENTRO DE FORMACIÓN AGROINDUSTRIAL</div>
        <div>REGIONAL HUILA</div>
        <div class="title">FORMATO SOCIOECONÓMICO DE CARACTERIZACIÓN</div>
        <div><strong>Fecha:</strong> {{ date('d/m/Y') }}</div>
    </div>

    <!-- Datos Personales -->
    <div class="section">
        <div class="section-title">1. DATOS PERSONALES DEL APRENDIZ</div>
        
        <div class="row">
            <div class="col col-2">
                <span class="label">Nombres y Apellidos:</span>
                <span class="underline">{{ $person->first_name }} {{ $person->first_last_name }} {{ $person->second_last_name }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Tipo de Documento:</span>
                <span class="underline">{{ $person->document_type }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Número de Documento:</span>
                <span class="underline">{{ $person->document_number }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Lugar de Expedición:</span>
                <span class="underline">{{ $person->expedition_place ?? '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col col-3">
                <span class="label">Género:</span>
                <span class="checkbox {{ $person->gender === 'masculino' ? 'checked' : '' }}">{{ $person->gender === 'masculino' ? 'X' : '' }}</span> M
                <span class="checkbox {{ $person->gender === 'femenino' ? 'checked' : '' }}">{{ $person->gender === 'femenino' ? 'X' : '' }}</span> F
            </div>
            <div class="col col-3">
                <span class="label">Edad:</span>
                <span class="underline">{{ $person->birth_date ? \Carbon\Carbon::parse($person->birth_date)->age : '' }} años</span>
            </div>
            <div class="col col-3">
                <span class="label">Fecha de Nacimiento:</span>
                <span class="underline">{{ $person->birth_date ? \Carbon\Carbon::parse($person->birth_date)->format('d/m/Y') : '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Departamento:</span>
                <span class="underline">{{ $person->department ?? '' }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Municipio:</span>
                <span class="underline">{{ $person->city ?? '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Dirección:</span>
                <span class="underline">{{ $person->address ?? '' }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Teléfono:</span>
                <span class="underline">{{ $person->phone ?? '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <span class="label">Correo Electrónico:</span>
                <span class="underline">{{ $person->personal_email ?? $person->misena_email ?? $person->sena_email ?? '' }}</span>
            </div>
        </div>
    </div>

    <!-- Datos de Formación -->
    <div class="section">
        <div class="section-title">2. DATOS DE FORMACIÓN</div>
        
        <div class="row">
            <div class="col">
                <span class="label">Programa de Formación:</span>
                <span class="underline">{{ $program->name ?? '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Número de Ficha:</span>
                <span class="underline">{{ $course->code ?? '' }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Modalidad:</span>
                <span class="checkbox {{ $course && $course->modality === 'presencial' ? 'checked' : '' }}">{{ $course && $course->modality === 'presencial' ? 'X' : '' }}</span> Presencial
                <span class="checkbox {{ $course && $course->modality === 'virtual' ? 'checked' : '' }}">{{ $course && $course->modality === 'virtual' ? 'X' : '' }}</span> Virtual
                <span class="checkbox {{ $course && $course->modality === 'a_distancia' ? 'checked' : '' }}">{{ $course && $course->modality === 'a_distancia' ? 'X' : '' }}</span> A Distancia
            </div>
        </div>
    </div>

    <!-- Condiciones Especiales -->
    <div class="section">
        <div class="section-title">3. CONDICIONES ESPECIALES</div>
        
        @if($conditions)
        <div class="row">
            <div class="col">
                @if($conditions->victim_conflict === 'SI')
                    <span class="badge danger">Víctima de Conflicto</span>
                @endif
                @if($conditions->head_of_household === 'SI')
                    <span class="badge success">Cabeza de Hogar</span>
                @endif
                @if($conditions->pregnant_or_lactating === 'SI')
                    <span class="badge warning">Gestante/Lactante</span>
                @endif
                @if($conditions->rural_apprentice === 'SI')
                    <span class="badge">Aprendiz Rural</span>
                @endif
                @if($conditions->sisben_group_a === 'SI')
                    <span class="badge">SISBEN Grupo A</span>
                @endif
                @if($conditions->sisben_group_b === 'SI')
                    <span class="badge">SISBEN Grupo B</span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Víctima de Conflicto:</span>
                <span class="checkbox {{ $conditions->victim_conflict === 'SI' ? 'checked' : '' }}">{{ $conditions->victim_conflict === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $conditions->victim_conflict !== 'SI' ? 'checked' : '' }}">{{ $conditions->victim_conflict !== 'SI' ? 'X' : '' }}</span> No
            </div>
            <div class="col col-2">
                <span class="label">Cabeza de Hogar:</span>
                <span class="checkbox {{ $conditions->head_of_household === 'SI' ? 'checked' : '' }}">{{ $conditions->head_of_household === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $conditions->head_of_household !== 'SI' ? 'checked' : '' }}">{{ $conditions->head_of_household !== 'SI' ? 'X' : '' }}</span> No
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Gestante/Lactante:</span>
                <span class="checkbox {{ $conditions->pregnant_or_lactating === 'SI' ? 'checked' : '' }}">{{ $conditions->pregnant_or_lactating === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $conditions->pregnant_or_lactating !== 'SI' ? 'checked' : '' }}">{{ $conditions->pregnant_or_lactating !== 'SI' ? 'X' : '' }}</span> No
            </div>
            <div class="col col-2">
                <span class="label">Aprendiz Rural:</span>
                <span class="checkbox {{ $conditions->rural_apprentice === 'SI' ? 'checked' : '' }}">{{ $conditions->rural_apprentice === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $conditions->rural_apprentice !== 'SI' ? 'checked' : '' }}">{{ $conditions->rural_apprentice !== 'SI' ? 'X' : '' }}</span> No
            </div>
        </div>
        @else
        <div class="row">
            <div class="col">
                <em>No se han registrado condiciones especiales</em>
            </div>
        </div>
        @endif
    </div>

    <!-- Información Socioeconómica -->
    <div class="section">
        <div class="section-title">4. INFORMACIÓN SOCIOECONÓMICA</div>
        
        @if($socioeconomic)
        <div class="row">
            <div class="col col-2">
                <span class="label">Beneficiario Renta Joven:</span>
                <span class="checkbox {{ $socioeconomic->renta_joven_beneficiary === 'SI' ? 'checked' : '' }}">{{ $socioeconomic->renta_joven_beneficiary === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $socioeconomic->renta_joven_beneficiary !== 'SI' ? 'checked' : '' }}">{{ $socioeconomic->renta_joven_beneficiary !== 'SI' ? 'X' : '' }}</span> No
            </div>
            <div class="col col-2">
                <span class="label">Contrato de Aprendizaje:</span>
                <span class="checkbox {{ $socioeconomic->has_apprenticeship_contract === 'SI' ? 'checked' : '' }}">{{ $socioeconomic->has_apprenticeship_contract === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $socioeconomic->has_apprenticeship_contract !== 'SI' ? 'checked' : '' }}">{{ $socioeconomic->has_apprenticeship_contract !== 'SI' ? 'X' : '' }}</span> No
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Apoyo Alimentación:</span>
                <span class="checkbox {{ $socioeconomic->receives_food_support === 'SI' ? 'checked' : '' }}">{{ $socioeconomic->receives_food_support === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $socioeconomic->receives_food_support !== 'SI' ? 'checked' : '' }}">{{ $socioeconomic->receives_food_support !== 'SI' ? 'X' : '' }}</span> No
            </div>
            <div class="col col-2">
                <span class="label">Apoyo Transporte:</span>
                <span class="checkbox {{ $socioeconomic->receives_transport_support === 'SI' ? 'checked' : '' }}">{{ $socioeconomic->receives_transport_support === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $socioeconomic->receives_transport_support !== 'SI' ? 'checked' : '' }}">{{ $socioeconomic->receives_transport_support !== 'SI' ? 'X' : '' }}</span> No
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Apoyo Tecnológico:</span>
                <span class="checkbox {{ $socioeconomic->receives_tech_support === 'SI' ? 'checked' : '' }}">{{ $socioeconomic->receives_tech_support === 'SI' ? 'X' : '' }}</span> Sí
                <span class="checkbox {{ $socioeconomic->receives_tech_support !== 'SI' ? 'checked' : '' }}">{{ $socioeconomic->receives_tech_support !== 'SI' ? 'X' : '' }}</span> No
            </div>
            <div class="col col-2">
                <span class="label">Estrato:</span>
                <span class="underline">{{ $person->socioeconomic_stratum ?? '' }}</span>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col">
                <em>No se ha registrado información socioeconómica</em>
            </div>
        </div>
        @endif
    </div>

    <!-- Información de Salud -->
    <div class="section">
        <div class="section-title">5. INFORMACIÓN DE SALUD</div>
        
        <div class="row">
            <div class="col col-2">
                <span class="label">Régimen de Salud:</span>
                <span class="checkbox {{ $person->health_regime === 'contributivo' ? 'checked' : '' }}">{{ $person->health_regime === 'contributivo' ? 'X' : '' }}</span> Contributivo
                <span class="checkbox {{ $person->health_regime === 'subsidiado' ? 'checked' : '' }}">{{ $person->health_regime === 'subsidiado' ? 'X' : '' }}</span> Subsidiado
            </div>
            <div class="col col-2">
                <span class="label">EPS:</span>
                <span class="underline">{{ $person->eps_name ?? '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <span class="label">Vinculación:</span>
                <span class="checkbox {{ $person->eps_relationship === 'cotizante' ? 'checked' : '' }}">{{ $person->eps_relationship === 'cotizante' ? 'X' : '' }}</span> Cotizante
                <span class="checkbox {{ $person->eps_relationship === 'beneficiario' ? 'checked' : '' }}">{{ $person->eps_relationship === 'beneficiario' ? 'X' : '' }}</span> Beneficiario
            </div>
        </div>
    </div>

    <!-- Representante Legal -->
    @if($representative)
    <div class="section">
        <div class="section-title">6. REPRESENTANTE LEGAL (Para menores de edad)</div>
        
        <div class="row">
            <div class="col col-2">
                <span class="label">Nombre:</span>
                <span class="underline">{{ $representative->name }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Documento:</span>
                <span class="underline">{{ $representative->document_type ?? '' }} {{ $representative->document_number ?? '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Parentesco:</span>
                <span class="underline">{{ $representative->relationship ?? '' }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Teléfono:</span>
                <span class="underline">{{ $representative->telephone1 ?? '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col col-2">
                <span class="label">Departamento:</span>
                <span class="underline">{{ $representative->department ?? '' }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Municipio:</span>
                <span class="underline">{{ $representative->city ?? '' }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <span class="label">Dirección:</span>
                <span class="underline">{{ $representative->address ?? '' }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Declaraciones Juramentadas -->
    @if($person->swornStatements->count())
    <div class="section">
        <div class="section-title">7. DECLARACIONES JURAMENTADAS</div>
        
        @foreach($person->swornStatements as $statement)
        <div class="row">
            <div class="col col-2">
                <span class="label">Responsable:</span>
                <span class="underline">{{ $statement->responsible_name }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Documento:</span>
                <span class="underline">{{ $statement->responsible_document }}</span>
            </div>
        </div>
        @if($statement->live_with)
        <div class="row">
            <div class="col">
                <span class="label">Convive con:</span>
                <span class="underline">{{ $statement->live_with }}</span>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    <!-- Contacto de Emergencia -->
    @if($person->emergency_contact_name)
    <div class="section">
        <div class="section-title">8. CONTACTO DE EMERGENCIA</div>
        
        <div class="row">
            <div class="col col-2">
                <span class="label">Nombre:</span>
                <span class="underline">{{ $person->emergency_contact_name }}</span>
            </div>
            <div class="col col-2">
                <span class="label">Teléfono:</span>
                <span class="underline">{{ $person->emergency_contact_phone ?? '' }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>Documento generado el:</strong> {{ date('d/m/Y H:i:s') }}</p>
        <p>Sistema de Gestión de Aprendices - SENA Centro de Formación Agroindustrial</p>
    </div>
</body>
</html>