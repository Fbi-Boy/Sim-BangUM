
@extends('layouts.app')

@section('title', 'Detail BMD')

@section('content')
<div class="bmd-detail">
    <div class="bmd-detail-heading">
        <div>
            <a href="{{ route('admin.barang-daerah.assets.index') }}" class="bmd-detail-back">
                ← Kembali ke daftar BMD
            </a>
            <div class="bmd-detail-title">
                <div class="bmd-detail-icon">▣</div>
                <div>
                    <h1>{{ $asset->name }}</h1>
                    <p>Kode aset: <strong>{{ $asset->bmd_code }}</strong></p>
                </div>
            </div>
        </div>
        <span class="bmd-detail-label">Barang Milik Daerah</span>
    </div>

    <div class="bmd-detail-card">
        <div class="bmd-detail-card-heading">
            <h3>Informasi Aset</h3>
            <span>Rincian data barang</span>
        </div>

        <div class="bmd-info-grid">
            <div class="bmd-info-item">
                <span>Kode BMD</span>
                <strong>{{ $asset->bmd_code }}</strong>
            </div>
            <div class="bmd-info-item">
                <span>Nama Aset</span>
                <strong>{{ $asset->name }}</strong>
            </div>
            <div class="bmd-info-item">
                <span>Kategori</span>
                <strong>{{ $asset->category ?? '-' }}</strong>
            </div>
            <div class="bmd-info-item">
                <span>Tahun Perolehan</span>
                <strong>{{ $asset->year_acquired ?? '-' }}</strong>
            </div>
            <div class="bmd-info-item">
                <span>Nilai Perolehan</span>
                <strong class="bmd-value">
                    {{ $asset->acquisition_value ? 'Rp '.number_format($asset->acquisition_value,0,',','.') : '-' }}
                </strong>
            </div>
            <div class="bmd-info-item">
                <span>Kondisi</span>
                <strong>{{ $asset->condition }}</strong>
            </div>
            <div class="bmd-info-item">
                <span>Status Aset</span>
                <strong>{{ $asset->bmd_status }}</strong>
            </div>
        </div>
    </div>

    <div class="bmd-detail-footer">
        <a href="{{ route('admin.barang-daerah.assets.index') }}" class="bmd-detail-btn">
            ← Kembali ke daftar
        </a>
    </div>
</div>

<style>
.bmd-detail { --bmd-accent:#3979c9; --bmd-soft:rgba(57,121,201,.10); max-width:950px; margin:0 auto; color:var(--text-main); }
.bmd-detail-heading { display:flex; justify-content:space-between; align-items:center; gap:15px; margin-bottom:23px; }
.bmd-detail-back { display:inline-block; text-decoration:none; font-size:13px; color:var(--text-muted,#84909d); margin-bottom:17px; }
.bmd-detail-back:hover { color:var(--bmd-accent); }
.bmd-detail-title { display:flex; align-items:center; gap:14px; }
.bmd-detail-icon { width:50px; height:50px; display:grid; place-items:center; background:var(--bmd-soft); color:var(--bmd-accent); border-radius:13px; font-size:23px; }
.bmd-detail-title h1 { margin:0 0 5px; font-size:23px; }
.bmd-detail-title p { margin:0; color:var(--text-muted,#84909d); font-size:12px; }
.bmd-detail-title p strong { color:var(--text-main); }
.bmd-detail-label { padding:8px 12px; border-radius:8px; background:var(--bmd-soft); color:var(--bmd-accent); font-size:11px; font-weight:700; }
.bmd-detail-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:14px; padding:24px; }
.bmd-detail-card-heading { display:flex; justify-content:space-between; align-items:center; margin-bottom:22px; }
.bmd-detail-card-heading h3 { margin:0; font-size:16px; }
.bmd-detail-card-heading span { font-size:11px; color:var(--text-muted,#84909d); }
.bmd-info-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0 25px; }
.bmd-info-item { display:flex; flex-direction:column; gap:8px; padding:15px 0; border-bottom:1px solid var(--border-color); min-width:0; }
.bmd-info-item span { font-size:12px; color:var(--text-muted,#84909d); }
.bmd-info-item strong { font-size:13px; font-weight:600; overflow-wrap:anywhere; }
.bmd-info-item .bmd-value { color:var(--bmd-accent); }
.bmd-detail-footer { display:flex; justify-content:flex-start; margin-top:19px; }
.bmd-detail-btn { display:inline-flex; align-items:center; min-height:40px; padding:0 15px; border:1px solid var(--border-color); border-radius:8px; text-decoration:none; color:var(--text-main); background:var(--bg-card); font-size:13px; font-weight:600; }
@media(max-width:600px) { .bmd-info-grid { grid-template-columns:1fr; } .bmd-detail-card { padding:17px; } .bmd-detail-heading { align-items:flex-start; flex-direction:column; } .bmd-detail-title h1 { font-size:19px; } }
</style>
@endsection
