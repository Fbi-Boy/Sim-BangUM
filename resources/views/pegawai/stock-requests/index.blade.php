@extends('layouts.app')
@section('title', 'Permintaan ATK Saya')

@section('content')
@include('partials.ui-extras')
<style>
    /* ===== Banner (gaya dashboard) ===== */
    .hero {
        position: relative; overflow: hidden; border-radius: 24px; padding: 28px 34px; color: #fff; margin-bottom: 18px;
        background:
            radial-gradient(circle at 85% 15%, rgba(167,139,250,.55) 0, transparent 45%),
            radial-gradient(circle at 10% 110%, rgba(56,189,248,.45) 0, transparent 50%),
            linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
        box-shadow: 0 18px 36px rgba(37,99,235,.28);
    }
    .hero::before {
        content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.16) 1.2px, transparent 1.2px);
        background-size: 20px 20px; opacity: .55;
        -webkit-mask-image: linear-gradient(90deg, transparent 35%, #000 100%); mask-image: linear-gradient(90deg, transparent 35%, #000 100%);
    }
    .hero-body { position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; gap: 20px; }
    .hero-text { max-width: 62%; }
    .hero-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; background: rgba(255,255,255,.18); backdrop-filter: blur(6px); font-size: 11px; font-weight: 500; margin-bottom: 12px; }
    .hero-tag i { width: 6px; height: 6px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 3px rgba(52,211,153,.35); }
    .hero h1 { font-size: 26px; font-weight: 700; line-height: 1.2; margin: 0 0 8px; letter-spacing: -.3px; color: #fff; }
    .hero p { font-size: 12.5px; opacity: .92; line-height: 1.65; margin: 0 0 18px; color: #fff; }
    .hero-btn {
        display: inline-flex; align-items: center; gap: 8px; background: #fff; color: #2563eb; padding: 9px 18px; border-radius: 10px;
        font-size: 12px; font-weight: 600; text-decoration: none; box-shadow: 0 8px 18px rgba(0,0,0,.15); transition: transform .2s;
    }
    .hero-btn:hover { transform: translateY(-2px); }
    .hero-art { flex: 0 0 200px; height: 130px; }
    .hero-art svg { width: 100%; height: 100%; overflow: visible; }
    .hero .float { animation: heroFloat 5s ease-in-out infinite; }
    .hero .float.d2 { animation-delay: -1.8s; }
    @keyframes heroFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
    @media (prefers-reduced-motion: reduce) { .hero .float { animation: none; } }
    @media (max-width: 760px) { .hero-art { display: none; } .hero-text { max-width: 100%; } .hero { padding: 24px 22px; } }

    /* ===== Daftar item ===== */
    .item-list { display: flex; flex-wrap: wrap; gap: 6px; min-width: 200px; max-width: 360px; }
    .item-chip {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 500;
        background: var(--bg-soft, #f3f6fd); color: var(--text-main); border: 1px solid var(--border-color);
    }
    .item-chip b { color: var(--accent, #2563eb); font-weight: 700; font-variant-numeric: tabular-nums; }
</style>

@php
    $onPage    = $requests->count();
    $total     = $requests instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $requests->total() : $onPage;
    $submitted = $requests->where('status', 'submitted')->count();
    $approved  = $requests->whereIn('status', ['approved', 'issued', 'fulfilled', 'completed'])->count();

    $labels = [
        'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'issued' => 'Diserahkan',
        'fulfilled' => 'Terpenuhi', 'completed' => 'Selesai', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan',
    ];
    $labelOf = fn($s) => $labels[strtolower((string) $s)] ?? $s;
    $badgeOf = fn($s) => match (strtolower((string) $s)) {
        'submitted'                                  => 'badge-warning',
        'approved', 'issued'                         => 'badge-info',
        'fulfilled', 'completed'                     => 'badge-success',
        'rejected', 'cancelled'                      => 'badge-danger',
        default                                      => 'badge-muted',
    };
@endphp

<!-- ===== KOTAK PANJANG (BANNER) ===== -->
<div class="hero">
    <div class="hero-body">
        <div class="hero-text">
            <div class="hero-tag"><i></i> <span data-translate>SIM-BU · Persediaan ATK</span></div>
            <h1 data-translate>Permintaan ATK Saya</h1>
            <p data-translate>Ajukan kebutuhan alat tulis kantor untuk bagian Anda dan pantau status persetujuannya di sini.</p>
            <a href="{{ route('pegawai.stock-requests.create') }}" class="hero-btn">
                <span data-translate>Ajukan Permintaan</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>
        <div class="hero-art" aria-hidden="true">
            <svg viewBox="0 0 200 130" fill="none">
                <circle cx="104" cy="68" r="54" fill="rgba(255,255,255,.10)"/>
                <circle cx="104" cy="68" r="36" fill="rgba(255,255,255,.10)"/>
                <g class="float d2"><rect x="44" y="26" width="72" height="88" rx="10" fill="rgba(255,255,255,.55)" transform="rotate(-9 80 70)"/></g>
                <g class="float">
                    <rect x="68" y="20" width="76" height="92" rx="11" fill="#fff"/>
                    <rect x="80" y="34" width="30" height="7" rx="3.5" fill="#2563eb"/>
                    <rect x="80" y="48" width="52" height="5" rx="2.5" fill="#cbd5e1"/>
                    <rect x="80" y="59" width="44" height="5" rx="2.5" fill="#cbd5e1"/>
                    <rect x="80" y="70" width="50" height="5" rx="2.5" fill="#cbd5e1"/>
                    <rect x="80" y="86" width="28" height="13" rx="6.5" fill="#10b981"/>
                    <path d="M87 92.5l3.5 3.5 6.5-7" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <g class="float d2">
                    <rect x="134" y="74" width="50" height="34" rx="8" fill="#fbbf24"/>
                    <path d="M138 80l21 16 21-16" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <circle cx="156" cy="30" r="4" fill="#fff" opacity=".8"/>
                <circle cx="30" cy="48" r="3" fill="#fff" opacity=".7"/>
            </svg>
        </div>
    </div>
</div>

<!-- ===== KARTU STATISTIK ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total permintaan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($submitted, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Menunggu persetujuan</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($approved, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Disetujui / selesai</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<!-- ===== TABEL ===== -->
<div class="table-card">
    <x-table-toolbar target="myReqBody" placeholder="Cari no., keperluan, nama item..."
        :add-url="route('pegawai.stock-requests.create')" add-label="Ajukan Permintaan" :report="false" />

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>No</th>
                    <th data-translate>Keperluan</th>
                    <th data-translate>Item</th>
                    <th data-translate>Status</th>
                </tr>
            </thead>
            <tbody id="myReqBody">
                @foreach($requests as $r)
                    @php
                        $label    = $labelOf($r->status);
                        $itemText = $r->items->map(fn($it) => $it->supplyItem?->name)->filter()->join(' ');
                    @endphp
                    <tr data-filter="{{ $r->status }}" data-filter-label="{{ $label }}"
                        data-search="{{ strtolower($r->request_number . ' ' . $r->purpose . ' ' . $itemText . ' ' . $label . ' ' . $r->status) }}">
                        <td><span class="code-tag" data-convert="full">{{ $r->request_number }}</span></td>
                        <td><div class="cell-main" style="min-width:180px"><span class="cell-title">{{ $r->purpose }}</span></div></td>
                        <td>
                            <div class="item-list">
                                @forelse($r->items as $it)
                                    <span class="item-chip">{{ $it->supplyItem?->name ?? '-' }} <b data-convert="digit">×{{ $it->requested_quantity }}</b></span>
                                @empty
                                    <span class="cell-sub">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td><span class="badge {{ $badgeOf($r->status) }}"><span class="dot"></span><span data-translate>{{ $label }}</span></span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="emptyServer" @if($onPage > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg></div>
        <h3 data-translate>Belum ada permintaan.</h3>
        <p data-translate>Klik "Ajukan Permintaan" untuk meminta ATK.</p>
    </div>
    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Permintaan tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter yang lain.</p>
    </div>

    <x-table-foot :paginator="$requests" noun="permintaan" />
</div>
@endsection
