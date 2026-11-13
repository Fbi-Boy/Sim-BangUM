<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BmdAsset extends Model
{
    protected $fillable = ["bmd_code","asset_id","name","category","year_acquired","acquisition_value","condition","bmd_status","notes"];
    protected $casts = ["acquisition_value"=>"decimal:2"];
    public function asset() { return $this->belongsTo(Asset::class); }
}
