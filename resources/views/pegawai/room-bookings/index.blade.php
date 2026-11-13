@extends('layouts.app')
@section('title', 'Booking Ruangan Saya')
@section('content')
<h1>Booking Ruangan Saya</h1>
@if(session(success))<div style="background:#16a34a;color:white;padding:8px">{{ session(success) }}</div>@endif
<p><a href="{{ route(pegawai.room-bookings.create) }}">+ Ajukan Booking</a></p>
<table border="1" cellpadding="6" cellspacing="0"><tr><th>No</th><th>Ruangan</th><th>Keperluan</th><th>Jadwal</th><th>Status</th></tr>
@forelse($bookings as $b)<tr><td>{{ $b->booking_number }}</td><td>{{ $b->room->name }}</td><td>{{ $b->purpose }}</td><td>{{ $b->starts_at->format(d-m-Y H:i) }}</td><td>{{ $b->status }}</td></tr>@empty<tr><td colspan="5">Belum ada booking.</td></tr>@endforelse
@endsection
