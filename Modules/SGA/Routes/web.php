<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['lang'])->group(function () {
    Route::prefix('sga')->group(function () {

        // Rutas públicas del módulo SGA
        Route::controller(SGAController::class)->group(function () {
            Route::get('index', 'index')->name('cefa.sga.home.index'); // Página principal del módulo SGA (pública)
            Route::get('developers', 'developers')->name('cefa.sga.home.developers'); // Vista de desarrolladores (pública)
            Route::get('about_this', 'aboutthis')->name('cefa.sga.home.about'); // Vista de información general (pública)
            Route::get('manual', 'manual')->name('cefa.sga.home.manual'); // Manual de usuario (pública)
        });

        // Dashboard del administrador
        Route::controller(AdminController::class)->group(function () {
            Route::get('admin', 'index')->name('cefa.sga.admin.index'); // Vista principal del administrador
        });
        Route::controller(AdmStaffController::class)->group(function () {
            // Acceso a CRUD de Funcionarios
            Route::get('admin/staff', 'index')->name('cefa.sga.admin.staff');
            Route::get('admin/staff/{user}', 'show')->name('cefa.sga.admin.staff.show');
            // Ruta para mostrar el formulario de cambio de contraseña
            Route::get('admin/staff/{user}/password', 'showPasswordForm')->name('cefa.sga.admin.staff.show-password-form');
            // Ruta para actualizar la contraseña
            Route::put('admin/staff/{user}/password', 'updatePassword')->name('cefa.sga.admin.staff.update-password');
        });
        Route::controller(AdmApprenticeController::class)->group(function () {
            // Acceso a CRUD de Aprendices
            Route::get('admin/apprentice', 'index')->name('cefa.sga.admin.apprentice');
        });
        Route::controller(AdmAsvController::class)->group(function () {
            // Ruta GET para mostrar la página inicial (sin filtros)
            Route::get('admin/asv', 'index')->name('cefa.sga.admin.asv');

            // Ruta POST para filtrar asistencias (misma URL pero diferente método)
            Route::post('admin/asv', 'asistenciasJornada')->name('cefa.sga.admin.asv.asistencias.jornada');
        });

        // Mantén las rutas de exportación como están
        Route::controller(AdmAsvExportController::class)->group(function () {
            Route::post('asv/export/pdf', 'exportPDF')->name('cefa.sga.admin.asv.export.pdf');
            Route::post('asv/export/word', 'exportWord')->name('cefa.sga.admin.asv.export.word');
        });

        Route::controller(DebugExportController::class)->group(function () {
            Route::post('debug/test-pdf', 'testPDF')->name('debug.test.pdf');
            Route::post('debug/test-word', 'testWord')->name('debug.test.word');
        });

        Route::controller(AdmSAssgnController::class)->group(function () {
            Route::get('admin/s-assgn', 'index')->name('cefa.sga.admin.s-assgn'); // Editar puntaje de convocatoria alimentaria
            Route::get('admin/s-assgn/debug', 'debug')->name('cefa.sga.admin.s-assgn.debug'); // Debug para verificar datos
            Route::post('admin/s-assgn/export-pdf', 'exportPDF')->name('cefa.sga.admin.s-assgn.export-pdf'); // Exportar a PDF
        });
        Route::controller(AdmEvController::class)->group(function () {
            Route::get('admin/ev-history', 'index')->name('cefa.sga.admin.ev-history'); // Visualizar historial de puntajes
            Route::post('admin/ev-history/puntajes-detallados', 'getPuntajesDetallados')->name('cefa.sga.admin.ev-history.puntajes-detallados'); // Obtener puntajes detallados
        });
        Route::controller(AdmBSummaryController::class)->group(function () {
            Route::get('admin/b-summary', 'index')->name('cefa.sga.admin.b-summary'); // Visualizar puntajes de aprendices
        });
        Route::controller(AdmConvocatoriesController::class)->group(function () {
            Route::get('admin/convocatories', 'index')->name('cefa.sga.admin.convocatories'); // Configurar convocatorias
            // Obtener listado de convocatorias
            Route::get('admin/convocatories/obtener', 'obtenerConvocatorias')->name('cefa.sga.admin.convocatories.obtener');
            // Crear nueva convocatoria
            Route::post('admin/convocatories', 'store')->name('cefa.sga.admin.convocatories.store');
            // Cambiar estado de convocatoria (Activa/Inactiva)
            Route::post('admin/convocatories/cambiar-estado/{id}', 'cambiarEstadoConvocatoria')->name('cefa.sga.admin.convocatories.cambiar-estado');
            // Eliminar convocatoria
            Route::delete('admin/convocatories/{id}', 'destroy')->name('cefa.sga.admin.convocatories.destroy');
        });

        // Rutas para puntos de convocatorias
        Route::controller(AdmConvocatoryPointsController::class)->group(function () {
            Route::get('admin/convocatory-points', 'index')->name('cefa.sga.admin.convocatory_points'); // Configurar puntos de convocatorias
            Route::post('admin/convocatory-points', 'store')->name('cefa.sga.admin.convocatory_points.store'); // Crear puntos
            Route::post('admin/convocatory-points/copy', 'copyPoints')->name('cefa.sga.admin.convocatory_points.copy'); // Copiar puntos
            Route::get('admin/convocatory-points/{id}', 'getPoints')->name('cefa.sga.admin.convocatory_points.get'); // Obtener puntos
        });
        Route::controller(AdmExternalEventsController::class)->group(function () {
            Route::get('admin/external-events', 'index')->name('cefa.sga.admin.external-events'); // Configurar eventos externos
            Route::post('admin/external-events', 'store')->name('cefa.sga.admin.external-events.store'); // Crear evento
            // Mantener ruta existente para compatibilidad
            Route::get('admin/external-events/obtener-eventos', 'obtenerEventos')->name('cefa.sga.admin.external-events.obtener-eventos');
            // Rutas con parámetros al final para evitar conflictos
            Route::get('admin/external-events/{id}', 'getEvent')->name('cefa.sga.admin.external-events.get'); // Obtener evento
            Route::put('admin/external-events/{id}', 'update')->name('cefa.sga.admin.external-events.update'); // Actualizar evento
            Route::delete('admin/external-events/{id}', 'destroy')->name('cefa.sga.admin.external-events.destroy'); // Eliminar evento
        });
        Route::controller(AdmProfileController::class)->group(function () {
            Route::get('admin/profile', 'index')->name('cefa.sga.admin.profile'); // Configurar perfil de usuario
            Route::put('/profile/change-password', 'changePassword')->name('cefa.sga.admin.profile.change-password'); // Cambiar contraseña del usuario
            Route::put('/profile/update-personal-info', 'updatePersonalInfo')->name('cefa.sga.admin.profile.update-personal-info'); // Actualizar información personal del usuario
        });

        // Dashboard del funcionario
        Route::controller(StaffController::class)->group(function () {
            // Vista principal del funcionario (dashboard)
            Route::get('staff', 'index')->name('cefa.sga.staff.index');
        });

        Route::controller(StaffOpsReportsController::class)->group(function () {
            Route::get('staff/ops-reports', 'index')->name('cefa.sga.staff.ops-reports');
            // ========================================
            // RUTAS PARA EXPORTAR REPORTES DE ASISTENCIA
            // ========================================
            // Permiten al staff generar reportes en diferentes formatos
            // para análisis, presentaciones y archivo oficial
            // Exportar reporte diario en Excel
            Route::get('staff/ops-reports/export-day', 'exportAttendanceDay')->name('cefa.sga.staff.ops-reports.export-day');
            // Exportar reporte semanal en Excel
            Route::get('staff/ops-reports/export-week', 'exportAttendanceWeek')->name('cefa.sga.staff.ops-reports.export-week');
            // Exportar reporte mensual en Excel
            Route::get('staff/ops-reports/export-month', 'exportAttendanceMonth')->name('cefa.sga.staff.ops-reports.export-month');
            // Exportar reporte diario en PDF
            Route::get('staff/ops-reports/export-day-pdf', 'exportAttendanceDayPDF')->name('cefa.sga.staff.ops-reports.export-day-pdf');
            // Exportar reporte semanal en PDF
            Route::get('staff/ops-reports/export-week-pdf', 'exportAttendanceWeekPDF')->name('cefa.sga.staff.ops-reports.export-week-pdf');
            // Exportar reporte mensual en PDF
            Route::get('staff/ops-reports/export-month-pdf', 'exportAttendanceMonthPDF')->name('cefa.sga.staff.ops-reports.export-month-pdf');
            // Obtener estadísticas en tiempo real para el dashboard
            Route::get('staff/stats', 'getStats')->name('cefa.sga.staff.stats');
        });

        Route::controller(StaffRecValidationController::class)->group(function () {
            // Vista principal de validación de asistencia
            Route::get('staff/rec-validation', 'index')->name('cefa.sga.staff.rec-validation');

            // Registrar nueva asistencia de un aprendiz
            Route::post('staff/rec-validation/register', 'registerAttendance')->name('cefa.sga.staff.rec-validation.register');

            // Cancelar registro de asistencia existente
            Route::post('staff/rec-validation/cancel', 'cancelAttendance')->name('cefa.sga.staff.rec-validation.cancel');

            // Obtener asistencias del día actual
            Route::get('staff/rec-validation/today', 'getTodayAttendances')->name('cefa.sga.staff.rec-validation.today');

            // Obtener información de un aprendiz por documento
            Route::post('staff/rec-validation/get-apprentice-info', 'getApprenticeInfo')->name('cefa.sga.staff.rec-validation.get-apprentice-info');

            // Obtener estadísticas del día para el dashboard
            Route::get('staff/rec-validation/stats', 'getStats')->name('cefa.sga.staff.rec-validation.stats');

            // Búsqueda rápida de aprendices por nombre o documento
            Route::post('staff/rec-validation/quick-search', 'quickSearch')->name('cefa.sga.staff.rec-validation.quick-search');

            // Exportar registros del día en diferentes formatos
            Route::post('staff/rec-validation/export', 'exportToday')->name('cefa.sga.staff.rec-validation.export');

            // Ruta temporal para obtener números de documento (solo desarrollo)
            Route::get('staff/rec-validation/documents', 'getDocumentNumbers')->name('cefa.sga.staff.rec-validation.documents');
        });

        Route::controller(StaffIncidentController::class)->group(function () {
            // Vista principal de gestión de incidencias
            Route::get('staff/incidents', 'index')->name('cefa.sga.staff.incidents');

            // Formulario para crear nueva incidencia
            Route::get('staff/incidents/create', 'create')->name('cefa.sga.staff.incidents-create');

            // Almacenar nueva incidencia en el sistema
            Route::post('staff/incidents', 'store')->name('cefa.sga.staff.incidents-store');

            // Ver detalles de una incidencia específica
            Route::get('staff/incidents/{id}', 'show')->name('cefa.sga.staff.incidents-show');

            // Actualizar información de una incidencia
            Route::put('staff/incidents/{id}', 'update')->name('cefa.sga.staff.incidents-update');

            // Asignar incidencia a un técnico o administrador
            Route::post('staff/incidents/{id}/assign', 'assign')->name('cefa.sga.staff.incidents-assign');

            // Marcar incidencia como resuelta
            Route::post('staff/incidents/{id}/resolve', 'resolve')->name('cefa.sga.staff.incidents-resolve');

            // Cerrar incidencia (archivar)
            Route::post('staff/incidents/{id}/close', 'close')->name('cefa.sga.staff.incidents-close');

            // Agregar comentario a una incidencia
            Route::post('staff/incidents/{id}/comment', 'addComment')->name('cefa.sga.staff.incidents-comment');

            // Obtener estadísticas de incidencias
            Route::get('staff/incidents/stats', 'getStats')->name('cefa.sga.staff.incidents-stats');

            // Exportar datos de incidencias para análisis
            Route::get('staff/incidents/export', 'export')->name('cefa.sga.staff.incidents-export');
        });

        Route::controller(StaffProfileController::class)->group(function () {
            Route::get('staff/profile', 'index')->name('cefa.sga.staff.profile');
            Route::put('staff/profile/update-personal-info', 'updatePersonalInfo')->name('sga.staff.profile.update-personal-info');
            Route::put('staff/profile/change-password', 'changePassword')->name('sga.staff.profile.change-password');
        });

        // Dashboard del aprendiz
        Route::controller(ApprenticeController::class)->group(function () {
            Route::get('apprentice', 'index')->name('cefa.sga.apprentice.index'); // Vista principal del aprendiz
        });
        Route::controller(ApzMyBenefitController::class)->group(function () {
            Route::get('apprentice/my-benefit', 'myBenefit')->name('cefa.sga.apprentice.my-benefit'); 
            Route::get('apprentice/ben-history', 'benHistory')->name('cefa.sga.apprentice.ben-history'); 
        });
        
        Route::controller(ApzApplyToCallController::class)->group(function () {
            Route::get('apprentice/apply-to-call', 'index')->name('cefa.sga.apprentice.apply-to-call'); // Enviar solicitud a convocatoria
            Route::post('/apply-to-call/process', 'processApplication')->name('cefa.sga.apprentice.apply-to-call.process');
        });
        Route::controller(ApzProfileController::class)->group(function () {
            Route::get('apprentice/profile', 'index')->name('cefa.sga.apprentice.profile'); // Configurar perfil de usuario
            Route::put('apprentice/profile', 'updateProfile')->name('cefa.sga.apprentice.profile.update');

            // Nuevas rutas para el perfil completo
            Route::put('apprentice/profile/personal-info', 'updatePersonalInfo')->name('cefa.sga.apprentice.profile.update-personal-info');
            Route::put('apprentice/profile/change-password', 'changePassword')->name('cefa.sga.apprentice.profile.change-password');
            Route::put('apprentice/profile/formation', 'updateFormationInfo')->name('cefa.sga.apprentice.profile.update-formation-info');
            Route::put('apprentice/profile/representative', 'updateRepresentativeInfo')->name('cefa.sga.apprentice.profile.update-representative-info');
            Route::put('apprentice/profile/housing', 'updateHousingInfo')->name('cefa.sga.apprentice.profile.update-housing-info');
            Route::put('apprentice/profile/medical', 'updateMedicalInfo')->name('cefa.sga.apprentice.profile.update-medical-info');
            Route::put('apprentice/profile/socioeconomic', 'updateSocioeconomicInfo')->name('cefa.sga.apprentice.profile.update-socioeconomic-info');
            Route::put('apprentice/profile/conditions', 'updateConditionsInfo')->name('cefa.sga.apprentice.profile.update-conditions-info');
            Route::put('apprentice/profile/declaration', 'updateDeclarationInfo')->name('cefa.sga.apprentice.profile.update-declaration-info');

            // Ruta para guardar todas las secciones
            Route::post('apprentice/profile/save-all', 'saveAllSections')->name('cefa.sga.apprentice.profile.save-all');
        });
    });
});
