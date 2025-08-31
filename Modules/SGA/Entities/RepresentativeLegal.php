<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepresentativeLegal extends Model
{
    protected $table = 'representative_legal';

    protected $fillable = [
        'person_id',
        'document_type',
        'document_number',
        'name',
        'relationship',
        'telephone1',
        'telephone2',
        'email',
        'address'
    ];

    /**
     * Relación con la persona
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(\Modules\SICA\Entities\Person::class, 'person_id');
    }

    /**
     * Tipos de documento disponibles
     */
    public static function getDocumentTypes(): array
    {
        return [
            'CC' => 'Cédula de Ciudadanía',
            'CE' => 'Cédula de Extranjería',
            'TI' => 'Tarjeta de Identidad',
            'NIT' => 'NIT',
            'PP' => 'Pasaporte',
            'RC' => 'Registro Civil'
        ];
    }

    /**
     * Tipos de relación disponibles
     */
    public static function getRelationships(): array
    {
        return [
            'Padre' => 'Padre',
            'Madre' => 'Madre',
            'Tutor' => 'Tutor',
            'Hermano' => 'Hermano',
            'Hermana' => 'Hermana',
            'Abuelo' => 'Abuelo',
            'Abuela' => 'Abuela',
            'Tío' => 'Tío',
            'Tía' => 'Tía',
            'Otro' => 'Otro'
        ];
    }
}