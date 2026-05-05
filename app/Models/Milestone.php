<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'category',
        'milestone_name',
        'description',
        'is_achieved',
        'achieved_at',
        'notes',
    ];

    protected $casts = [
        'is_achieved' => 'boolean',
        'achieved_at' => 'date',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
