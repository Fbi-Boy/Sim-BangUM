<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Event extends Model {
    protected $fillable = ["event_number","title","event_type","coordinator_user_id","starts_at","ends_at","location","description","status"];
    protected $casts = ["starts_at"=>"datetime","ends_at"=>"datetime"];
    public function coordinator() { return $this->belongsTo(User::class, "coordinator_user_id"); }
    public function agendas() { return $this->hasMany(EventAgenda::class)->orderBy("sequence_number"); }
    public function tasks() { return $this->hasMany(EventTask::class); }
}
