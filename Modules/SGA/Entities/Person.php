<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    protected $fillable = [
        'first_name',
        'first_last_name',
        'second_last_name',
        'document_number',
    ];

    /**
     * Relación con los aprendices
     */
    public function apprentices(): HasMany
    {
        return $this->hasMany(Apprentice::class);
    }

    public function conditions()
    {
        return $this->hasOne(ApprenticeCondition::class, 'person_id');
    }

    public function socioeconomic()
    {
        return $this->hasOne(SocioeconomicInformation::class, 'person_id');
    }

    public function swornStatements()
    {
        return $this->hasMany(SwornStatement::class, 'person_id');
    }

    public function representativeLegal()
    {
        return $this->hasOne(RepresentativeLegal::class, 'person_id');
    }

    /**
     * Accessor para obtener el nombre completo
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->first_last_name . ' ' . $this->second_last_name);
    }

    /**
     * Accessor para compatibilidad con código existente
     */
    public function getNameAttribute()
    {
        return $this->getFullNameAttribute();
    }
}
