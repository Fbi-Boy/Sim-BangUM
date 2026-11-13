@extends('layouts.app')
@section('title', 'Tambah Ruangan')
@section('content')
<h1>Tambah Ruangan</h1>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route(admin.fasilitas.rooms.store) }}">@csrf
<p>Kode *<br><input name="room_code" required value="{{ old(room_code) }}"></p>
<p>Nama *<br><input name="name" required value="{{ old(name) }}" size="50"></p>
<p>Kapasitas *<br><input type="number" name="capacity" required value="{{ old(capacity) }}" min="1"></p>
<p>Tipe *<br><select name="room_type" required><option value="meeting">Meeting</option><option value="auditorium">Auditorium</option><option value="reception">Reception</option><option value="other">Other</option></select></p>
<p>Catatan<br><textarea name="usage_notes" rows="2" cols="50">{{ old(usage_notes) }}</textarea></p>
<button>Simpan</button> <a href="{{ route(admin.fasilitas.rooms.index) }}">Batal</a>
@endsection
