<?php

namespace App\Http\Controllers\Admin\Fasilitas;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomBooking;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(15);
        return view('admin.fasilitas.rooms.index', compact('rooms'));
    }

    public function create() { return view('admin.fasilitas.rooms.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_code' => 'required|string|max:50|unique:rooms,room_code',
            'name' => 'required|string|max:160',
            'capacity' => 'required|integer|min:1|max:1000',
            'room_type' => 'required|in:meeting,auditorium,reception,other',
            'usage_notes' => 'nullable|string',
        ]);
        $data['status'] = 'active';
        Room::create($data);
        return redirect()->route('admin.fasilitas.rooms.index')->with('success', 'Ruangan ditambahkan.');
    }

    public function show(Room $room) { return redirect()->route('admin.fasilitas.rooms.index'); }

    public function bookings()
    {
        $bookings = RoomBooking::with(['room', 'requester'])->latest()->paginate(15);
        return view('admin.fasilitas.bookings.index', compact('bookings'));
    }

    public function approveBooking(RoomBooking $booking)
    {
        $conflict = RoomBooking::where('room_id', $booking->room_id)
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['approved', 'in_use'])
            ->where('starts_at', '<', $booking->ends_at)
            ->where('ends_at', '>', $booking->starts_at)
            ->exists();
        if ($conflict) { return back()->with('error', 'Jadwal bentrok dengan booking lain.'); }
        $booking->update(['status' => 'approved']);
        return back()->with('success', 'Booking disetujui.');
    }

    public function rejectBooking(Request $request, RoomBooking $booking)
    {
        $data = $request->validate(['rejection_reason' => 'required|string']);
        $booking->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason']]);
        return back()->with('success', 'Booking ditolak.');
    }
}
