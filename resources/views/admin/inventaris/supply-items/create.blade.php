@extends('layouts.app')
@section('title', 'Tambah Item ATK')
@section('content')
<h1>Tambah Item ATK</h1>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route('admin.inventaris.supply-items.store') }}">@csrf
<p>SKU *<br><input name="sku" required value="{{ old('sku') }}"></p>
<p>Nama *<br><input name="name" required value="{{ old('name') }}" size="50"></p>
<p>Kategori *<br><select name="supply_category_id" required><option value="">-- pilih --</option>
@foreach($categories as $c)<option value="{{ $c->id }}" {{ old('supply_category_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
</select></p>
<p>Satuan *<br><input name="unit" required value="{{ old('unit') }}" placeholder="rim/box/pcs"></p>
<p>Stok Awal *<br><input type="number" step="0.01" name="current_stock" required value="{{ old('current_stock',0) }}"></p>
<p>Minimum Stok *<br><input type="number" step="0.01" name="minimum_stock" required value="{{ old('minimum_stock',0) }}"></p>
<button>Simpan</button> <a href="{{ route('admin.inventaris.supply-items.index') }}">Batal</a>
@endsection
