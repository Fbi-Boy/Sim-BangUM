@extends('layouts.app')
@section('title', 'Tambah Kendaraan')
@section('content')
<h1>Tambah Kendaraan</h1>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route('admin.kendaraan.vehicles.store') }}">
@csrf
<p>No Polisi *<br><input name="license_plate" required value="{{ old('license_plate') }}"></p>
<p>Merek<br><input name="make" value="{{ old('make') }}"></p>
<p>Model<br><input name="model" value="{{ old('model') }}"></p>
<p>Tahun<br><input type="number" name="manufacture_year" value="{{ old('manufacture_year') }}"></p>
<p>Kapasitas Penumpang<br><input type="number" name="passenger_capacity" value="{{ old('passenger_capacity') }}"></p>
<button>Simpan</button> <a href="{{ route('admin.kendaraan.vehicles.index') }}">Batal</a>
</form>
@endsection
