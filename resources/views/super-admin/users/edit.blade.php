@extends('layouts.app')
@section('title','Atur Role User')
@section('content')
<div class="card" style="max-width:600px">
<h2>{{ $user->name }}</h2>
<p style="color:#6b7280">{{ $user->email }}</p>
<form method="POST" action="{{ route('super-admin.users.roles.update',$user) }}" style="margin-top:16px">
@csrf @method('PUT')
<h3 style="margin-bottom:12px">Centang role yang diberikan:</h3>
@foreach($roles as $r)
<label style="display:block;padding:8px;border-bottom:1px solid #e5e7eb">
<input type="checkbox" name="roles[]" value="{{ $r->id }}" style="width:auto;margin-right:8px"
    {{ $user->roles->contains($r->id) ? 'checked' : '' }}>
<strong>{{ $r->name }}</strong> <small style="color:#6b7280">({{ $r->code }})</small>
</label>
@endforeach
<div style="margin-top:16px">
<button class="btn">Simpan</button>
<a href="{{ route('super-admin.users.index') }}" style="margin-left:12px">Batal</a>
</div>
</form>
</div>
@endsection
