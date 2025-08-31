<?php

namespace Modules\SGA\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConvocatoryRequest extends FormRequest
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
            'nombre' => [
                'required',
                'string',
                'min:5',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-\.]+$/'
            ],
            'tipo_convocatoria' => [
                'required',
                'integer',
                'exists:types_convocatories,id',
                function ($attribute, $value, $fail) {
                    $tipo = DB::table('types_convocatories')->where('id', $value)->first();
                    if (!$tipo || $tipo->name !== 'Apoyo de Alimentación') {
                        $fail('Solo se permiten convocatorias de tipo "Apoyo de Alimentación".');
                    }
                }
            ],
            'trimestre' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4])
            ],
            'fecha_inicio' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'fecha_cierre' => [
                'required',
                'date',
                'after:fecha_inicio'
            ],
            'cupos' => [
                'required',
                'integer',
                'min:1',
                'max:1000'
            ],
            'año' => [
                'required',
                'integer',
                'min:' . (date('Y') - 1),
                'max:' . (date('Y') + 2)
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
            'nombre.required' => 'El nombre de la convocatoria es requerido.',
            'nombre.min' => 'El nombre debe tener al menos 5 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios, guiones y puntos.',
            'tipo_convocatoria.required' => 'El tipo de convocatoria es requerido.',
            'tipo_convocatoria.exists' => 'El tipo de convocatoria seleccionado no es válido.',
            'trimestre.required' => 'El trimestre es requerido.',
            'trimestre.in' => 'El trimestre seleccionado no es válido.',
            'fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser hoy o una fecha futura.',
            'fecha_cierre.required' => 'La fecha de cierre es requerida.',
            'fecha_cierre.after' => 'La fecha de cierre debe ser posterior a la fecha de inicio.',
            'cupos.required' => 'La cantidad de cupos es requerida.',
            'cupos.integer' => 'Los cupos deben ser un número entero.',
            'cupos.min' => 'Los cupos deben ser al menos 1.',
            'cupos.max' => 'Los cupos no pueden ser más de 1000.',
            'año.required' => 'El año es requerido.',
            'año.integer' => 'El año debe ser un número entero.',
            'año.min' => 'El año no puede ser anterior a ' . (date('Y') - 1) . '.',
            'año.max' => 'El año no puede ser posterior a ' . (date('Y') + 2) . '.'
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
            'nombre' => 'nombre de la convocatoria',
            'tipo_convocatoria' => 'tipo de convocatoria',
            'trimestre' => 'trimestre',
            'fecha_inicio' => 'fecha de inicio',
            'fecha_cierre' => 'fecha de cierre',
            'cupos' => 'cupos disponibles',
            'año' => 'año'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Limpiar espacios en blanco del nombre
        if ($this->has('nombre')) {
            $this->merge([
                'nombre' => trim($this->nombre)
            ]);
        }

        // Asegurar que las fechas estén en el formato correcto
        if ($this->has('fecha_inicio')) {
            $this->merge([
                'fecha_inicio' => Carbon::parse($this->fecha_inicio)->format('Y-m-d')
            ]);
        }

        if ($this->has('fecha_cierre')) {
            $this->merge([
                'fecha_cierre' => Carbon::parse($this->fecha_cierre)->format('Y-m-d')
            ]);
        }
    }
}
