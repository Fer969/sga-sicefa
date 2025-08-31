<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConvocatoryPoints extends Model
{
    use HasFactory;

    protected $table = 'convocatories_points';

    protected $fillable = [
        'convocatory_selected',
        'victim_conflict_score',
        'gender_violence_victim_score',
        'disability_score',
        'head_of_household_score',
        'pregnant_or_lactating_score',
        'ethnic_group_affiliation_score',
        'natural_displacement_score',
        'sisben_group_a_score',
        'sisben_group_b_score',
        'rural_apprentice_score',
        'institutional_representative_score',
        'lives_in_rural_area_score',
        'spokesperson_elected_score',
        'research_participation_score',
        'previous_boarding_quota_score',
        'has_certification_score',
        'attached_sworn_statement_score',
        'knows_obligations_support_score',
        'renta_joven_beneficiary_score',
        'has_apprenticeship_contract_score',
        'received_fic_support_score',
        'received_regular_support_score',
        'has_income_contract_score',
        'has_sponsored_practice_score',
        'receives_food_support_score',
        'receives_transport_support_score',
        'receives_tech_support_score'
    ];

    protected $casts = [
        'victim_conflict_score' => 'integer',
        'gender_violence_victim_score' => 'integer',
        'disability_score' => 'integer',
        'head_of_household_score' => 'integer',
        'pregnant_or_lactating_score' => 'integer',
        'ethnic_group_affiliation_score' => 'integer',
        'natural_displacement_score' => 'integer',
        'sisben_group_a_score' => 'integer',
        'sisben_group_b_score' => 'integer',
        'rural_apprentice_score' => 'integer',
        'institutional_representative_score' => 'integer',
        'lives_in_rural_area_score' => 'integer',
        'spokesperson_elected_score' => 'integer',
        'research_participation_score' => 'integer',
        'previous_boarding_quota_score' => 'integer',
        'has_certification_score' => 'integer',
        'attached_sworn_statement_score' => 'integer',
        'knows_obligations_support_score' => 'integer',
        'renta_joven_beneficiary_score' => 'integer',
        'has_apprenticeship_contract_score' => 'integer',
        'received_fic_support_score' => 'integer',
        'received_regular_support_score' => 'integer',
        'has_income_contract_score' => 'integer',
        'has_sponsored_practice_score' => 'integer',
        'receives_food_support_score' => 'integer',
        'receives_transport_support_score' => 'integer',
        'receives_tech_support_score' => 'integer'
    ];

    public function convocatory()
    {
        return $this->belongsTo(Convocatory::class, 'convocatory_selected');
    }
}
