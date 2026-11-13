<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutgoingLetter extends Model
{
    protected $fillable = [
        "register_year","agenda_sequence","official_letter_number","letter_date",
        "subject","body_summary","creating_unit_id","created_by_user_id","sent_at","status",
    ];
    protected $casts = ["letter_date"=>"date","sent_at"=>"datetime"];
    public function createdBy() { return $this->belongsTo(User::class, "created_by_user_id"); }
}
