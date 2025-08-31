<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

/**
 * Entidad principal para el registro de asistencias en el Sistema de Gestión Académica (SGA)
 * 
 * Esta entidad representa el núcleo del sistema de gestión de asistencias,
 * permitiendo al personal administrativo (staff) del SENA:
 * - Registrar asistencias de aprendices en tiempo real
 * - Gestionar diferentes estados de asistencia
 * - Mantener auditoría completa de todos los registros
 * - Generar reportes y estadísticas de asistencia
 * - Realizar seguimiento del rendimiento académico
 * 
 * Características principales:
 * - Soft deletes para mantener historial completo
 * - Auditoría automática de todos los cambios
 * - Relaciones optimizadas con aprendices y usuarios
 * - Scopes para consultas comunes y eficientes
 * - Accessors y mutators para formateo de datos
 * - Validaciones automáticas de integridad
 * 
 * Estados de asistencia disponibles:
 * - 'registered': Registro inicial de asistencia
 * - 'attended': Asistencia confirmada
 * - 'absent': Ausencia confirmada
 * - 'late': Tardanza registrada
 * - 'cancelled': Registro cancelado
 * 
 * @package Modules\SGA\Entities
 * @author SENA - Sistema de Gestión Académica
 * @version 1.0
 */
class AttendanceRegistration extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    /**
     * Campos de fecha que se manejan automáticamente como instancias de Carbon
     * 
     * Esto permite manipulación fácil de fechas y horas
     * para cálculos, comparaciones y formateo.
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Campos que se ocultan en respuestas JSON y arrays
     * 
     * Los timestamps de creación y actualización se ocultan
     * para simplificar las respuestas del API.
     */
    protected $hidden = ['created_at','updated_at'];
    
    /**
     * Nombre de la tabla en la base de datos
     * 
     * Se especifica explícitamente para evitar conflictos
     * con convenciones de nomenclatura de Laravel.
     */
    protected $table = 'apprentice_asistencia';
    
    /**
     * Campos que se pueden asignar masivamente
     * 
     * Solo estos campos pueden ser asignados directamente
     * desde formularios o arrays, previniendo asignación
     * masiva no deseada de campos sensibles.
     */
    protected $fillable = [
        'apprentice_id',
        'asistance_id',
        'asistencia', // 'si' | 'no'
    ];

    /**
     * Conversiones automáticas de tipos de datos
     * 
     * Las fechas se convierten automáticamente a instancias Carbon,
     * y el porcentaje se maneja como entero para cálculos precisos.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Constantes para los estados de asistencia del sistema
     * 
     * Estas constantes definen los valores válidos para el campo status,
     * asegurando consistencia en toda la aplicación y facilitando
     * la validación y comparación de estados.
     */
    const STATUS_ATTENDED = 'attended';      // Mapeo a 'si'
    const STATUS_ABSENT = 'absent';          // Mapeo a 'no'

    /**
     * Relación con el aprendiz asociado a este registro de asistencia
     * 
     * Cada registro de asistencia pertenece a un aprendiz específico.
     * La relación se establece a través del campo apprentice_id.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apprentice(){
        return $this->belongsTo(\Modules\SICA\Entities\Apprentice::class, 'apprentice_id');
    }
    
    /**
     * Relación con el usuario que registró la asistencia
     * 
     * Cada registro de asistencia es creado por un usuario del sistema
     * (generalmente personal administrativo). Esta relación permite
     * auditoría completa de quién realizó cada registro.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registeredBy(){
        // Campo no existente en el nuevo esquema. Se mantiene por compatibilidad.
        return $this->belongsTo(\App\Models\User::class, 'registered_by');
    }

    /**
     * Relación con la cabecera de asistencia (fecha)
     */
    public function asistance()
    {
        return $this->belongsTo(Asistance::class, 'asistance_id');
    }
    
    /**
     * Scope para filtrar registros del día actual
     * 
     * Permite consultas como: AttendanceRegistration::today()->get()
     * para obtener fácilmente todos los registros del día actual.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereHas('asistance', function ($q) {
            $q->whereDate('date', now()->toDateString());
        });
    }
    
    /**
     * Scope para filtrar registros por estado específico
     * 
     * Permite consultas como: AttendanceRegistration::byStatus('attended')->get()
     * para obtener registros con un estado particular.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status Estado de asistencia a filtrar
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        // Mapear estados lógicos a valores físicos 'si'/'no'
        $map = [
            self::STATUS_ATTENDED => 'si',
            self::STATUS_ABSENT => 'no',
        ];
        $value = $map[$status] ?? $status;
        return $query->where('asistencia', $value);
    }
    
    /**
     * Scope para filtrar registros activos (no cancelados)
     * 
     * Excluye registros cancelados de las consultas, útil para
     * estadísticas y reportes que solo deben considerar
     * registros válidos.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        // No existe estado cancelado; devolvemos el query sin cambios
        return $query;
    }
    
    /**
     * Scope para filtrar registros por fecha específica
     * 
     * Permite consultas como: AttendanceRegistration::byDate('2024-01-15')->get()
     * para obtener registros de una fecha particular.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $date Fecha en formato Y-m-d
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereHas('asistance', function ($q) use ($date) {
            $q->whereDate('date', $date);
        });
    }
    
    /**
     * Scope para filtrar registros por aprendiz específico
     * 
     * Permite consultas como: AttendanceRegistration::byApprentice(123)->get()
     * para obtener el historial de asistencia de un aprendiz particular.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $apprenticeId ID del aprendiz
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByApprentice($query, $apprenticeId)
    {
        return $query->where('apprentice_id', $apprenticeId);
    }

    /**
     * Scope para filtrar registros por rango de fechas
     * 
     * Permite consultas como: AttendanceRegistration::byDateRange('2024-01-01', '2024-01-31')->get()
     * para obtener registros de un período específico.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate Fecha de inicio en formato Y-m-d
     * @param string $endDate Fecha de fin en formato Y-m-d
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereHas('asistance', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        });
    }

    /**
     * Scope para filtrar registros por programa específico
     * 
     * Permite consultas como: AttendanceRegistration::byProgram(5)->get()
     * para obtener asistencias de aprendices de un programa particular.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $programId ID del programa
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProgram($query, $programId)
    {
        return $query->whereHas('apprentice.course', function($q) use ($programId) {
            $q->where('program_id', $programId);
        });
    }

    /**
     * Scope para filtrar registros por curso específico
     * 
     * Permite consultas como: AttendanceRegistration::byCourse(10)->get()
     * para obtener asistencias de aprendices de un curso particular.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $courseId ID del curso
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCourse($query, $courseId)
    {
        return $query->whereHas('apprentice', function($q) use ($courseId) {
            $q->where('course_id', $courseId);
        });
    }
    
    /**
     * Accessor para obtener la hora formateada de asistencia
     * 
     * Convierte automáticamente el campo attendance_time a un formato
     * legible (HH:MM) para mostrar en vistas y reportes.
     * 
     * @return string Hora formateada en formato HH:MM
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('H:i') : null;
    }
    
    /**
     * Accessor para obtener la fecha formateada de asistencia
     * 
     * Convierte automáticamente el campo attendance_date a un formato
     * legible (dd/mm/yyyy) para mostrar en vistas y reportes.
     * 
     * @return string Fecha formateada en formato dd/mm/yyyy
     */
    public function getFormattedDateAttribute()
    {
        $date = $this->asistance->date ?? null;
        return $date ? Carbon::parse($date)->format('d/m/Y') : null;
    }
    
    /**
     * Accessor para obtener el estado de asistencia con un label legible
     * 
     * Convierte el estado de asistencia (status) a un texto descriptivo
     * para mostrar en las vistas.
     * 
     * @return string Texto descriptivo del estado de asistencia
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'si' => 'Asistió',
            'no' => 'No asistió',
        ];
        return $labels[$this->asistencia] ?? $this->asistencia;
    }

    /**
     * Accessor para obtener el estado de asistencia con un badge (etiqueta)
     * 
     * Convierte el estado de asistencia (status) a una clase CSS para
     * mostrar en las vistas como un badge (etiqueta) de color.
     * 
     * @return string Clase CSS para el badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'si' => 'badge-info',
            'no' => 'badge-secondary',
        ];
        return $badges[$this->asistencia] ?? 'badge-secondary';
    }

    /**
     * Accessor para determinar si la asistencia es tardanza
     * 
     * Verifica si la hora de asistencia (attendance_time) es posterior
     * a la hora de inicio de la jornada (8:00 AM) para considerarla tardanza.
     * 
     * @return bool True si es tardanza, false en caso contrario
     */
    public function getIsLateAttribute()
    {
        // Sin manejo de tardanza en el nuevo esquema
        return false;
    }

    /**
     * Accessor para obtener la duración de la asistencia
     * 
     * Si el estado es 'attended', esta propiedad puede ser utilizada
     * para indicar la duración completa de la asistencia.
     * 
     * @return string|null Texto descriptivo de la duración o null
     */
    public function getAttendanceDurationAttribute()
    {
        return null;
    }
    
    /**
     * Validaciones para el registro de asistencias
     * 
     * Define las reglas de validación para asegurar que los datos
     * ingresados sean correctos antes de ser guardados.
     * 
     * @return array Reglas de validación
     */
    public static function rules()
    {
        return [
            'apprentice_id' => 'required|exists:apprentices,id',
            'asistance_id' => 'required|exists:asistances,id',
            'asistencia' => 'required|in:si,no',
        ];
    }

    /**
     * Métodos de negocio para la gestión de asistencias
     * 
     * Define lógica de negocio para determinar si un registro
     * de asistencia puede ser cancelado o modificado.
     * 
     * @return bool True si puede ser cancelado/modificado, false en caso contrario
     */
    public function canBeCancelled()
    {
        // Solo se puede cancelar registros del día actual
        return $this->attendance_date->isToday() && $this->status !== self::STATUS_CANCELLED;
    }

    public function canBeModified()
    {
        // Solo se puede modificar registros del día actual que no estén cancelados
        return $this->attendance_date->isToday() && $this->status !== self::STATUS_CANCELLED;
    }

    /**
     * Método para obtener el porcentaje de asistencia
     * 
     * Si el porcentaje está presente, lo devuelve como un string con el símbolo %.
     * Si no está presente, devuelve '-'.
     * 
     * @return string Porcentaje de asistencia o '-'
     */
    public function getAttendancePercentage()
    {
        if ($this->porcentaje) {
            return $this->porcentaje . '%';
        }
        return '-';
    }

    /**
     * Métodos estáticos para generar estadísticas de asistencia
     * 
     * Proporcionan métodos para obtener totales, porcentajes y
     * estadísticas de asistencia por fecha.
     * 
     * @param string|null $date Fecha para la cual obtener estadísticas (opcional)
     * @return array Estadísticas de asistencia
     */
    public static function getTodayStats()
    {
        $today = now()->toDateString();
        
        $base = self::whereHas('asistance', function ($q) use ($today) {
            $q->whereDate('date', $today);
        });
        return [
            'total' => (clone $base)->count(),
            'attended' => (clone $base)->where('asistencia', 'si')->count(),
            'absent' => (clone $base)->where('asistencia', 'no')->count(),
            'late' => 0,
            'cancelled' => 0,
            'registered' => 0,
        ];
    }

    public static function getAttendanceRate($date = null)
    {
        $date = $date ?: now()->toDateString();
        $query = self::whereHas('asistance', function ($q) use ($date) {
            $q->whereDate('date', $date);
        });
        $total = (clone $query)->count();
        $attended = (clone $query)->where('asistencia', 'si')->count();
        return $total > 0 ? round(($attended / $total) * 100, 1) : 0;
    }

    /**
     * Eventos del modelo para la auditoría y el cacheo
     * 
     * Define acciones que se ejecutan antes y después de la creación,
     * actualización y eliminación de registros.
     */
    protected static function boot()
    {
        parent::boot();
        // Sin acciones especiales; se mantiene método por compatibilidad
    }
}