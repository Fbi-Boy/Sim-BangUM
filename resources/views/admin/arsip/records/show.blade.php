@extends('layouts.app')
@section('title','Detail Arsip')
@section('content')
<div class="card">
<h2>{{ $record->title }}</h2>
<p style="margin-top:8px"><strong>Kode:</strong> {{ $record->archive_code }}</p>
<p><strong>Klasifikasi:</strong> {{ $record->classification->code }} — {{ $record->classification->name }}</p>
<p><strong>Tanggal:</strong> {{ $record->record_date?->format('d-m-Y') ?? '-' }}</p>
<p><strong>Lokasi Fisik:</strong> {{ $record->physical_location ?? '-' }}</p>
<p><strong>Status:</strong> {{ $record->archive_status }}</p>
<p><strong>Deskripsi:</strong> {{ $record->description ?? '-' }}</p>
</div>

<div class="card">
<h3 style="margin-bottom:12px">Lampiran ({{ $record->documents->count() }})</h3>
<table>
<tr><th>Nama File</th><th>Ukuran</th><th>Aksi</th></tr>
@forelse($record->documents as $doc)
<tr><td>{{ $doc->original_filename }}</td><td>{{ $doc->humanSize() }}</td>
<td><a href="{{ route('admin.arsip.records.download',[$record,$doc]) }}" class="btn btn-sm">Download</a></td></tr>
@empty<tr><td colspan="3">Belum ada lampiran.</td></tr>@endforelse
</table>
<form method="POST" action="{{ route('admin.arsip.records.upload',$record) }}" enctype="multipart/form-data" style="margin-top:12px">@csrf
<input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
<button class="btn btn-sm">Upload</button>
<span style="font-size:12px;color:#6b7280;margin-left:8px">Maks 10MB</span>
</form>
</div>
<a href="{{ route('admin.arsip.records.index') }}">← Kembali</a>
@endsection
