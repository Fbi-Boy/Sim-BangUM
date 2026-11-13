@extends('layouts.app')
@section('title','Detail Disposisi')
@section('content')
<div class="card">
<h2>{{ $disposition->incomingLetter->subject }}</h2>
<p style="margin-top:8px"><strong>Dari:</strong> {{ $disposition->fromUser->name }}</p>
<p><strong>Perihal:</strong> {{ $disposition->incomingLetter->subject }}</p>
<p><strong>Instruksi:</strong> {{ $disposition->instruction }}</p>
<p><strong>Prioritas:</strong> {{ $disposition->priority }}</p>
<p><strong>Tenggat:</strong> {{ $disposition->due_at?->format('d-m-Y') ?? '-' }}</p>
<p><strong>Status saat ini:</strong> {{ $disposition->status }}</p>
</div>

<div class="card">
<h3 style="margin-bottom:12px">Riwayat Progres ({{ $disposition->updates->count() }})</h3>
<table>
<tr><th>Waktu</th><th>Oleh</th><th>Status</th><th>Catatan</th></tr>
@forelse($disposition->updates as $u)
<tr><td>{{ $u->created_at->format('d-m-Y H:i') }}</td><td>{{ $u->user->name }}</td>
<td>{{ $u->status }}</td><td>{{ $u->progress_note }}</td></tr>
@empty<tr><td colspan="4">Belum ada update.</td></tr>@endforelse
</table>
</div>

<div class="card">
<h3 style="margin-bottom:12px">Update Progres</h3>
<form method="POST" action="{{ route('pegawai.dispositions.update', $disposition) }}">@csrf
<p style="margin-bottom:12px"><label>Status Baru</label><br>
<select name="status" required>
<option value="accepted">Diterima</option>
<option value="in_progress">Sedang dikerjakan</option>
<option value="completed">Selesai</option>
<option value="returned">Dikembalikan</option>
</select></p>
<p style="margin-bottom:12px"><label>Catatan Progres *</label><br>
<textarea name="progress_note" rows="3" required style="width:100%;max-width:600px"></textarea></p>
<button class="btn">Simpan Update</button>
</form>
</div>

<a href="{{ route('pegawai.dispositions.index') }}">← Kembali ke daftar disposisi</a>
@endsection
