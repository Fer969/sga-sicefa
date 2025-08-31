<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocioeconomicInformation extends Model
{
    use HasFactory;

    protected $table = 'socioeconomic_informations';

    protected $fillable = [
        'person_id',
        'renta_joven_beneficiary',
        'has_apprenticeship_contract',
        'received_fic_support',
        'received_regular_support',
        'has_income_contract',
        'has_sponsored_practice',
        'receives_food_support',
        'receives_transport_support',
        'receives_tech_support',
        'status'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
