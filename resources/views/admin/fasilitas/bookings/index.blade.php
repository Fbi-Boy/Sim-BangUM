@extends('layouts.app')
@section('title','Booking Ruangan')
@section('content')
<h2 style="font-size:20px;margin-bottom:16px">Booking Ruangan</h2>
<div class="card" style="padding:0;overflow:hidden">
<table>
<tr><th>No</th><th>Ruangan</th><th>Pemohon</th><th>Jadwal</th><th>Status</th><th>Aksi</th></tr>
@forelse($bookings as $b)
<tr>
<td>{{ $b->booking_number }}</td>
<td>{{ $b->room->name }}</td>
<td>{{ $b->requester->name }}</td>
<td>{{ $b->starts_at->format('d-m-Y H:i') }} s/d {{ $b->ends_at->format('H:i') }}</td>
<td><span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:12px">{{ $b->status }}</span></td>
<td>
@if($b->status === 'submitted')
<form method="POST" action="{{ route('admin.fasilitas.bookings.approve', $b) }}" style="display:inline">@csrf<button class="btn btn-sm">Setujui</button></form>
<form method="POST" action="{{ route('admin.fasilitas.bookings.reject', $b) }}" style="display:inline">@csrf<button class="btn btn-sm btn-danger">Tolak</button></form>
@endif
</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center;padding:24px;color:#9ca3af">Belum ada booking.</td></tr>
@endforelse
</table>
</div>
<div style="margin-top:16px">{{ $bookings->links() }}</div>
@endsection
