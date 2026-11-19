
@extends('layouts.app')

@section('title', 'Detail Arsip')

@section('content')
<style>
    .archive-show {
        --show-accent: #28b982;
        --show-accent-hover: #159966;
        --show-accent-soft: rgba(40, 185, 130, .12);
        --show-accent-border: rgba(40, 185, 130, .3);

        color: var(--text-main);
    }

    .archive-show * {
        box-sizing: border-box;
    }

    .as-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 21px;
    }

    .as-heading-main {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .as-heading-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 14px;
        color: var(--show-accent);
        background: var(--show-accent-soft);
        border: 1px solid var(--show-accent-border);
    }

    .as-heading-icon svg {
        width: 23px;
        height: 23px;
    }

    .as-heading h2 {
        margin: 0 0 5px;
        color: var(--text-main);
        font-size: 21px;
        font-weight: 750;
        letter-spacing: -.4px;
    }

    .as-heading p {
        margin: 0;
        color: var(--text-muted, #92929e);
        font-size: 12px;
    }

    .as-back {
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

    .as-back:hover {
        color: var(--show-accent);
        border-color: var(--show-accent-border);
        background: var(--show-accent-soft);
    }

    .as-hero {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
        margin-bottom: 19px;
        border: 1px solid var(--show-accent-border);
        border-radius: 14px;
        background: linear-gradient(
            110deg,
            var(--show-accent-soft),
            var(--bg-card) 85%
        );
    }

    .as-hero::after {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        right: -60px;
        top: -85px;
        border: 1px solid var(--show-accent-border);
        border-radius: 50%;
        box-shadow: 0 0 0 22px var(--show-accent-soft);
        pointer-events: none;
    }

    .as-hero-label {
        display: flex;
        align-items: center;
        gap: 7px;
        color: var(--show-accent);
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .9px;
        text-transform: uppercase;
    }

    .as-hero h2 {
        position: relative;
        z-index: 1;
        max-width: 750px;
        margin: 10px 0 15px;
        color: var(--text-main);
        font-size: 21px;
        font-weight: 750;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .as-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .as-hero-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border: 1px solid var(--show-accent-border);
        border-radius: 8px;
        background: var(--bg-card);
        color: var(--text-main);
        font-size: 11px;
    }

    .as-section {
        overflow: hidden;
        margin-bottom: 19px;
        border: 1px solid var(--border-color);
        border-radius: 13px;
        background: var(--bg-card);
        box-shadow: 0 4px 15px rgba(0,0,0,.025);
    }

    .as-section-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 17px 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .as-section-icon {
        width: 35px;
        height: 35px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        color: var(--show-accent);
        background: var(--show-accent-soft);
    }

    .as-section-icon svg {
        width: 18px;
        height: 18px;
    }

    .as-section-header h3 {
        margin: 0 0 4px;
        color: var(--text-main);
        font-size: 13px;
        font-weight: 750;
    }

    .as-section-header p {
        margin: 0;
        color: var(--text-muted, #92929e);
        font-size: 11px;
    }

    .as-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        padding: 6px 20px 15px;
    }

    .as-info-item {
        min-width: 0;
        padding: 13px 12px 13px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .as-info-item.full {
        grid-column: 1 / -1;
        border-bottom: 0;
    }

    .as-info-label {
        display: block;
        margin-bottom: 6px;
        color: var(--text-muted, #92929e);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .as-info-value {
        color: var(--text-main);
        font-size: 12px;
        font-weight: 600;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .as-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border: 1px solid var(--show-accent-border);
        border-radius: 30px;
        color: var(--show-accent);
        background: var(--show-accent-soft);
        font-size: 11px;
        font-weight: 700;
    }

    .as-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .as-description {
        padding: 12px 14px;
        border: 1px solid var(--border-color);
        border-radius: 9px;
        background: var(--bg-soft);
        color: var(--text-main);
        font-size: 12px;
        line-height: 1.7;
        font-weight: 400;
        white-space: pre-line;
    }

    .as-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .as-table {
        width: 100%;
        min-width: 490px;
        border-collapse: collapse;
    }

    .as-table th {
        padding: 12px 17px;
        border-bottom: 1px solid var(--border-color);
        background: var(--bg-soft);
        color: var(--text-muted, #92929e);
        text-align: left;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .as-table td {
        padding: 13px 17px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-main);
        font-size: 12px;
        vertical-align: middle;
    }

    .as-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .as-file-name {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 650;
    }

    .as-file-icon {
        width: 34px;
        height: 34px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 9px;
        background: rgba(239, 68, 68, .1);
        color: #e56b6b;
    }

    .as-file-icon svg {
        width: 17px;
        height: 17px;
    }

    .as-download {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border: 1px solid var(--show-accent-border);
        border-radius: 8px;
        color: var(--show-accent);
        background: var(--show-accent-soft);
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s;
    }

    .as-download:hover {
        color: white;
        border-color: var(--show-accent);
        background: var(--show-accent);
    }

    .as-empty {
        padding: 30px 15px !important;
        text-align: center;
        color: var(--text-muted, #92929e) !important;
    }

    .as-empty-icon {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        margin: 0 auto 9px;
        border-radius: 11px;
        color: var(--show-accent);
        background: var(--show-accent-soft);
    }

    .as-empty-icon svg {
        width: 20px;
        height: 20px;
    }

    .as-empty strong {
        display: block;
        margin-bottom: 4px;
        color: var(--text-main);
    }

    .as-upload {
        padding: 19px 20px;
        border-top: 1px solid var(--border-color);
        background: var(--bg-soft);
    }

    .as-upload-heading {
        margin-bottom: 12px;
    }

    .as-upload-heading strong {
        display: block;
        margin-bottom: 4px;
        color: var(--text-main);
        font-size: 12px;
    }

    .as-upload-heading span {
        color: var(--text-muted, #92929e);
        font-size: 11px;
    }

    .as-upload-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .as-file-input {
        flex: 1;
        min-width: 200px;
        min-height: 40px;
        padding: 8px;
        border: 1px dashed var(--border-color);
        border-radius: 9px;
        background: var(--bg-card);
        color: var(--text-main);
        font-family: inherit;
        font-size: 11px;
    }

    .as-file-input::file-selector-button {
        margin-right: 9px;
        padding: 6px 9px;
        border: 0;
        border-radius: 6px;
        background: var(--show-accent-soft);
        color: var(--show-accent);
        font-size: 11px;
        font-weight: 650;
        cursor: pointer;
    }

    .as-upload-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 15px;
        border: 1px solid var(--show-accent);
        border-radius: 9px;
        background: var(--show-accent);
        color: white;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }

    .as-upload-btn:hover {
        background: var(--show-accent-hover);
        border-color: var(--show-accent-hover);
        transform: translateY(-1px);
    }

    .as-upload-hint {
        margin-top: 9px;
        color: var(--text-muted, #92929e);
        font-size: 10px;
    }

    @media (max-width: 650px) {
        .as-hero {
            padding: 19px;
        }

        .as-hero h2 {
            font-size: 18px;
        }

        .as-info-grid {
            grid-template-columns: 1fr;
        }

        .as-info-item,
        .as-info-item.full {
            grid-column: auto;
            border-bottom: 1px solid var(--border-color);
        }

        .as-info-item:last-child {
            border-bottom: 0;
        }

        .as-upload-row {
            flex-direction: column;
            align-items: stretch;
        }

        .as-file-input,
        .as-upload-btn {
            width: 100%;
        }
    }
</style>

<div class="archive-show">

    <div class="as-heading">
        <div class="as-heading-main">
            <div class="as-heading-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 1 4 17.5z"/>
                    <path d="M4 17.5A2.5 2.5 0 0 1 6.5 15H20"/>
                    <path d="M9 7h7M9 10h5"/>
                </svg>
            </div>
            <div>
                <h2>Detail Arsip</h2>
                <p>Informasi dokumen dan pengelolaan lampiran arsip.</p>
            </div>
        </div>

        <a href="{{ route('admin.arsip.records.index') }}" class="as-back">
            ← Kembali ke daftar
        </a>
    </div>

    <div class="as-hero">
        <div class="as-hero-label">
            <span>▣</span> IDENTITAS ARSIP
        </div>

        <h2>{{ $record->title }}</h2>

        <div class="as-hero-meta">
            <span class="as-hero-chip">
                <strong>Kode:</strong> {{ $record->archive_code }}
            </span>
            <span class="as-hero-chip">
                <strong>Klasifikasi:</strong> {{ $record->classification->code }}
            </span>
        </div>
    </div>

    <div class="as-section">
        <div class="as-section-header">
            <div class="as-section-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 4h16v16H4z"/>
                    <path d="M8 8h8M8 12h8M8 16h5"/>
                </svg>
            </div>
            <div>
                <h3>Informasi Dokumen</h3>
                <p>Identitas dan informasi penyimpanan arsip.</p>
            </div>
        </div>

        <div class="as-info-grid">

            <div class="as-info-item">
                <span class="as-info-label">Kode Arsip</span>
                <div class="as-info-value">{{ $record->archive_code }}</div>
            </div>

            <div class="as-info-item">
                <span class="as-info-label">Klasifikasi</span>
                <div class="as-info-value">
                    {{ $record->classification->code }} — {{ $record->classification->name }}
                </div>
            </div>

            <div class="as-info-item">
                <span class="as-info-label">Tanggal Dokumen</span>
                <div class="as-info-value">
                    {{ $record->record_date?->format('d-m-Y') ?? '-' }}
                </div>
            </div>

            <div class="as-info-item">
                <span class="as-info-label">Lokasi Fisik</span>
                <div class="as-info-value">
                    {{ $record->physical_location ?? '-' }}
                </div>
            </div>

            <div class="as-info-item full">
                <span class="as-info-label">Status Arsip</span>
                <div class="as-info-value">
                    <span class="as-status">
                        <span class="as-status-dot"></span>
                        {{ $record->archive_status }}
                    </span>
                </div>
            </div>

            <div class="as-info-item full">
                <span class="as-info-label">Deskripsi</span>
                <div class="as-description">
                    {{ $record->description ?: 'Belum ada deskripsi untuk arsip ini.' }}
                </div>
            </div>

        </div>
    </div>

    <div class="as-section">
        <div class="as-section-header">
            <div class="as-section-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M13 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10z"/>
                    <path d="M13 3v7h7M8 15h8M8 18h6"/>
                </svg>
            </div>
            <div>
                <h3>Lampiran Dokumen</h3>
                <p>Dokumen digital yang tersimpan pada arsip ini.</p>
            </div>
        </div>

        <div class="as-table-wrap">
            <table class="as-table">
                <thead>
                    <tr>
                        <th>Nama File</th>
                        <th>Ukuran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($record->documents as $doc)
                        <tr>
                            <td>
                                <div class="as-file-name">
                                    <div class="as-file-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M13 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10z"/>
                                            <path d="M13 3v7h7"/>
                                        </svg>
                                    </div>
                                    <span>{{ $doc->original_filename }}</span>
                                </div>
                            </td>
                            <td>{{ $doc->humanSize() }}</td>
                            <td>
                                <a
                                    href="{{ route('admin.arsip.records.download', [$record, $doc]) }}"
                                    class="as-download"
                                >
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 3v12m-5-5 5 5 5-5M5 20h14"/>
                                    </svg>
                                    Download
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="as-empty">
                                <div class="as-empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M13 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10z"/>
                                        <path d="M13 3v7h7"/>
                                    </svg>
                                </div>
                                <strong>Belum ada lampiran</strong>
                                <span>Silakan tambahkan dokumen digital di bawah ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <form
            method="POST"
            action="{{ route('admin.arsip.records.upload', $record) }}"
            enctype="multipart/form-data"
            class="as-upload"
        >
            @csrf

            <div class="as-upload-heading">
                <strong>Tambah Lampiran</strong>
                <span>Unggah file pendukung untuk melengkapi arsip.</span>
            </div>

            <div class="as-upload-row">
                <input
                    type="file"
                    name="file"
                    id="archive_file"
                    class="as-file-input"
                    required
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                >

                <button type="submit" class="as-upload-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 16V4m-4 4 4-4 4 4"/>
                        <path d="M4 16v4h16v-4"/>
                    </svg>
                    Upload Dokumen
                </button>
            </div>

            <div class="as-upload-hint">
                PDF, JPG, JPEG, PNG, DOC, DOCX · Maksimal 10 MB
            </div>
        </form>
    </div>

</div>
@endsection
