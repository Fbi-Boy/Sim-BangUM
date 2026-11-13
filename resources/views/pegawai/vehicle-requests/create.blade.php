@extends('layouts.app')
@section('title', 'Ajukan Permohonan Kendaraan')
@section('content')
<h1>Ajukan Permohonan Kendaraan</h1>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route(pegawai.vehicle-requests.store) }}">
@csrf
<p>Tujuan *<br><input name="destination" required value="{{ old(destination) }}" size="60"></p>
<p>Keperluan *<br><textarea name="purpose" rows="3" cols="60" required>{{ old(purpose) }}</textarea></p>
<p>Berangkat *<br><input type="datetime-local" name="starts_at" required value="{{ old(starts_at) }}"></p>
<p>Kembali *<br><input type="datetime-local" name="ends_at" required value="{{ old(ends_at) }}"></p>
<p>Jumlah Penumpang *<br><input type="number" name="passenger_count" required value="{{ old(passenger_count,1) }}" min="1"></p>
<button>Ajukan</button> <a href="{{ route(pegawai.vehicle-requests.index) }}">Batal</a>
@endsection
