@extends('layouts.app')
@section('title','Detail Tugas')
@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif

<div class="card">
<h2>{{ $task->vehicle->license_plate }} — {{ $task->vehicle->make }} {{ $task->vehicle->model }}</h2>
<p style="margin-top:8px"><strong>Tujuan:</strong> {{ $task->request->destination }}</p>
<p><strong>Keperluan:</strong> {{ $task->request->purpose }}</p>
<p><strong>Penumpang:</strong> {{ $task->request->passenger_count }} orang</p>
<p><strong>Jadwal:</strong> {{ $task->planned_start_at->format('d-m-Y H:i') }} s/d {{ $task->planned_end_at->format('d-m-Y H:i') }}</p>
<p><strong>Ditugaskan oleh:</strong> {{ $task->assigner->name }}</p>
<p><strong>Status:</strong> <strong>{{ $task->status }}</strong></p>
@if($task->odometer_start)<p><strong>Km Awal:</strong> {{ $task->odometer_start }}</p>@endif
@if($task->odometer_end)<p><strong>Km Akhir:</strong> {{ $task->odometer_end }}</p>@endif
</div>

@if($task->status === 'assigned')
<div class="card">
<h3>Konfirmasi Penugasan</h3>
<form method="POST" action="{{ route('pengemudi.tasks.confirm',$task) }}" style="margin-top:8px">@csrf
<button class="btn">Saya Terima Tugas Ini</button>
</form>
</div>
@elseif($task->status === 'confirmed')
<div class="card">
<h3>Mulai Perjalanan</h3>
<form method="POST" action="{{ route('pengemudi.tasks.depart',$task) }}" style="margin-top:8px">@csrf
<p style="margin-bottom:12px"><label>Km Awal *</label><br>
<input type="number" step="0.1" name="odometer_start" required style="width:150px"></p>
<button class="btn">Mulai Berangkat</button>
</form>
</div>
@elseif($task->status === 'departed')
<div class="card">
<h3>Selesai Perjalanan</h3>
<form method="POST" action="{{ route('pengemudi.tasks.return',$task) }}" style="margin-top:8px">@csrf
<p style="margin-bottom:12px"><label>Km Akhir *</label><br>
<input type="number" step="0.1" name="odometer_end" required min="{{ $task->odometer_start }}" style="width:150px"></p>
<p style="margin-bottom:12px"><label>Catatan Kondisi</label><br>
<textarea name="return_notes" rows="2" style="width:100%;max-width:500px"></textarea></p>
<button class="btn">Selesai</button>
</form>
</div>
@endif

@if($task->logs->count() > 0)
<div class="card">
<h3 style="margin-bottom:12px">Riwayat Log</h3>
<table>
<tr><th>Waktu</th><th>Event</th><th>Odometer</th><th>Catatan</th></tr>
@foreach($task->logs as $l)
<tr><td>{{ $l->happened_at->format('d-m-Y H:i') }}</td><td>{{ $l->event_type }}</td>
<td>{{ $l->odometer ?? '-' }}</td><td>{{ $l->note ?? '-' }}</td></tr>
@endforeach
</table>
</div>
@endif

<a href="{{ route('pengemudi.tasks.index') }}">← Kembali</a>
@endsection
