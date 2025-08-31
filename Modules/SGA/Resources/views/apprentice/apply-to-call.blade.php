@extends('sga::layouts.master')

@section('content')
<style>
    .apply-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .apply-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }

    .apply-header {
        background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
        color: white;
        border-radius: 0;
        padding: 30px;
    }

    .section-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #dee2e6;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .info-item {
        margin-bottom: 10px;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
    }

    .info-value {
        color: #6c757d;
    }

    .feature-highlight {
        padding: 15px;
        border-radius: 12px;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffeaa7;
        transition: all 0.3s ease;
    }

    .feature-highlight:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
    }

    .btn-primary {
        background: #495057;
        border: 1px solid #495057;
        border-radius: 6px;
        padding: 12px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #343a40;
        border-color: #343a40;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(73, 80, 87, 0.2);
    }

    .btn-success {
        background: #28a745;
        border: 1px solid #28a745;
        border-radius: 6px;
        padding: 12px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background: #218838;
        border-color: #1e7e34;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(40, 167, 69, 0.2);
    }

    .btn-secondary {
        background: #6c757d;
        border: 1px solid #6c757d;
        border-radius: 6px;
        padding: 12px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #5a6268;
        border-color: #545b62;
        transform: translateY(-1px);
    }

    .status-badge {
        font-size: 1rem;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
    }

    .feature-item {
        text-align: center;
        padding: 20px;
        border-radius: 12px;
        background: white;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
    }

    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        transition: all 0.3s ease;
        color: #495057;
    }

    .feature-item:hover .feature-icon {
        background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
        color: white;
    }

    .rounded-4 {
        border-radius: 1rem !important;
    }

    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important;
    }
</style>

