
@extends('layouts.app')

@section('title', 'Tambah Item ATK')

@section('content')
<div class="atk-page">
    <div class="atk-heading">
        <div>
            <a href="{{ route('admin.inventaris.supply-items.index') }}" class="atk-back">
                <span>←</span> Kembali ke daftar ATK
            </a>
            <h1>Tambah Item ATK</h1>
            <p>Tambahkan data barang alat tulis kantor ke inventaris.</p>
        </div>
        <div class="atk-icon">▤</div>
    </div>

    @if($errors->any())
        <div class="atk-alert">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.inventaris.supply-items.store') }}">
        @csrf

        <div class="atk-card">
            <div class="atk-card-heading">
                <div>
                    <h3>Informasi Barang</h3>
                    <p>Lengkapi identitas dan klasifikasi item ATK.</p>
                </div>
                <span class="atk-required">* Wajib diisi</span>
            </div>

            <div class="atk-grid">
                <div class="atk-field">
                    <label>SKU <span>*</span></label>
                    <input type="text" name="sku" required
                        value="{{ old('sku') }}" placeholder="Contoh: ATK-001">
                </div>

                <div class="atk-field">
                    <label>Nama Barang <span>*</span></label>
                    <input type="text" name="name" required
                        value="{{ old('name') }}" placeholder="Masukkan nama barang">
                </div>

                <div class="atk-field">
                    <label>Kategori <span>*</span></label>
                    <select name="supply_category_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('supply_category_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="atk-field">
                    <label>Satuan <span>*</span></label>
                    <input type="text" name="unit" required
                        value="{{ old('unit') }}" placeholder="Rim / Box / Pcs">
                </div>
            </div>
        </div>

        <div class="atk-card">
            <div class="atk-card-heading">
                <div>
                    <h3>Pengaturan Persediaan</h3>
                    <p>Tentukan jumlah stok awal dan batas minimum persediaan.</p>
                </div>
                <span class="atk-stock-icon">▥</span>
            </div>

            <div class="atk-grid">
                <div class="atk-field">
                    <label>Stok Awal <span>*</span></label>
                    <input type="number" step="0.01" min="0"
                        name="current_stock" required
                        value="{{ old('current_stock', 0) }}">
                    <small>Jumlah barang yang tersedia saat ini.</small>
                </div>

                <div class="atk-field">
                    <label>Minimum Stok <span>*</span></label>
                    <input type="number" step="0.01" min="0"
                        name="minimum_stock" required
                        value="{{ old('minimum_stock', 0) }}">
                    <small>Batas stok untuk mengantisipasi kehabisan barang.</small>
                </div>
            </div>
        </div>

        <div class="atk-actions">
            <a href="{{ route('admin.inventaris.supply-items.index') }}" class="atk-btn atk-btn-secondary">
                Batal
            </a>
            <button type="submit" class="atk-btn atk-btn-primary">
                Simpan Item ATK
            </button>
        </div>
    </form>
</div>

<style>
.atk-page {
    --atk-accent: #269b78;
    --atk-soft: rgba(38,155,120,.10);
    max-width: 900px;
    margin: 0 auto;
    color: var(--text-main);
}
.atk-heading {
    display:flex; justify-content:space-between; align-items:center;
    margin-bottom:22px; gap:16px;
}
.atk-back {
    display:inline-flex; gap:7px; align-items:center;
    color:var(--text-muted, #84909d); text-decoration:none;
    font-size:13px; margin-bottom:12px;
}
.atk-back:hover { color:var(--atk-accent); }
.atk-heading h1 { font-size:25px; margin:0 0 5px; font-weight:700; }
.atk-heading p,.atk-card-heading p {
    margin:0; color:var(--text-muted, #84909d); font-size:13px;
}
.atk-icon {
    width:52px; height:52px; display:grid; place-items:center;
    border-radius:14px; background:var(--atk-soft);
    color:var(--atk-accent); font-size:25px;
}
.atk-card {
    background:var(--bg-card); border:1px solid var(--border-color);
    border-radius:14px; padding:23px; margin-bottom:17px;
}
.atk-card-heading {
    display:flex; align-items:center; justify-content:space-between;
    gap:12px; margin-bottom:22px;
}
.atk-card-heading h3 { font-size:16px; margin:0 0 5px; }
.atk-required { font-size:11px; color:var(--text-muted, #84909d); }
.atk-stock-icon { color:var(--atk-accent); font-size:21px; }
.atk-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:19px; }
.atk-field { display:flex; flex-direction:column; gap:8px; min-width:0; }
.atk-field label { font-size:13px; font-weight:600; }
.atk-field label span { color:#e05d5d; }
.atk-field input,.atk-field select {
    width:100%; min-height:43px; box-sizing:border-box;
    border:1px solid var(--border-color); border-radius:8px;
    padding:10px 12px; background:var(--bg-soft);
    color:var(--text-main); font:inherit; font-size:13px;
}
.atk-field input:focus,.atk-field select:focus {
    outline:none; border-color:var(--atk-accent);
    box-shadow:0 0 0 3px var(--atk-soft);
}
.atk-field small { font-size:11px; color:var(--text-muted, #84909d); }
.atk-alert {
    padding:14px 17px; border-radius:10px; margin-bottom:18px;
    border:1px solid rgba(220,38,38,.25);
    background:rgba(220,38,38,.08); color:var(--text-main); font-size:13px;
}
.atk-alert ul { margin:8px 0 0 18px; color:#dc6262; }
.atk-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.atk-btn {
    display:inline-flex; align-items:center; justify-content:center;
    min-height:41px; padding:0 17px; border-radius:8px;
    text-decoration:none; font-size:13px; font-weight:600;
    cursor:pointer; border:1px solid transparent;
}
.atk-btn-secondary {
    background:var(--bg-card); border-color:var(--border-color);
    color:var(--text-main);
}
.atk-btn-primary { background:var(--atk-accent); color:white; }
.atk-btn:hover { filter:brightness(.96); }
@media(max-width:600px) {
    .atk-grid { grid-template-columns:1fr; }
    .atk-card { padding:17px; }
    .atk-heading h1 { font-size:21px; }
}
</style>
@endsection
