@extends('layouts.app')
@section('title', 'Permintaan ATK Saya')
@section('content')
<h1>Permintaan ATK Saya</h1>

<p><a href="{{ route('pegawai.stock-requests.create') }}">+ Ajukan</a></p>
<table border="1" cellpadding="6"><tr><th>No</th><th>Keperluan</th><th>Item</th><th>Status</th></tr>
@forelse($requests as $r)<tr><td>{{ $r->request_number }}</td><td>{{ $r->purpose }}</td>
<td>@foreach($r->items as $it){{ $it->supplyItem->name }} x{{ $it->requested_quantity }}<br>@endforeach</td>
<td>{{ $r->status }}</td></tr>
@empty<tr><td colspan="4">Belum ada permintaan.</td></tr>@endforelse</table>
<p>{{ $requests->links() }}</p><p><a href="{{ route('dashboard') }}">← Dashboard</a></p>
@endsection
