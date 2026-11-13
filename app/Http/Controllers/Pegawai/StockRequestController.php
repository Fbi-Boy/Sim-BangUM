<?php
namespace App\Http\Controllers\Pegawai;
use App\Http\Controllers\Controller;
use App\Models\StockRequest;
use App\Models\StockRequestItem;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockRequestController extends Controller
{
    public function index() {
        $requests = StockRequest::with("items.supplyItem")->where("requester_user_id",auth()->id())->latest()->paginate(15);
        return view("pegawai.stock-requests.index", compact("requests"));
    }
    public function create() {
        $items = SupplyItem::where("is_active",true)->orderBy("name")->get();
        return view("pegawai.stock-requests.create", compact("items"));
    }
    public function store(Request $r) {
        $d = $r->validate([
            "purpose"=>"required|string",
            "needed_at"=>"nullable|date",
            "items"=>"required|array",
        ]);
        $rows = [];
        foreach ($d["items"] as $row) {
            if (!empty($row["id"]) && !empty($row["qty"]) && (float)$row["qty"] > 0) {
                $rows[] = ["id"=>(int)$row["id"], "qty"=>(float)$row["qty"]];
            }
        }
        if (empty($rows)) return back()->withErrors(["items"=>"Minimal 1 item harus diisi."])->withInput();
        DB::transaction(function () use ($d,$rows) {
            $year = now()->year;
            $seq = (StockRequest::whereYear("created_at",$year)->count() ?? 0) + 1;
            $req = StockRequest::create([
                "request_number"=>"ATK-{$year}-".str_pad($seq,4,"0",STR_PAD_LEFT),
                "requester_user_id"=>auth()->id(),
                "purpose"=>$d["purpose"], "needed_at"=>$d["needed_at"] ?? null,
                "status"=>"submitted",
            ]);
            foreach ($rows as $row) {
                StockRequestItem::create([
                    "stock_request_id"=>$req->id, "supply_item_id"=>$row["id"],
                    "requested_quantity"=>$row["qty"],
                ]);
            }
        });
        return redirect()->route("pegawai.stock-requests.index")->with("success","Permintaan terkirim.");
    }
    public function show(StockRequest $stockRequest) { return redirect()->route("pegawai.stock-requests.index"); }
}
