<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;

class IncidentActivity extends Model
{
    protected $fillable = [
        'incident_id',
        'user_id',
        'action',
        'description',
        'old_values',
        'new_values'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    public function getActionBadgeAttribute()
    {
        $badges = [
            'Creada' => 'badge-success',
            'Asignada' => 'badge-info',
            'En Progreso' => 'badge-warning',
            'Resuelta' => 'badge-success',
            'Cerrada' => 'badge-dark',
            'Comentario' => 'badge-secondary',
            'Actualizada' => 'badge-primary'
        ];
        
        return $badges[$this->action] ?? 'badge-secondary';
    }
} 