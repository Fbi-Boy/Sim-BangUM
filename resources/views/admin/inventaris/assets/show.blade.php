
@extends('layouts.app')
@section('title', 'Detail Aset')

@section('content')
<style>
    .asset-show {
        --asset-accent: #20b8c5;
        --asset-accent-hover: #1099a5;
        --asset-soft: rgba(32,184,197,.12);
        --asset-border: rgba(32,184,197,.3);
        color: var(--text-main);
    }

    .asset-show * { box-sizing: border-box; }

    .as-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
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
        color: var(--asset-accent);
        background: var(--asset-soft);
        border: 1px solid var(--asset-border);
    }

    .as-heading-icon svg { width: 23px; height: 23px; }

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
        color: var(--asset-accent);
        border-color: var(--asset-border);
        background: var(--asset-soft);
    }

    .as-hero {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 23px 25px;
        margin-bottom: 19px;
        border: 1px solid var(--asset-border);
        border-radius: 14px;
        background: linear-gradient(110deg, var(--asset-soft), var(--bg-card) 90%);
    }

    .as-hero::after {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        right: -60px;
        top: -90px;
        border: 1px solid var(--asset-border);
        border-radius: 50%;
        box-shadow: 0 0 0 22px var(--asset-soft);
        pointer-events: none;
    }

    .as-hero-label {
        color: var(--asset-accent);
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .9px;
        text-transform: uppercase;
    }

    .as-hero h2 {
        margin: 9px 0 13px;
        color: var(--text-main);
        font-size: 22px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .as-hero-code {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border: 1px solid var(--asset-border);
        border-radius: 8px;
        background: var(--bg-card);
        color: var(--text-main);
        font-size: 11px;
    }

    .as-hero-illustration {
        position: relative;
        z-index: 1;
        width: 76px;
        height: 76px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border: 1px solid var(--asset-border);
        border-radius: 20px;
        background: var(--bg-card);
        color: var(--asset-accent);
    }

    .as-hero-illustration svg { width: 38px; height: 38px; }

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
        color: var(--asset-accent);
        background: var(--asset-soft);
    }

    .as-section-icon svg { width: 18px; height: 18px; }

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
        grid-template-columns: repeat(2, minmax(0,1fr));
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
        border: 1px solid var(--asset-border);
        border-radius: 30px;
        color: var(--asset-accent);
        background: var(--asset-soft);
        font-size: 11px;
        font-weight: 700;
    }

    .as-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .as-table-wrap { width: 100%; overflow-x: auto; }

    .as-table {
        width: 100%;
        min-width: 650px;
        border-collapse: collapse;
    }

    .as-table th {
        padding: 12px 16px;
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
        padding: 13px 16px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-main);
        font-size: 12px;
        vertical-align: middle;
    }

    .as-table tbody tr:last-child td { border-bottom: 0; }

    .as-table tbody tr { transition: background .15s; }
    .as-table tbody tr:hover { background: var(--bg-soft); }

    .as-loan-number {
        color: var(--asset-accent);
        font-weight: 700;
    }

    .as-requester {
        display: flex;
        align-items: center;
        gap: 9px;
        font-weight: 600;
    }

    .as-requester-avatar {
        width: 30px;
        height: 30px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 9px;
        background: var(--asset-soft);
        color: var(--asset-accent);
        font-size: 11px;
        font-weight: 750;
    }

    .as-loan-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        background: var(--bg-soft);
        color: var(--text-main);
        font-size: 10px;
        font-weight: 650;
    }

    .as-empty {
        padding: 32px 15px !important;
        text-align: center;
        color: var(--text-muted, #92929e) !important;
    }

    .as-empty-icon {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        margin: 0 auto 9px;
        border-radius: 12px;
        background: var(--asset-soft);
        color: var(--asset-accent);
    }

    .as-empty-icon svg { width: 21px; height: 21px; }

    .as-empty strong {
        display: block;
        margin-bottom: 4px;
        color: var(--text-main);
    }

    @media(max-width:650px) {
        .as-hero { padding: 19px; }
        .as-hero h2 { font-size: 19px; }
        .as-hero-illustration { width: 58px; height: 58px; }
        .as-info-grid { grid-template-columns: 1fr; }
        .as-info-item,
        .as-info-item.full {
            grid-column: auto;
            border-bottom: 1px solid var(--border-color);
        }
        .as-info-item:last-child { border-bottom: 0; }
    }
</style>

<div class="asset-show">

    <div class="as-heading">
        <div class="as-heading-main">
            <div class="as-heading-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5z"/>
                    <path d="m4 7.5 8 4.5 8-4.5M12 12v9"/>
                </svg>
            </div>
            <div>
                <h2>Detail Aset</h2>
                <p>Informasi aset dan riwayat penggunaan inventaris.</p>
            </div>
        </div>

        <a href="{{ route('admin.inventaris.assets.index') }}" class="as-back">
            ← Kembali ke daftar
        </a>
    </div>

    <div class="as-hero">
        <div>
            <div class="as-hero-label">INFORMASI INVENTARIS</div>
            <h2>{{ $asset->name }}</h2>
            <div class="as-hero-code">
                <strong>Kode Aset:</strong> {{ $asset->asset_code }}
            </div>
        </div>

        <div class="as-hero-illustration">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5z"/>
                <path d="m4 7.5 8 4.5 8-4.5M12 12v9M8 5.3l8 4.5"/>
            </svg>
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
                <h3>Informasi Aset</h3>
                <p>Identitas dan kondisi inventaris.</p>
            </div>
        </div>

        <div class="as-info-grid">

            <div class="as-info-item">
                <span class="as-info-label">Kode Aset</span>
                <div class="as-info-value">{{ $asset->asset_code }}</div>
            </div>

            <div class="as-info-item">
                <span class="as-info-label">Nama Aset</span>
                <div class="as-info-value">{{ $asset->name }}</div>
            </div>

            <div class="as-info-item">
                <span class="as-info-label">Kategori</span>
                <div class="as-info-value">{{ $asset->category->name }}</div>
            </div>

            <div class="as-info-item">
                <span class="as-info-label">Merek</span>
                <div class="as-info-value">{{ $asset->brand ?? '-' }}</div>
            </div>

            <div class="as-info-item">
                <span class="as-info-label">Kondisi</span>
                <div class="as-info-value">{{ $asset->condition }}</div>
            </div>

            <div class="as-info-item">
                <span class="as-info-label">Status</span>
                <div class="as-info-value">
                    <span class="as-status">
                        <span class="as-status-dot"></span>
                        {{ $asset->status }}
                    </span>
                </div>
            </div>

        </div>
    </div>

    <div class="as-section">
        <div class="as-section-header">
            <div class="as-section-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18M8 14h3m-3 3h7"/>
                </svg>
            </div>
            <div>
                <h3>Riwayat Peminjaman</h3>
                <p>Daftar penggunaan dan pengembalian aset.</p>
            </div>
        </div>

        <div class="as-table-wrap">
            <table class="as-table">
                <thead>
                    <tr>
                        <th>Nomor Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Tanggal Mulai</th>
                        <th>Rencana Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($asset->loans as $l)
                        <tr>
                            <td>
                                <span class="as-loan-number">{{ $l->loan_number }}</span>
                            </td>
                            <td>
                                <div class="as-requester">
                                    <div class="as-requester-avatar">
                                        {{ strtoupper(substr($l->requester->name, 0, 1)) }}
                                    </div>
                                    {{ $l->requester->name }}
                                </div>
                            </td>
                            <td>{{ $l->starts_at->format('d-m-Y') }}</td>
                            <td>{{ $l->expected_return_at->format('d-m-Y') }}</td>
                            <td>
                                <span class="as-loan-status">{{ $l->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="as-empty">
                                <div class="as-empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                                        <path d="M16 2v4M8 2v4M3 10h18M8 14h8"/>
                                    </svg>
                                </div>
                                <strong>Belum ada riwayat peminjaman</strong>
                                <span>Aset ini belum memiliki catatan peminjaman.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
