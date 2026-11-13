<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StockRequestItem extends Model {
    protected $fillable = ["stock_request_id","supply_item_id","requested_quantity","approved_quantity","issued_quantity"];
    public function supplyItem() { return $this->belongsTo(SupplyItem::class); }
}
