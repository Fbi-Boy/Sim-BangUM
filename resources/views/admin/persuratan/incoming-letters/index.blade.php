@extends('layouts.app')
@section('title','Surat Masuk')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
<h2 style="font-size:20px">Surat Masuk</h2>
<a href="{{ route('admin.persuratan.incoming-letters.create') }}" class="btn">+ Tambah Surat</a>
</div>
<div class="card" style="padding:0;overflow:hidden">
<table>
<tr><th>Agenda</th><th>Pengirim</th><th>Perihal</th><th>Status</th><th>Aksi</th></tr>
@forelse($letters as $l)
<tr>
<td>{{ $l->register_year }}/{{ str_pad($l->agenda_sequence,4,'0',STR_PAD_LEFT) }}</td>
<td>{{ $l->sender_name }}</td>
<td>{{ $l->subject }}</td>
<td><span style="background:#f3f4f6;padding:2px 8px;border-radius:4px;font-size:12px">{{ $l->status }}</span></td>
<td><a href="{{ route('admin.persuratan.incoming-letters.show',$l) }}" class="btn btn-sm btn-secondary">Detail</a></td>
</tr>
@empty
<tr><td colspan="5" style="text-align:center;padding:24px;color:#9ca3af">Belum ada surat masuk.</td></tr>
@endforelse
</table>
</div>
<div style="margin-top:16px">{{ $letters->links() }}</div>
@endsection
