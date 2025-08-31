<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asistance extends Model
{
    protected $fillable = [
        'date',
        'type_asistance',
        // otros campos que tengas
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relación con las asistencias de aprendices
     */
    public function apprenticeAsistencias(): HasMany
    {
        return $this->hasMany(ApprenticeAsistencia::class);
    }

    /**
     * Scope para convocatorias de alimentación
     */
    public function scopeConvocatoriasAlimentacion($query)
    {
        return $query->where('type_asistance', 'Convocatorias de Alimentación');
    }
}