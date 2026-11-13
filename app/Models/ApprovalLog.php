<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ApprovalLog extends Model
{
    protected $fillable = ["subject_type","subject_id","approver_user_id","decision","note"];
    public function approver() { return $this->belongsTo(User::class, "approver_user_id"); }
    public function subject() { return $this->morphTo(); }
}
