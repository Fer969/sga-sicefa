<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;

class TypeConvocatory extends Model
{
    protected $table = 'types_convocatories';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function convocatories()
    {
        return $this->hasMany(Convocatory::class, 'types_convocatories_id');
    }
}
