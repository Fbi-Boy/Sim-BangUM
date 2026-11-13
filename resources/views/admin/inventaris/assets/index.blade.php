@extends('layouts.app')
@section('title','Inventaris Aset')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
<h2 style="font-size:20px">Inventaris Aset</h2>
<a href="{{ route('admin.inventaris.assets.create') }}" class="btn">+ Tambah Aset</a>
</div>
<div class="card" style="padding:0;overflow:hidden">
<table>
<tr><th>Kode Aset</th><th>Nama Aset</th><th>Kategori</th><th>Kondisi</th><th>Status</th><th>Aksi</th></tr>
@forelse($assets as $a)
<tr>
<td>{{ $a->asset_code }}</td>
<td>{{ $a->name }}</td>
<td>{{ $a->category->name }}</td>
<td>{{ $a->condition }}</td>
<td><span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:12px">{{ $a->status }}</span></td>
<td><a href="{{ route('admin.inventaris.assets.show',$a) }}" class="btn btn-sm btn-secondary">Detail</a></td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center;padding:24px;color:#9ca3af">Belum ada aset.</td></tr>
@endforelse
</table>
</div>
<div style="margin-top:16px">{{ $assets->links() }}</div>
@endsection
