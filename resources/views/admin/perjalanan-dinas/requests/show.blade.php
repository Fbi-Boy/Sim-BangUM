@extends('layouts.app')
@section('title','Detail Perjalanan Dinas')
@section('content')
<div class="card">
<h2>{{ $travelRequest->request_number }}</h2>
<p>Pemohon: {{ $travelRequest->requester->name }}</p>
<p>Tujuan: {{ $travelRequest->destination }}</p>
<p>Keperluan: {{ $travelRequest->purpose }}</p>
<p>Berangkat: {{ $travelRequest->departure_at->format('d-m-Y H:i') }}</p>
<p>Kembali: {{ $travelRequest->return_at->format('d-m-Y H:i') }}</p>
<p>Transport: {{ $travelRequest->transport_mode ?? '-' }}</p>
<p>Status: <strong>{{ $travelRequest->status }}</strong></p>
@if($travelRequest->rejection_reason)<p style="color:#dc2626">Alasan tolak: {{ $travelRequest->rejection_reason }}</p>@endif
</div>

<div class="card">
<h3>Peserta ({{ $travelRequest->participants->count() }})</h3>
<ul>@foreach($travelRequest->participants as $p)<li>{{ $p->participant_name }} ({{ $p->participant_role }})</li>@endforeach</ul>
</div>

@if($travelRequest->status === 'submitted')
<div class="card">
<form method="POST" action="{{ route('admin.perjalanan-dinas.requests.approve',$travelRequest) }}" style="display:inline">@csrf
<button class="btn">Setujui</button></form>
<form method="POST" action="{{ route('admin.perjalanan-dinas.requests.reject',$travelRequest) }}" style="display:inline">@csrf
<input name="rejection_reason" placeholder="Alasan" required style="width:200px">
<button class="btn btn-danger">Tolak</button></form>
</div>
@endif
<a href="{{ route('admin.perjalanan-dinas.requests.index') }}">← Kembali</a>
@endsection
