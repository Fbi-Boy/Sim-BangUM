
@extends('layouts.app')

@section('title', 'Tambah BMD')

@section('content')
<div class="bmd-page">
    <div class="bmd-heading">
        <div>
            <a href="{{ route('admin.barang-daerah.assets.index') }}" class="bmd-back">
                ← Kembali ke daftar BMD
            </a>
            <h1>Tambah Barang Milik Daerah</h1>
            <p>Daftarkan aset baru ke dalam sistem inventaris barang milik daerah.</p>
        </div>
        <div class="bmd-icon">▣</div>
    </div>

    @if($errors->any())
        <div class="bmd-alert">
            <strong>Terjadi kesalahan pada input data.</strong>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.barang-daerah.assets.store') }}">
        @csrf

        <div class="bmd-card">
            <div class="bmd-card-heading">
                <h3>Identitas Aset</h3>
                <span>* Wajib diisi</span>
            </div>

            <div class="bmd-fields">
                <div class="bmd-field">
                    <label>Kode BMD <span>*</span></label>
                    <input type="text" name="bmd_code" required
                        value="{{ old('bmd_code') }}" placeholder="Masukkan kode BMD">
                </div>

                <div class="bmd-field">
                    <label>Nama Aset <span>*</span></label>
                    <input type="text" name="name" required
                        value="{{ old('name') }}" placeholder="Masukkan nama aset">
                </div>

                <div class="bmd-field bmd-full">
                    <label>Kategori</label>
                    <input type="text" name="category"
                        value="{{ old('category') }}" placeholder="Contoh: Peralatan dan Mesin">
                </div>
            </div>
        </div>

        <div class="bmd-card">
            <div class="bmd-card-heading">
                <h3>Informasi Perolehan</h3>
                <span>Data aset</span>
            </div>

            <div class="bmd-fields">
                <div class="bmd-field">
                    <label>Tahun Perolehan</label>
                    <input type="number" name="year_acquired"
                        value="{{ old('year_acquired') }}" min="1900" max="2100"
                        placeholder="Contoh: 2024">
                </div>

                <div class="bmd-field">
                    <label>Nilai Perolehan (Rp)</label>
                    <input type="number" step="0.01" min="0"
                        name="acquisition_value"
                        value="{{ old('acquisition_value') }}" placeholder="0">
                    <small>Masukkan nilai aset saat pertama kali diperoleh.</small>
                </div>
            </div>
        </div>

        <div class="bmd-actions">
            <a href="{{ route('admin.barang-daerah.assets.index') }}" class="bmd-btn bmd-secondary">Batal</a>
            <button type="submit" class="bmd-btn bmd-primary">Simpan BMD</button>
        </div>
    </form>
</div>

<style>
.bmd-page {
    --bmd-accent:#3979c9;
    --bmd-soft:rgba(57,121,201,.10);
    max-width:850px; margin:0 auto; color:var(--text-main);
}
.bmd-heading { display:flex; justify-content:space-between; align-items:center; gap:15px; margin-bottom:22px; }
.bmd-back { display:block; text-decoration:none; color:var(--text-muted,#84909d); font-size:13px; margin-bottom:12px; }
.bmd-back:hover { color:var(--bmd-accent); }
.bmd-heading h1 { margin:0 0 6px; font-size:24px; }
.bmd-heading p { margin:0; font-size:13px; color:var(--text-muted,#84909d); }
.bmd-icon { width:52px; height:52px; display:grid; place-items:center; border-radius:14px; background:var(--bmd-soft); color:var(--bmd-accent); font-size:25px; }
.bmd-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:13px; padding:23px; margin-bottom:17px; }
.bmd-card-heading { display:flex; justify-content:space-between; align-items:center; margin-bottom:21px; }
.bmd-card-heading h3 { margin:0; font-size:16px; }
.bmd-card-heading span { font-size:11px; color:var(--text-muted,#84909d); }
.bmd-fields { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:19px; }
.bmd-full { grid-column:1/-1; }
.bmd-field { display:flex; flex-direction:column; gap:8px; }
.bmd-field label { font-size:13px; font-weight:600; }
.bmd-field label span { color:#dc6262; }
.bmd-field input {
    width:100%; box-sizing:border-box; min-height:43px;
    padding:10px 12px; border:1px solid var(--border-color);
    border-radius:8px; background:var(--bg-soft);
    color:var(--text-main); font:inherit; font-size:13px;
}
.bmd-field input:focus { outline:none; border-color:var(--bmd-accent); box-shadow:0 0 0 3px var(--bmd-soft); }
.bmd-field small { color:var(--text-muted,#84909d); font-size:11px; }
.bmd-alert { padding:14px 17px; margin-bottom:18px; border-radius:10px; background:rgba(220,38,38,.08); border:1px solid rgba(220,38,38,.25); color:var(--text-main); font-size:13px; }
.bmd-alert ul { margin:8px 0 0 18px; color:#dc6262; }
.bmd-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.bmd-btn { display:inline-flex; align-items:center; justify-content:center; min-height:41px; padding:0 17px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600; cursor:pointer; border:1px solid transparent; }
.bmd-secondary { background:var(--bg-card); border-color:var(--border-color); color:var(--text-main); }
.bmd-primary { background:var(--bmd-accent); color:white; }
@media(max-width:600px) { .bmd-fields { grid-template-columns:1fr; } .bmd-full { grid-column:auto; } .bmd-card { padding:17px; } .bmd-heading h1 { font-size:20px; } }
</style>
@endsection
