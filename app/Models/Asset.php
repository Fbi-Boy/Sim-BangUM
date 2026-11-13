<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = ["asset_code","asset_category_id","name","brand","condition","status","qr_code_value","notes","created_by_user_id"];
    public function category() { return $this->belongsTo(AssetCategory::class, "asset_category_id"); }
    public function loans() { return $this->hasMany(AssetLoan::class); }
    public function bmd() { return $this->hasOne(BmdAsset::class); }
    public function activeLoan() { return $this->hasOne(AssetLoan::class)->where("status", "issued"); }
}
