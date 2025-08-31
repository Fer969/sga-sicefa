<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Incident extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'category',
        'user_id',
        'assigned_to',
        'resolution_notes',
        'resolved_at',
        'estimated_resolution_date',
        'tags',
        'location',
        'contact_info',
        'attachments',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'estimated_resolution_date' => 'date',
        'tags' => 'array',
        'attachments' => 'array',
    ];

    // Constantes para estados
    const STATUS_OPEN = 'Abierta';
    const STATUS_IN_PROGRESS = 'En Progreso';
    const STATUS_PENDING = 'Pendiente';
    const STATUS_RESOLVED = 'Resuelta';
    const STATUS_CLOSED = 'Cerrada';
    const STATUS_CANCELLED = 'Cancelada';

    // Constantes para prioridades
    const PRIORITY_LOW = 'Baja';
    const PRIORITY_MEDIUM = 'Media';
    const PRIORITY_HIGH = 'Alta';
    const PRIORITY_CRITICAL = 'Crítica';

    // Constantes para categorías
    const CATEGORY_SYSTEM = 'Sistema';
    const CATEGORY_ATTENDANCE = 'Asistencia';
    const CATEGORY_USER_ACCOUNT = 'Cuenta de Usuario';
    const CATEGORY_REPORT = 'Reporte';
    const CATEGORY_TECHNICAL = 'Técnica';
    const CATEGORY_OTHER = 'Otro';

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(IncidentComment::class);
    }

    public function activities()
    {
        return $this->hasMany(IncidentActivity::class);
    }

    // Scopes para consultas comunes
    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('estimated_resolution_date', '<', now())
                    ->whereNotIn('status', [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    // Accessors y Mutators
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_OPEN => 'badge-warning',
            self::STATUS_IN_PROGRESS => 'badge-info',
            self::STATUS_PENDING => 'badge-secondary',
            self::STATUS_RESOLVED => 'badge-success',
            self::STATUS_CLOSED => 'badge-dark',
            self::STATUS_CANCELLED => 'badge-danger'
        ];
        
        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getPriorityBadgeAttribute()
    {
        $badges = [
            self::PRIORITY_LOW => 'badge-success',
            self::PRIORITY_MEDIUM => 'badge-warning',
            self::PRIORITY_HIGH => 'badge-danger',
            self::PRIORITY_CRITICAL => 'badge-dark'
        ];
        
        return $badges[$this->priority] ?? 'badge-secondary';
    }

    public function getIsOverdueAttribute()
    {
        return $this->estimated_resolution_date && 
               $this->estimated_resolution_date->isPast() && 
               !in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    public function getDaysOpenAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    public function getResolutionTimeAttribute()
    {
        if ($this->resolved_at) {
            return $this->created_at->diffInHours($this->resolved_at);
        }
        return null;
    }

    // Métodos de negocio
    public function canBeAssigned()
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_PENDING]);
    }

    public function canBeResolved()
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_IN_PROGRESS, self::STATUS_PENDING]);
    }

    public function canBeClosed()
    {
        return in_array($this->status, [self::STATUS_RESOLVED]);
    }

    public function assignTo($userId)
    {
        $this->update([
            'assigned_to' => $userId,
            'status' => self::STATUS_IN_PROGRESS
        ]);

        $this->activities()->create([
            'action' => 'Asignada',
            'description' => 'Incidencia asignada a usuario ID: ' . $userId,
            'user_id' => auth()->id()
        ]);
    }

    public function resolve($resolutionNotes = null)
    {
        $this->update([
            'status' => self::STATUS_RESOLVED,
            'resolved_at' => now(),
            'resolution_notes' => $resolutionNotes
        ]);

        $this->activities()->create([
            'action' => 'Resuelta',
            'description' => 'Incidencia marcada como resuelta',
            'user_id' => auth()->id()
        ]);
    }

    public function close()
    {
        $this->update([
            'status' => self::STATUS_CLOSED
        ]);

        $this->activities()->create([
            'action' => 'Cerrada',
            'description' => 'Incidencia cerrada',
            'user_id' => auth()->id()
        ]);
    }

    // Métodos estáticos para estadísticas
    public static function getStats()
    {
        return [
            'total' => self::count(),
            'open' => self::where('status', self::STATUS_OPEN)->count(),
            'in_progress' => self::where('status', self::STATUS_IN_PROGRESS)->count(),
            'resolved' => self::where('status', self::STATUS_RESOLVED)->count(),
            'closed' => self::where('status', self::STATUS_CLOSED)->count(),
            'overdue' => self::overdue()->count(),
            'avg_resolution_time' => self::whereNotNull('resolved_at')
                ->avg(\DB::raw('TIMESTAMPDIFF(HOUR, created_at, resolved_at)'))
        ];
    }

    public static function getPriorityStats()
    {
        return [
            'critical' => self::where('priority', self::PRIORITY_CRITICAL)->count(),
            'high' => self::where('priority', self::PRIORITY_HIGH)->count(),
            'medium' => self::where('priority', self::PRIORITY_MEDIUM)->count(),
            'low' => self::where('priority', self::PRIORITY_LOW)->count(),
        ];
    }

    public static function getCategoryStats()
    {
        return self::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category')
            ->toArray();
    }

    // Eventos del modelo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($incident) {
            if (!$incident->status) {
                $incident->status = self::STATUS_OPEN;
            }
            if (!$incident->priority) {
                $incident->priority = self::PRIORITY_MEDIUM;
            }
            if (!$incident->category) {
                $incident->category = self::CATEGORY_OTHER;
            }
        });

        static::created(function ($incident) {
            $incident->activities()->create([
                'action' => 'Creada',
                'description' => 'Incidencia creada',
                'user_id' => auth()->id()
            ]);
        });
    }
} 