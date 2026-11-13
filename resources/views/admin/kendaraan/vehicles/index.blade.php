@extends('layouts.app')
@section('title','Kendaraan Dinas')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
<h2 style="font-size:20px">Kendaraan Dinas</h2>
<a href="{{ route('admin.kendaraan.vehicles.create') }}" class="btn">+ Tambah Kendaraan</a>
</div>
<div class="card" style="padding:0;overflow:hidden">
<table>
<tr><th>No Polisi</th><th>Merek</th><th>Model</th><th>Kapasitas</th><th>Status</th></tr>
@forelse($vehicles as $v)
<tr>
<td><strong>{{ $v->license_plate }}</strong></td>
<td>{{ $v->make }}</td>
<td>{{ $v->model }}</td>
<td>{{ $v->passenger_capacity }} org</td>
<td><span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:12px">{{ $v->status }}</span></td>
</tr>
@empty
<tr><td colspan="5" style="text-align:center;padding:24px;color:#9ca3af">Belum ada kendaraan.</td></tr>
@endforelse
</table>
</div>
<div style="margin-top:16px">{{ $vehicles->links() }}</div>
@endsection
