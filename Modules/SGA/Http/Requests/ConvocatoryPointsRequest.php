<?php

namespace Modules\SGA\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ConvocatoryPointsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'convocatory_selected' => [
                'required',
                'integer',
                'exists:convocatories,id',
                function ($attribute, $value, $fail) {
                    // Verificar que la convocatoria sea de tipo "Apoyo de Alimentación"
                    $convocatoria = DB::table('convocatories as c')
                        ->join('types_convocatories as tc', 'c.types_convocatories_id', '=', 'tc.id')
                        ->where('c.id', $value)
                        ->where('tc.name', 'Apoyo de Alimentación')
                        ->whereNull('c.deleted_at')
                        ->first();
                    
                    if (!$convocatoria) {
                        $fail('La convocatoria seleccionada no es válida o no es de tipo "Apoyo de Alimentación".');
                    }
                }
            ],
            'victim_conflict_score' => 'nullable|integer|min:0|max:100',
            'gender_violence_victim_score' => 'nullable|integer|min:0|max:100',
            'disability_score' => 'nullable|integer|min:0|max:100',
            'head_of_household_score' => 'nullable|integer|min:0|max:100',
            'pregnant_or_lactating_score' => 'nullable|integer|min:0|max:100',
            'ethnic_group_affiliation_score' => 'nullable|integer|min:0|max:100',
            'natural_displacement_score' => 'nullable|integer|min:0|max:100',
            'sisben_group_a_score' => 'nullable|integer|min:0|max:100',
            'sisben_group_b_score' => 'nullable|integer|min:0|max:100',
            'rural_apprentice_score' => 'nullable|integer|min:0|max:100',
            'institutional_representative_score' => 'nullable|integer|min:0|max:100',
            'lives_in_rural_area_score' => 'nullable|integer|min:0|max:100',
            'spokesperson_elected_score' => 'nullable|integer|min:0|max:100',
            'research_participation_score' => 'nullable|integer|min:0|max:100',
            'previous_boarding_quota_score' => 'nullable|integer|min:0|max:100',
            'has_certification_score' => 'nullable|integer|min:0|max:100',
            'attached_sworn_statement_score' => 'nullable|integer|min:0|max:100',
            'knows_obligations_support_score' => 'nullable|integer|min:0|max:100',
            'renta_joven_beneficiary_score' => 'nullable|integer|min:0|max:100',
            'has_apprenticeship_contract_score' => 'nullable|integer|min:0|max:100',
            'received_fic_support_score' => 'nullable|integer|min:0|max:100',
            'received_regular_support_score' => 'nullable|integer|min:0|max:100',
            'has_income_contract_score' => 'nullable|integer|min:0|max:100',
            'has_sponsored_practice_score' => 'nullable|integer|min:0|max:100',
            'receives_food_support_score' => 'nullable|integer|min:0|max:100',
            'receives_transport_support_score' => 'nullable|integer|min:0|max:100',
            'receives_tech_support_score' => 'nullable|integer|min:0|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'convocatory_selected.required' => 'Debe seleccionar una convocatoria.',
            'convocatory_selected.exists' => 'La convocatoria seleccionada no existe.',
            '*.score.integer' => 'El puntaje debe ser un número entero.',
            '*.score.min' => 'El puntaje no puede ser menor a 0.',
            '*.score.max' => 'El puntaje no puede ser mayor a 100.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'convocatory_selected' => 'convocatoria',
            'victim_conflict_score' => 'víctima del conflicto',
            'gender_violence_victim_score' => 'víctima de violencia de género',
            'disability_score' => 'discapacidad',
            'head_of_household_score' => 'jefe de hogar',
            'pregnant_or_lactating_score' => 'embarazada o lactante',
            'ethnic_group_affiliation_score' => 'pertenencia a grupo étnico',
            'natural_displacement_score' => 'desplazamiento natural',
            'sisben_group_a_score' => 'grupo A del SISBEN',
            'sisben_group_b_score' => 'grupo B del SISBEN',
            'rural_apprentice_score' => 'aprendiz rural',
            'institutional_representative_score' => 'representante institucional',
            'lives_in_rural_area_score' => 'vive en zona rural',
            'spokesperson_elected_score' => 'vocero electo',
            'research_participation_score' => 'participación en investigación',
            'previous_boarding_quota_score' => 'cuota de internado anterior',
            'has_certification_score' => 'tiene certificación',
            'attached_sworn_statement_score' => 'declaración jurada adjunta',
            'knows_obligations_support_score' => 'conoce obligaciones del apoyo',
            'renta_joven_beneficiary_score' => 'beneficiario de renta joven',
            'has_apprenticeship_contract_score' => 'tiene contrato de aprendizaje',
            'received_fic_support_score' => 'recibió apoyo FIC',
            'received_regular_support_score' => 'recibió apoyo regular',
            'has_income_contract_score' => 'tiene contrato de ingresos',
            'has_sponsored_practice_score' => 'tiene práctica patrocinada',
            'receives_food_support_score' => 'recibe apoyo alimentario',
            'receives_transport_support_score' => 'recibe apoyo de transporte',
            'receives_tech_support_score' => 'recibe apoyo tecnológico',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convertir campos vacíos a null para puntajes
        $data = $this->all();
        
        foreach ($data as $key => $value) {
            if (str_ends_with($key, '_score') && ($value === '' || $value === null)) {
                $data[$key] = null;
            }
        }
        
        $this->replace($data);
    }
}
