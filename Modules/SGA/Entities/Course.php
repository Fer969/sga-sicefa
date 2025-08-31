<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    protected $fillable = [
        'code',
        'status',
        'deschooling',
    ];

    /**
     * Relación con los aprendices
     */
    public function apprentices(): HasMany
    {
        return $this->hasMany(Apprentice::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Scope para cursos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Activo');
    }

    /**
     * Scope para cursos presenciales
     */
    public function scopePresencial($query)
    {
        return $query->where('deschooling', 'Presencial');
    }
}
