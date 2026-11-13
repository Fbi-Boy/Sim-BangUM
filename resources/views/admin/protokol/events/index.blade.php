@extends('layouts.app')
@section('title','Kegiatan')
@section('content')
<a href="{{ route('admin.protokol.events.create') }}" class="btn">+ Buat Kegiatan</a>
<div style="margin-top:16px">
<table>
<tr><th>No</th><th>Judul</th><th>Jenis</th><th>Jadwal</th><th>Lokasi</th><th>Status</th><th></th></tr>
@forelse($events as $e)
<tr><td>{{ $e->event_number }}</td><td>{{ $e->title }}</td><td>{{ $e->event_type }}</td>
<td>{{ $e->starts_at->format('d-m-Y H:i') }}</td><td>{{ $e->location ?? '-' }}</td><td>{{ $e->status }}</td>
<td><a href="{{ route('admin.protokol.events.show',$e) }}" class="btn btn-sm">Detail</a></td></tr>
@empty<tr><td colspan="7">Belum ada kegiatan.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $events->links() }}</div>
</div>
@endsection
