@extends('layouts.app')
@section('title', 'Detail Surat Masuk')
@section('content')
<h1>Detail Surat Masuk</h1>


<h2>Informasi Surat</h2>
<table border="1" cellpadding="6" cellspacing="0">
<tr><th align="left">Agenda</th><td>{{ $incomingLetter->register_year }}/{{ str_pad($incomingLetter->agenda_sequence,4,'0',STR_PAD_LEFT) }}</td></tr>
<tr><th align="left">Nomor Surat</th><td>{{ $incomingLetter->external_letter_number ?? '-' }}</td></tr>
<tr><th align="left">Pengirim</th><td>{{ $incomingLetter->sender_name }}</td></tr>
<tr><th align="left">Perihal</th><td>{{ $incomingLetter->subject }}</td></tr>
<tr><th align="left">Tanggal Surat</th><td>{{ $incomingLetter->letter_date?->format('d-m-Y') ?? '-' }}</td></tr>
<tr><th align="left">Diterima</th><td>{{ $incomingLetter->received_at->format('d-m-Y H:i') }}</td></tr>
<tr><th align="left">Status</th><td>{{ $incomingLetter->status }}</td></tr>
<tr><th align="left">Ringkasan</th><td>{{ $incomingLetter->summary ?? '-' }}</td></tr>
</table>

<h2>Disposisi ({{ $dispositions->count() }})</h2>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
<tr><th>Dari</th><th>Kepada</th><th>Instruksi</th><th>Prioritas</th><th>Tenggat</th><th>Status</th></tr>
@forelse($dispositions as $d)
<tr>
<td>{{ $d->fromUser->name }}</td>
<td>{{ $d->toUser->name ?? '-' }}</td>
<td>{{ $d->instruction }}</td>
<td>{{ $d->priority }}</td>
<td>{{ $d->due_at?->format('d-m-Y') ?? '-' }}</td>
<td>{{ $d->status }}</td>
</tr>
@empty
<tr><td colspan="6">Belum ada disposisi.</td></tr>
@endforelse
</table>

<div class="card">
<h3 style="margin-bottom:12px">Lampiran ({{ $incomingLetter->documents->count() }})</h3>
<table>
<tr><th>Nama File</th><th>Ukuran</th><th>Diunggah</th><th>Aksi</th></tr>
@forelse($incomingLetter->documents as $doc)
<tr>
<td>{{ $doc->original_filename }}</td>
<td>{{ $doc->humanSize() }}</td>
<td>{{ $doc->created_at->format("d-m-Y H:i") }}</td>
<td>
<a href="{{ route("admin.persuratan.incoming-letters.documents.download", [$incomingLetter, $doc]) }}" class="btn btn-sm">Download</a>
<form method="POST" action="{{ route("admin.persuratan.incoming-letters.documents.delete", [$incomingLetter, $doc]) }}" style="display:inline" onsubmit="return confirm(\"Hapus lampiran?\")">@csrf @method("DELETE")
<button class="btn btn-sm btn-danger">Hapus</button></form>
</td>
</tr>
@empty<tr><td colspan="4">Belum ada lampiran.</td></tr>@endforelse
</table>
<form method="POST" action="{{ route("admin.persuratan.incoming-letters.documents.upload", $incomingLetter) }}" enctype="multipart/form-data" style="margin-top:12px">
@csrf
<input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
<input name="title" placeholder="Judul (opsional)" style="width:200px">
<button class="btn btn-sm">Upload</button>
<span style="font-size:12px;color:#6b7280;margin-left:8px">Maks 10MB · PDF/JPG/PNG/DOC</span>
</form>
</div>

<h2>Beri Disposisi Baru</h2>
@if($errors->any())<ul style="color:#dc2626">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<form method="POST" action="{{ route('admin.persuratan.incoming-letters.dispositions.store', $incomingLetter) }}">
@csrf
<p>Kepada *<br>
<select name="to_user_id" required>
<option value="">-- pilih pengguna --</option>
@foreach($users as $u)
<option value="{{ $u->id }}" {{ old('to_user_id')==$u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
@endforeach
</select>
</p>
<p>Instruksi *<br><textarea name="instruction" rows="3" cols="60" required>{{ old('instruction') }}</textarea></p>
<p>Prioritas *<br>
<select name="priority" required>
<option value="normal" {{ old('priority','normal')=='normal'?'selected':'' }}>Normal</option>
<option value="urgent" {{ old('priority')=='urgent'?'selected':'' }}>Urgent</option>
<option value="immediate" {{ old('priority')=='immediate'?'selected':'' }}>Immediate</option>
</select>
</p>
<p>Tenggat<br><input type="date" name="due_at" value="{{ old('due_at') }}"></p>
<button>Kirim Disposisi</button>
</form>

<p><a href="{{ route('admin.persuratan.incoming-letters.index') }}">← Kembali ke Daftar</a></p>
@endsection
