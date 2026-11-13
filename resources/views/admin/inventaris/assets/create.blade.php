@extends('layouts.app')
@section('title','Tambah Aset')
@section('content')
<div style="margin-bottom:16px"><a href="{{ route('admin.inventaris.assets.index') }}" style="color:#6b7280;text-decoration:none">← Kembali</a></div>
<div class="card" style="max-width:600px">
<h2>Tambah Aset Baru</h2>
@if($errors->any())<div class="alert alert-error"><ul style="margin-left:16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.inventaris.assets.store') }}">@csrf
<p style="margin-bottom:12px"><label>Kode Aset *</label><input name="asset_code" required value="{{ old('asset_code') }}"></p>
<p style="margin-bottom:12px"><label>Nama Aset *</label><input name="name" required value="{{ old('name') }}"></p>
<p style="margin-bottom:12px"><label>Kategori *</label>
<select name="asset_category_id" required>
<option value="">-- Pilih Kategori --</option>
@foreach($categories as $c)<option value="{{ $c->id }}" {{ old('asset_category_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
</select></p>
<p style="margin-bottom:12px"><label>Merek</label><input name="brand" value="{{ old('brand') }}"></p>
<p style="margin-bottom:16px"><label>Catatan</label><textarea name="notes" rows="2">{{ old('notes') }}</textarea></p>
<button class="btn">Simpan</button> <a href="{{ route('admin.inventaris.assets.index') }}" class="btn btn-secondary">Batal</a>
</form>
</div>
@endsection
