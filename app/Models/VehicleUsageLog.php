<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VehicleUsageLog extends Model
{
    protected $fillable = ["vehicle_assignment_id","recorded_by_user_id","event_type","happened_at","odometer","note"];
    protected $casts = ["happened_at"=>"datetime"];
    public function assignment() { return $this->belongsTo(VehicleAssignment::class, "vehicle_assignment_id"); }
    public function recorder() { return $this->belongsTo(User::class, "recorded_by_user_id"); }
}
