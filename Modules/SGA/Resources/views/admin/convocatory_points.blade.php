@extends('sga::layouts.master')

@section('content')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Mensajes de Sesión con SweetAlert -->
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Entendido'
            });
        });
    </script>
@endif

<!-- TAB PUNTOS DE CONVOCATORIAS -->
<div class="tab-pane fade show active" id="convocatory_points" role="tabpanel">
    <div class="row">
        <!-- Formulario Configurar Puntos -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configurar Puntos de Convocatoria</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cefa.sga.admin.convocatory_points.store') }}" id="formPuntosConvocatoria">
                        @csrf
                        
                        <!-- Selector de Convocatoria -->
                        <div class="mb-4">
                            <label for="convocatory_selected" class="form-label">Seleccionar Convocatoria</label>
                            <select class="form-select @error('convocatory_selected') is-invalid @enderror" 
                                    id="convocatory_selected" name="convocatory_selected" required>
                                <option value="">Seleccione una convocatoria...</option>
                                @if(isset($convocatorias) && count($convocatorias) > 0)
                                    @foreach($convocatorias as $conv)
                                        <option value="{{ $conv->id }}" {{ old('convocatory_selected') == $conv->id ? 'selected' : '' }}>
                                            {{ $conv->name }} - {{ $conv->quarter }}° Trimestre {{ $conv->year }} ({{ $conv->status }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('convocatory_selected')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Solo se muestran convocatorias de "Apoyo de Alimentación"</small>
                        </div>

                        <!-- Puntos de Vulnerabilidad y Condiciones Especiales -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">Vulnerabilidad y Condiciones Especiales</h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="victim_conflict_score" class="form-label">Víctima del Conflicto</label>
                                <input type="number" class="form-control @error('victim_conflict_score') is-invalid @enderror" 
                                       id="victim_conflict_score" name="victim_conflict_score" 
                                       min="0" max="100" value="{{ old('victim_conflict_score') }}">
                                @error('victim_conflict_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender_violence_victim_score" class="form-label">Víctima de Violencia de Género</label>
                                <input type="number" class="form-control @error('gender_violence_victim_score') is-invalid @enderror" 
                                       id="gender_violence_victim_score" name="gender_violence_victim_score" 
                                       min="0" max="100" value="{{ old('gender_violence_victim_score') }}">
                                @error('gender_violence_victim_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="disability_score" class="form-label">Discapacidad</label>
                                <input type="number" class="form-control @error('disability_score') is-invalid @enderror" 
                                       id="disability_score" name="disability_score" 
                                       min="0" max="100" value="{{ old('disability_score') }}">
                                @error('disability_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="head_of_household_score" class="form-label">Jefe de Hogar</label>
                                <input type="number" class="form-control @error('head_of_household_score') is-invalid @enderror" 
                                       id="head_of_household_score" name="head_of_household_score" 
                                       min="0" max="100" value="{{ old('head_of_household_score') }}">
                                @error('head_of_household_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pregnant_or_lactating_score" class="form-label">Embarazada o Lactante</label>
                                <input type="number" class="form-control @error('pregnant_or_lactating_score') is-invalid @enderror" 
                                       id="pregnant_or_lactating_score" name="pregnant_or_lactating_score" 
                                       min="0" max="100" value="{{ old('pregnant_or_lactating_score') }}">
                                @error('pregnant_or_lactating_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ethnic_group_affiliation_score" class="form-label">Pertenencia a Grupo Étnico</label>
                                <input type="number" class="form-control @error('ethnic_group_affiliation_score') is-invalid @enderror" 
                                       id="ethnic_group_affiliation_score" name="ethnic_group_affiliation_score" 
                                       min="0" max="100" value="{{ old('ethnic_group_affiliation_score') }}">
                                @error('ethnic_group_affiliation_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Puntos de Condiciones Socioeconómicas -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">Condiciones Socioeconómicas</h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="natural_displacement_score" class="form-label">Desplazamiento Natural</label>
                                <input type="number" class="form-control @error('natural_displacement_score') is-invalid @enderror" 
                                       id="natural_displacement_score" name="natural_displacement_score" 
                                       min="0" max="100" value="{{ old('natural_displacement_score') }}">
                                @error('natural_displacement_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sisben_group_a_score" class="form-label">Grupo A del SISBEN</label>
                                <input type="number" class="form-control @error('sisben_group_a_score') is-invalid @enderror" 
                                       id="sisben_group_a_score" name="sisben_group_a_score" 
                                       min="0" max="100" value="{{ old('sisben_group_a_score') }}">
                                @error('sisben_group_a_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sisben_group_b_score" class="form-label">Grupo B del SISBEN</label>
                                <input type="number" class="form-control @error('sisben_group_b_score') is-invalid @enderror" 
                                       id="sisben_group_b_score" name="sisben_group_b_score" 
                                       min="0" max="100" value="{{ old('sisben_group_b_score') }}">
                                @error('sisben_group_b_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="rural_apprentice_score" class="form-label">Aprendiz Rural</label>
                                <input type="number" class="form-control @error('rural_apprentice_score') is-invalid @enderror" 
                                       id="rural_apprentice_score" name="rural_apprentice_score" 
                                       min="0" max="100" value="{{ old('rural_apprentice_score') }}">
                                @error('rural_apprentice_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lives_in_rural_area_score" class="form-label">Vive en Zona Rural</label>
                                <input type="number" class="form-control @error('lives_in_rural_area_score') is-invalid @enderror" 
                                       id="lives_in_rural_area_score" name="lives_in_rural_area_score" 
                                       min="0" max="100" value="{{ old('lives_in_rural_area_score') }}">
                                @error('lives_in_rural_area_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Puntos de Participación y Representación -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">Participación y Representación</h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="institutional_representative_score" class="form-label">Representante Institucional</label>
                                <input type="number" class="form-control @error('institutional_representative_score') is-invalid @enderror" 
                                       id="institutional_representative_score" name="institutional_representative_score" 
                                       min="0" max="100" value="{{ old('institutional_representative_score') }}">
                                @error('institutional_representative_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="spokesperson_elected_score" class="form-label">Vocero Electo</label>
                                <input type="number" class="form-control @error('spokesperson_elected_score') is-invalid @enderror" 
                                       id="spokesperson_elected_score" name="spokesperson_elected_score" 
                                       min="0" max="100" value="{{ old('spokesperson_elected_score') }}">
                                @error('spokesperson_elected_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="research_participation_score" class="form-label">Participación en Investigación</label>
                                <input type="number" class="form-control @error('research_participation_score') is-invalid @enderror" 
                                       id="research_participation_score" name="research_participation_score" 
                                       min="0" max="100" value="{{ old('research_participation_score') }}">
                                @error('research_participation_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Puntos de Experiencia y Certificaciones -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">Experiencia y Certificaciones</h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="previous_boarding_quota_score" class="form-label">Cuota de Internado Anterior</label>
                                <input type="number" class="form-control @error('previous_boarding_quota_score') is-invalid @enderror" 
                                       id="previous_boarding_quota_score" name="previous_boarding_quota_score" 
                                       min="0" max="100" value="{{ old('previous_boarding_quota_score') }}">
                                @error('previous_boarding_quota_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="has_certification_score" class="form-label">Tiene Certificación</label>
                                <input type="number" class="form-control @error('has_certification_score') is-invalid @enderror" 
                                       id="has_certification_score" name="has_certification_score" 
                                       min="0" max="100" value="{{ old('has_certification_score') }}">
                                @error('has_certification_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="attached_sworn_statement_score" class="form-label">Declaración Jurada Adjunta</label>
                                <input type="number" class="form-control @error('attached_sworn_statement_score') is-invalid @enderror" 
                                       id="attached_sworn_statement_score" name="attached_sworn_statement_score" 
                                       min="0" max="100" value="{{ old('attached_sworn_statement_score') }}">
                                @error('attached_sworn_statement_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="knows_obligations_support_score" class="form-label">Conoce Obligaciones del Apoyo</label>
                                <input type="number" class="form-control @error('knows_obligations_support_score') is-invalid @enderror" 
                                       id="knows_obligations_support_score" name="knows_obligations_support_score" 
                                       min="0" max="100" value="{{ old('knows_obligations_support_score') }}">
                                @error('knows_obligations_support_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Puntos de Beneficios y Contratos -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">Beneficios y Contratos</h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="renta_joven_beneficiary_score" class="form-label">Beneficiario de Renta Joven</label>
                                <input type="number" class="form-control @error('renta_joven_beneficiary_score') is-invalid @enderror" 
                                       id="renta_joven_beneficiary_score" name="renta_joven_beneficiary_score" 
                                       min="0" max="100" value="{{ old('renta_joven_beneficiary_score') }}">
                                @error('renta_joven_beneficiary_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="has_apprenticeship_contract_score" class="form-label">Tiene Contrato de Aprendizaje</label>
                                <input type="number" class="form-control @error('has_apprenticeship_contract_score') is-invalid @enderror" 
                                       id="has_apprenticeship_contract_score" name="has_apprenticeship_contract_score" 
                                       min="0" max="100" value="{{ old('has_apprenticeship_contract_score') }}">
                                @error('has_apprenticeship_contract_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="has_income_contract_score" class="form-label">Tiene Contrato de Ingresos</label>
                                <input type="number" class="form-control @error('has_income_contract_score') is-invalid @enderror" 
                                       id="has_income_contract_score" name="has_income_contract_score" 
                                       min="0" max="100" value="{{ old('has_income_contract_score') }}">
                                @error('has_income_contract_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="has_sponsored_practice_score" class="form-label">Tiene Práctica Patrocinada</label>
                                <input type="number" class="form-control @error('has_sponsored_practice_score') is-invalid @enderror" 
                                       id="has_sponsored_practice_score" name="has_sponsored_practice_score" 
                                       min="0" max="100" value="{{ old('has_sponsored_practice_score') }}">
                                @error('has_sponsored_practice_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Puntos de Apoyos Recibidos -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2">Apoyos Recibidos</h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="received_fic_support_score" class="form-label">Recibió Apoyo FIC</label>
                                <input type="number" class="form-control @error('received_fic_support_score') is-invalid @enderror" 
                                       id="received_fic_support_score" name="received_fic_support_score" 
                                       min="0" max="100" value="{{ old('received_fic_support_score') }}">
                                @error('received_fic_support_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="received_regular_support_score" class="form-label">Recibió Apoyo Regular</label>
                                <input type="number" class="form-control @error('received_regular_support_score') is-invalid @enderror" 
                                       id="received_regular_support_score" name="received_regular_support_score" 
                                       min="0" max="100" value="{{ old('received_regular_support_score') }}">
                                @error('received_regular_support_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="receives_food_support_score" class="form-label">Recibe Apoyo Alimentario</label>
                                <input type="number" class="form-control @error('receives_food_support_score') is-invalid @enderror" 
                                       id="receives_food_support_score" name="receives_food_support_score" 
                                       min="0" max="100" value="{{ old('receives_food_support_score') }}">
                                @error('receives_food_support_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="receives_transport_support_score" class="form-label">Recibe Apoyo de Transporte</label>
                                <input type="number" class="form-control @error('receives_transport_support_score') is-invalid @enderror" 
                                       id="receives_transport_support_score" name="receives_transport_support_score" 
                                       min="0" max="100" value="{{ old('receives_transport_support_score') }}">
                                @error('receives_transport_support_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="receives_tech_support_score" class="form-label">Recibe Apoyo Tecnológico</label>
                                <input type="number" class="form-control @error('receives_tech_support_score') is-invalid @enderror" 
                                       id="receives_tech_support_score" name="receives_tech_support_score" 
                                       min="0" max="100" value="{{ old('receives_tech_support_score') }}">
                                @error('receives_tech_support_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success" id="btnGuardarPuntos">
                                <i class="fas fa-save me-2"></i>Guardar Puntos
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="confirmarLimpiarFormulario()">
                                <i class="fas fa-eraser me-2"></i>Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de Copiar Puntos -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-copy me-2"></i>Copiar Puntos de Convocatoria Anterior</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cefa.sga.admin.convocatory_points.copy') }}" id="formCopiarPuntos">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="convocatory_destino" class="form-label">Convocatoria Destino</label>
                            <select class="form-select" id="convocatory_destino" name="convocatory_selected" required>
                                <option value="">Seleccione convocatoria destino...</option>
                                @if(isset($convocatorias) && count($convocatorias) > 0)
                                    @foreach($convocatorias as $conv)
                                        <option value="{{ $conv->id }}">
                                            {{ $conv->name }} - {{ $conv->quarter }}° Trimestre {{ $conv->year }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="form-text text-muted">Convocatoria que recibirá los puntos</small>
                        </div>

                        <div class="mb-3">
                            <label for="convocatory_origen" class="form-label">Convocatoria Origen</label>
                            <select class="form-select" id="convocatory_origen" name="convocatory_source" required>
                                <option value="">Seleccione convocatoria origen...</option>
                                @if(isset($convocatoriasConPuntos) && count($convocatoriasConPuntos) > 0)
                                    @foreach($convocatoriasConPuntos as $conv)
                                        <option value="{{ $conv->convocatory_selected }}">
                                            {{ $conv->name }} - {{ $conv->quarter }}° Trimestre {{ $conv->year }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No hay convocatorias con puntos configurados</option>
                                @endif
                            </select>
                            <small class="form-text text-muted">Convocatoria de donde se copiarán los puntos</small>
                        </div>

                        <button type="submit" class="btn btn-info w-100" onclick="return confirmarCopiarPuntos()">
                            <i class="fas fa-copy me-2"></i>Copiar Puntos
                        </button>
                    </form>
                </div>
            </div>

            <!-- Información de Puntos Actuales -->
            @if(isset($puntajesActuales))
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Puntos Configurados</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Convocatoria:</strong> {{ $puntajesActuales->convocatoria_name }}</p>
                    <p class="mb-2"><strong>Configurado:</strong> {{ \Carbon\Carbon::parse($puntajesActuales->created_at)->format('d/m/Y H:i') }}</p>
                    <p class="mb-0 text-success">
                        <i class="fas fa-check-circle me-1"></i>Esta convocatoria ya tiene puntos configurados
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Limpiar formulario si hay mensaje de éxito
    @if(session('success'))
        limpiarFormulario();
    @endif
});

/**
 * Confirmar copia de puntos con SweetAlert
 */
function confirmarCopiarPuntos() {
    const destino = document.getElementById('convocatory_destino').value;
    const origen = document.getElementById('convocatory_origen').value;
    
    if (!destino || !origen) {
        Swal.fire({
            icon: 'warning',
            title: '¡Atención!',
            text: 'Debe seleccionar tanto la convocatoria destino como la origen.',
            confirmButtonText: 'Entendido'
        });
        return false;
    }
    
    return Swal.fire({
        title: '¿Copiar puntos?',
        text: '¿Está seguro de que desea copiar los puntos de la convocatoria origen a la destino?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, copiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        return result.isConfirmed;
    });
}

/**
 * Confirmar limpieza del formulario con SweetAlert
 */
function confirmarLimpiarFormulario() {
    Swal.fire({
        title: '¿Limpiar formulario?',
        text: '¿Está seguro de que desea limpiar todos los campos del formulario?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            limpiarFormulario();
            Swal.fire({
                icon: 'success',
                title: '¡Formulario limpiado!',
                text: 'Todos los campos han sido limpiados',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

/**
 * Limpiar formulario
 */
function limpiarFormulario() {
    document.getElementById('formPuntosConvocatoria').reset();
    
    // Limpiar todos los campos de puntaje
    const scoreFields = document.querySelectorAll('input[name$="_score"]');
    scoreFields.forEach(field => {
        field.value = '';
    });
}

/**
 * Mostrar notificación de éxito con SweetAlert
 */
function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: mensaje,
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

/**
 * Mostrar notificación de error con SweetAlert
 */
function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: mensaje,
        confirmButtonText: 'Entendido'
    });
}
</script>
@endpush
