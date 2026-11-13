@extends('layouts.app')
@section('title','Pinjaman Aset Saya')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
<h2 style="font-size:20px">Pinjaman Aset Saya</h2>
<a href="{{ route('pegawai.asset-loans.create') }}" class="btn">+ Ajukan Pinjaman</a>
</div>
<div class="card" style="padding:0;overflow:hidden">
<table>
<tr><th>No Pinjaman</th><th>Aset</th><th>Tgl Mulai</th><th>Status</th></tr>
@forelse($loans as $l)
<tr>
<td>{{ $l->loan_number }}</td>
<td>{{ $l->asset->name }}</td>
<td>{{ $l->starts_at->format('d-m-Y') }}</td>
<td><span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:12px">{{ $l->status }}</span></td>
</tr>
@empty
<tr><td colspan="4" style="text-align:center;padding:24px;color:#9ca3af">Belum ada pengajuan.</td></tr>
@endforelse
</table>
</div>
<div style="margin-top:16px">{{ $loans->links() }}</div>
@endsection
