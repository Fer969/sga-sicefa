<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SICA\Entities\Person;

class CallsApplication extends Model
{
    use HasFactory;

    protected $table = 'calls_applications';

    protected $fillable = [
        'convocatory_selected',
        'person_id',
        'renta_joven_beneficiary_points',
        'has_apprenticeship_contract_points',
        'received_fic_support_points',
        'received_regular_support_points',
        'has_income_contract_points',
        'has_sponsored_practice_points',
        'receives_food_support_points',
        'receives_transport_support_points',
        'receives_tech_support_points',
        'victim_conflict_points',
        'gender_violence_victim_points',
        'disability_points',
        'head_of_household_points',
        'pregnant_or_lactating_points',
        'ethnic_group_affiliation_points',
        'natural_displacement_points',
        'sisben_group_a_points',
        'sisben_group_b_points',
        'rural_apprentice_points',
        'institutional_representative_points',
        'lives_in_rural_area_points',
        'spokesperson_elected_points',
        'research_participation_points',
        'previous_boarding_quota_points',
        'has_certification_points',
        'attached_sworn_statement_points',
        'knows_obligations_support_points',
        'total_points'
    ];

    protected $casts = [
        'total_points' => 'integer',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function convocatory()
    {
        return $this->belongsTo(Convocatory::class, 'convocatory_selected');
    }
}
