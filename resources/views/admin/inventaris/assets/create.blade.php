
@extends('layouts.app')
@section('title', 'Tambah Aset')

@section('content')
<style>
    .asset-create {
        --asset-accent: #5795f5;
        --asset-accent-hover: #3979df;
        --asset-soft: rgba(87,149,245,.12);
        --asset-border: rgba(87,149,245,.3);
        color: var(--text-main);
    }

    .asset-create * { box-sizing: border-box; }

    .asset-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .asset-heading-main {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .asset-heading-icon {
        width: 49px;
        height: 49px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 14px;
        color: var(--asset-accent);
        background: var(--asset-soft);
        border: 1px solid var(--asset-border);
    }

    .asset-heading-icon svg { width: 23px; height: 23px; }

    .asset-heading h2 {
        margin: 0 0 5px;
        font-size: 21px;
        font-weight: 750;
        color: var(--text-main);
        letter-spacing: -.4px;
    }

    .asset-heading p {
        margin: 0;
        font-size: 12px;
        color: var(--text-muted, #92929e);
    }

    .asset-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        border: 1px solid var(--border-color);
        border-radius: 9px;
        color: var(--text-main);
        background: var(--bg-card);
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: .2s;
    }

    .asset-back:hover {
        color: var(--asset-accent);
        background: var(--asset-soft);
        border-color: var(--asset-border);
    }

    .asset-card {
        overflow: hidden;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        background: var(--bg-card);
        box-shadow: 0 4px 16px rgba(0,0,0,.025);
    }

    .asset-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 19px 22px;
        border-bottom: 1px solid var(--border-color);
    }

    .asset-card-header-icon {
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        color: var(--asset-accent);
        background: var(--asset-soft);
    }

    .asset-card-header-icon svg { width: 18px; height: 18px; }

    .asset-card-header h3 {
        margin: 0 0 4px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .asset-card-header p {
        margin: 0;
        color: var(--text-muted, #92929e);
        font-size: 11px;
    }

    .asset-form { padding: 23px; }

    .asset-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0,1fr));
        gap: 20px;
    }

    .asset-field { min-width: 0; }
    .asset-field.full { grid-column: 1 / -1; }

    .asset-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-main);
        font-size: 12px;
        font-weight: 650;
    }

    .asset-required { color: #ef6464; }

    .asset-input,
    .asset-select,
    .asset-textarea {
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: 9px;
        background: var(--bg-soft);
        color: var(--text-main);
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: .2s;
    }

    .asset-input,
    .asset-select {
        height: 43px;
        padding: 0 12px;
    }

    .asset-textarea {
        min-height: 100px;
        padding: 11px 12px;
        resize: vertical;
        line-height: 1.6;
    }

    .asset-input::placeholder,
    .asset-textarea::placeholder {
        color: var(--text-muted, #92929e);
        opacity: .8;
    }

    .asset-input:focus,
    .asset-select:focus,
    .asset-textarea:focus {
        border-color: var(--asset-accent);
        box-shadow: 0 0 0 3px var(--asset-soft);
    }

    .asset-select option {
        background: var(--bg-card);
        color: var(--text-main);
    }

    .asset-hint {
        margin-top: 7px;
        color: var(--text-muted, #92929e);
        font-size: 11px;
        line-height: 1.5;
    }

    .asset-error {
        padding: 13px 16px;
        margin-bottom: 17px;
        border: 1px solid rgba(239,68,68,.3);
        border-radius: 10px;
        background: rgba(239,68,68,.09);
        color: #ef7777;
        font-size: 12px;
    }

    .asset-error ul {
        margin: 7px 0 0;
        padding-left: 18px;
    }

    .asset-form-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 9px;
        margin-top: 24px;
        padding-top: 19px;
        border-top: 1px solid var(--border-color);
    }

    .asset-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 15px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .asset-btn-cancel {
        color: var(--text-main);
        background: var(--bg-soft);
        border: 1px solid var(--border-color);
    }

    .asset-btn-cancel:hover {
        color: var(--asset-accent);
        border-color: var(--asset-border);
    }

    .asset-btn-save {
        color: #fff;
        background: var(--asset-accent);
        border: 1px solid var(--asset-accent);
    }

    .asset-btn-save:hover {
        background: var(--asset-accent-hover);
        border-color: var(--asset-accent-hover);
        transform: translateY(-1px);
    }

    @media(max-width:650px) {
        .asset-form { padding: 17px; }
        .asset-form-grid { grid-template-columns: 1fr; gap: 17px; }
        .asset-field.full { grid-column: auto; }
        .asset-form-footer { flex-direction: column-reverse; }
        .asset-btn { width: 100%; }
    }
</style>

<div class="asset-create">

    <div class="asset-heading">
        <div class="asset-heading-main">
            <div class="asset-heading-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="4" width="18" height="16" rx="2"/>
                    <path d="M8 4V2m8 2V2M3 9h18M8 14h3m-3 3h8"/>
                </svg>
            </div>
            <div>
                <h2>Tambah Aset</h2>
                <p>Daftarkan barang dan inventaris baru ke dalam sistem.</p>
            </div>
        </div>

        <a href="{{ route('admin.inventaris.assets.index') }}" class="asset-back">
            ← Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="asset-error">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="asset-card">
        <div class="asset-card-header">
            <div class="asset-card-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5z"/>
                    <path d="m4 7.5 8 4.5 8-4.5M12 12v9M8 5.3l8 4.5"/>
                </svg>
            </div>
            <div>
                <h3>Informasi Aset</h3>
                <p>Lengkapi identitas dan keterangan barang.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.inventaris.assets.store') }}" class="asset-form">
            @csrf

            <div class="asset-form-grid">

                <div class="asset-field">
                    <label for="asset_code" class="asset-label">
                        Kode Aset <span class="asset-required">*</span>
                    </label>
                    <input
                        type="text"
                        name="asset_code"
                        id="asset_code"
                        class="asset-input"
                        placeholder="Contoh: INV-2026-001"
                        value="{{ old('asset_code') }}"
                        required
                    >
                    <div class="asset-hint">Kode identifikasi unik setiap aset.</div>
                </div>

                <div class="asset-field">
                    <label for="name" class="asset-label">
                        Nama Aset <span class="asset-required">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="asset-input"
                        placeholder="Contoh: Laptop Lenovo"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="asset-field full">
                    <label for="asset_category_id" class="asset-label">
                        Kategori <span class="asset-required">*</span>
                    </label>
                    <select
                        name="asset_category_id"
                        id="asset_category_id"
                        class="asset-select"
                        required
                    >
                        <option value="">Pilih kategori aset</option>
                        @foreach($categories as $c)
                            <option
                                value="{{ $c->id }}"
                                @selected(old('asset_category_id') == $c->id)
                            >
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="asset-hint">Kelompokkan aset berdasarkan jenis barang.</div>
                </div>

                <div class="asset-field full">
                    <label for="brand" class="asset-label">Merek</label>
                    <input
                        type="text"
                        name="brand"
                        id="brand"
                        class="asset-input"
                        placeholder="Contoh: Lenovo, HP, Epson, dan lainnya"
                        value="{{ old('brand') }}"
                    >
                </div>

                <div class="asset-field full">
                    <label for="notes" class="asset-label">Catatan</label>
                    <textarea
                        name="notes"
                        id="notes"
                        class="asset-textarea"
                        rows="3"
                        placeholder="Tambahkan keterangan atau informasi tambahan mengenai aset..."
                    >{{ old('notes') }}</textarea>
                </div>

            </div>

            <div class="asset-form-footer">
                <a href="{{ route('admin.inventaris.assets.index') }}" class="asset-btn asset-btn-cancel">
                    Batal
                </a>

                <button type="submit" class="asset-btn asset-btn-save">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                    Simpan Aset
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
