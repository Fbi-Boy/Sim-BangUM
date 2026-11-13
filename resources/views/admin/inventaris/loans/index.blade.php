@extends('layouts.app')
@section('title','Peminjaman Aset')
@section('content')
<h2 style="font-size:20px;margin-bottom:16px">Daftar Peminjaman Aset</h2>
<div class="card" style="padding:0;overflow:hidden">
<table>
<tr><th>No Pinjaman</th><th>Aset</th><th>Peminjam</th><th>Tgl Mulai</th><th>Status</th><th>Aksi</th></tr>
@forelse($loans as $l)
<tr>
<td>{{ $l->loan_number }}</td>
<td>{{ $l->asset->name }}</td>
<td>{{ $l->requester->name }}</td>
<td>{{ $l->starts_at->format('d-m-Y') }}</td>
<td><span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:12px">{{ $l->status }}</span></td>
<td>
@if($l->status === 'submitted')
<form method="POST" action="{{ route('admin.inventaris.loans.approve', $l) }}" style="display:inline">@csrf<button class="btn btn-sm">Setujui</button></form>
@elseif($l->status === 'issued')
<form method="POST" action="{{ route('admin.inventaris.loans.return', $l) }}" style="display:inline">@csrf<button class="btn btn-sm btn-secondary">Kembali</button></form>
@endif
</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center;padding:24px;color:#9ca3af">Belum ada peminjaman.</td></tr>
@endforelse
</table>
</div>
<div style="margin-top:16px">{{ $loans->links() }}</div>
@endsection
