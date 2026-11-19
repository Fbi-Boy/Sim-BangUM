@extends('layouts.app')
@section('title', 'Peminjaman Aset')

@section('content')
<style>
    .asset-page { max-width:1000px; margin:auto; color:var(--text-main); }
    .as-head { margin-bottom:22px; }
    .as-head a { display:inline-block; color:var(--text-muted); text-decoration:none; font-size:12px; margin-bottom:12px; }
    .as-head h2 { font-size:23px; font-weight:750; margin:0 0 6px; }
    .as-head p { color:var(--text-muted); font-size:12px; margin:0; }
    .as-layout { display:grid; grid-template-columns:minmax(0,1fr) 270px; gap:18px; align-items:start; }
    .as-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:15px; padding:23px; margin-bottom:17px; }
    .as-card h3 { font-size:14px; margin:0 0 5px; }
    .as-card > p { font-size:11px; color:var(--text-muted); margin:0 0 20px; }
    .as-field { margin-bottom:17px; }
    .as-label { display:block; font-size:11px; font-weight:650; margin-bottom:7px; }
    .as-control { width:100%; box-sizing:border-box; border:1px solid var(--border-color); border-radius:9px; padding:11px 12px; background:var(--bg-soft); color:var(--text-main); font-size:12px; outline:none; }
    .as-control:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-soft); }
    textarea.as-control { min-height:105px; resize:vertical; }
    .as-grid { display:grid; grid-template-columns:1fr 1fr; gap:0 14px; }
    .as-error { color:#dc6464; font-size:10px; margin-top:5px; }
    .as-preview { padding:14px; border-radius:11px; background:var(--bg-soft); border:1px solid var(--border-color); margin-bottom:18px; }
    .as-preview small { display:block; color:var(--text-muted); font-size:10px; margin-bottom:5px; }
    .as-preview strong { font-size:12px; }
    .as-side { position:sticky; top:18px; }
    .as-info { padding:17px; border-radius:13px; background:var(--accent-soft); border:1px solid var(--border-color); }
    .as-info h4 { font-size:12px; margin:0 0 9px; }
    .as-info p { color:var(--text-muted); font-size:10.5px; line-height:1.8; margin:0; }
    .as-actions { display:flex; justify-content:flex-end; gap:9px; }
    .as-btn { display:inline-flex; justify-content:center; align-items:center; padding:11px 17px; border-radius:9px; border:1px solid var(--border-color); text-decoration:none; font-size:11px; font-weight:700; cursor:pointer; }
    .as-btn.primary { background:var(--accent); border-color:transparent; color:#fff; }
    .as-btn.secondary { background:var(--bg-soft); color:var(--text-main); }
    @media(max-width:760px) { .as-layout { grid-template-columns:1fr; } .as-side { position:static; } }
    @media(max-width:520px) { .as-grid { grid-template-columns:1fr; } .as-card { padding:17px; } .as-actions { flex-direction:column-reverse; } .as-btn { width:100%; box-sizing:border-box; } }
</style>

<div class="asset-page">
    <div class="as-head">
        <a href="{{ route('pegawai.asset-loans.index') }}">← Kembali ke peminjaman aset</a>
        <h2>Pengajuan Peminjaman Aset</h2>
        <p>Pilih aset yang diperlukan dan tentukan periode peminjaman.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:18px;">
            <strong>Periksa kembali formulir peminjaman.</strong>
            <ul style="margin:7px 0 0 18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pegawai.asset-loans.store') }}">
        @csrf

        <div class="as-layout">
            <div>
                <section class="as-card">
                    <h3>Pilih aset</h3>
                    <p>Pilih aset yang akan digunakan untuk menunjang pekerjaan.</p>

                    <div class="as-field">
                        <label class="as-label" for="asset_id">Aset yang dipinjam *</label>
                        <select class="as-control" id="asset_id" name="asset_id" required>
                            <option value="">Pilih aset</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}"
                                    data-name="{{ $asset->name }}"
                                    data-code="{{ $asset->asset_code }}"
                                    @selected(old('asset_id') == $asset->id)>
                                    {{ $asset->name }} — {{ $asset->asset_code }}
                                </option>
                            @endforeach
                        </select>
                        @error('asset_id')<div class="as-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="as-preview" id="assetPreview">
                        <small>Aset yang dipilih</small>
                        <strong id="assetPreviewName">Belum ada aset dipilih</strong>
                        <small style="margin-top:9px;">Kode aset</small>
                        <strong id="assetPreviewCode">-</strong>
                    </div>

                    <div class="as-field" style="margin-bottom:0;">
                        <label class="as-label" for="purpose">Keperluan peminjaman *</label>
                        <textarea class="as-control" id="purpose" name="purpose" required placeholder="Jelaskan tujuan penggunaan aset...">{{ old('purpose') }}</textarea>
                        @error('purpose')<div class="as-error">{{ $message }}</div>@enderror
                    </div>
                </section>

                <section class="as-card">
                    <h3>Periode peminjaman</h3>
                    <p>Tentukan kapan aset mulai digunakan dan kapan akan dikembalikan.</p>

                    <div class="as-grid">
                        <div class="as-field">
                            <label class="as-label" for="starts_at">Mulai peminjaman *</label>
                            <input class="as-control" type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required>
                            @error('starts_at')<div class="as-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="as-field">
                            <label class="as-label" for="expected_return_at">Rencana pengembalian *</label>
                            <input class="as-control" type="datetime-local" id="expected_return_at" name="expected_return_at" value="{{ old('expected_return_at') }}" required>
                            @error('expected_return_at')<div class="as-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </section>

                <div class="as-actions">
                    <a href="{{ route('pegawai.asset-loans.index') }}" class="as-btn secondary">Batal</a>
                    <button type="submit" class="as-btn primary">Ajukan Peminjaman →</button>
                </div>
            </div>

            <aside class="as-side">
                <div class="as-info">
                    <h4>Ketentuan peminjaman</h4>
                    <p>Pastikan aset yang dipilih sesuai kebutuhan pekerjaan. Gunakan aset dengan bertanggung jawab dan kembalikan sesuai jadwal. Pengajuan akan diproses sesuai prosedur peminjaman aset kantor.</p>
                </div>
            </aside>
        </div>
    </form>
</div>

<script>
(function () {
    const select = document.getElementById('asset_id');
    const previewName = document.getElementById('assetPreviewName');
    const previewCode = document.getElementById('assetPreviewCode');
    const start = document.getElementById('starts_at');
    const end = document.getElementById('expected_return_at');

    function updateAssetPreview() {
        const option = select.options[select.selectedIndex];

        if (!select.value) {
            previewName.textContent = 'Belum ada aset dipilih';
            previewCode.textContent = '-';
            return;
        }

        previewName.textContent = option.dataset.name || option.textContent.trim();
        previewCode.textContent = option.dataset.code || '-';
    }

    select.addEventListener('change', updateAssetPreview);
    updateAssetPreview();

    start.addEventListener('change', function () {
        end.min = start.value || '';
        if (start.value && end.value && end.value < start.value) end.value = '';
    });

    end.addEventListener('change', function () {
        if (start.value && end.value && end.value < start.value) {
            alert('Tanggal pengembalian harus setelah tanggal mulai peminjaman.');
            end.value = '';
        }
    });
})();
</script>
@endsection
