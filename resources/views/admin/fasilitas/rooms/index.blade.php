@extends('layouts.app')
@section('title','Ruangan Rapat')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
<h2 style="font-size:20px">Ruangan</h2>
<a href="{{ route('admin.fasilitas.rooms.create') }}" class="btn">+ Tambah Ruangan</a>
</div>
<div class="card" style="padding:0;overflow:hidden">
<table>
<tr><th>Kode</th><th>Nama Ruangan</th><th>Kapasitas</th><th>Tipe</th><th>Status</th></tr>
@forelse($rooms as $r)
<tr>
<td><strong>{{ $r->room_code }}</strong></td>
<td>{{ $r->name }}</td>
<td>{{ $r->capacity }} orang</td>
<td>{{ $r->room_type }}</td>
<td><span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:12px">{{ $r->status }}</span></td>
</tr>
@empty
<tr><td colspan="5" style="text-align:center;padding:24px;color:#9ca3af">Belum ada ruangan.</td></tr>
@endforelse
</table>
</div>
<div style="margin-top:16px">{{ $rooms->links() }}</div>
@endsection
