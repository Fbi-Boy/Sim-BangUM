<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ActivityLog extends Model
{
    protected $fillable = ["actor_user_id","action","subject_type","subject_id","old_values","new_values","description","ip_address","user_agent"];
    public $timestamps = false;
    protected $casts = ["old_values"=>"array","new_values"=>"array","created_at"=>"datetime"];
    public function actor() { return $this->belongsTo(User::class, "actor_user_id"); }
}
