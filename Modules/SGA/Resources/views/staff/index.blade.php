@extends('sga::layouts.master')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #14532d 0%, #064e3b 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .dashboard-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        border-left: 4px solid #28a745;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stats-icon {
        font-size: 2.5rem;
        color: #28a745;
        margin-bottom: 1rem;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: #14532d;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .quick-actions-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: none;
    }

    .quick-actions-title {
        color: #14532d;
        font-weight: 600;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
        border: 2px solid transparent;
        border-radius: 10px;
        text-decoration: none;
        color: #495057;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: #e9ecef;
        border-color: #28a745;
        color: #14532d;
        text-decoration: none;
        transform: translateX(5px);
    }

    .action-btn i {
        font-size: 1.5rem;
        color: #28a745;
        margin-right: 1rem;
        width: 30px;
        text-align: center;
    }

    .action-btn .action-text {
        flex-grow: 1;
    }

    .action-btn .action-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .action-btn .action-description {
        font-size: 0.85rem;
        opacity: 0.7;
        margin: 0;
    }

    .recent-activity-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: none;
    }

    .recent-activity-title {
        color: #14532d;
        font-weight: 600;
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e8f5e8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: #28a745;
    }

    .activity-content {
        flex-grow: 1;
    }

    .activity-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        border-left: 4px solid #28a745;
    }

    .welcome-icon {
        font-size: 4rem;
        color: #28a745;
        margin-bottom: 1rem;
    }

    .welcome-text {
        color: #495057;
        font-size: 1.1rem;
        margin-bottom: 0;
    }
</style>

<!-- Dashboard Header -->
<div class="dashboard-header">
    <h1 class="dashboard-title">
        <i class="fas fa-user-tie me-3"></i>
        Dashboard de Funcionario
    </h1>
    <p class="dashboard-subtitle">Bienvenido al Sistema de Gestión de Almuerzos</p>
</div>

<div class="row">
    <!-- Columna izquierda -->
    <div class="col-lg-8">
        <!-- Banner de bienvenida -->
        <div class="welcome-banner">
            <div class="welcome-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <p class="welcome-text">
                ¡Bienvenido! Aquí podrás gestionar tus reportes operativos, validar registros de asistencia, 
                manejar incidentes y acceder a tu perfil de usuario.
            </p>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="text-center">
                        <div class="stats-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="stats-number">12</div>
                        <div class="stats-label">Reportes Generados</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="text-center">
                        <div class="stats-icon">
                            <i class="fas fa-check-square"></i>
                        </div>
                        <div class="stats-number">45</div>
                        <div class="stats-label">Registros Validados</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="text-center">
                        <div class="stats-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stats-number">3</div>
                        <div class="stats-label">Incidentes Activos</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="text-center">
                        <div class="stats-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stats-number">28</div>
                        <div class="stats-label">Días de Asistencia</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna derecha -->
    <div class="col-lg-4">
        <!-- Acciones rápidas -->
        <div class="quick-actions-card">
            <h5 class="quick-actions-title">
                <i class="fas fa-bolt me-2"></i>
                Acciones Rápidas
            </h5>
            
            <a href="{{ route('cefa.sga.staff.ops-reports') }}" class="action-btn">
                <i class="fas fa-chart-bar"></i>
                <div class="action-text">
                    <div class="action-title">Reportes Operativos</div>
                    <div class="action-description">Generar y consultar reportes</div>
                </div>
            </a>

            <a href="{{ route('cefa.sga.staff.rec-validation') }}" class="action-btn">
                <i class="fas fa-check-square"></i>
                <div class="action-text">
                    <div class="action-title">Validar Asistencia</div>
                    <div class="action-description">Revisar registros de asistencia</div>
                </div>
            </a>

            <a href="{{ route('cefa.sga.staff.incidents') }}" class="action-btn">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="action-text">
                    <div class="action-title">Gestionar Incidentes</div>
                    <div class="action-description">Reportar y seguir incidentes</div>
                </div>
            </a>

            <a href="{{ route('cefa.sga.staff.profile') }}" class="action-btn">
                <i class="fas fa-user"></i>
                <div class="action-text">
                    <div class="action-title">Mi Perfil</div>
                    <div class="action-description">Actualizar información personal</div>
                </div>
            </a>
        </div>

        <!-- Actividad reciente -->
        <div class="recent-activity-card">
            <h5 class="recent-activity-title">
                <i class="fas fa-clock me-2"></i>
                Actividad Reciente
            </h5>
            
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Validación de asistencia completada</div>
                    <div class="activity-time">Hace 2 horas</div>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Reporte operativo generado</div>
                    <div class="activity-time">Hace 1 día</div>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Nuevo incidente reportado</div>
                    <div class="activity-time">Hace 2 días</div>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Perfil actualizado</div>
                    <div class="activity-time">Hace 1 semana</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información del sistema -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #14532d 0%, #064e3b 100%); color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Versión del Sistema:</strong> SGA v2.1.0</p>
                        <p><strong>Última Actualización:</strong> {{ date('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Estado del Servicio:</strong> <span class="badge badge-success">Operativo</span></p>
                        <p><strong>Horario de Atención:</strong> 7:00 AM - 6:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 