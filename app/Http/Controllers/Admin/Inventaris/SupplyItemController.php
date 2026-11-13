<?php
namespace App\Http\Controllers\Admin\Inventaris;
use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Models\SupplyCategory;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyItemController extends Controller
{
    public function index() {
        $items = SupplyItem::with("category")->latest()->paginate(15);
        return view("admin.inventaris.supply-items.index", compact("items"));
    }
    public function create() {
        $categories = SupplyCategory::where("is_active",true)->orderBy("name")->get();
        return view("admin.inventaris.supply-items.create", compact("categories"));
    }
    public function store(Request $r) {
        $d = $r->validate([
            "sku"=>"required|string|max:60|unique:supply_items,sku",
            "supply_category_id"=>"required|exists:supply_categories,id",
            "name"=>"required|string|max:180","unit"=>"required|string|max:30",
            "minimum_stock"=>"required|numeric|min:0","current_stock"=>"required|numeric|min:0",
        ]);
        $d["is_active"] = true;
        SupplyItem::create($d);
        return redirect()->route("admin.inventaris.supply-items.index")->with("success","Item ditambahkan.");
    }
    public function requests() {
        $requests = StockRequest::with(["requester","items.supplyItem"])->latest()->paginate(15);
        return view("admin.inventaris.stock-requests.index", compact("requests"));
    }
    public function approveRequest(Request $r, StockRequest $stockRequest) {
        if ($stockRequest->status !== "submitted") return back()->with("error","Status tidak valid.");
        $d = $r->validate(["approved_qty"=>"required|array","approved_qty.*"=>"required|numeric|min:0"]);
        DB::transaction(function () use ($stockRequest,$d) {
            foreach ($stockRequest->items as $item) {
                $item->update(["approved_quantity"=>(float)($d["approved_qty"][$item->id] ?? 0)]);
            }
            $stockRequest->update(["status"=>"approved","approved_by"=>auth()->id(),"approved_at"=>now()]);
            \App\Support\ActivityLogger::log("stock_request.approved", $stockRequest, "Permintaan ATK disetujui: ".$stockRequest->request_number);
        });
        return back()->with("success","Disetujui. Siap dikeluarkan.");
    }
    public function issueRequest(StockRequest $stockRequest) {
        if ($stockRequest->status !== "approved") return back()->with("error","Status tidak valid.");
        try {
            DB::transaction(function () use ($stockRequest) {
                foreach ($stockRequest->items as $item) {
                    $qty = (float)($item->approved_quantity ?? 0);
                    if ($qty <= 0) continue;
                    $sup = SupplyItem::lockForUpdate()->find($item->supply_item_id);
                    if (!$sup || $sup->current_stock < $qty) throw new \RuntimeException("Stok kurang: ".($sup->name ?? "?"));
                    $sup->current_stock -= $qty; $sup->save();
                    $item->update(["issued_quantity"=>$qty]);
                }
                $stockRequest->update(["status"=>"issued"]);
                \App\Support\ActivityLogger::log("stock_request.issued", $stockRequest, "Stok dikeluarkan: ".$stockRequest->request_number);
            });
            return back()->with("success","Stok dikeluarkan.");
        } catch (\RuntimeException $e) { return back()->with("error",$e->getMessage()); }
    }
    public function rejectRequest(Request $r, StockRequest $stockRequest) {
        $d = $r->validate(["rejection_reason"=>"required|string"]);
        $stockRequest->update(["status"=>"rejected","rejection_reason"=>$d["rejection_reason"]]);
        \App\Support\ActivityLogger::log("stock_request.rejected", $stockRequest, "Ditolak: ".$d["rejection_reason"]);
        return back()->with("success","Ditolak.");
    }
}
