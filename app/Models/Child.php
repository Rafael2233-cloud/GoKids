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
}
