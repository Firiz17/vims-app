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
}
