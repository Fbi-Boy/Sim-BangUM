@extends('layouts.app')
@section('title', 'Pengajuan Kendaraan')

@section('content')
<style>
    .vehicle-page { max-width:1000px; margin:auto; color:var(--text-main); }
    .vh-head { margin-bottom:22px; }
    .vh-head a { display:inline-block; text-decoration:none; color:var(--text-muted); font-size:12px; margin-bottom:12px; }
    .vh-head h2 { font-size:23px; margin:0 0 6px; font-weight:750; }
    .vh-head p { font-size:12px; color:var(--text-muted); margin:0; }
    .vh-layout { display:grid; grid-template-columns:minmax(0,1fr) 270px; gap:18px; align-items:start; }
    .vh-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:15px; padding:23px; margin-bottom:17px; }
    .vh-title { font-size:14px; font-weight:700; margin:0 0 4px; }
    .vh-subtitle { color:var(--text-muted); font-size:11px; margin:0 0 20px; }
    .vh-grid { display:grid; grid-template-columns:1fr 1fr; gap:0 14px; }
    .vh-field { margin-bottom:17px; min-width:0; }
    .vh-field.full { grid-column:1/-1; }
    .vh-label { display:block; font-size:11px; font-weight:650; margin-bottom:7px; }
    .vh-control { width:100%; box-sizing:border-box; border:1px solid var(--border-color); border-radius:9px; padding:11px 12px; background:var(--bg-soft); color:var(--text-main); outline:none; font-size:12px; }
    .vh-control:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-soft); }
    textarea.vh-control { resize:vertical; min-height:100px; }
    .vh-error { font-size:10px; color:#dc6464; margin-top:5px; }
    .vh-side { position:sticky; top:18px; }
    .vh-info { background:var(--accent-soft); border:1px solid var(--border-color); border-radius:13px; padding:17px; }
    .vh-info h4 { font-size:12px; margin:0 0 8px; }
    .vh-info p,.vh-info li { font-size:10.5px; color:var(--text-muted); line-height:1.7; }
    .vh-info ul { padding-left:16px; margin-bottom:0; }
    .vh-actions { display:flex; justify-content:flex-end; gap:9px; }
    .vh-btn { border:0; border-radius:9px; padding:11px 17px; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; display:inline-flex; justify-content:center; }
    .vh-primary { background:var(--accent); color:white; }
    .vh-secondary { background:var(--bg-soft); color:var(--text-main); border:1px solid var(--border-color); }
    @media(max-width:760px) { .vh-layout { grid-template-columns:1fr; } .vh-side { position:static; } }
    @media(max-width:520px) { .vh-grid { grid-template-columns:1fr; } .vh-field.full { grid-column:auto; } .vh-card { padding:17px; } .vh-actions { flex-direction:column-reverse; } .vh-btn { width:100%; box-sizing:border-box; } }
</style>

<div class="vehicle-page">
    <div class="vh-head">
        <a href="{{ route('pegawai.vehicle-requests.index') }}">← Kembali ke pengajuan kendaraan</a>
        <h2>Pengajuan Kendaraan</h2>
        <p>Ajukan penggunaan kendaraan dinas untuk mendukung kegiatan pekerjaan.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:18px;">
            <strong>Pengajuan belum dapat disimpan.</strong>
            <ul style="margin:7px 0 0 18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pegawai.vehicle-requests.store') }}">
        @csrf
        <div class="vh-layout">
            <div>
                <div class="vh-card">
                    <h3 class="vh-title">Tujuan penggunaan</h3>
                    <p class="vh-subtitle">Jelaskan kebutuhan kendaraan dan tujuan perjalanan.</p>

                    <div class="vh-field">
                        <label class="vh-label" for="destination">Tujuan perjalanan *</label>
                        <input class="vh-control" id="destination" name="destination" value="{{ old('destination') }}" required placeholder="Contoh: Kantor Kecamatan">
                        @error('destination')<div class="vh-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="vh-field" style="margin-bottom:0;">
                        <label class="vh-label" for="purpose">Keperluan *</label>
                        <textarea class="vh-control" id="purpose" name="purpose" required placeholder="Jelaskan keperluan penggunaan kendaraan...">{{ old('purpose') }}</textarea>
                        @error('purpose')<div class="vh-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="vh-card">
                    <h3 class="vh-title">Jadwal dan penumpang</h3>
                    <p class="vh-subtitle">Tentukan rentang penggunaan kendaraan.</p>

                    <div class="vh-grid">
                        <div class="vh-field">
                            <label class="vh-label" for="starts_at">Waktu berangkat *</label>
                            <input class="vh-control" type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required>
                            @error('starts_at')<div class="vh-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="vh-field">
                            <label class="vh-label" for="ends_at">Waktu selesai *</label>
                            <input class="vh-control" type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') }}" required>
                            @error('ends_at')<div class="vh-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="vh-field full" style="margin-bottom:0;">
                            <label class="vh-label" for="passenger_count">Jumlah penumpang *</label>
                            <input class="vh-control" type="number" id="passenger_count" name="passenger_count" min="1" value="{{ old('passenger_count', 1) }}" required>
                            <small style="display:block;color:var(--text-muted);font-size:10px;margin-top:6px;">Masukkan jumlah seluruh penumpang, termasuk pemohon jika ikut.</small>
                            @error('passenger_count')<div class="vh-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="vh-actions">
                    <a href="{{ route('pegawai.vehicle-requests.index') }}" class="vh-btn vh-secondary">Batal</a>
                    <button type="submit" class="vh-btn vh-primary">Kirim Pengajuan →</button>
                </div>
            </div>

            <aside class="vh-side">
                <div class="vh-info">
                    <h4>Informasi pengajuan</h4>
                    <p>Pengajuan akan diteruskan untuk diperiksa oleh petugas yang berwenang. Pastikan jadwal dan jumlah penumpang sudah sesuai.</p>
                    <ul>
                        <li>Isi tujuan secara spesifik.</li>
                        <li>Jelaskan keperluan dengan singkat.</li>
                        <li>Pastikan waktu selesai setelah keberangkatan.</li>
                        <li>Sesuaikan jumlah penumpang dengan kebutuhan.</li>
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
        end.min = start.value || '';
        if (start.value && end.value && end.value < start.value) end.value = '';
    });

    end.addEventListener('change', function () {
        if (start.value && end.value && end.value < start.value) {
            alert('Waktu selesai harus setelah waktu keberangkatan.');
            end.value = '';
        }
    });
})();
</script>
@endsection
