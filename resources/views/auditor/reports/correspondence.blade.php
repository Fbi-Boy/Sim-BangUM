@extends('layouts.app')
@section('title','Laporan Persuratan')
@section('content')
<a href="{{ route('auditor.reports.index') }}">← Kembali</a>
<a href="{{ route('auditor.export', 'incoming-letters') }}" class="btn" style="float:right">⬇ Export CSV</a>
<div class="card" style="margin-top:16px">
<h3>Surat Masuk ({{ $incoming->total() }})</h3>
<table>
<tr><th>Agenda</th><th>Pengirim</th><th>Perihal</th><th>Tanggal</th><th>Status</th></tr>
@forelse($incoming as $l)
<tr><td>{{ $l->register_year }}/{{ str_pad($l->agenda_sequence,4,"0",STR_PAD_LEFT) }}</td><td>{{ $l->sender_name }}</td><td>{{ $l->subject }}</td><td>{{ $l->letter_date?->format("d-m-Y") }}</td><td>{{ $l->status }}</td></tr>
@empty<tr><td colspan="5">Tidak ada.</td></tr>@endforelse
</table>
<div style="margin-top:8px">{{ $incoming->links() }}</div>
</div>
<div class="card">
<h3>Surat Keluar ({{ $outgoing->total() }})</h3>
<table>
<tr><th>Agenda</th><th>Perihal</th><th>Status</th></tr>
@forelse($outgoing as $l)
<tr><td>{{ $l->register_year }}/{{ str_pad($l->agenda_sequence,4,"0",STR_PAD_LEFT) }}</td><td>{{ $l->subject }}</td><td>{{ $l->status }}</td></tr>
@empty<tr><td colspan="3">Tidak ada.</td></tr>@endforelse
</table>
<div style="margin-top:8px">{{ $outgoing->links() }}</div>
</div>
@endsection
