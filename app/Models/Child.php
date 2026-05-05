<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'birth_date',
        'gender',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function growthRecords()
    {
        return $this->hasMany(GrowthRecord::class);
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

    public function getAgeAttribute(): string
    {
        $now = Carbon::now();
        $birth = $this->birth_date;
        $years = (int) $birth->diffInYears($now);
        $months = (int) $birth->copy()->addYears($years)->diffInMonths($now);

        if ($years > 0) {
            return $years . ' tahun ' . $months . ' bulan';
        }
        return $months . ' bulan';
    }

    public function latestGrowth()
    {
        return $this->hasOne(GrowthRecord::class)->latestOfMany('recorded_at');
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function getNutritionalStatusAttribute(): ?string
    {
        $latest = $this->latestGrowth;
        if (!$latest) return null;

        $heightM = $latest->height / 100;
        if ($heightM <= 0) return null;

        $bmi = $latest->weight / ($heightM * $heightM);
        $ageInMonths = $this->birth_date->diffInMonths(Carbon::now());

        // Simplified WHO standards for BMI-for-age
        if ($ageInMonths < 60) { // Under 5 years
            if ($bmi < 14) return 'Stunting';
            if ($bmi > 18) return 'Obesitas';
            return 'Normal';
        } else { // 5 years and above
            if ($bmi < 14) return 'Stunting';
            if ($bmi > 22) return 'Obesitas';
            return 'Normal';
        }
    }

    public function getBmiAttribute(): ?float
    {
        $latest = $this->latestGrowth;
        if (!$latest || $latest->height <= 0) return null;
        return round($latest->weight / (($latest->height / 100) ** 2), 1);
    }

    public function getLastCheckupAttribute(): ?string
    {
        $latest = $this->latestGrowth;
        if (!$latest) return null;
        return $latest->recorded_at->diffForHumans(null, true);
    }
}
