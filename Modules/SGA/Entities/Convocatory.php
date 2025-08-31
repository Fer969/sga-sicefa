<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Convocatory extends Model
{
    use SoftDeletes;

    protected $table = 'convocatories';

    protected $fillable = [
        'types_convocatories_id',
        'name',
        'quarter',
        'status',
        'coups',
        'year',
        'registration_start_date',
        'registration_deadline',
    ];

    protected $dates = ['registration_start_date', 'registration_deadline'];

    // Relaciones
    public function type()
    {
        return $this->belongsTo(TypeConvocatory::class, 'types_convocatories_id');
    }

    public function points()
    {
        return $this->hasMany(ConvocatoryPoints::class, 'convocatory_selected');
    }

    public function events()
    {
        return $this->hasMany(ConvocatoryEvent::class, 'convocatory_id');
    }
}
