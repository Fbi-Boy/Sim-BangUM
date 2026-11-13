@extends('layouts.app')
@section('title','Tambah Surat Masuk')
@section('content')
<div style="margin-bottom:16px"><a href="{{ route('admin.persuratan.incoming-letters.index') }}" style="color:#6b7280;text-decoration:none">← Kembali</a></div>
<div class="card" style="max-width:600px">
<h2>Registrasi Surat Masuk</h2>
@if($errors->any())<div class="alert alert-error"><ul style="margin-left:16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('admin.persuratan.incoming-letters.store') }}">@csrf
<p style="margin-bottom:12px"><label>Pengirim *</label><input name="sender_name" required value="{{ old('sender_name') }}"></p>
<p style="margin-bottom:12px"><label>Perihal *</label><input name="subject" required value="{{ old('subject') }}"></p>
<p style="margin-bottom:12px"><label>Nomor Surat</label><input name="external_letter_number" value="{{ old('external_letter_number') }}"></p>
<p style="margin-bottom:12px"><label>Tanggal Surat</label><input type="date" name="letter_date" value="{{ old('letter_date') }}"></p>
<p style="margin-bottom:16px"><label>Ringkasan</label><textarea name="summary" rows="3">{{ old('summary') }}</textarea></p>
<button class="btn">Simpan</button> <a href="{{ route('admin.persuratan.incoming-letters.index') }}" class="btn btn-secondary">Batal</a>
</form>
</div>
@endsection
