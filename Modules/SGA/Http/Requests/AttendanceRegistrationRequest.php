<?php

namespace Modules\SGA\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'documentNumber' => [
                'required',
                'string',
                'min:8',
                'max:10',
                'regex:/^\d+$/'
            ],
            'status' => [
                'nullable',
                'string',
                Rule::in(['asistio', 'no_asistio', 'tardanza', 'registered'])
            ],
            'porcentaje' => [
                'nullable',
                'integer',
                'min:0',
                'max:100'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'documentNumber.required' => 'El número de documento es requerido.',
            'documentNumber.min' => 'El número de documento debe tener al menos 8 dígitos.',
            'documentNumber.max' => 'El número de documento no puede tener más de 10 dígitos.',
            'documentNumber.regex' => 'El número de documento solo puede contener números.',
            'status.in' => 'El estado de asistencia no es válido.',
            'porcentaje.integer' => 'El porcentaje debe ser un número entero.',
            'porcentaje.min' => 'El porcentaje no puede ser menor a 0.',
            'porcentaje.max' => 'El porcentaje no puede ser mayor a 100.',
            'notes.max' => 'Las notas no pueden tener más de 500 caracteres.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'documentNumber' => 'número de documento',
            'status' => 'estado de asistencia',
            'porcentaje' => 'porcentaje',
            'notes' => 'notas'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Limpiar espacios en blanco del número de documento
        if ($this->has('documentNumber')) {
            $this->merge([
                'documentNumber' => trim($this->documentNumber)
            ]);
        }
    }
} 