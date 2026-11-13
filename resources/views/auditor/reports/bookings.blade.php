@extends('layouts.app')
@section('title','Laporan Booking Ruangan')
@section('content')
<a href="{{ route('auditor.reports.index') }}">← Kembali</a>
<table style="margin-top:16px">
<tr><th>No</th><th>Ruangan</th><th>Pemohon</th><th>Keperluan</th><th>Jadwal</th><th>Status</th></tr>
@forelse($bookings as $b)
<tr><td>{{ $b->booking_number }}</td><td>{{ $b->room->name ?? '-' }}</td><td>{{ $b->requester->name ?? '-' }}</td><td>{{ $b->purpose }}</td><td>{{ $b->starts_at?->format("d-m-Y H:i") }}</td><td>{{ $b->status }}</td></tr>
@empty<tr><td colspan="6">Tidak ada.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $bookings->links() }}</div>
@endsection
