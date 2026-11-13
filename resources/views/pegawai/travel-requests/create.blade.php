@extends('layouts.app')
@section('title','Ajukan Perjalanan Dinas')
@section('content')
<div class="card" style="max-width:600px">
@if($errors->any())<div class="alert alert-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('pegawai.travel-requests.store') }}">@csrf
<p style="margin-bottom:12px"><label>Tujuan *</label><input name="destination" required value="{{ old('destination') }}"></p>
<p style="margin-bottom:12px"><label>Keperluan *</label><textarea name="purpose" rows="3" required>{{ old('purpose') }}</textarea></p>
<p style="margin-bottom:12px"><label>Berangkat *</label><input type="datetime-local" name="departure_at" required value="{{ old('departure_at') }}"></p>
<p style="margin-bottom:12px"><label>Kembali *</label><input type="datetime-local" name="return_at" required value="{{ old('return_at') }}"></p>
<p style="margin-bottom:12px"><label>Transport</label><input name="transport_mode" placeholder="darat/udara/lainnya" value="{{ old('transport_mode') }}"></p>
<p style="margin-bottom:12px"><label>Peserta Lain (satu per baris, opsional)</label><textarea name="participants" rows="3">{{ old('participants') }}</textarea></p>
<button class="btn">Ajukan</button> <a href="{{ route('pegawai.travel-requests.index') }}">Batal</a>
</form>
</div>
@endsection
