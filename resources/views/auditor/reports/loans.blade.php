@extends('layouts.app')
@section('title','Laporan Peminjaman Aset')
@section('content')
<a href="{{ route('auditor.reports.index') }}">← Kembali</a>
<a href="{{ route('auditor.export', 'asset-loans') }}" class="btn" style="float:right">⬇ Export CSV</a>
<table style="margin-top:16px">
<tr><th>No</th><th>Aset</th><th>Pemohon</th><th>Mulai</th><th>Kembali</th><th>Status</th></tr>
@forelse($loans as $l)
<tr><td>{{ $l->loan_number }}</td><td>{{ $l->asset->name ?? '-' }}</td><td>{{ $l->requester->name ?? '-' }}</td><td>{{ $l->starts_at?->format("d-m-Y") }}</td><td>{{ $l->expected_return_at?->format("d-m-Y") }}</td><td>{{ $l->status }}</td></tr>
@empty<tr><td colspan="6">Tidak ada.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $loans->links() }}</div>
@endsection
