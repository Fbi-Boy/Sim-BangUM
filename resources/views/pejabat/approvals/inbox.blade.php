@extends('layouts.app')
@section('title','Inbox Persetujuan')
@section('content')
<h2 style="margin-bottom:16px">Persetujuan Menunggu</h2>

@php
$sections = [
  ['label'=>'í³¦ Peminjaman Aset','type'=>'asset-loan','items'=>$asset_loans,'titleFn'=>fn($i)=>$i->asset->name,'byFn'=>fn($i)=>$i->requester->name],
  ['label'=>'íº— Permohonan Kendaraan','type'=>'vehicle-request','items'=>$vehicle_requests,'titleFn'=>fn($i)=>$i->destination,'byFn'=>fn($i)=>$i->requester->name],
  ['label'=>'í¿¢ Booking Ruangan','type'=>'room-booking','items'=>$room_bookings,'titleFn'=>fn($i)=>$i->room->name.' â€” '.$i->purpose,'byFn'=>fn($i)=>$i->requester->name],
  ['label'=>'í³Ž Permintaan ATK','type'=>'stock-request','items'=>$stock_requests,'titleFn'=>fn($i)=>$i->purpose,'byFn'=>fn($i)=>$i->requester->name],
  ['label'=>'âœˆï¸ Perjalanan Dinas','type'=>'travel-request','items'=>$travel_requests,'titleFn'=>fn($i)=>$i->destination,'byFn'=>fn($i)=>$i->requester->name],
];
@endphp

@foreach($sections as $s)
<div class="card">
<h3 style="color:#1e40af;margin-bottom:12px">{{ $s['label'] }} ({{ $s['items']->count() }})</h3>
<table>
<tr><th>Ringkasan</th><th>Pemohon</th><th>Keputusan</th></tr>
@forelse($s['items'] as $item)
<tr>
<td>{{ $s['titleFn']($item) }}</td>
<td>{{ $s['byFn']($item) }}</td>
<td>
<form method="POST" action="{{ route('pejabat.approvals.decide', [$s['type'], $item->id]) }}" style="display:inline">@csrf
<input name="note" placeholder="Catatan" style="width:120px;display:inline-block">
<input type="hidden" name="decision" value="approve">
<button class="btn btn-sm">Setujui</button>
</form>
<form method="POST" action="{{ route('pejabat.approvals.decide', [$s['type'], $item->id]) }}" style="display:inline">@csrf
<input name="note" placeholder="Alasan tolak" required style="width:120px;display:inline-block">
<input type="hidden" name="decision" value="reject">
<button class="btn btn-sm btn-danger">Tolak</button>
</form>
</td>
</tr>
@empty<tr><td colspan="3" style="color:#6b7280">Tidak ada antrean.</td></tr>@endforelse
</table>
</div>
@endforeach
@endsection
