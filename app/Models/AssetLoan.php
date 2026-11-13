<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetLoan extends Model
{
    protected $fillable = ["loan_number","asset_id","requester_user_id","starts_at","expected_return_at","purpose","status","returned_at"];
    protected $casts = ["starts_at"=>"datetime","expected_return_at"=>"datetime","returned_at"=>"datetime"];
    public function asset() { return $this->belongsTo(Asset::class); }
    public function requester() { return $this->belongsTo(User::class, "requester_user_id"); }
}
