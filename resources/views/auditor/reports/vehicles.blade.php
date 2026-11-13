@extends('layouts.app')
@section('title','Laporan Kendaraan')
@section('content')
<a href="{{ route('auditor.reports.index') }}">← Kembali</a>
<a href="{{ route('auditor.export', 'vehicle-requests') }}" class="btn" style="float:right">⬇ Export CSV</a>
<table style="margin-top:16px">
<tr><th>No</th><th>Pemohon</th><th>Tujuan</th><th>Berangkat</th><th>Kendaraan</th><th>Status</th></tr>
@forelse($requests as $r)
<tr><td>{{ $r->request_number }}</td><td>{{ $r->requester->name ?? '-' }}</td><td>{{ $r->destination }}</td><td>{{ $r->starts_at?->format("d-m-Y H:i") }}</td><td>{{ $r->vehicle->license_plate ?? '-' }}</td><td>{{ $r->status }}</td></tr>
@empty<tr><td colspan="6">Tidak ada.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $requests->links() }}</div>
@endsection
