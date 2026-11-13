@extends('layouts.app')
@section('title','Permohonan Kendaraan')
@section('content')
<h2 style="font-size:20px;margin-bottom:16px">Permohonan Kendaraan</h2>
<div class="card" style="padding:0;overflow:hidden">
<table>
<tr><th>No</th><th>Pemohon</th><th>Tujuan</th><th>Jadwal</th><th>Status</th><th>Aksi</th></tr>
@forelse($requests as $r)
<tr>
<td>{{ $r->request_number }}</td>
<td>{{ $r->requester->name }}</td>
<td>{{ $r->destination }}</td>
<td>{{ $r->starts_at->format('d-m-Y H:i') }}</td>
<td><span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:12px">{{ $r->status }}</span></td>
<td>
@if($r->status === 'submitted')
<form method="POST" action="{{ route('admin.kendaraan.requests.approve',$r) }}" style="display:inline">@csrf
<select name="vehicle_id" required style="width:120px;display:inline-block;padding:4px;font-size:12px">
<option value="">-- Mobil --</option>
@foreach(App\Models\Vehicle::where('status','available')->get() as $v)<option value="{{ $v->id }}">{{ $v->license_plate }}</option>@endforeach
</select>
<select name="driver_user_id" required style="width:120px;display:inline-block;padding:4px;font-size:12px">
<option value="">-- Driver --</option>
@foreach($drivers as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
</select>
<button class="btn btn-sm">Tugaskan</button>
</form>
@endif
</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center;padding:24px;color:#9ca3af">Belum ada permohonan.</td></tr>
@endforelse
</table>
</div>
<div style="margin-top:16px">{{ $requests->links() }}</div>
@endsection
