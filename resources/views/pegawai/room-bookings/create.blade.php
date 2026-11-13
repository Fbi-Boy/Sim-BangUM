@extends('layouts.app')
@section('title', 'Ajukan Booking Ruangan')
@section('content')
<h1>Ajukan Booking Ruangan</h1>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route(pegawai.room-bookings.store) }}">@csrf
<p>Ruangan *<br><select name="room_id" required><option value="">-- pilih --</option>@foreach($rooms as $r)<option value="{{ $r->id }}">{{ $r->name }} (kap. {{ $r->capacity }})</option>@endforeach</select></p>
<p>Keperluan *<br><input name="purpose" required value="{{ old(purpose) }}" size="60"></p>
<p>Jumlah Peserta *<br><input type="number" name="attendee_count" required value="{{ old(attendee_count) }}" min="1"></p>
<p>Mulai *<br><input type="datetime-local" name="starts_at" required value="{{ old(starts_at) }}"></p>
<p>Selesai *<br><input type="datetime-local" name="ends_at" required value="{{ old(ends_at) }}"></p>
<button>Ajukan</button> <a href="{{ route(pegawai.room-bookings.index) }}">Batal</a>
@endsection
