@extends('layouts.app')
@section('title','Perjalanan Dinas Saya')
@section('content')
<a href="{{ route('pegawai.travel-requests.create') }}" class="btn">+ Ajukan</a>
<table style="margin-top:16px">
<tr><th>No</th><th>Tujuan</th><th>Berangkat</th><th>Status</th></tr>
@forelse($requests as $r)
<tr><td>{{ $r->request_number }}</td><td>{{ $r->destination }}</td>
<td>{{ $r->departure_at->format('d-m-Y H:i') }}</td><td>{{ $r->status }}</td></tr>
@empty<tr><td colspan="4">Belum ada pengajuan.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $requests->links() }}</div>
@endsection
