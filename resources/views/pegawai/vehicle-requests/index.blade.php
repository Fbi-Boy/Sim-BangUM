@extends('layouts.app')
@section('title', 'Permohonan Kendaraan Saya')
@section('content')
<h1>Permohonan Kendaraan Saya</h1>

<p><a href="{{ route('pegawai.vehicle-requests.create') }}">+ Ajukan Permohonan</a></p>
<table border="1" cellpadding="6" cellspacing="0">
<tr><th>No</th><th>Tujuan</th><th>Jadwal</th><th>Kendaraan</th><th>Status</th></tr>
@forelse($requests as $r)
<tr><td>{{ $r->request_number }}</td><td>{{ $r->destination }}</td><td>{{ $r->starts_at->format('d-m-Y H:i') }}</td><td>{{ $r->vehicle->license_plate ?? '-' }}</td><td>{{ $r->status }}</td></tr>
@empty
<tr><td colspan="5">Belum ada permohonan.</td></tr>
@endforelse
</table>
<p>{{ $requests->links() }}</p>
<p><a href="{{ route('dashboard') }}">← Dashboard</a></p>
@endsection
