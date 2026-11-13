@extends('layouts.app')
@section('title','Perjalanan Dinas')
@section('content')
<table>
<tr><th>No</th><th>Pemohon</th><th>Tujuan</th><th>Berangkat</th><th>Status</th><th></th></tr>
@forelse($requests as $r)
<tr><td>{{ $r->request_number }}</td><td>{{ $r->requester->name }}</td><td>{{ $r->destination }}</td>
<td>{{ $r->departure_at->format('d-m-Y H:i') }}</td><td>{{ $r->status }}</td>
<td><a href="{{ route('admin.perjalanan-dinas.requests.show',$r) }}" class="btn btn-sm">Detail</a></td></tr>
@empty<tr><td colspan="6">Belum ada pengajuan.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $requests->links() }}</div>
@endsection
