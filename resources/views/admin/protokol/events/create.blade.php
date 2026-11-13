@extends('layouts.app')
@section('title','Buat Kegiatan')
@section('content')
<div class="card" style="max-width:600px">
<h2>Buat Kegiatan</h2>
@if($errors->any())<div class="alert alert-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.protokol.events.store') }}">@csrf
<p style="margin-bottom:12px"><label>Judul *</label><input name="title" required value="{{ old('title') }}"></p>
<p style="margin-bottom:12px"><label>Jenis *</label>
<select name="event_type" required>
<option value="rapat">Rapat</option><option value="upacara">Upacara</option>
<option value="kunjungan">Kunjungan</option><option value="lainnya">Lainnya</option>
</select></p>
<p style="margin-bottom:12px"><label>Mulai *</label><input type="datetime-local" name="starts_at" required value="{{ old('starts_at') }}"></p>
<p style="margin-bottom:12px"><label>Selesai *</label><input type="datetime-local" name="ends_at" required value="{{ old('ends_at') }}"></p>
<p style="margin-bottom:12px"><label>Lokasi</label><input name="location" value="{{ old('location') }}"></p>
<p style="margin-bottom:12px"><label>Deskripsi</label><textarea name="description" rows="3">{{ old('description') }}</textarea></p>
<button class="btn">Simpan</button> <a href="{{ route('admin.protokol.events.index') }}">Batal</a>
</form>
</div>
@endsection
