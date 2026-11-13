@extends('layouts.app')
@section('title','Audit Log')
@section('content')
<form method="GET" style="margin-bottom:16px">
<input name="action" placeholder="Filter aksi..." value="{{ request('action') }}" style="width:200px;display:inline-block">
<input name="user" placeholder="Filter user..." value="{{ request('user') }}" style="width:150px;display:inline-block">
<button class="btn btn-sm">Filter</button>
<a href="{{ route('super-admin.activity-log.index') }}" class="btn btn-sm" style="background:#6b7280">Reset</a>
</form>
<table>
<tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Deskripsi</th><th>IP</th></tr>
@forelse($logs as $l)
<tr><td style="white-space:nowrap">{{ $l->created_at->format('d-m-Y H:i:s') }}</td>
<td>{{ $l->actor->name ?? 'system' }}</td>
<td><code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:12px">{{ $l->action }}</code></td>
<td>{{ $l->description ?? '-' }}</td>
<td style="font-size:12px;color:#6b7280">{{ $l->ip_address ?? '-' }}</td></tr>
@empty<tr><td colspan="5">Belum ada aktivitas.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $logs->links() }}</div>
@endsection
