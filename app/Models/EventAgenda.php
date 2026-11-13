<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EventAgenda extends Model {
    protected $fillable = ["event_id","sequence_number","agenda_title","starts_at","ends_at","person_in_charge","notes"];
    protected $casts = ["starts_at"=>"datetime","ends_at"=>"datetime"];
    public function event() { return $this->belongsTo(Event::class); }
}