<div class="apply-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="apply-card">
                    <!-- Header de la Card -->
                    <div class="apply-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-0">
                                    <i class="fas fa-bullhorn me-2"></i>
                                    Solicitar Convocatoria
                                </h2>
                                <p class="mb-0 mt-2 opacity-75">Sistema de Gestión de Aprendices - SGA</p>
                            </div>
                            <div class="text-end">
                                <div class="feature-highlight">
                                    <i class="fas fa-star text-warning fa-2x mb-2"></i>
                                    <p class="mb-0 text-muted small">Oportunidad única</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido Principal -->
                    <div class="p-4">
                        <!-- Información de la Convocatoria -->
                        <h4 class="section-title">
                            <i class="fas fa-info-circle me-2"></i>Información de la Convocatoria
                        </h4>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span class="info-label">Período:</span>
                                    </div>
                                    <div class="info-value">{{ $convocatory->quarter ?? 'N/A' }} - {{ $convocatory->year ?? 'N/A' }}</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-users text-success me-2"></i>
                                        <span class="info-label">Cupos:</span>
                                    </div>
                                    <div class="info-value">{{ $convocatory->coups ?? 'N/A' }} disponibles</div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-plus text-info me-2"></i>
                                        <span class="info-label">Fecha de Inicio:</span>
                                    </div>
                                    <div class="info-value">
                                        @if($convocatory && $convocatory->registration_start_date)
                                            {{ \Carbon\Carbon::parse($convocatory->registration_start_date)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="info-card">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-times text-warning me-2"></i>
                                        <span class="info-label">Fecha de Cierre:</span>
                                    </div>
                                    <div class="info-value">
                                        @if($convocatory && $convocatory->registration_deadline)
                                            {{ \Carbon\Carbon::parse($convocatory->registration_deadline)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estado de la Convocatoria -->
                        <div class="text-center mb-4">
                            @if($convocatory && $convocatory->status === 'Active')
                                <span class="status-badge bg-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Convocatoria Abierta
                                </span>
                            @else
                                <span class="status-badge bg-danger">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Convocatoria Cerrada
                                </span>
                            @endif
                        </div>

                        <!-- Botón de Aplicación -->
                        <div class="text-center mb-4">
                            @if($convocatory && $convocatory->status === 'Active')
                                <button id="applyButton" class="btn btn-success btn-lg px-5 py-3">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Aplicar Ahora
                                </button>
                            @else
                                <button class="btn btn-secondary btn-lg px-5 py-3" disabled>
                                    <i class="fas fa-clock me-2"></i>
                                    Convocatoria Cerrada
                                </button>
                            @endif
                        </div>

                        <hr class="my-4">

                        <!-- Información Adicional -->
                        <h4 class="section-title">
                            <i class="fas fa-tools me-2"></i>Recursos y Herramientas
                        </h4>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="feature-item" id="profileButton">
                                    <div class="feature-icon">
                                        <i class="fas fa-clipboard-check fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">Verificación de Perfil</h5>
                                    <p class="text-muted small mb-3">Asegúrate de tener toda tu información actualizada</p>
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-arrow-right me-2"></i>Ir al Perfil
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="feature-item" id="scoringButton">
                                    <div class="feature-icon">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">Sistema de Puntaje</h5>
                                    <p class="text-muted small mb-3">Tu puntaje se calcula automáticamente</p>
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-info-circle me-2"></i>Ver Detalles
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Botón de Volver -->
                        <div class="text-center">
                            <a href="{{ route('cefa.sga.apprentice.index') }}" class="btn btn-secondary btn-lg px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyButton = document.getElementById('applyButton');
    if (applyButton) {
        applyButton.addEventListener('click', showApplyConfirmation);
    }

    const profileButton = document.getElementById('profileButton');
    if (profileButton) {
        profileButton.addEventListener('click', goToProfile);
    }

    const scoringButton = document.getElementById('scoringButton');
    if (scoringButton) {
        scoringButton.addEventListener('click', showScoringSystem);
    }
});

function showApplyConfirmation() {
    Swal.fire({
        html: `
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x"></i>
                </div>
                <h3 class="fw-bold mb-3">Confirmar Aplicación</h3>
                <p class="mb-4">Confirma que tu información está completa antes de aplicar.</p>
                <div class="alert alert-info text-start">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Importante:</strong> Una vez enviada la aplicación, no podrás modificarla.
                </div>
            </div>
        `,
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: '<i class="fas fa-paper-plane me-2"></i>Estoy seguro, aplicar',
        cancelButtonText: '<i class="fas fa-edit me-2"></i>Editar información',
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            proceedWithApplication();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            window.location.href = '{{ route("cefa.sga.apprentice.profile") }}';
        }
    });
}

function proceedWithApplication() {
    Swal.fire({
        title: 'Procesando aplicación...',
        text: 'Por favor espera mientras procesamos tu solicitud.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route("cefa.sga.apprentice.apply-to-call.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                html: `
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success fa-3x"></i>
                        </div>
                        <h3 class="fw-bold mb-3 text-success">¡Aplicación Enviada!</h3>
                        <p class="mb-3">${data.message}</p>
                        <div class="alert alert-success">
                            <h4 class="mb-2">Puntaje Total</h4>
                            <span class="h2 fw-bold">${data.total_points}</span>
                        </div>
                        <p class="text-muted">Tu solicitud ha sido registrada exitosamente.</p>
                    </div>
                `,
                confirmButtonText: '<i class="fas fa-check me-2"></i>Entendido',
                confirmButtonColor: '#28a745'
            });
        } else {
            Swal.fire({
                html: `
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-times-circle text-danger fa-3x"></i>
                        </div>
                        <h3 class="fw-bold mb-3 text-danger">Error en la Aplicación</h3>
                        <p class="mb-4">${data.message}</p>
                    </div>
                `,
                confirmButtonText: '<i class="fas fa-times me-2"></i>Entendido',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            html: `
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x"></i>
                    </div>
                    <h3 class="fw-bold mb-3 text-warning">Error del Sistema</h3>
                    <p class="mb-4">Ocurrió un error al procesar tu aplicación. Por favor, intenta nuevamente.</p>
                </div>
            `,
            confirmButtonText: '<i class="fas fa-redo me-2"></i>Reintentar',
            confirmButtonColor: '#ffc107'
        });
    });
}

function goToProfile() {
    Swal.fire({
        title: 'Redirigiendo al Perfil',
        text: 'Serás redirigido a tu perfil para verificar la información.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#495057',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route("cefa.sga.apprentice.profile") }}';
        }
    });
}

function showScoringSystem() {
    Swal.fire({
        html: `
            <div class="text-center mb-4">
                <i class="fas fa-chart-line text-primary fa-3x mb-3"></i>
                <h3 class="fw-bold mb-3 text-primary">Sistema de Puntaje</h3>
                <p class="text-muted mb-4">Tu puntaje se calcula automáticamente basado en la siguiente información:</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Criterio</th>
                            <th class="text-center">Puntos</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Beneficiario Renta Joven</strong></td>
                            <td class="text-center"><span class="badge bg-success">+10</span></td>
                            <td>Si eres beneficiario del programa Renta Joven</td>
                        </tr>
                        <tr>
                            <td><strong>Víctima del Conflicto</strong></td>
                            <td class="text-center"><span class="badge bg-success">+15</span></td>
                            <td>Si eres víctima del conflicto armado</td>
                        </tr>
                        <tr>
                            <td><strong>Persona Desplazada</strong></td>
                            <td class="text-center"><span class="badge bg-success">+12</span></td>
                            <td>Si eres persona en situación de desplazamiento</td>
                        </tr>
                        <tr>
                            <td><strong>Discapacidad</strong></td>
                            <td class="text-center"><span class="badge bg-success">+8</span></td>
                            <td>Si tienes alguna discapacidad</td>
                        </tr>
                        <tr>
                            <td><strong>Participación en Investigación</strong></td>
                            <td class="text-center"><span class="badge bg-success">+5</span></td>
                            <td>Si participas en proyectos de investigación</td>
                        </tr>
                        <tr>
                            <td><strong>Declaración Jurada</strong></td>
                            <td class="text-center"><span class="badge bg-success">+3</span></td>
                            <td>Si presentas declaración jurada</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-info text-start mt-3">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>Consejo:</strong> Completa todas las secciones de tu perfil para maximizar tu puntaje y aumentar las posibilidades de ser beneficiado.
            </div>
        `,
        width: '800px',
        confirmButtonText: '<i class="fas fa-check me-2"></i>Entendido',
        confirmButtonColor: '#28a745'
    });
}
</script>

@endsection