@extends('layouts.app')
@section('title', 'Draft Surat Keluar')
@section('content')
<h1>Draft Surat Keluar</h1>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route('admin.persuratan.outgoing-letters.store') }}">
@csrf
<p>Perihal *<br><input name="subject" required value="{{ old('subject') }}" size="60"></p>
<p>Tanggal<br><input type="date" name="letter_date" value="{{ old('letter_date') }}"></p>
<p>Ringkasan Isi<br><textarea name="body_summary" rows="3" cols="60">{{ old('body_summary') }}</textarea></p>
<button>Simpan Draft</button> <a href="{{ route('admin.persuratan.outgoing-letters.index') }}">Batal</a>
</form>
@endsection
