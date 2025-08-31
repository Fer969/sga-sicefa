<?php

namespace Modules\SICA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocioeconomicInformation extends Model
{
    use HasFactory;

    // 👇 Indicar nombre real de la tabla
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

    // Relación: un registro socioeconómico pertenece a una persona
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
