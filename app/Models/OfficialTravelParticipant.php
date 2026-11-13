<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OfficialTravelParticipant extends Model {
    protected $fillable = ["official_travel_request_id","participant_name","participant_role"];
    public function request() { return $this->belongsTo(OfficialTravelRequest::class, "official_travel_request_id"); }
}
