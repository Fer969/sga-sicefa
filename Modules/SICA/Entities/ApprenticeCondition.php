<?php

namespace Modules\SICA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprenticeCondition extends Model
{
    use HasFactory;

    protected $table = 'apprentice_conditions';

    protected $fillable = [
        'person_id',
        'victim_conflict',
        'gender_violence_victim',
        'disability',
        'head_of_household',
        'pregnant_or_lactating',
        'ethnic_group_affiliation',
        'natural_displacement',
        'sisben_group_a',
        'sisben_group_b',
        'rural_apprentice',
        'institutional_representative',
        'lives_in_rural_area',
        'spokesperson_elected',
        'research_participation',
        'previous_boarding_quota',
        'has_certification',
        'attached_sworn_statement',
        'knows_obligations_support'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
