@extends('layouts.app')
@section('title','Tambah BMD')
@section('content')
<div class="card" style="max-width:600px">
@if($errors->any())<div class="alert alert-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.barang-daerah.assets.store') }}">@csrf
<p style="margin-bottom:12px"><label>Kode BMD *</label><input name="bmd_code" required value="{{ old('bmd_code') }}"></p>
<p style="margin-bottom:12px"><label>Nama *</label><input name="name" required value="{{ old('name') }}" size="50"></p>
<p style="margin-bottom:12px"><label>Kategori</label><input name="category" value="{{ old('category') }}"></p>
<p style="margin-bottom:12px"><label>Tahun Perolehan</label><input type="number" name="year_acquired" value="{{ old('year_acquired') }}" min="1900" max="2100"></p>
<p style="margin-bottom:12px"><label>Nilai Perolehan (Rp)</label><input type="number" step="0.01" name="acquisition_value" value="{{ old('acquisition_value') }}" min="0"></p>
<button class="btn">Simpan</button> <a href="{{ route('admin.barang-daerah.assets.index') }}">Batal</a>
</form>
</div>
@endsection
