@extends('layouts.app')
@section('title','Detail BMD')
@section('content')
<div class="card">
<h2>{{ $asset->name }}</h2>
<table style="margin-top:12px">
<tr><th align="left">Kode BMD</th><td>{{ $asset->bmd_code }}</td></tr>
<tr><th align="left">Kategori</th><td>{{ $asset->category ?? '-' }}</td></tr>
<tr><th align="left">Tahun</th><td>{{ $asset->year_acquired ?? '-' }}</td></tr>
<tr><th align="left">Nilai</th><td>{{ $asset->acquisition_value ? 'Rp '.number_format($asset->acquisition_value,0,',','.') : '-' }}</td></tr>
<tr><th align="left">Kondisi</th><td>{{ $asset->condition }}</td></tr>
<tr><th align="left">Status</th><td>{{ $asset->bmd_status }}</td></tr>
</table>
</div>
<a href="{{ route('admin.barang-daerah.assets.index') }}">← Kembali</a>
@endsection
