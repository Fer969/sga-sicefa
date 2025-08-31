@extends('sga::layouts.master')

@section('title', 'Usuarios Staff')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 text-dark-green">
                        <i class="fas fa-users text-olive-green me-2"></i>
                        {{ trans('sga::contents.AdmStaTitle') }}
                    </h1>
                    <p class="text-muted">{{ trans('sga::contents.AdmStaDescription-1') }}</p>
                </div>
                <div class="badge bg-olive-green fs-6">
                    Total: {{ $staffUsers->total() }} usuarios
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark-green text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Lista de Usuarios Staff
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light-green">
                                <tr>
                                    <th class="px-3 py-3">
                                        <i class="fas fa-id-card me-1 text-dark-green"></i>
                                        <span class="text-dark-green">Documento</span>
                                    </th>
                                    <th class="px-3 py-3">
                                        <i class="fas fa-user me-1 text-dark-green"></i>
                                        <span class="text-dark-green">Nombre Completo</span>
                                    </th>
                                    <th class="px-3 py-3">
                                        <i class="fas fa-envelope me-1 text-dark-green"></i>
                                        <span class="text-dark-green">Email</span>
                                    </th>
                                    <th class="px-3 py-3">
                                        <i class="fas fa-check-circle me-1 text-dark-green"></i>
                                        <span class="text-dark-green">Estado</span>
                                    </th>
                                    <th class="px-3 py-3 text-center">
                                        <i class="fas fa-cogs me-1 text-dark-green"></i>
                                        <span class="text-dark-green">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffUsers as $user)
                                    <tr class="staff-row">
                                        <td class="px-3 py-3">
                                            <div class="fw-bold text-dark-green">{{ $user->document_type }}</div>
                                            <small class="text-muted">{{ $user->document_number }}</small>
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="fw-bold text-dark">
                                                {{ $user->first_name }} {{ $user->first_last_name }}
                                            </div>
                                            <small class="text-muted">{{ $user->second_last_name }}</small>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-dark">{{ $user->email }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Verificado
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>
                                                    No verificado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-outline-olive-green btn-sm change-password-btn" 
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->first_name }} {{ $user->first_last_name }}"
                                                        title="Cambiar contraseña">
                                                    <i class="fas fa-key"></i>
                                                    <span class="d-none d-md-inline ms-1">Contraseña</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3 text-olive-green"></i>
                                                <p class="fs-5">No hay usuarios staff registrados</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            @if($staffUsers->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $staffUsers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para cambiar contraseña -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-olive-green text-white">
                <h5 class="modal-title" id="passwordModalLabel">
                    <i class="fas fa-key me-2"></i>
                    Cambiar Contraseña
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="passwordForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="alert alert-info-green">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Usuario:</strong> <span id="userName"></span><br>
                        <strong>Nota:</strong> La contraseña debe tener al menos 8 caracteres.
                    </div>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label text-dark-green">
                            <i class="fas fa-lock me-1"></i>
                            Contraseña Actual
                        </label>
                        <input type="password" 
                               class="form-control border-olive-green" 
                               id="current_password" 
                               name="current_password" 
                               required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label text-dark-green">
                            <i class="fas fa-lock me-1"></i>
                            Nueva Contraseña
                        </label>
                        <input type="password" 
                               class="form-control border-olive-green" 
                               id="new_password" 
                               name="new_password" 
                               required
                               minlength="8">
                        <div class="form-text">La contraseña debe tener al menos 8 caracteres.</div>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label text-dark-green">
                            <i class="fas fa-lock me-1"></i>
                            Confirmar Nueva Contraseña
                        </label>
                        <input type="password" 
                               class="form-control border-olive-green" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               required
                               minlength="8">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-dark-green" id="submitPasswordBtn">
                        <i class="fas fa-save me-1"></i>
                        <span class="btn-text">Cambiar Contraseña</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordModal = document.getElementById('passwordModal');
    const passwordForm = document.getElementById('passwordForm');
    const submitBtn = document.getElementById('submitPasswordBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');

    // Abrir modal cuando se hace clic en el botón de contraseña
    document.querySelectorAll('.change-password-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            // Actualizar información del modal
            document.getElementById('userName').textContent = userName;
            passwordForm.action = `/admin/staff/${userId}/password`;
            
            // Limpiar formulario
            passwordForm.reset();
            clearValidationErrors();
            
            // Mostrar modal
            const modal = new bootstrap.Modal(passwordModal);
            modal.show();
        });
    });

    // Validación en tiempo real
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');

    confirmPassword.addEventListener('input', function() {
        if (newPassword.value !== confirmPassword.value && confirmPassword.value !== '') {
            confirmPassword.classList.add('is-invalid');
            confirmPassword.nextElementSibling.textContent = 'Las contraseñas no coinciden';
        } else {
            confirmPassword.classList.remove('is-invalid');
        }
    });

    // Manejar envío del formulario
    passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar que las contraseñas coincidan
        if (newPassword.value !== confirmPassword.value) {
            showFieldError(confirmPassword, 'Las contraseñas no coinciden');
            return;
        }

        // Mostrar loading
        setLoadingState(true);
        clearValidationErrors();

        const formData = new FormData(passwordForm);
        const userId = passwordForm.action.split('/').slice(-2, -1)[0];

        fetch(`/admin/staff/${userId}/password`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(response => response.json())
        .then(data => {
            setLoadingState(false);
            
            if (data.success) {
                // Cerrar modal
                bootstrap.Modal.getInstance(passwordModal).hide();
                
                // Mostrar mensaje de éxito
                showAlert('success', data.message);
                
                // Limpiar formulario
                passwordForm.reset();
            } else {
                // Mostrar errores
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = document.getElementById(field);
                        if (input) {
                            showFieldError(input, data.errors[field][0]);
                        }
                    });
                } else {
                    showAlert('danger', data.message);
                }
            }
        })
        .catch(error => {
            setLoadingState(false);
            console.error('Error:', error);
            showAlert('danger', 'Error al actualizar la contraseña. Por favor, intente de nuevo.');
        });
    });

    // Funciones auxiliares
    function setLoadingState(loading) {
        if (loading) {
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');
        } else {
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    }

    function showFieldError(input, message) {
        input.classList.add('is-invalid');
        const feedback = input.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.textContent = message;
        }
    }

    function clearValidationErrors() {
        passwordForm.querySelectorAll('.is-invalid').forEach(input => {
            input.classList.remove('is-invalid');
        });
    }

    function showAlert(type, message) {
        const alertContainer = document.querySelector('.container .row .col-12');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        alertContainer.insertBefore(alert, alertContainer.children[1]);
        
        // Auto-dismiss después de 5 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
});
</script>
@endpush

