@extends('layouts.app')
@section('title','Kelola User')
@section('content')
<table>
<tr><th>Nama</th><th>Email</th><th>Role Aktif</th><th>Aksi</th></tr>
@forelse($users as $u)
<tr>
<td>{{ $u->name }}</td>
<td>{{ $u->email }}</td>
<td>{{ $u->roles->pluck('name')->join(', ') ?: '-' }}</td>
<td><a href="{{ route('super-admin.users.edit',$u) }}" class="btn btn-sm">Atur Role</a></td>
</tr>
@empty<tr><td colspan="4">Belum ada user.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $users->links() }}</div>
@endsection
