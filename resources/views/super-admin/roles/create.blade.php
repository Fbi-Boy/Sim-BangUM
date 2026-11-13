@extends('layouts.app')
@section('title','Tambah Role')
@section('content')
<div class="card" style="max-width:600px">
@if($errors->any())<div class="alert alert-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('super-admin.roles.store') }}">@csrf
<p style="margin-bottom:12px"><label>Kode * (huruf kecil, tanpa spasi)</label><input name="code" required value="{{ old('code') }}" placeholder="admin_gudang"></p>
<p style="margin-bottom:12px"><label>Nama *</label><input name="name" required value="{{ old('name') }}" placeholder="Admin Gudang"></p>
<p style="margin-bottom:12px"><label>Deskripsi</label><textarea name="description" rows="2">{{ old('description') }}</textarea></p>
<button class="btn">Simpan</button> <a href="{{ route('super-admin.roles.index') }}">Batal</a>
</form>
</div>
@endsection
