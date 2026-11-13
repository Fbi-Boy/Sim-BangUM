@extends('layouts.app')
@section('title', 'Ajukan Permintaan ATK')
@section('content')
<h1>Ajukan Permintaan ATK</h1>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route('pegawai.stock-requests.store') }}">@csrf
<p>Keperluan *<br><textarea name="purpose" rows="2" cols="60" required>{{ old('purpose') }}</textarea></p>
<p>Dibutuhkan Tanggal<br><input type="date" name="needed_at" value="{{ old('needed_at') }}"></p>
<h3>Item (isi minimal 1, kosongkan lainnya)</h3>
@for($i=0;$i<5;$i++)
<p><select name="items[{{ $i }}][id]"><option value="">-- pilih --</option>
@foreach($items as $it)<option value="{{ $it->id }}" {{ old("items.$i.id")==$it->id?'selected':'' }}>{{ $it->name }} (stok {{ $it->current_stock }} {{ $it->unit }})</option>@endforeach
</select>
<input type="number" step="0.01" name="items[{{ $i }}][qty]" min="0" value="{{ old("items.$i.qty") }}" placeholder="qty" style="width:80px"></p>
@endfor
<button>Ajukan</button> <a href="{{ route('pegawai.stock-requests.index') }}">Batal</a>
@endsection
