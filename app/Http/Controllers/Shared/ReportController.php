<?php
namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\Event;
use App\Models\IncomingLetter;
use App\Models\OfficialTravelRequest;
use App\Models\OutgoingLetter;
use App\Models\RoomBooking;
use App\Models\StockRequest;
use App\Models\SupplyItem;
use App\Models\Vehicle;
use App\Models\VehicleRequest;

class ReportController extends Controller
{
    public function index()
    {
        $data = [
            "letters" => [
                "incoming_total" => IncomingLetter::count(),
                "incoming_month" => IncomingLetter::whereMonth("received_at", now()->month)->count(),
                "outgoing_total" => OutgoingLetter::count(),
                "outgoing_month" => OutgoingLetter::whereMonth("created_at", now()->month)->count(),
            ],
            "inventory" => [
                "assets_total" => Asset::count(),
                "assets_available" => Asset::where("status","available")->count(),
                "assets_loaned" => Asset::where("status","loaned")->count(),
                "loans_active" => AssetLoan::where("status","issued")->count(),
                "loans_pending" => AssetLoan::where("status","submitted")->count(),
            ],
            "supply" => [
                "items_total" => SupplyItem::count(),
                "low_stock" => SupplyItem::whereColumn("current_stock","<=","minimum_stock")->count(),
                "requests_pending" => StockRequest::where("status","submitted")->count(),
            ],
            "vehicles" => [
                "total" => Vehicle::count(),
                "available" => Vehicle::where("status","available")->count(),
                "requests_pending" => VehicleRequest::where("status","submitted")->count(),
                "requests_approved" => VehicleRequest::where("status","approved")->count(),
            ],
            "facilities" => [
                "bookings_pending" => RoomBooking::where("status","submitted")->count(),
                "bookings_approved" => RoomBooking::where("status","approved")->count(),
                "bookings_month" => RoomBooking::whereMonth("created_at", now()->month)->count(),
            ],
            "events" => [
                "total" => Event::count(),
                "upcoming" => Event::where("starts_at", ">=", now())->where("status","!=","cancelled")->count(),
                "this_month" => Event::whereMonth("starts_at", now()->month)->count(),
            ],
            "travel" => [
                "total" => OfficialTravelRequest::count(),
                "pending" => OfficialTravelRequest::where("status","submitted")->count(),
                "approved" => OfficialTravelRequest::where("status","approved")->count(),
            ],
        ];
        return view("shared.reports.index", compact("data"));
    }
}
