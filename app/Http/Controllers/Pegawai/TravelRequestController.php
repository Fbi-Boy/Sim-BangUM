<?php
namespace App\Http\Controllers\Pegawai;
use App\Http\Controllers\Controller;
use App\Models\OfficialTravelRequest;
use App\Models\OfficialTravelParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelRequestController extends Controller
{
    public function index() {
        $requests = OfficialTravelRequest::where("requester_user_id",auth()->id())->latest()->paginate(15);
        return view("pegawai.travel-requests.index", compact("requests"));
    }
    public function create() { return view("pegawai.travel-requests.create"); }
    public function store(Request $r) {
        $d = $r->validate([
            "purpose"=>"required|string",
            "destination"=>"required|string|max:255",
            "departure_at"=>"required|date","return_at"=>"required|date|after:departure_at",
            "transport_mode"=>"nullable|string|max:30",
            "participants"=>"nullable|string",
        ]);
        DB::transaction(function () use ($d) {
            $year = now()->year;
            $seq = (OfficialTravelRequest::whereYear("created_at",$year)->count() ?? 0) + 1;
            $req = OfficialTravelRequest::create([
                "request_number"=>"PD-{$year}-".str_pad($seq,4,"0",STR_PAD_LEFT),
                "requester_user_id"=>auth()->id(),
                "purpose"=>$d["purpose"],"destination"=>$d["destination"],
                "departure_at"=>$d["departure_at"],"return_at"=>$d["return_at"],
                "transport_mode"=>$d["transport_mode"] ?? null,
                "status"=>"submitted",
            ]);
            OfficialTravelParticipant::create([
                "official_travel_request_id"=>$req->id,
                "participant_name"=>auth()->user()->name,
                "participant_role"=>"leader",
            ]);
            if (!empty($d["participants"])) {
                foreach (array_filter(array_map("trim", explode("\n", $d["participants"]))) as $name) {
                    OfficialTravelParticipant::create([
                        "official_travel_request_id"=>$req->id,
                        "participant_name"=>$name,
                        "participant_role"=>"participant",
                    ]);
                }
            }
        });
        return redirect()->route("pegawai.travel-requests.index")->with("success","Pengajuan terkirim.");
    }
    public function show(OfficialTravelRequest $travelRequest) {
        return redirect()->route("pegawai.travel-requests.index");
    }
}
