<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ["license_plate","make","model","manufacture_year","passenger_capacity","status"];
    public function requests() { return $this->hasMany(VehicleRequest::class); }
}