@push('styles')
<style>
/* Paleta de colores verdes */
:root {
    --dark-green: #2d5016;
    --olive-green: #6b8e23;
    --light-green: #f0f8e8;
    --medium-green: #8fbc8f;
}

.text-dark-green {
    color: var(--dark-green) !important;
}

.text-olive-green {
    color: var(--olive-green) !important;
}

.bg-dark-green {
    background-color: var(--dark-green) !important;
}

.bg-olive-green {
    background-color: var(--olive-green) !important;
}

.bg-light-green {
    background-color: var(--light-green) !important;
}

.table-light-green {
    background-color: var(--light-green);
}

.border-olive-green {
    border-color: var(--olive-green) !important;
}

.btn-dark-green {
    background-color: var(--dark-green);
    border-color: var(--dark-green);
    color: white;
}

.btn-dark-green:hover {
    background-color: #1a3009;
    border-color: #1a3009;
    color: white;
}

.btn-outline-olive-green {
    color: var(--olive-green);
    border-color: var(--olive-green);
    background-color: transparent;
}

.btn-outline-olive-green:hover {
    background-color: var(--olive-green);
    border-color: var(--olive-green);
    color: white;
}

.alert-info-green {
    background-color: var(--light-green);
    border-color: var(--olive-green);
    color: var(--dark-green);
}

/* Efectos hover mejorados */
.staff-row:hover {
    background-color: var(--light-green) !important;
    transition: background-color 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: var(--light-green) !important;
}

.btn-group .btn {
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.btn-group .btn:not(:last-child) {
    margin-right: 0.25rem;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(45, 80, 22, 0.1);
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 0.25rem 0.75rem rgba(45, 80, 22, 0.15);
}

.badge {
    font-size: 0.75em;
}

.modal-content {
    border-radius: 0.75rem;
}

.form-control:focus {
    border-color: var(--olive-green);
    box-shadow: 0 0 0 0.2rem rgba(107, 142, 35, 0.25);
}

/* Animaciones */
@keyframes slideInDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.alert {
    animation: slideInDown 0.3s ease-out;
}

/* Responsive mejoras */
@media (max-width: 768px) {
    .btn-group .btn span {
        display: none !important;
    }
    
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
    }
}
</style>
@endpush
@endsection