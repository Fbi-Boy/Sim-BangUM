@extends('layouts.app')
@section('title', 'Buat Kegiatan')

@section('content')
<style>
    .event-page { max-width: 1100px; margin: auto; color: var(--text-main); }
    .event-hero { display:flex; justify-content:space-between; align-items:flex-start; gap:20px; margin-bottom:24px; }
    .event-hero h2 { margin:0 0 7px; font-size:24px; font-weight:750; letter-spacing:-.6px; }
    .event-hero p { margin:0; color:var(--text-muted); font-size:13px; }
    .event-back { display:inline-flex; align-items:center; gap:7px; color:var(--text-muted); text-decoration:none; font-size:12px; margin-bottom:12px; }
    .event-back:hover { color:var(--accent); }
    .event-layout { display:grid; grid-template-columns:minmax(0,1fr) 290px; gap:20px; align-items:start; }
    .event-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:16px; padding:24px; margin-bottom:18px; }
    .event-card-head { display:flex; align-items:center; gap:12px; margin-bottom:22px; }
    .event-icon { width:40px; height:40px; display:grid; place-items:center; border-radius:12px; background:var(--accent-soft); color:var(--accent); flex-shrink:0; }
    .event-card-head h3 { font-size:14px; margin:0 0 3px; font-weight:700; }
    .event-card-head p { font-size:11px; color:var(--text-muted); margin:0; }
    .event-grid { display:grid; grid-template-columns:1fr 1fr; gap:0 16px; }
    .event-field { margin-bottom:18px; min-width:0; }
    .event-field.full { grid-column:1/-1; }
    .event-label { display:block; font-size:12px; font-weight:650; margin-bottom:8px; color:var(--text-main); }
    .event-label span { color:#dc6464; }
    .event-control { width:100%; box-sizing:border-box; padding:11px 13px; border:1px solid var(--border-color); background:var(--bg-soft); color:var(--text-main); border-radius:10px; outline:none; font-size:13px; transition:.2s; }
    .event-control:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-soft); background:var(--bg-card); }
    .event-control::placeholder { color:var(--text-muted); }
    textarea.event-control { resize:vertical; min-height:110px; }
    .event-hint { display:block; margin-top:6px; font-size:10.5px; color:var(--text-muted); }
    .event-error { color:#dc6464; font-size:11px; margin-top:6px; }
    .event-side { position:sticky; top:20px; }
    .event-tip { background:var(--accent-soft); border:1px solid var(--border-color); border-radius:14px; padding:18px; }
    .event-tip h4 { font-size:12px; margin:0 0 10px; }
    .event-tip p { font-size:11px; line-height:1.7; color:var(--text-muted); margin:0; }
    .event-tip ul { padding-left:17px; margin:12px 0 0; font-size:11px; line-height:1.9; color:var(--text-muted); }
    .event-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:20px; }
    .event-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:11px 18px; border-radius:10px; border:1px solid transparent; text-decoration:none; cursor:pointer; font-size:12px; font-weight:700; transition:.2s; }
    .event-btn.primary { background:var(--accent); color:#fff; }
    .event-btn.primary:hover { filter:brightness(.94); transform:translateY(-1px); }
    .event-btn.secondary { background:var(--bg-soft); border-color:var(--border-color); color:var(--text-main); }
    .event-btn.secondary:hover { border-color:var(--accent); }
    @media(max-width:850px) { .event-layout { grid-template-columns:1fr; } .event-side { position:static; } }
    @media(max-width:560px) { .event-hero { flex-direction:column; } .event-grid { grid-template-columns:1fr; } .event-field.full { grid-column:auto; } .event-card { padding:17px; } .event-actions { flex-direction:column-reverse; } .event-btn { width:100%; box-sizing:border-box; } }
</style>

<div class="event-page">
    <div class="event-hero">
        <div>
            <a href="{{ route('admin.protokol.events.index') }}" class="event-back">← Kembali ke daftar kegiatan</a>
            <h2>Buat Kegiatan</h2>
            <p>Rencanakan kegiatan kantor dan siapkan informasi pelaksanaannya.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:18px;">
            <strong>Periksa kembali data berikut:</strong>
            <ul style="margin:8px 0 0 18px;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.protokol.events.store') }}">
        @csrf
        <div class="event-layout">
            <div>
                <section class="event-card">
                    <div class="event-card-head">
                        <div class="event-icon">01</div>
                        <div>
                            <h3>Informasi Kegiatan</h3>
                            <p>Identitas utama kegiatan yang akan diselenggarakan.</p>
                        </div>
                    </div>

                    <div class="event-field">
                        <label class="event-label" for="title">Nama Kegiatan <span>*</span></label>
                        <input class="event-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Contoh: Rapat Koordinasi Bulanan">
                        @error('title')<div class="event-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="event-field">
                        <label class="event-label" for="event_type">Jenis Kegiatan <span>*</span></label>
                        <select class="event-control" id="event_type" name="event_type" required>
                            <option value="">Pilih jenis kegiatan</option>
                            @foreach(['rapat'=>'Rapat','upacara'=>'Upacara','kunjungan'=>'Kunjungan','lainnya'=>'Lainnya'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('event_type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('event_type')<div class="event-error">{{ $message }}</div>@enderror
                    </div>
                </section>

                <section class="event-card">
                    <div class="event-card-head">
                        <div class="event-icon">02</div>
                        <div>
                            <h3>Waktu dan Lokasi</h3>
                            <p>Tentukan kapan dan di mana kegiatan berlangsung.</p>
                        </div>
                    </div>

                    <div class="event-grid">
                        <div class="event-field">
                            <label class="event-label" for="starts_at">Mulai Kegiatan <span>*</span></label>
                            <input type="datetime-local" class="event-control" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required>
                            @error('starts_at')<div class="event-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="event-field">
                            <label class="event-label" for="ends_at">Selesai Kegiatan <span>*</span></label>
                            <input type="datetime-local" class="event-control" id="ends_at" name="ends_at" value="{{ old('ends_at') }}" required>
                            @error('ends_at')<div class="event-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="event-field full">
                            <label class="event-label" for="location">Lokasi <span>*</span></label>
                            <input class="event-control" id="location" name="location" value="{{ old('location') }}" required placeholder="Contoh: Ruang Rapat Utama">
                            @error('location')<div class="event-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <section class="event-card">
                    <div class="event-card-head">
                        <div class="event-icon">03</div>
                        <div>
                            <h3>Deskripsi dan Catatan</h3>
                            <p>Tambahkan konteks agar pelaksana memahami kegiatan.</p>
                        </div>
                    </div>
                    <div class="event-field" style="margin-bottom:0;">
                        <label class="event-label" for="description">Deskripsi</label>
                        <textarea class="event-control" id="description" name="description" rows="4" placeholder="Tuliskan tujuan, agenda umum, atau catatan penting...">{{ old('description') }}</textarea>
                        @error('description')<div class="event-error">{{ $message }}</div>@enderror
                    </div>
                </section>

                <div class="event-actions">
                    <a href="{{ route('admin.protokol.events.index') }}" class="event-btn secondary">Batal</a>
                    <button type="submit" class="event-btn primary">Simpan Kegiatan →</button>
                </div>
            </div>

            <aside class="event-side">
                <div class="event-tip">
                    <h4>ⓘ Panduan pengisian</h4>
                    <p>Pastikan informasi kegiatan sudah benar sebelum disimpan. Data ini akan menjadi dasar penyusunan agenda dan pembagian tugas.</p>
                    <ul>
                        <li>Gunakan nama kegiatan yang spesifik.</li>
                        <li>Pastikan waktu selesai setelah waktu mulai.</li>
                        <li>Tulis lokasi secara jelas.</li>
                        <li>Tambahkan deskripsi jika ada arahan khusus.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </form>
</div>

<script>
(function () {
    const start = document.getElementById('starts_at');
    const end = document.getElementById('ends_at');

    if (!start || !end) return;

    start.addEventListener('change', function () {
        if (start.value) {
            end.min = start.value;
            if (end.value && end.value < start.value) end.value = '';
        }
    });

    end.addEventListener('change', function () {
        if (start.value && end.value && end.value < start.value) {
            alert('Waktu selesai tidak boleh lebih awal dari waktu mulai.');
            end.value = '';
        }
    });
})();
</script>
@endsection
