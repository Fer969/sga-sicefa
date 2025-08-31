<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;

class ConvocatoryEvent extends Model
{
    protected $table = 'convocatories_events';

    protected $fillable = [
        'convocatory_id',
        'name',
        'description',
        'number_lunchs',
        'lunchs_discount',
        'required_elements',
    ];

    public function convocatory()
    {
        return $this->belongsTo(Convocatory::class, 'convocatory_id');
    }
}
