<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdiInspection extends Model
{
    protected $fillable = [
        'tyre', 'oil_level', 'coolant', 'interior_cleanliness',
        'tinted_window', 'dashcam', 'inspection_date',
        'result', 'vehicle_id', 'user_id'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
