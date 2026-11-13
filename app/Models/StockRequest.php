<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StockRequest extends Model {
    protected $fillable = ["request_number","requester_user_id","purpose","needed_at","status","approved_by","approved_at","rejection_reason"];
    protected $casts = ["needed_at"=>"datetime","approved_at"=>"datetime"];
    public function requester() { return $this->belongsTo(User::class, "requester_user_id"); }
    public function items() { return $this->hasMany(StockRequestItem::class); }
}
