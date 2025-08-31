<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = [
        'sofia_code',
        'version',
        'training_type',
        'name',
        'quarter_number',
        'knowledge_network_id',
        'program_type',
        'maximum_duration',
        'modality',
        'priority_bets',
        'fic',
        'months_lectiva',
        'months_productiva',
    ];

    // Un programa tiene muchos cursos
    public function courses()
    {
        return $this->hasMany(Course::class, 'program_id');
    }
}
