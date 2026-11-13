@extends(layouts.app)
@section(title,Tugas Saya)
@section(content)
<h2 style="margin-bottom:16px">Penugasan Perjalanan</h2>
<table>
<tr><th>Kendaraan</th><th>Tujuan</th><th>Jadwal</th><th>Status</th><th></th></tr>
@forelse($tasks as $t)
<tr><td>{{ $t->vehicle->license_plate }}</td><td>{{ $t->request->destination }}</td><td>{{ $t->planned_start_at->format(d-m-Y H:i) }}</td><td><strong>{{ $t->status }}</strong></td><td><a href="{{ route(pengemudi.tasks.show,$t) }}" class="btn btn-sm">Buka</a></td></tr>
@empty<tr><td colspan="5">Belum ada penugasan.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $tasks->links() }}</div>
@endsection
