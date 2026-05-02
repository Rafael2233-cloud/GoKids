<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'weight',
        'height',
        'head_circumference',
        'recorded_at',
        'notes',
    ];

    protected $casts = [
        'recorded_at' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'head_circumference' => 'decimal:2',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Calculate nutritional status based on simplified WHO standards
     * This is a simplified version - real implementation would use WHO z-score tables
     */
    public function getNutritionalStatusAttribute(): string
    {
        $child = $this->child;
        if (!$child) return 'Unknown';

        $ageInMonths = $child->birth_date->diffInMonths(now());
        $bmi = $this->height > 0 ? ($this->weight / (($this->height / 100) ** 2)) : 0;

        // Simplified BMI-for-age assessment
        if ($ageInMonths <= 60) { // Under 5 years
            if ($bmi < 14) return 'Buruk';
            if ($bmi < 16) return 'Kurang';
            return 'Baik';
        }

        if ($bmi < 15) return 'Buruk';
        if ($bmi < 17) return 'Kurang';
        return 'Baik';
    }
}
