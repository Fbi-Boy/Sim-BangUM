<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LetterDisposition extends Model
{
    protected $fillable = [
        "incoming_letter_id","from_user_id","to_user_id","to_unit_id",
        "instruction","priority","due_at","status",
    ];
    protected $casts = ["due_at"=>"datetime"];

    public function incomingLetter() { return $this->belongsTo(IncomingLetter::class); }
    public function fromUser() { return $this->belongsTo(User::class, "from_user_id"); }
    public function toUser() { return $this->belongsTo(User::class, "to_user_id"); }
    public function toUnit() { return $this->belongsTo(OrganizationalUnit::class, "to_unit_id"); }
    public function updates() { return $this->hasMany(LetterDispositionUpdate::class)->latest(); }
}
