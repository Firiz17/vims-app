<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    // Allow name and capacity to be saved
    protected $fillable = ['name', 'total_capacity','allowed_capacity'];

    // A location has many vehicles parked in it
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
