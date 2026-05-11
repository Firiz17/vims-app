<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'vin', 'model', 'principal', 'status',
        'arrival_date', 'delivered_date', 'location_id'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(VehicleStatusLog::class);
    }

    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    public function pdiInspections()
    {
        return $this->hasMany(PdiInspection::class);
    }
    public function getDaysInYardAttribute()
    {
        // Safety check: if there is no creation date, return 0
        if (!$this->created_at) {
            return 0;
        }

        // 1. startOfDay() resets the clock to 00:00:00 so it counts strict calendar days
        // 2. (int) forces the final result to be a clean whole number, killing all decimals
        return (int) $this->created_at->startOfDay()->diffInDays(now()->startOfDay());
    }
}
