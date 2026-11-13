@extends('layouts.app')
@section('title','Disposisi Saya')
@section('content')
<p style="color:#6b7280;margin-bottom:16px">Surat yang didisposisikan kepada Anda:</p>
<table>
<tr><th>Surat</th><th>Pengirim</th><th>Instruksi</th><th>Prioritas</th><th>Tenggat</th><th>Status</th><th></th></tr>
@forelse($dispositions as $d)
<tr>
<td>{{ $d->incomingLetter->subject }}</td>
<td>{{ $d->fromUser->name }}</td>
<td>{{ Str::limit($d->instruction, 60) }}</td>
<td>{{ $d->priority }}</td>
<td>{{ $d->due_at?->format('d-m-Y') ?? '-' }}</td>
<td><strong>{{ $d->status }}</strong></td>
<td><a href="{{ route('pegawai.dispositions.show', $d) }}" class="btn btn-sm">Buka</a></td>
</tr>
@empty<tr><td colspan="7">Belum ada disposisi untuk Anda.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $dispositions->links() }}</div>
@endsection
