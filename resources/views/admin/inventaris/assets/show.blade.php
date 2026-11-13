@extends('layouts.app')
@section('title', '{{ $asset->name }}')
@section('content')
<h1>{{ $asset->name }}</h1>
<table border="1" cellpadding="6" cellspacing="0">
<tr><th align="left">Kode</th><td>{{ $asset->asset_code }}</td></tr>
<tr><th align="left">Kategori</th><td>{{ $asset->category->name }}</td></tr>
<tr><th align="left">Merek</th><td>{{ $asset->brand ?? '-' }}</td></tr>
<tr><th align="left">Kondisi</th><td>{{ $asset->condition }}</td></tr>
<tr><th align="left">Status</th><td>{{ $asset->status }}</td></tr>
</table>
<h2>Riwayat Peminjaman</h2>
<table border="1" cellpadding="6" cellspacing="0">
<tr><th>No</th><th>Peminjam</th><th>Mulai</th><th>Kembali</th><th>Status</th></tr>
@forelse($asset->loans as $l)
<tr><td>{{ $l->loan_number }}</td><td>{{ $l->requester->name }}</td><td>{{ $l->starts_at->format('d-m-Y') }}</td><td>{{ $l->expected_return_at->format('d-m-Y') }}</td><td>{{ $l->status }}</td></tr>
@empty
<tr><td colspan="5">Belum ada peminjaman.</td></tr>
@endforelse
</table>
<p><a href="{{ route('admin.inventaris.assets.index') }}">← Kembali</a></p>
@endsection
