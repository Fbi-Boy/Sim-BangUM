<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SupplyItem extends Model {
    protected $fillable = ["sku","supply_category_id","name","unit","minimum_stock","current_stock","is_active"];
    protected $casts = ["is_active"=>"boolean"];
    public function category() { return $this->belongsTo(SupplyCategory::class, "supply_category_id"); }
}
