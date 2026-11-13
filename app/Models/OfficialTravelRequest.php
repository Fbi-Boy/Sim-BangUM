<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficialTravelRequest extends Model {
    protected $fillable = ["request_number","requester_user_id","purpose","destination","departure_at","return_at","transport_mode","status","approved_by","approved_at","rejection_reason"];
    protected $casts = ["departure_at"=>"datetime","return_at"=>"datetime","approved_at"=>"datetime"];
    public function requester() { return $this->belongsTo(User::class, "requester_user_id"); }
    public function approver() { return $this->belongsTo(User::class, "approved_by"); }
    public function participants() { return $this->hasMany(OfficialTravelParticipant::class); }
}
