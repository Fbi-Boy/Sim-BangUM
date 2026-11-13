<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    protected $fillable = ["booking_number","room_id","requester_user_id","purpose","attendee_count","starts_at","ends_at","status","rejection_reason"];
    protected $casts = ["starts_at"=>"datetime","ends_at"=>"datetime"];
    public function room() { return $this->belongsTo(Room::class); }
    public function requester() { return $this->belongsTo(User::class, "requester_user_id"); }
}
