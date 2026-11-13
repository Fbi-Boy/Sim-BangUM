@extends('layouts.app')
@section('title', 'Surat Keluar')
@section('content')
<h1>Surat Keluar</h1>

<p><a href="{{ route('admin.persuratan.outgoing-letters.create') }}">+ Tambah Surat</a></p>
<table border="1" cellpadding="6" cellspacing="0">
<tr><th>Agenda</th><th>Perihal</th><th>Status</th></tr>
@forelse($letters as $l)
<tr><td>{{ $l->register_year }}/{{ str_pad($l->agenda_sequence,4,'0',STR_PAD_LEFT) }}</td><td>{{ $l->subject }}</td><td>{{ $l->status }}</td></tr>
@empty
<tr><td colspan="3">Belum ada surat.</td></tr>
@endforelse
</table>
<p>{{ $letters->links() }}</p>
<p><a href="{{ route('dashboard') }}">← Dashboard</a></p>
@endsection
