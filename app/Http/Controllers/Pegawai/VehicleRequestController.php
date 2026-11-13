<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\VehicleRequest;
use Illuminate\Http\Request;

class VehicleRequestController extends Controller
{
    public function index()
    {
        $requests = VehicleRequest::with('vehicle')->where('requester_user_id', auth()->id())->latest()->paginate(15);
        return view('pegawai.vehicle-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('pegawai.vehicle-requests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'purpose' => 'required|string',
            'destination' => 'required|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'passenger_count' => 'required|integer|min:1|max:50',
        ]);
        $year = now()->year;
        $seq = (VehicleRequest::whereYear('created_at', $year)->count() ?? 0) + 1;
        $data['request_number'] = "VR-{$year}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
        $data['requester_user_id'] = auth()->id();
        $data['status'] = 'submitted';
        VehicleRequest::create($data);
        return redirect()->route('pegawai.vehicle-requests.index')->with('success', 'Permohonan terkirim.');
    }

    public function show(VehicleRequest $vehicleRequest) { return redirect()->route('pegawai.vehicle-requests.index'); }
}
