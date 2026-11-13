<?php
namespace App\Http\Controllers\Admin\PerjalananDinas;
use App\Http\Controllers\Controller;
use App\Models\OfficialTravelRequest;
use Illuminate\Http\Request;

class TravelRequestController extends Controller
{
    public function index() {
        $requests = OfficialTravelRequest::with("requester")->latest()->paginate(15);
        return view("admin.perjalanan-dinas.requests.index", compact("requests"));
    }
    public function show(OfficialTravelRequest $travelRequest) {
        $travelRequest->load(["requester","approver","participants"]);
        return view("admin.perjalanan-dinas.requests.show", compact("travelRequest"));
    }
    public function approve(OfficialTravelRequest $travelRequest) {
        if ($travelRequest->status !== "submitted") return back()->with("error","Status tidak valid.");
        $travelRequest->update(["status"=>"approved","approved_by"=>auth()->id(),"approved_at"=>now()]);
        return back()->with("success","Perjalanan disetujui.");
    }
    public function reject(Request $r, OfficialTravelRequest $travelRequest) {
        $d = $r->validate(["rejection_reason"=>"required|string"]);
        $travelRequest->update(["status"=>"rejected","rejection_reason"=>$d["rejection_reason"]]);
        return back()->with("success","Perjalanan ditolak.");
    }
}
