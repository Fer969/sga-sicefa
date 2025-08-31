<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwornStatement extends Model
{
    use HasFactory;

    protected $table = 'sworn_statement';

    protected $fillable = [
        'person_id',
        'responsible_name',
        'responsible_phone',
        'responsible_document',
        'live_with',
        'status',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
