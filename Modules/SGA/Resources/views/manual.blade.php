@extends('sga::layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book me-2"></i>
                    Manual de Usuario SGA
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Instrucciones:</strong> Este manual contiene toda la información necesaria para utilizar el Sistema de Gestión de Almuerzos (SGA). 
                    Puedes navegar por las páginas usando los controles del visor PDF.
                </div>
                
                <!-- Contenedor del PDF -->
                <div class="pdf-container" style="height: 80vh; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden;">
                    <iframe 
                        src="{{ asset('modules/sga/manual/manual-usuario-sga.pdf') }}" 
                        width="100%" 
                        height="100%" 
                        style="border: none;"
                        title="Manual de Usuario SGA">
                        <p>Tu navegador no soporta la visualización de PDFs. 
                           <a href="{{ asset('modules/sga/manual/manual-usuario-sga.pdf') }}" target="_blank" class="btn btn-primary">
                               <i class="fas fa-download me-2"></i>Descargar Manual
                           </a>
                        </p>
                    </iframe>
                </div>
                
                <!-- Botones de acción -->
                <div class="mt-3 text-center">
                    <a href="{{ asset('modules/sga/manual/manual-usuario-sga.pdf') }}" 
                       target="_blank" 
                       class="btn btn-success me-2">
                        <i class="fas fa-external-link-alt me-2"></i>
                        Abrir en Nueva Ventana
                    </a>
                    <a href="{{ asset('modules/sga/manual/manual-usuario-sga.pdf') }}" 
                       download="manual-usuario-sga.pdf" 
                       class="btn btn-primary me-2">
                        <i class="fas fa-download me-2"></i>
                        Descargar Manual
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-question-circle me-2"></i>
                    ¿Necesitas Ayuda?
                </h5>
            </div>
            <div class="card-body">
                <p>Si tienes dudas sobre el uso del sistema, puedes:</p>
                <ul>
                    <li>Consultar este manual de usuario</li>
                    <li>Contactar al soporte técnico</li>
                    <li>Revisar las preguntas frecuentes</li>
                </ul>
                <a href="mailto:soporte.sga@cefa.edu.co" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-envelope me-2"></i>
                    Contactar Soporte
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Información del Manual
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Versión:</strong> 1.0</p>
                <p><strong>Última actualización:</strong> {{ date('d/m/Y') }}</p>
                <p><strong>Formato:</strong> PDF</p>
                <p><strong>Idioma:</strong> Español</p>
            </div>
        </div>
    </div>
</div>

<style>
.pdf-container {
    background: #f8f9fa;
    position: relative;
}

.pdf-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y="50" font-size="20" fill="%23ccc" text-anchor="middle" x="50">Cargando PDF...</text></svg>') center/contain no-repeat;
    z-index: 1;
    pointer-events: none;
}

.pdf-container iframe {
    position: relative;
    z-index: 2;
}

@media (max-width: 768px) {
    .pdf-container {
        height: 60vh;
    }
}
</style>
@endsection
