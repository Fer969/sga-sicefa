<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\SGA\Entities\Person;
use Modules\SGA\Entities\Course;
use Modules\SGA\Entities\Asistance;
use Illuminate\Database\Eloquent\Factories\HasFactory;      

class Apprentice extends Model
{
    protected $fillable = [
        'person_id',
        'course_id',
    ];

    /**
     * Relación con la tabla Person
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relación con la tabla Course
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relación con las asistencias del aprendiz
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(ApprenticeAsistencia::class);
    }
}
