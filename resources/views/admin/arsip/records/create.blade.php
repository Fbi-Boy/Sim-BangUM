@extends('layouts.app')
@section('title','Daftarkan Arsip')
@section('content')
<div class="card" style="max-width:600px">
@if($errors->any())<div class="alert alert-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.arsip.records.store') }}">@csrf
<p style="margin-bottom:12px"><label>Klasifikasi *</label>
<select name="archive_classification_id" required>
<option value="">-- pilih --</option>
@foreach($classifications as $c)<option value="{{ $c->id }}">{{ $c->code }} — {{ $c->name }}</option>@endforeach
</select></p>
<p style="margin-bottom:12px"><label>Judul Arsip *</label><input name="title" required value="{{ old('title') }}"></p>
<p style="margin-bottom:12px"><label>Tanggal Dokumen</label><input type="date" name="record_date" value="{{ old('record_date') }}"></p>
<p style="margin-bottom:12px"><label>Lokasi Fisik</label><input name="physical_location" placeholder="mis: Rak A-2, Lemari 3" value="{{ old('physical_location') }}"></p>
<p style="margin-bottom:12px"><label>Deskripsi</label><textarea name="description" rows="3">{{ old('description') }}</textarea></p>
<button class="btn">Simpan</button> <a href="{{ route('admin.arsip.records.index') }}">Batal</a>
</form>
</div>
@endsection
