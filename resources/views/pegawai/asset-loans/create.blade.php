@extends('layouts.app')
@section('title', 'Ajukan Peminjaman Aset')
@section('content')
<h1>Ajukan Peminjaman Aset</h1>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route('pegawai.asset-loans.store') }}">
@csrf
<p>Aset *<br>
<select name="asset_id" required>
<option value="">-- pilih --</option>
@foreach($assets as $a)<option value="{{ $a->id }}" {{ old('asset_id')==$a->id?'selected':'' }}>{{ $a->name }} ({{ $a->asset_code }})</option>@endforeach
</select>
</p>
<p>Mulai *<br><input type="date" name="starts_at" required value="{{ old('starts_at') }}"></p>
<p>Rencana Kembali *<br><input type="date" name="expected_return_at" required value="{{ old('expected_return_at') }}"></p>
<p>Tujuan *<br><textarea name="purpose" rows="3" cols="50" required>{{ old('purpose') }}</textarea></p>
<button>Ajukan</button> <a href="{{ route('pegawai.asset-loans.index') }}">Batal</a>
</form>
@endsection
