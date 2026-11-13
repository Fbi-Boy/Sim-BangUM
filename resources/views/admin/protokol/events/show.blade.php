@extends('layouts.app')
@section('title','Detail Kegiatan')
@section('content')
<div class="card">
<h2>{{ $event->title }}</h2>
<p><strong>{{ $event->event_number }}</strong> · {{ $event->event_type }} · {{ $event->status }}</p>
<p>Jadwal: {{ $event->starts_at->format('d-m-Y H:i') }} s/d {{ $event->ends_at->format('d-m-Y H:i') }}</p>
<p>Lokasi: {{ $event->location ?? '-' }}</p>
@if($event->description)<p>{{ $event->description }}</p>@endif
</div>

<div class="card">
<h3>Rundown / Agenda ({{ $event->agendas->count() }})</h3>
<table>
<tr><th>#</th><th>Acara</th><th>Waktu</th><th>PIC</th></tr>
@forelse($event->agendas as $a)
<tr><td>{{ $a->sequence_number }}</td><td>{{ $a->agenda_title }}</td>
<td>{{ $a->starts_at->format('H:i') }} - {{ $a->ends_at->format('H:i') }}</td>
<td>{{ $a->person_in_charge ?? '-' }}</td></tr>
@empty<tr><td colspan="4">Belum ada agenda.</td></tr>@endforelse
</table>
<form method="POST" action="{{ route('admin.protokol.events.agendas.store',$event) }}" style="margin-top:12px">@csrf
<input name="agenda_title" placeholder="Nama acara" required style="width:200px">
<input type="datetime-local" name="starts_at" required>
<input type="datetime-local" name="ends_at" required>
<input name="person_in_charge" placeholder="PIC" style="width:120px">
<button class="btn btn-sm">Tambah Agenda</button>
</form>
</div>

<div class="card">
<h3>Checklist Tugas ({{ $event->tasks->count() }})</h3>
<table>
<tr><th>Tugas</th><th>PIC</th><th>Tenggat</th><th>Prioritas</th><th>Status</th><th></th></tr>
@forelse($event->tasks as $t)
<tr><td>{{ $t->task_title }}</td><td>{{ $t->assignee->name ?? '-' }}</td>
<td>{{ $t->due_at?->format('d-m-Y') ?? '-' }}</td><td>{{ $t->priority }}</td><td>{{ $t->status }}</td>
<td>@if($t->status !== 'completed')
<form method="POST" action="{{ route('admin.protokol.tasks.complete',$t) }}" style="display:inline">@csrf
<button class="btn btn-sm">Selesai</button></form>
@endif</td></tr>
@empty<tr><td colspan="6">Belum ada tugas.</td></tr>@endforelse
</table>
<form method="POST" action="{{ route('admin.protokol.events.tasks.store',$event) }}" style="margin-top:12px">@csrf
<input name="task_title" placeholder="Nama tugas" required style="width:200px">
<select name="assigned_to_user_id"><option value="">-- PIC --</option>
@foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select>
<input type="datetime-local" name="due_at">
<select name="priority"><option value="normal">Normal</option><option value="high">High</option><option value="urgent">Urgent</option><option value="low">Low</option></select>
<button class="btn btn-sm">Tambah Tugas</button>
</form>
</div>

<a href="{{ route('admin.protokol.events.index') }}">← Kembali</a>
@endsection
