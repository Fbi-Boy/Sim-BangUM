@extends('layouts.app')
@section('title','Tambah Surat Masuk')

@section('content')
<style>
    /* Style khusus untuk form agar sesuai UI Kit */
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-main); margin-bottom: 8px; }
    .form-control {
        width: 100%; padding: 11px 14px; background: var(--bg-soft); border: 1px solid var(--border-color);
        border-radius: 10px; color: var(--text-main); font-size: 13.5px; outline: none; transition: .2s;
    }
    .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); background: var(--bg-card); }
    .form-control::placeholder { color: var(--text-muted); }

    .upload-box { border: 2px dashed var(--border-color); background: var(--bg-soft); padding: 40px 20px; border-radius: 16px; text-align: center; cursor: pointer; transition: .2s; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; }
    .upload-box:hover { border-color: var(--accent); background: var(--accent-soft); }
</style>

<div class="page-head" style="margin-bottom: 24px;">
    <div>
        <a href="{{ route('admin.persuratan.incoming-letters.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:12.5px; font-weight:500; display:inline-flex; align-items:center; gap:6px; margin-bottom:8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h2>Registrasi Surat Masuk</h2>
        <p>Lengkapi data surat dan unggah file PDF-nya.</p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-error" style="margin-bottom: 20px;">
        <ul style="margin-left:16px;">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.persuratan.incoming-letters.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: stretch;">

        <!-- KOLOM KIRI: Data Surat -->
        <div class="table-card" style="padding: 28px; margin-bottom: 0;">
            <div class="form-group">
                <label class="form-label">Nama Pengirim *</label>
                <input name="sender_name" required value="{{ old('sender_name') }}" class="form-control" placeholder="Instansi atau nama perorangan">
            </div>
            <div class="form-group">
                <label class="form-label">Perihal Surat *</label>
                <input name="subject" required value="{{ old('subject') }}" class="form-control" placeholder="Judul atau maksud surat">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nomor Surat</label>
                    <input name="external_letter_number" value="{{ old('external_letter_number') }}" class="form-control" placeholder="001/UND/2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" name="letter_date" value="{{ old('letter_date') }}" class="form-control">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Ringkasan / Catatan</label>
                <textarea name="summary" rows="3" class="form-control" placeholder="Tulis ringkasan singkat..." style="resize: vertical;">{{ old('summary') }}</textarea>
            </div>
        </div>

        <!-- KOLOM KANAN: Upload File -->
        <div class="table-card" style="padding: 28px; display: flex; flex-direction: column; margin-bottom: 0;">
            <label class="form-label" style="margin-bottom: 12px;">Lampiran Scan (PDF/Gambar)</label>
            <div class="upload-box" onclick="document.getElementById('fileInput').click()">
                <div class="empty-ico" style="margin-bottom: 12px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div style="font-size: 13.5px; font-weight: 600; color: var(--text-main);" id="fileName">Pilih File / Drag & Drop</div>
                <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 4px;">Maksimal 10MB</div>
                <input type="file" name="document" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" onchange="document.getElementById('fileName').innerText = this.files[0].name">
            </div>
        </div>
    </div>

    <div style="margin-top: 24px; display: flex; gap: 12px;">
        <button type="submit" class="btn" style="padding: 12px 24px; font-size: 14px;">Simpan Surat</button>
        <a href="{{ route('admin.persuratan.incoming-letters.index') }}" class="btn" style="padding: 12px 24px; font-size: 14px; background: var(--bg-soft); color: var(--text-main); border: 1px solid var(--border-color);">Batal</a>
    </div>
</form>
@endsection
