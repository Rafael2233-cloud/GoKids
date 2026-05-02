<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'vaccine_name',
        'scheduled_date',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
