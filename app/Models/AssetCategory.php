<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    protected $fillable = ["code","name","is_active"];
    protected $casts = ["is_active"=>"boolean"];
    public function assets() { return $this->hasMany(Asset::class); }
}
