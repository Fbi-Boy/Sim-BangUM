<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VehicleAssignment extends Model
{
    protected $fillable = ["vehicle_request_id","vehicle_id","driver_user_id","assigned_by_user_id","planned_start_at","planned_end_at","actual_start_at","actual_end_at","odometer_start","odometer_end","return_notes","status"];
    protected $casts = ["planned_start_at"=>"datetime","planned_end_at"=>"datetime","actual_start_at"=>"datetime","actual_end_at"=>"datetime"];
    public function request() { return $this->belongsTo(VehicleRequest::class, "vehicle_request_id"); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function driver() { return $this->belongsTo(User::class, "driver_user_id"); }
    public function assigner() { return $this->belongsTo(User::class, "assigned_by_user_id"); }
    public function logs() { return $this->hasMany(VehicleUsageLog::class); }
}
