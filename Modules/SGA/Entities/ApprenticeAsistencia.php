<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprenticeAsistencia extends Model
{
    protected $table = 'apprentice_asistencia';
    
    protected $fillable = [
        'apprentice_id',
        'asistance_id',
        // otros campos que tengas
    ];

    /**
     * Relación con el aprendiz
     */
    public function apprentice(): BelongsTo
    {
        return $this->belongsTo(Apprentice::class);
    }

    /**
     * Relación con la asistencia
     */
    public function asistance(): BelongsTo
    {
        return $this->belongsTo(Asistance::class);
    }
}