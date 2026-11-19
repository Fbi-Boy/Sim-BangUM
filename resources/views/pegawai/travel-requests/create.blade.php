@extends('layouts.app')
@section('title', 'Pengajuan Perjalanan Dinas')

@section('content')
<style>
    .trip-page { max-width:1000px; margin:auto; color:var(--text-main); }
    .trip-head { margin-bottom:22px; }
    .trip-head a { display:inline-block; color:var(--text-muted); text-decoration:none; font-size:12px; margin-bottom:12px; }
    .trip-head h2 { font-size:23px; margin:0 0 6px; font-weight:750; }
    .trip-head p { font-size:12px; color:var(--text-muted); margin:0; }
    .trip-layout { display:grid; grid-template-columns:minmax(0,1fr) 270px; gap:18px; align-items:start; }
    .trip-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:15px; padding:23px; margin-bottom:17px; }
    .trip-card h3 { font-size:14px; margin:0 0 4px; }
    .trip-card > p { color:var(--text-muted); font-size:11px; margin:0 0 20px; }
    .trip-grid { display:grid; grid-template-columns:1fr 1fr; gap:0 14px; }
    .trip-field { margin-bottom:17px; min-width:0; }
    .trip-field.full { grid-column:1/-1; }
    .trip-label { display:block; font-size:11px; font-weight:650; margin-bottom:7px; }
    .trip-control { width:100%; box-sizing:border-box; padding:11px 12px; border:1px solid var(--border-color); border-radius:9px; background:var(--bg-soft); color:var(--text-main); outline:none; font-size:12px; }
    .trip-control:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-soft); }
    textarea.trip-control { min-height:105px; resize:vertical; }
    .trip-error { color:#dc6464; font-size:10px; margin-top:5px; }
    .trip-info { position:sticky; top:18px; padding:17px; border-radius:13px; background:var(--accent-soft); border:1px solid var(--border-color); }
    .trip-info h4 { margin:0 0 8px; font-size:12px; }
    .trip-info p { margin:0; font-size:10.5px; line-height:1.8; color:var(--text-muted); }
    .trip-actions { display:flex; justify-content:flex-end; gap:9px; }
    .trip-btn { padding:11px 17px; border-radius:9px; font-size:11px; font-weight:700; border:1px solid var(--border-color); cursor:pointer; text-decoration:none; display:inline-flex; justify-content:center; }
    .trip-btn.primary { background:var(--accent); color:white; border-color:transparent; }
    .trip-btn.secondary { background:var(--bg-soft); color:var(--text-main); }
    @media(max-width:760px) { .trip-layout { grid-template-columns:1fr; } .trip-info { position:static; } }
    @media(max-width:520px) { .trip-grid { grid-template-columns:1fr; } .trip-field.full { grid-column:auto; } .trip-card { padding:17px; } .trip-actions { flex-direction:column-reverse; } .trip-btn { width:100%; box-sizing:border-box; } }
</style>

<div class="trip-page">
    <div class="trip-head">
        <a href="{{ route('pegawai.travel-requests.index') }}">← Kembali ke pengajuan perjalanan</a>
        <h2>Pengajuan Perjalanan Dinas</h2>
        <p>Lengkapi rencana perjalanan dinas untuk diajukan kepada pihak terkait.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:18px;">
            <strong>Periksa kembali isian formulir.</strong>
            <ul style="margin:7px 0 0 18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pegawai.travel-requests.store') }}">
        @csrf
        <div class="trip-layout">
            <div>
                <section class="trip-card">
                    <h3>Tujuan dan keperluan</h3>
                    <p>Informasi dasar mengenai perjalanan yang akan dilakukan.</p>

                    <div class="trip-field">
                        <label class="trip-label" for="destination">Tujuan perjalanan *</label>
                        <input class="trip-control" id="destination" name="destination" value="{{ old('destination') }}" required placeholder="Kota atau lokasi tujuan">
                        @error('destination')<div class="trip-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="trip-field" style="margin-bottom:0;">
                        <label class="trip-label" for="purpose">Keperluan perjalanan *</label>
                        <textarea class="trip-control" id="purpose" name="purpose" required placeholder="Jelaskan tujuan dan kegiatan yang akan dilakukan...">{{ old('purpose') }}</textarea>
                        @error('purpose')<div class="trip-error">{{ $message }}</div>@enderror
                    </div>
                </section>

                <section class="trip-card">
                    <h3>Jadwal perjalanan</h3>
                    <p>Masukkan waktu keberangkatan dan kepulangan.</p>
                    <div class="trip-grid">
                        <div class="trip-field">
                            <label class="trip-label" for="departure_at">Keberangkatan *</label>
                            <input class="trip-control" type="datetime-local" id="departure_at" name="departure_at" value="{{ old('departure_at') }}" required>
                            @error('departure_at')<div class="trip-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="trip-field">
                            <label class="trip-label" for="return_at">Kepulangan *</label>
                            <input class="trip-control" type="datetime-local" id="return_at" name="return_at" value="{{ old('return_at') }}" required>
                            @error('return_at')<div class="trip-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <section class="trip-card">
                    <h3>Transportasi dan peserta</h3>
                    <p>Tentukan moda transportasi serta peserta yang ikut dalam perjalanan.</p>
                    <div class="trip-grid">
                        <div class="trip-field full">
                            <label class="trip-label" for="transport_mode">Moda transportasi *</label>
                            <select class="trip-control" id="transport_mode" name="transport_mode" required>
                                <option value="">Pilih transportasi</option>
                                @foreach(['dinas'=>'Kendaraan dinas','pribadi'=>'Kendaraan pribadi','umum'=>'Transportasi umum','lainnya'=>'Lainnya'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('transport_mode') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('transport_mode')<div class="trip-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="trip-field full" style="margin-bottom:0;">
                            <label class="trip-label" for="participants">Peserta perjalanan</label>
                            <textarea class="trip-control" id="participants" name="participants" placeholder="Tuliskan nama peserta lain jika ada...">{{ old('participants') }}</textarea>
                            <small style="font-size:10px;color:var(--text-muted);display:block;margin-top:6px;">Isi sesuai format yang diterima controller aplikasi.</small>
                            @error('participants')<div class="trip-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <div class="trip-actions">
                    <a href="{{ route('pegawai.travel-requests.index') }}" class="trip-btn secondary">Batal</a>
                    <button type="submit" class="trip-btn primary">Kirim Pengajuan →</button>
                </div>
            </div>

            <aside class="trip-info">
                <h4>Periksa sebelum mengirim</h4>
                <p>Pastikan tujuan dan keperluan perjalanan ditulis dengan jelas. Periksa kembali tanggal keberangkatan, kepulangan, serta moda transportasi yang digunakan. Data yang lengkap membantu proses pemeriksaan lebih cepat.</p>
            </aside>
        </div>
    </form>
</div>

<script>
(function () {
    const departure = document.getElementById('departure_at');
    const returning = document.getElementById('return_at');
    if (!departure || !returning) return;

    departure.addEventListener('change', function () {
        returning.min = departure.value || '';
        if (departure.value && returning.value && returning.value < departure.value) returning.value = '';
    });

    returning.addEventListener('change', function () {
        if (departure.value && returning.value && returning.value < departure.value) {
            alert('Waktu kepulangan tidak boleh lebih awal dari keberangkatan.');
            returning.value = '';
        }
    });
})();
</script>
@endsection
