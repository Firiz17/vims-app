<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'total_capacity', 'current_count'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
