<?php

namespace Modules\SGA\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalEventRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:150',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-\.\,\&\()]+$/'
            ],
            'number_lunchs' => [
                'required',
                'integer',
                'min:0',
                'max:999'
            ],
            'lunchs_discount' => [
                'required',
                'integer',
                'min:0',
                'max:999'
            ],
            'description' => [
                'nullable',
                'string',
                'max:250'
            ],
            'required_elements' => [
                'nullable',
                'string',
                'max:250'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del evento es obligatorio.',
            'name.min' => 'El nombre del evento debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre del evento no puede exceder 150 caracteres.',
            'name.regex' => 'El nombre del evento solo puede contener letras, espacios, guiones, puntos, comas, ampersand y paréntesis.',
            'number_lunchs.required' => 'El número de almuerzos es obligatorio.',
            'number_lunchs.integer' => 'El número de almuerzos debe ser un número entero.',
            'number_lunchs.min' => 'El número de almuerzos no puede ser menor a 0.',
            'number_lunchs.max' => 'El número de almuerzos no puede exceder 999.',
            'lunchs_discount.required' => 'El descuento de almuerzos es obligatorio.',
            'lunchs_discount.integer' => 'El descuento de almuerzos debe ser un número entero.',
            'lunchs_discount.min' => 'El descuento de almuerzos no puede ser menor a 0.',
            'lunchs_discount.max' => 'El descuento de almuerzos no puede exceder 999.',
            'description.max' => 'La descripción no puede exceder 250 caracteres.',
            'required_elements.max' => 'Los elementos requeridos no pueden exceder 250 caracteres.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre del evento',
            'number_lunchs' => 'número de almuerzos',
            'lunchs_discount' => 'descuento de almuerzos',
            'description' => 'descripción',
            'required_elements' => 'elementos requeridos'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convertir campos vacíos a null para campos opcionales
        $data = $this->all();
        
        if (empty($data['description'])) {
            $data['description'] = null;
        }
        
        if (empty($data['required_elements'])) {
            $data['required_elements'] = null;
        }
        
        $this->replace($data);
    }
}
