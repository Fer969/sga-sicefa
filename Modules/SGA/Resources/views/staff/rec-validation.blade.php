@php
// $role_name = getRoleRouteName(Route::currentRouteName());
@endphp
@extends('sga::layouts.master')

@section('content')
<!-- Main content -->
<div class="container">
    <div class="container-fluid">
        <!-- Header mejorado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-2"> {{ trans(' Registro de Asistencia')}} <i class="fas fa-clipboard-check text-primary"></i></h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0">
                                <li class="breadcrumb-item"><a href="{{ route('cefa.sga.staff.index') }}">SGA</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Validación de Asistencia</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="text-right">
                        <div class="badge badge-success p-2">
                            <i class="fas fa-clock"></i> {{ now()->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de registro mejorado -->
        <div class="row justify-content-md-center pt-4">
            <div class="card shadow col-md-8 border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle"></i> Registrar Asistencia
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="documentNumber" class="form-label">
                            <i class="fas fa-id-card"></i> Número de Documento
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="number"
                                name="search"
                                class="form-control form-control-lg"
                                placeholder="Ej: 1029880898"
                                id="documentNumber"
                                autofocus
                                min="1000000"
                                max="9999999999">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-lg" type="button" id="searchApprenticeButton">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="documentNumberError"></div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Ingrese el número de documento del aprendiz y presione Buscar para ver los datos y habilitar el registro.
                        </small>
                    </div>

                    <!-- Información del aprendiz mejorada -->
                    <div id="apprenticeInfo" class="mt-4" style="display:none;">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-user"></i> Información del Aprendiz</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="apprenticeTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Documento</th>
                                        <th>Nombre Completo</th>
                                        <th>Ficha</th>
                                        <th>Programa</th>
                                        <th>Estado de Asistencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se insertará la fila con JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de registros del día mejorada -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list"></i> Registros de Hoy - {{ now()->format('d/m/Y') }}
                        </h5>
                        <div>
                            <span class="badge badge-light mr-2" id="totalRegistrations">0 registros</span>
                            <button class="btn btn-light btn-sm" onclick="refreshTable()" id="refreshButton">
                                <i class="fas fa-sync-alt"></i> Actualizar
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if(count($lunchRegistrations ?? []) > 0)
                            <table class="table table-striped table-hover" id="lunchTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>{{ trans('sga::menu.Document Number')}}</th>
                                        <th>{{ trans('sga::menu.Apprentice')}}</th>
                                        <th>{{ trans('sga::menu.Course')}}</th>
                                        <th>{{ trans('sga::menu.Program')}}</th>
                                        <th>{{ trans('sga::menu.Time')}}</th>
                                        <th>{{ trans('sga::menu.Status')}}</th>
                                        <th>{{ trans('sga::menu.Actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lunchRegistrations ?? [] as $registration)
                                    <tr>
                                        <td><strong>{{ $registration->document_number }}</strong></td>
                                        <td>{{ $registration->first_name }} {{ $registration->first_last_name }} {{ $registration->second_last_name }}</td>
                                        <td><span class="badge badge-secondary">{{ $registration->course_code }}</span></td>
                                        <td>{{ $registration->program_name }}</td>
                                        <td><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($registration->attendance_time)->format('H:i') }}</td>
                                        <td>
                                            @if($registration->status == 'registered')
                                            <span class="badge badge-success">Registrado</span>
                                            @elseif($registration->status == 'cancelled')
                                            <span class="badge badge-danger">Cancelado</span>
                                            @elseif($registration->status == 'attended' || $registration->status == 'si')
                                            <span class="badge badge-info">Asistió</span>
                                            @elseif($registration->status == 'no')
                                            <span class="badge badge-secondary">No asistió</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($registration->status == 'registered')
                                            <button class="btn btn-sm btn-outline-danger" onclick="cancelRegistration({{ $registration->id }})">
                                                <i class="fas fa-times"></i> Cancelar
                                            </button>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay registros para hoy</h5>
                                <p class="text-muted">Los registros de asistencia aparecerán aquí una vez que se realicen.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Incluir jQuery desde CDN antes de los scripts que lo usan --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    jQuery(document).ready(function() {
        jQuery('#documentNumber').focus();

        // Rest of your JavaScript code remains the same...
        // Validación en tiempo real del documento
        jQuery('#documentNumber').on('input', function() {
            var value = jQuery(this).val();
            var isValid = value.length >= 8 && value.length <= 10;

            if (isValid) {
                jQuery(this).removeClass('is-invalid').addClass('is-valid');
                jQuery('#documentNumberError').hide();
            } else {
                jQuery(this).removeClass('is-valid').addClass('is-invalid');
                jQuery('#documentNumberError').text('El número de documento debe tener entre 8 y 10 dígitos').show();
            }
        });

        // Buscar aprendiz al hacer click en Buscar
        jQuery('#searchApprenticeButton').on('click', function(e) {
            e.preventDefault();
            buscarAprendiz();
        });

        // Permitir buscar con Enter
        jQuery('#documentNumber').on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                buscarAprendiz();
            }
        });

        // Actualizar contador de registros
        updateRegistrationCount();
    });

    function buscarAprendiz() {
        var documentNumber = jQuery('#documentNumber').val();
        if (!documentNumber) {
            showSweetAlert('warning', 'Advertencia', 'Por favor ingrese un número de documento.');
            return;
        }

        if (documentNumber.length < 8 || documentNumber.length > 10) {
            showSweetAlert('warning', 'Advertencia', 'El número de documento debe tener entre 8 y 10 dígitos.');
            return;
        }

        jQuery('#searchApprenticeButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Buscando...');
        jQuery('#apprenticeInfo').hide();

        axios.post('/sga/staff/rec-validation/get-apprentice-info', {
                documentNumber: documentNumber
            })
            .then(function(response) {
                if (response.data && response.data.apprentice) {
                    var a = response.data.apprentice;
                    var row = '<tr class="table-success">' +
                        '<td><strong>' + a.document_number + '</strong></td>' +
                        '<td><strong>' + a.first_name + ' ' + a.first_last_name + ' ' + a.second_last_name + '</strong></td>' +
                        '<td><span class="badge badge-secondary">' + a.course_code + '</span></td>' +
                        '<td>' + a.program_name + '</td>' +
                        '<td>' +
                        '<div class="form-row">' +
                        '<div class="col-md-12">' +
                        '<select id="attendanceStatus" class="form-control form-control-sm">' +
                        '<option value="asistio">Asistió</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<button class="btn btn-success btn-sm mt-2" onclick="registrarAsistenciaEstado()">' +
                        '<i class="fas fa-check"></i> Registrar Asistencia' +
                        '</button>' +
                        '</td>' +
                        '</tr>';
                    jQuery('#apprenticeTable tbody').html(row);
                    jQuery('#apprenticeInfo').show();
                } else {
                    jQuery('#apprenticeTable tbody').html('<tr class="table-danger"><td colspan="5" class="text-center">No se encontró información válida.</td></tr>');
                    jQuery('#apprenticeInfo').show();
                }
            })
            .catch(function(error) {
                var msg = 'No se encontró información válida.';
                if (error.response && error.response.data && error.response.data.error) {
                    msg = error.response.data.error;
                }
                jQuery('#apprenticeTable tbody').html('<tr class="table-danger"><td colspan="5" class="text-center">' + msg + '</td></tr>');
                jQuery('#apprenticeInfo').show();
            })
            .finally(function() {
                jQuery('#searchApprenticeButton').prop('disabled', false).html('<i class="fas fa-search"></i> Buscar');
            });
    }

    function registrarAsistencia() {
        var documentNumber = jQuery('#documentNumber').val();
        if (!documentNumber) {
            showSweetAlert('warning', 'Advertencia', 'Por favor ingrese un número de documento.');
            return;
        }
        jQuery('#registerButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Registrando...');
        axios.post('/sga/staff/rec-validation/register', {
                documentNumber: documentNumber
            })
            .then(function(response) {
                if (response.status === 200 && response.data.success) {
                    showSweetAlert('success', '¡Éxito!', response.data.success, 2000);
                    jQuery('#documentNumber').val('').focus();
                    jQuery('#apprenticeInfo').hide();
                    jQuery('#registerButton').prop('disabled', true).html('<i class="fas fa-barcode"></i> Registrar Asistencia');
                    refreshTable();
                } else if (response.data.error) {
                    showSweetAlert('error', 'Error', response.data.error, 3000);
                    jQuery('#registerButton').prop('disabled', false).html('<i class="fas fa-barcode"></i> Registrar Asistencia');
                }
            })
            .catch(function(error) {
                showSweetAlert('error', 'Error', 'Ocurrió un error al registrar la asistencia.', 3000);
                jQuery('#registerButton').prop('disabled', false).html('<i class="fas fa-barcode"></i> Registrar Asistencia');
            });
    }

    function cancelRegistration(registrationId) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea cancelar este registro de asistencia?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, mantener'
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar la cancelación mediante AJAX
                axios.post('/sga/staff/rec-validation/cancel', {
                        registrationId: registrationId
                    })
                    .then(function(response) {
                        if (response.status === 200 && response.data.success) {
                            showSweetAlert('success', "{{ trans('sga::menu.Success!') }}", response.data.success, 2000);
                            refreshTable();
                        } else {
                            showSweetAlert('error', 'Error', response.data.error || 'Error al cancelar el registro.');
                        }
                    })
                    .catch(function(error) {
                        showSweetAlert('error', 'Error', "{{ trans('sga::menu.An error occurred while trying to cancel.') }}", 3000);
                        console.error('Error en la solicitud AJAX:', error);
                    });
            }
        });
    }

    function refreshTable() {
        // Mostrar loading en la tabla
        jQuery('#lunchTable tbody').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>');
        jQuery('#refreshButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Actualizando...');

        // Obtener registros actualizados
        axios.get('/sga/staff/rec-validation/today')
            .then(function(response) {
                if (response.status === 200) {
                    updateTable(response.data.registrations);
                }
            })
            .catch(function(error) {
                showSweetAlert('error', 'Error', 'Error al actualizar la tabla.', 3000);
                console.error('Error al actualizar tabla:', error);
            })
            .finally(function() {
                jQuery('#refreshButton').prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Actualizar');
            });
    }

    function updateTable(registrations) {
        if (registrations.length === 0) {
            jQuery('#lunchTable tbody').html('<tr><td colspan="7" class="text-center text-muted">No hay registros para hoy.</td></tr>');
            updateRegistrationCount(0);
            return;
        }

        let tableBody = '';
        registrations.forEach(function(registration) {
            let statusBadge = '';
            if (registration.status == 'registered') {
                statusBadge = '<span class="badge badge-success">Registrado</span>';
            } else if (registration.status == 'cancelled') {
                statusBadge = '<span class="badge badge-danger">Cancelado</span>';
            } else if (registration.status == 'attended' || registration.status == 'si') {
                statusBadge = '<span class="badge badge-info">Asistió</span>';
            } else if (registration.status == 'no') {
                statusBadge = '<span class="badge badge-secondary">No asistió</span>';
            }

            let actionButton = '';
            if (registration.status == 'registered') {
                actionButton = `<button class="btn btn-sm btn-outline-danger" onclick="cancelRegistration(${registration.id})">
                    <i class="fas fa-times"></i> Cancelar
                </button>`;
            } else {
                actionButton = '<span class="text-muted">-</span>';
            }

            tableBody += `
                <tr>
                    <td><strong>${registration.document_number}</strong></td>
                    <td>${registration.first_name} ${registration.first_last_name} ${registration.second_last_name}</td>
                    <td><span class="badge badge-secondary">${registration.course_code}</span></td>
                    <td>${registration.program_name}</td>
                    <td><i class="fas fa-clock"></i> ${new Date('2000-01-01T' + registration.attendance_time).toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'})}</td>
                    <td>${statusBadge}</td>
                    <td>${actionButton}</td>
                </tr>
            `;
        });

        jQuery('#lunchTable tbody').html(tableBody);
        updateRegistrationCount(registrations.length);
    }

    function updateRegistrationCount(count) {
        if (count === undefined) {
            count = jQuery('#lunchTable tbody tr').length;
        }
        jQuery('#totalRegistrations').text(count + ' registros');
    }

    function showSweetAlert(icon, title, text, timer = 3000) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            showConfirmButton: false,
            timer: timer
        });
    }

    // Nueva función para registrar asistencia con estado
    function registrarAsistenciaEstado() {
        var documentNumber = jQuery('#documentNumber').val();
        var estado = jQuery('#attendanceStatus').val();

        if (!documentNumber) {
            showSweetAlert('warning', 'Advertencia', 'Por favor ingrese un número de documento.');
            return;
        }
        if (!estado) {
            showSweetAlert('warning', 'Advertencia', 'Por favor seleccione un estado.');
            return;
        }
        jQuery('#attendanceStatus').prop('disabled', true);

        axios.post('/sga/staff/rec-validation/register', {
                documentNumber: documentNumber,
                status: estado
            })
            .then(function(response) {
                if (response.status === 200 && response.data.success) {
                    showSweetAlert('success', '¡Éxito!', response.data.success, 2000);
                    jQuery('#documentNumber').val('').focus();
                    jQuery('#apprenticeInfo').hide();
                    refreshTable();
                } else if (response.data.error) {
                    showSweetAlert('error', 'Error', response.data.error, 3000);
                }
            })
            .catch(function(error) {
                showSweetAlert('error', 'Error', 'Ocurrió un error al registrar la asistencia.', 3000);
            })
            .finally(function() {
                jQuery('#attendanceStatus').prop('disabled', false);
            });
    }
</script>

@endsection