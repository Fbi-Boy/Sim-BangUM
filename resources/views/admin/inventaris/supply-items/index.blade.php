@extends('layouts.app')
@section('title', 'Item ATK')
@section('content')
<h1>Item ATK</h1>

<p><a href="{{ route('admin.inventaris.supply-items.create') }}">+ Tambah</a> | <a href="{{ route('admin.inventaris.stock-requests.index') }}">Permintaan ATK</a></p>
<table border="1" cellpadding="6"><tr><th>SKU</th><th>Nama</th><th>Kategori</th><th>Satuan</th><th>Stok</th><th>Min</th></tr>
@forelse($items as $i)<tr><td>{{ $i->sku }}</td><td>{{ $i->name }}</td><td>{{ $i->category->name }}</td><td>{{ $i->unit }}</td><td>{{ $i->current_stock }}</td><td>{{ $i->minimum_stock }}</td></tr>
@empty<tr><td colspan="6">Belum ada item.</td></tr>@endforelse</table>
<p>{{ $items->links() }}</p><p><a href="{{ route('dashboard') }}">← Dashboard</a></p>
@endsection
