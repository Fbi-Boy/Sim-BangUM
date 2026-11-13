@extends('layouts.app')
@section('title','Laporan ATK')
@section('content')
<a href="{{ route('auditor.reports.index') }}">← Kembali</a>
<a href="{{ route('auditor.export', 'stock-requests') }}" class="btn" style="float:right">⬇ Export CSV</a>
<table style="margin-top:16px">
<tr><th>No</th><th>Pemohon</th><th>Item</th><th>Status</th></tr>
@forelse($requests as $r)
<tr><td>{{ $r->request_number }}</td><td>{{ $r->requester->name ?? '-' }}</td>
<td>@foreach($r->items as $it){{ $it->supplyItem->name ?? '-' }} x{{ $it->requested_quantity }}<br>@endforeach</td>
<td>{{ $r->status }}</td></tr>
@empty<tr><td colspan="4">Tidak ada.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $requests->links() }}</div>
@endsection
