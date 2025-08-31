<?php

namespace Modules\SGA\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'incident_id',
        'user_id',
        'comment',
        'is_internal',
        'is_resolution_note'
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'is_resolution_note' => 'boolean'
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getShortCommentAttribute()
    {
        return \Str::limit($this->comment, 100);
    }
} 