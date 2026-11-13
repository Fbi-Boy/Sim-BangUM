<?php
namespace App\Http\Controllers\Admin\BarangDaerah;
use App\Http\Controllers\Controller;
use App\Models\BmdAsset;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class BmdAssetController extends Controller
{
    public function index(Request $r)
    {
        $items = BmdAsset::when($r->filled("q"), function($q) use ($r) {
                $q->where("name","like","%".$r->q."%")->orWhere("bmd_code","like","%".$r->q."%");
            })->latest()->paginate(20)->withQueryString();
        return view("admin.barang-daerah.assets.index", compact("items"));
    }
    public function create() { return view("admin.barang-daerah.assets.create"); }
    public function store(Request $r)
    {
        $d = $r->validate([
            "bmd_code"=>"required|string|max:80|unique:bmd_assets,bmd_code",
            "name"=>"required|string|max:200",
            "category"=>"nullable|string|max:100",
            "year_acquired"=>"nullable|integer|min:1900|max:2100",
            "acquisition_value"=>"nullable|numeric|min:0",
        ]);
        $d["condition"]="good"; $d["bmd_status"]="active";
        $bmd = BmdAsset::create($d);
        ActivityLogger::log("bmd.created", $bmd, "BMD: ".$bmd->bmd_code);
        return redirect()->route("admin.barang-daerah.assets.index")->with("success","Data BMD ditambahkan.");
    }
    public function show(BmdAsset $asset) { return view("admin.barang-daerah.assets.show", compact("asset")); }
}
