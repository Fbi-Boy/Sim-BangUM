<?php
namespace App\Http\Controllers\Pejabat;
use App\Http\Controllers\Controller;
use App\Models\ApprovalLog;
use App\Models\AssetLoan;
use App\Models\OfficialTravelRequest;
use App\Models\RoomBooking;
use App\Models\StockRequest;
use App\Models\VehicleRequest;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalInboxController extends Controller
{
    public function index()
    {
        return view("pejabat.approvals.inbox", [
            "asset_loans" => AssetLoan::with(["asset","requester"])->where("status","submitted")->latest()->get(),
            "vehicle_requests" => VehicleRequest::with("requester")->where("status","submitted")->latest()->get(),
            "room_bookings" => RoomBooking::with(["room","requester"])->where("status","submitted")->latest()->get(),
            "stock_requests" => StockRequest::with(["requester","items.supplyItem"])->where("status","submitted")->latest()->get(),
            "travel_requests" => OfficialTravelRequest::with("requester")->where("status","submitted")->latest()->get(),
        ]);
    }

    public function decide(Request $r, string $type, int $id)
    {
        $r->validate([
            "decision" => "required|in:approve,reject",
            "note" => "nullable|string|max:500",
        ]);
        $model = $this->resolveModel($type, $id);
        if (!$model) abort(404);
        if ($model->status !== "submitted") return back()->with("error", "Sudah diproses.");

        DB::transaction(function () use ($model, $type, $r) {
            $newStatus = $r->decision === "approve" ? "approved" : "rejected";
            $updates = ["status" => $newStatus];
            if ($r->decision === "reject") {
                $updates["rejection_reason"] = $r->note;
            } else {
                if (method_exists($model, "getApprovedByField")) {}
                if (in_array("approved_by", $model->getFillable())) {
                    $updates["approved_by"] = auth()->id();
                }
                if (in_array("approved_at", $model->getFillable())) {
                    $updates["approved_at"] = now();
                }
            }
            $model->update($updates);
            ApprovalLog::create([
                "subject_type" => get_class($model),
                "subject_id" => $model->id,
                "approver_user_id" => auth()->id(),
                "decision" => $newStatus,
                "note" => $r->note,
            ]);
            ActivityLogger::log("approval.{$type}.{$newStatus}", $model, "Oleh pejabat. Catatan: ".($r->note ?? "-"));
        });
        return back()->with("success", "Keputusan tersimpan.");
    }

    private function resolveModel(string $type, int $id)
    {
        return match ($type) {
            "asset-loan" => AssetLoan::find($id),
            "vehicle-request" => VehicleRequest::find($id),
            "room-booking" => RoomBooking::find($id),
            "stock-request" => StockRequest::find($id),
            "travel-request" => OfficialTravelRequest::find($id),
            default => null,
        };
    }
}
