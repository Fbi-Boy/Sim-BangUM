<?php

namespace App\Http\Controllers\Admin\Kendaraan;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use App\Models\VehicleRequest;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::latest()->paginateSimbu();  // ⬅️ GANTI
        return view("admin.kendaraan.vehicles.index", compact("vehicles"));
    }

    public function create()
    {
        return view("admin.kendaraan.vehicles.create");
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            "license_plate" => "required|string|max:20|unique:vehicles,license_plate",
            "make" => "nullable|string|max:80",
            "model" => "nullable|string|max:100",
            "manufacture_year" => "nullable|integer|min:1900|max:2100",
            "passenger_capacity" => "nullable|integer|min:1|max:100",
        ]);
        $d["status"] = "available";
        Vehicle::create($d);
        return redirect()->route("admin.kendaraan.vehicles.index")
            ->with("success", "Kendaraan ditambahkan.");
    }

    public function show(Vehicle $vehicle)
    {
        return redirect()->route("admin.kendaraan.vehicles.index");
    }

    public function requests()
    {
        $requests = VehicleRequest::with(["vehicle", "requester"])
            ->latest()
            ->paginateSimbu();  // ⬅️ GANTI

        $drivers = User::whereHas("roles", fn($q) => $q->where("code", "pengemudi"))
            ->orderBy("name")
            ->get();

        return view("admin.kendaraan.requests.index", compact("requests", "drivers"));
    }

    public function approveRequest(Request $request, VehicleRequest $req)
    {
        $d = $request->validate([
            "vehicle_id" => "required|exists:vehicles,id",
            "driver_user_id" => "required|exists:users,id",
        ]);

        $vehicle = Vehicle::findOrFail($d["vehicle_id"]);
        if ($vehicle->status !== "available") {
            return back()->with("error", "Kendaraan tidak tersedia.");
        }

        DB::transaction(function () use ($req, $vehicle, $d) {
            $req->update(["vehicle_id" => $vehicle->id, "status" => "approved"]);
            VehicleAssignment::create([
                "vehicle_request_id" => $req->id,
                "vehicle_id" => $vehicle->id,
                "driver_user_id" => $d["driver_user_id"],
                "assigned_by_user_id" => auth()->id(),
                "planned_start_at" => $req->starts_at,
                "planned_end_at" => $req->ends_at,
                "status" => "assigned",
            ]);
            ActivityLogger::log("vehicle.assignment.created", $req, "Kendaraan & driver ditetapkan");
        });

        return back()->with("success", "Ditugaskan ke pengemudi.");
    }

    public function rejectRequest(Request $request, VehicleRequest $req)
    {
        $d = $request->validate(["rejection_reason" => "required|string"]);
        $req->update([
            "status" => "rejected",
            "rejection_reason" => $d["rejection_reason"],
        ]);
        return back()->with("success", "Ditolak.");
    }
}
