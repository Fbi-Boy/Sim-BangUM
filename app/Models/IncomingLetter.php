<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    protected $fillable = [
        "register_year","agenda_sequence","external_letter_number","letter_date",
        "received_at","sender_name","sender_organization","subject","summary",
        "confidentiality","urgency","received_channel","recipient_unit_id",
        "registered_by_user_id","status",
    ];
    protected $casts = [
        "letter_date" => "date",
        "received_at" => "datetime",
    ];
    public function recipientUnit() { return $this->belongsTo(OrganizationalUnit::class, "recipient_unit_id"); }
    public function registeredBy() { return $this->belongsTo(User::class, "registered_by_user_id"); }
    public function documents() { return $this->morphMany(Document::class, "documentable"); }
    public function dispositions() { return $this->hasMany(LetterDisposition::class); }
}
