<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRequest extends Model
{
    protected $fillable = ["request_number","requester_user_id","vehicle_id","purpose","destination","starts_at","ends_at","passenger_count","status","rejection_reason"];
    protected $casts = ["starts_at"=>"datetime","ends_at"=>"datetime"];
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function requester() { return $this->belongsTo(User::class, "requester_user_id"); }
}
