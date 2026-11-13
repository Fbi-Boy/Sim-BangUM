@extends('layouts.app')
@section('title','Kelola Role')
@section('content')
<a href="{{ route('super-admin.roles.create') }}" class="btn">+ Tambah Role</a>
<table style="margin-top:16px">
<tr><th>Kode</th><th>Nama</th><th>Deskripsi</th><th>Jumlah User</th></tr>
@forelse($roles as $r)
<tr><td><code>{{ $r->code }}</code></td><td>{{ $r->name }}</td>
<td>{{ $r->description ?? '-' }}</td><td>{{ $r->users_count }}</td></tr>
@empty<tr><td colspan="4">Belum ada role.</td></tr>@endforelse
</table>
@endsection
