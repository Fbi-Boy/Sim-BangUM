@extends('layouts.app')
@section('title','Barang Milik Daerah')
@section('content')
<a href="{{ route('admin.barang-daerah.assets.create') }}" class="btn">+ Tambah BMD</a>
<form method="GET" style="display:inline;margin-left:16px">
<input name="q" placeholder="Cari..." value="{{ request('q') }}" style="width:200px;display:inline-block">
<button class="btn btn-sm">Cari</button>
</form>
<table style="margin-top:16px">
<tr><th>Kode BMD</th><th>Nama</th><th>Kategori</th><th>Tahun</th><th>Nilai</th><th>Status</th><th></th></tr>
@forelse($items as $i)
<tr><td>{{ $i->bmd_code }}</td><td>{{ $i->name }}</td><td>{{ $i->category ?? '-' }}</td>
<td>{{ $i->year_acquired ?? '-' }}</td>
<td>{{ $i->acquisition_value ? 'Rp '.number_format($i->acquisition_value,0,',','.') : '-' }}</td>
<td>{{ $i->bmd_status }}</td>
<td><a href="{{ route('admin.barang-daerah.assets.show',$i) }}" class="btn btn-sm">Detail</a></td></tr>
@empty<tr><td colspan="7">Belum ada data BMD.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $items->links() }}</div>
@endsection
