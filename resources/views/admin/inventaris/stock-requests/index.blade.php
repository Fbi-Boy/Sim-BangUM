@extends('layouts.app')
@section('title', 'Permintaan ATK')
@section('content')
<h1>Permintaan ATK</h1>


<table border="1" cellpadding="6"><tr><th>No</th><th>Pemohon</th><th>Keperluan</th><th>Item</th><th>Status</th><th>Aksi</th></tr>
@forelse($requests as $r)<tr>
<td>{{ $r->request_number }}</td><td>{{ $r->requester->name }}</td><td>{{ $r->purpose }}</td>
<td>@foreach($r->items as $it){{ $it->supplyItem->name }} (minta {{ $it->requested_quantity }}, setuju {{ $it->approved_quantity ?? '-' }}, keluar {{ $it->issued_quantity }}) {{ $it->supplyItem->unit }}<br>@endforeach</td>
<td>{{ $r->status }}</td>
<td>
@if($r->status === 'submitted')
<form method="POST" action="{{ route('admin.inventaris.stock-requests.approve',$r) }}">@csrf
@foreach($r->items as $it)<input type="number" step="0.01" name="approved_qty[{{ $it->id }}]" value="{{ $it->requested_quantity }}" min="0" style="width:70px"> {{ $it->supplyItem->name }}<br>@endforeach
<button>Setujui</button></form>
<form method="POST" action="{{ route('admin.inventaris.stock-requests.reject',$r) }}">@csrf
<input name="rejection_reason" placeholder="Alasan" required><button>Tolak</button></form>
@elseif($r->status === 'approved')
<form method="POST" action="{{ route('admin.inventaris.stock-requests.issue',$r) }}">@csrf<button>Keluarkan Stok</button></form>
@endif
</td></tr>
@empty<tr><td colspan="6">Belum ada permintaan.</td></tr>@endforelse</table>
<p>{{ $requests->links() }}</p><p><a href="{{ route('admin.inventaris.supply-items.index') }}">← Item ATK</a></p>
@endsection
