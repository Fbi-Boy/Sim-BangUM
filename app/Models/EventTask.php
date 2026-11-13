<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EventTask extends Model {
    protected $fillable = ["event_id","task_title","description","assigned_to_user_id","due_at","priority","status","completed_at"];
    protected $casts = ["due_at"=>"datetime","completed_at"=>"datetime"];
    public function event() { return $this->belongsTo(Event::class); }
    public function assignee() { return $this->belongsTo(User::class, "assigned_to_user_id"); }
}
