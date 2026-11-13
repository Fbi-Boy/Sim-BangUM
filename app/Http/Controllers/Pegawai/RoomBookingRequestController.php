<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomBooking;
use Illuminate\Http\Request;

class RoomBookingRequestController extends Controller
{
    public function index()
    {
        $bookings = RoomBooking::with('room')->where('requester_user_id', auth()->id())->latest()->paginate(15);
        return view('pegawai.room-bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'active')->orderBy('name')->get();
        return view('pegawai.room-bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'purpose' => 'required|string|max:250',
            'attendee_count' => 'required|integer|min:1|max:1000',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);
        $room = Room::findOrFail($data['room_id']);
        if ($data['attendee_count'] > $room->capacity) {
            return back()->withErrors(['attendee_count' => 'Melebihi kapasitas ('.$room->capacity.' orang).'])->withInput();
        }
        $year = now()->year;
        $seq = (RoomBooking::whereYear('created_at', $year)->count() ?? 0) + 1;
        $data['booking_number'] = "RB-{$year}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
        $data['requester_user_id'] = auth()->id();
        $data['status'] = 'submitted';
        RoomBooking::create($data);
        return redirect()->route('pegawai.room-bookings.index')->with('success', 'Booking terkirim.');
    }

    public function show(RoomBooking $roomBooking) { return redirect()->route('pegawai.room-bookings.index'); }
}
