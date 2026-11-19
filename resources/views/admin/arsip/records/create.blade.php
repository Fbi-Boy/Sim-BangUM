
@extends('layouts.app')

@section('title', 'Daftarkan Arsip')

@section('content')
<style>
    .archive-create {
        --archive-accent: #9b75f5;
        --archive-accent-hover: #8054df;
        --archive-accent-soft: rgba(155, 117, 245, .12);
        --archive-accent-border: rgba(155, 117, 245, .3);

        color: var(--text-main);
    }

    .archive-create * {
        box-sizing: border-box;
    }

    .ac-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .ac-heading-main {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .ac-heading-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 14px;
        color: var(--archive-accent);
        background: var(--archive-accent-soft);
        border: 1px solid var(--archive-accent-border);
    }

    .ac-heading-icon svg {
        width: 23px;
        height: 23px;
    }

    .ac-heading h2 {
        margin: 0 0 5px;
        color: var(--text-main);
        font-size: 21px;
        font-weight: 750;
        letter-spacing: -.4px;
    }

    .ac-heading p {
        margin: 0;
        color: var(--text-muted, #92929e);
        font-size: 12px;
    }

    .ac-back {
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

    .ac-back:hover {
        color: var(--archive-accent);
        border-color: var(--archive-accent-border);
        background: var(--archive-accent-soft);
    }

    .ac-card {
        overflow: hidden;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        background: var(--bg-card);
        box-shadow: 0 4px 16px rgba(0,0,0,.035);
    }

    .ac-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 19px 22px;
        border-bottom: 1px solid var(--border-color);
    }

    .ac-card-header-icon {
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        background: var(--archive-accent-soft);
        color: var(--archive-accent);
    }

    .ac-card-header-icon svg {
        width: 18px;
        height: 18px;
    }

    .ac-card-header h3 {
        margin: 0 0 4px;
        color: var(--text-main);
        font-size: 14px;
        font-weight: 700;
    }

    .ac-card-header p {
        margin: 0;
        color: var(--text-muted, #92929e);
        font-size: 11px;
    }

    .ac-form {
        padding: 23px;
    }

    .ac-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .ac-field {
        min-width: 0;
    }

    .ac-field.full {
        grid-column: 1 / -1;
    }

    .ac-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-main);
        font-size: 12px;
        font-weight: 650;
    }

    .ac-required {
        color: #ef6464;
    }

    .ac-input,
    .ac-select,
    .ac-textarea {
        display: block;
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: 9px;
        outline: none;
        background: var(--bg-soft);
        color: var(--text-main);
        font-family: inherit;
        font-size: 13px;
        transition: border-color .2s, box-shadow .2s;
    }

    .ac-input,
    .ac-select {
        height: 43px;
        padding: 0 12px;
    }

    .ac-textarea {
        min-height: 105px;
        padding: 11px 12px;
        resize: vertical;
        line-height: 1.6;
    }

    .ac-input::placeholder,
    .ac-textarea::placeholder {
        color: var(--text-muted, #92929e);
        opacity: .8;
    }

    .ac-input:focus,
    .ac-select:focus,
    .ac-textarea:focus {
        border-color: var(--archive-accent);
        box-shadow: 0 0 0 3px var(--archive-accent-soft);
    }

    .ac-select option {
        background: var(--bg-card);
        color: var(--text-main);
    }

    .ac-hint {
        margin-top: 7px;
        color: var(--text-muted, #92929e);
        font-size: 11px;
        line-height: 1.5;
    }

    .ac-error {
        padding: 13px 16px;
        margin-bottom: 17px;
        border: 1px solid rgba(239, 68, 68, .3);
        border-radius: 10px;
        background: rgba(239, 68, 68, .09);
        color: #ef7777;
        font-size: 12px;
    }

    .ac-error ul {
        margin: 7px 0 0;
        padding-left: 18px;
    }

    .ac-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 9px;
        margin-top: 24px;
        padding-top: 19px;
        border-top: 1px solid var(--border-color);
    }

    .ac-btn {
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

    .ac-btn-cancel {
        color: var(--text-main);
        background: var(--bg-soft);
        border: 1px solid var(--border-color);
    }

    .ac-btn-cancel:hover {
        border-color: var(--archive-accent-border);
        color: var(--archive-accent);
    }

    .ac-btn-save {
        color: #fff;
        border: 1px solid var(--archive-accent);
        background: var(--archive-accent);
    }

    .ac-btn-save:hover {
        background: var(--archive-accent-hover);
        border-color: var(--archive-accent-hover);
        transform: translateY(-1px);
    }

    @media (max-width: 650px) {
        .ac-form {
            padding: 17px;
        }

        .ac-grid {
            grid-template-columns: 1fr;
            gap: 17px;
        }

        .ac-field.full {
            grid-column: auto;
        }

        .ac-footer {
            flex-direction: column-reverse;
        }

        .ac-btn {
            width: 100%;
        }
    }
</style>

<div class="archive-create">

    <div class="ac-heading">
        <div class="ac-heading-main">
            <div class="ac-heading-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 1 4 17.5z"/>
                    <path d="M4 17.5A2.5 2.5 0 0 1 6.5 15H20"/>
                    <path d="M9 7h7M9 10h5"/>
                </svg>
            </div>
            <div>
                <h2>Daftarkan Arsip</h2>
                <p>Registrasikan dokumen ke dalam sistem pengarsipan.</p>
            </div>
        </div>

        <a href="{{ route('admin.arsip.records.index') }}" class="ac-back">
            ← Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="ac-error">
            <strong>Terjadi kesalahan pada input data.</strong>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="ac-card">
        <div class="ac-card-header">
            <div class="ac-card-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3v12m-4-4 4 4 4-4"/>
                    <path d="M5 17v3h14v-3"/>
                </svg>
            </div>
            <div>
                <h3>Informasi Dokumen</h3>
                <p>Isi informasi dasar arsip yang akan didaftarkan.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.arsip.records.store') }}" class="ac-form">
            @csrf

            <div class="ac-grid">

                <div class="ac-field full">
                    <label for="archive_classification_id" class="ac-label">
                        Klasifikasi Arsip <span class="ac-required">*</span>
                    </label>

                    <select name="archive_classification_id" id="archive_classification_id" class="ac-select" required>
                        <option value="">Pilih klasifikasi arsip</option>
                        @foreach($classifications as $c)
                            <option value="{{ $c->id }}" @selected(old('archive_classification_id') == $c->id)>
                                {{ $c->code }} — {{ $c->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="ac-hint">Pilih klasifikasi sesuai jenis dan isi dokumen.</div>
                </div>

                <div class="ac-field full">
                    <label for="title" class="ac-label">
                        Judul Arsip <span class="ac-required">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="ac-input"
                        placeholder="Contoh: Laporan Kegiatan Tahun 2026"
                        value="{{ old('title') }}"
                        required
                    >
                </div>

                <div class="ac-field">
                    <label for="record_date" class="ac-label">Tanggal Dokumen</label>
                    <input
                        type="date"
                        name="record_date"
                        id="record_date"
                        class="ac-input"
                        value="{{ old('record_date') }}"
                    >
                    <div class="ac-hint">Tanggal yang tercantum pada dokumen.</div>
                </div>

                <div class="ac-field">
                    <label for="physical_location" class="ac-label">Lokasi Fisik</label>
                    <input
                        type="text"
                        name="physical_location"
                        id="physical_location"
                        class="ac-input"
                        placeholder="Misal: Rak A-2, Lemari 3"
                        value="{{ old('physical_location') }}"
                    >
                    <div class="ac-hint">Lokasi penyimpanan arsip fisik.</div>
                </div>

                <div class="ac-field full">
                    <label for="description" class="ac-label">Deskripsi</label>
                    <textarea
                        name="description"
                        id="description"
                        class="ac-textarea"
                        rows="4"
                        placeholder="Tuliskan ringkasan atau keterangan tambahan..."
                    >{{ old('description') }}</textarea>
                </div>

            </div>

            <div class="ac-footer">
                <a href="{{ route('admin.arsip.records.index') }}" class="ac-btn ac-btn-cancel">
                    Batal
                </a>

                <button type="submit" class="ac-btn ac-btn-save">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                    Simpan Arsip
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
