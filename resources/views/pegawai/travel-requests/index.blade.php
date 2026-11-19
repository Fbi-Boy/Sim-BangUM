@extends('layouts.app')
@section('title','Perjalanan Dinas Saya')
@section('content')
@php
    /* Pemetaan status -> [kelas warna, label]. Nilai tak dikenal tampil apa adanya (warna netral). */
    $statusMap = [
        'draft'       => ['off',  'Draf'],
        'draf'        => ['off',  'Draf'],
        'submitted'   => ['info', 'Diajukan'],
        'diajukan'    => ['info', 'Diajukan'],
        'pending'     => ['warn', 'Menunggu'],
        'menunggu'    => ['warn', 'Menunggu'],
        'approved'    => ['ok',   'Disetujui'],
        'disetujui'   => ['ok',   'Disetujui'],
        'ongoing'     => ['sky',  'Dalam Perjalanan'],
        'in_progress' => ['sky',  'Dalam Perjalanan'],
        'berlangsung' => ['sky',  'Dalam Perjalanan'],
        'completed'   => ['ok',   'Selesai'],
        'done'        => ['ok',   'Selesai'],
        'selesai'     => ['ok',   'Selesai'],
        'rejected'    => ['bad',  'Ditolak'],
        'ditolak'     => ['bad',  'Ditolak'],
        'cancelled'   => ['bad',  'Dibatalkan'],
        'canceled'    => ['bad',  'Dibatalkan'],
        'dibatalkan'  => ['bad',  'Dibatalkan'],
    ];

    /* Statistik kartu. Jika controller mengirim $stats (['total'=>,'pending'=>,'approved'=>,'next'=>model]) dipakai apa adanya;
       jika tidak, dihitung dari data pada halaman yang sedang tampil. */
    $col  = collect(method_exists($requests, 'getCollection') ? $requests->getCollection() : $requests);
    $norm = fn($s) => strtolower(trim((string) $s));
    $st   = isset($stats) && is_array($stats) ? $stats : [];

    $total    = $st['total']    ?? (method_exists($requests, 'total') ? $requests->total() : $col->count());
    $pending  = $st['pending']  ?? $col->filter(fn($r) => in_array($norm($r->status), ['pending','menunggu','submitted','diajukan']))->count();
    $approved = $st['approved'] ?? $col->filter(fn($r) => in_array($norm($r->status), ['approved','disetujui']))->count();
    $next     = $st['next']     ?? $col->filter(fn($r) => $r->departure_at && $r->departure_at->isFuture() && in_array($norm($r->status), ['approved','disetujui']))
                                       ->sortBy('departure_at')->first();
    $nextDays = $next ? (int) now()->startOfDay()->diffInDays($next->departure_at->copy()->startOfDay(), false) : null;

    $planePath = 'M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z';
@endphp

<style>
    /* ===== Modul Travel · Perjalanan Dinas Saya (tema langit / penerbangan) ===== */
    .pt { --mod: #0284c7; --mod-2: #38bdf8; --mod-soft: rgba(14,165,233,.12); --sun: #f97316; }
    body.dark-mode .pt { --mod: #38bdf8; --mod-soft: rgba(56,189,248,.15); }

    /* angka naik (count-up) tanpa mengubah DOM */
    @property --pn { syntax: '<integer>'; initial-value: 0; inherits: false; }
    .pt-num { --pn: var(--to); counter-reset: ptn var(--pn); animation: ptcount 1.1s ease-out both; }
    .pt-num::after { content: counter(ptn); }
    @keyframes ptcount { from { --pn: 0; } to { --pn: var(--to); } }
    .pt-sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }

    /* ---------- HERO (kotak panjang beranimasi) ---------- */
    .pt-hero {
        position: relative; overflow: hidden; border-radius: 20px; padding: 26px 28px; min-height: 160px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap; color: #fff;
        background: linear-gradient(160deg, #0369a1 0%, #0ea5e9 55%, #7dd3fc 100%);
        box-shadow: 0 16px 38px rgba(2,132,199,.28);
    }
    .pt-sun {
        position: absolute; inset-inline-end: 24px; top: -40px; width: 150px; height: 150px; border-radius: 50%;
        background: radial-gradient(circle, #fde68a 0%, #fbbf24 38%, rgba(249,115,22,0) 72%); opacity: .7;
        animation: ptpulse 5s ease-in-out infinite;
    }
    @keyframes ptpulse { 0%,100% { transform: scale(1); opacity: .65; } 50% { transform: scale(1.08); opacity: .85; } }

    .pt-cloud { position: absolute; background: rgba(255,255,255,.22); border-radius: 50px; animation: ptdrift linear infinite; }
    .pt-cloud::before, .pt-cloud::after { content: ''; position: absolute; background: inherit; border-radius: 50%; }
    .pt-cloud.c1 { width: 120px; height: 28px; top: 22px; inset-inline-start: 6%; animation-duration: 26s; }
    .pt-cloud.c1::before { width: 46px; height: 46px; top: -22px; inset-inline-start: 18px; }
    .pt-cloud.c1::after  { width: 34px; height: 34px; top: -14px; inset-inline-start: 60px; }
    .pt-cloud.c2 { width: 90px; height: 22px; bottom: 26px; inset-inline-start: 40%; animation-duration: 34s; animation-direction: reverse; opacity: .8; }
    .pt-cloud.c2::before { width: 36px; height: 36px; top: -17px; inset-inline-start: 14px; }
    .pt-cloud.c2::after  { width: 26px; height: 26px; top: -10px; inset-inline-start: 46px; }
    @keyframes ptdrift { 0%,100% { transform: translateX(-24px); } 50% { transform: translateX(34px); } }

    .pt-hero-ico {
        width: 58px; height: 58px; border-radius: 18px; flex-shrink: 0; display: grid; place-items: center; position: relative; z-index: 2;
        background: rgba(255,255,255,.20); border: 1px solid rgba(255,255,255,.38); backdrop-filter: blur(4px);
    }
    .pt-hero-ico svg { width: 30px; height: 30px; animation: ptbob 3.2s ease-in-out infinite; }
    @keyframes ptbob { 0%,100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-3px) rotate(-6deg); } }
    .pt-hero-text { flex: 1; min-width: 220px; position: relative; z-index: 2; }
    .pt-crumb { display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 500; opacity: .88; margin-bottom: 4px; }
    .pt-crumb i { font-style: normal; opacity: .6; }
    .pt-title { font-size: 23px; font-weight: 700; letter-spacing: -.2px; line-height: 1.2; }
    .pt-sub { font-size: 12.5px; opacity: .92; margin-top: 4px; max-width: 420px; }
    .pt-add {
        display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; padding: 11px 18px; border-radius: 12px; text-decoration: none;
        background: #fff; color: #0369a1; font-size: 13px; font-weight: 600; box-shadow: 0 8px 18px rgba(0,0,0,.16);
        transition: transform .2s, box-shadow .2s;
    }
    .pt-add:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,.2); }
    .pt-add svg { width: 16px; height: 16px; }

    /* adegan: pesawat terbang melengkung dari titik asal ke tujuan */
    .pt-scene { position: absolute; inset-inline-end: 24px; bottom: 6px; width: 380px; height: 130px; z-index: 1; pointer-events: none; }
    body[dir="rtl"] .pt-scene { transform: scaleX(-1); }
    .pt-scene .arc { fill: none; stroke: rgba(255,255,255,.7); stroke-width: 2; stroke-dasharray: 4 7; animation: ptdash 1.4s linear infinite; }
    @keyframes ptdash { to { stroke-dashoffset: -22; } }
    .pt-scene .orig { fill: none; stroke: #fff; stroke-width: 2.5; }
    .pt-scene .dest { fill: #fff; filter: drop-shadow(0 3px 4px rgba(0,0,0,.2)); animation: ptpin 2s ease-in-out infinite; transform-box: fill-box; transform-origin: 50% 100%; }
    @keyframes ptpin { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
    .pt-scene .jet { filter: drop-shadow(0 4px 5px rgba(0,0,0,.25)); }

    /* ---------- KARTU STATISTIK ---------- */
    .pt-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-bottom: 18px; }
    .pt-stat {
        position: relative; overflow: hidden; display: flex; align-items: center; gap: 14px; padding: 16px 18px; border-radius: 16px;
        background: var(--bg-card); border: 1px solid var(--border-color);
        animation: ptrise .55s cubic-bezier(.2,.8,.2,1) both; transition: transform .25s, box-shadow .25s;
    }
    .pt-stat:nth-child(2) { animation-delay: .08s; } .pt-stat:nth-child(3) { animation-delay: .16s; } .pt-stat:nth-child(4) { animation-delay: .24s; }
    .pt-stat:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(20,30,80,.12); }
    .pt-stat::after { content: ''; position: absolute; inset-inline-end: -26px; bottom: -26px; width: 90px; height: 90px; border-radius: 50%; background: var(--tone-soft); }
    @keyframes ptrise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    .pt-stat-ico { width: 46px; height: 46px; border-radius: 14px; flex-shrink: 0; display: grid; place-items: center; color: #fff; background: var(--tone); box-shadow: 0 8px 16px var(--tone-soft); position: relative; z-index: 1; }
    .pt-stat-ico svg { width: 22px; height: 22px; }
    .pt-stat-body { min-width: 0; position: relative; z-index: 1; }
    .pt-stat-label { font-size: 12px; font-weight: 500; color: var(--text-muted); }
    .pt-stat-val { font-size: 24px; font-weight: 700; line-height: 1.2; color: var(--text-main); }
    .pt-stat-val.sm { font-size: 15px; line-height: 1.5; padding-top: 3px; }
    .pt-stat-hint { font-size: 11.5px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; }
    .pt-stat.t1 { --tone: linear-gradient(135deg,#0369a1,#38bdf8); --tone-soft: rgba(14,165,233,.16); }
    .pt-stat.t2 { --tone: linear-gradient(135deg,#d97706,#fbbf24); --tone-soft: rgba(245,158,11,.16); }
    .pt-stat.t3 { --tone: linear-gradient(135deg,#059669,#34d399); --tone-soft: rgba(16,185,129,.16); }
    .pt-stat.t4 { --tone: linear-gradient(135deg,#ea580c,#fb923c); --tone-soft: rgba(249,115,22,.16); }

    /* ---------- KARTU TABEL ---------- */
    .pt-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; animation: ptrise .6s .3s cubic-bezier(.2,.8,.2,1) both; }
    .pt-card-head { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px dashed var(--border-color); }
    .pt-card-head svg { width: 18px; height: 18px; color: var(--sun); transform: rotate(45deg); }
    body[dir="rtl"] .pt-card-head svg { transform: scaleX(-1) rotate(45deg); }
    .pt-card-head h3 { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .pt-scroll { overflow-x: auto; }
    .pt table { margin: 0; }
    .pt th { white-space: nowrap; }
    .pt td { vertical-align: middle; }
    .pt tbody tr:hover td { background: var(--mod-soft); }

    .pt-no { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; white-space: nowrap; font-family: ui-monospace, Menlo, Consolas, monospace; font-size: 12px; font-weight: 700; letter-spacing: .3px; color: var(--mod); background: var(--mod-soft); border: 1px dashed var(--mod-2); }
    .pt-no svg { width: 13px; height: 13px; }

    .pt-dest { display: flex; align-items: center; gap: 10px; min-width: 190px; }
    .pt-route { display: flex; align-items: center; flex-shrink: 0; color: var(--mod); }
    .pt-route .dot { width: 8px; height: 8px; border-radius: 50%; border: 2px solid var(--mod); background: var(--bg-card); }
    .pt-route .ln { position: relative; width: 34px; height: 0; border-top: 2px dashed var(--mod-2); }
    .pt-route .ln svg { position: absolute; top: -8px; inset-inline-start: 9px; width: 15px; height: 15px; background: var(--bg-card); color: var(--sun); transform: rotate(45deg); }
    body[dir="rtl"] .pt-route .ln svg { transform: scaleX(-1) rotate(45deg); }
    .pt-route .pin { width: 17px; height: 17px; color: #ef4444; }
    .pt-dest-name { font-weight: 600; color: var(--text-main); }

    .pt-when { display: flex; flex-direction: column; gap: 2px; white-space: nowrap; }
    .pt-when-d, .pt-when-t { display: flex; align-items: center; gap: 7px; }
    .pt-when-d { font-weight: 600; color: var(--text-main); }
    .pt-when-t { font-size: 12px; color: var(--text-muted); }
    .pt-when svg { width: 14px; height: 14px; color: var(--mod); flex-shrink: 0; }
    .pt-cd { display: inline-flex; align-items: center; gap: 4px; margin-top: 5px; width: fit-content; padding: 2px 9px; border-radius: 20px; font-size: 10.5px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #f97316, #fbbf24); white-space: nowrap; }

    .pt-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .pt-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .pt-badge.ok   { background: rgba(16,185,129,.14); color: #059669; }
    .pt-badge.info { background: rgba(59,130,246,.14); color: #2563eb; }
    .pt-badge.sky  { background: rgba(14,165,233,.16); color: #0284c7; }
    .pt-badge.warn { background: rgba(245,158,11,.16); color: #b45309; }
    .pt-badge.bad  { background: rgba(239,68,68,.14);  color: #dc2626; }
    .pt-badge.off  { background: rgba(107,114,128,.16); color: #6b7280; }
    body.dark-mode .pt-badge.ok   { color: #34d399; }
    body.dark-mode .pt-badge.info { color: #60a5fa; }
    body.dark-mode .pt-badge.sky  { color: #7dd3fc; }
    body.dark-mode .pt-badge.warn { color: #fbbf24; }
    body.dark-mode .pt-badge.bad  { color: #f87171; }
    body.dark-mode .pt-badge.off  { color: #cbd5e1; }

    .pt-empty { text-align: center; padding: 46px 16px; }
    .pt-empty-ico { width: 68px; height: 68px; margin: 0 auto 14px; border-radius: 22px; display: grid; place-items: center; background: var(--mod-soft); color: var(--mod); }
    .pt-empty-ico svg { width: 32px; height: 32px; }
    .pt-empty strong { display: block; font-size: 14px; font-weight: 600; color: var(--text-main); }
    .pt-empty p { font-size: 12.5px; color: var(--text-muted); margin: 4px 0 14px; }
    .pt-empty .pt-add { margin-top: 0; color: #fff; background: linear-gradient(135deg, #0369a1, #0ea5e9); box-shadow: 0 8px 18px rgba(2,132,199,.28); }

    .pt-pager { padding: 14px 18px; border-top: 1px dashed var(--border-color); }
    .pt-pager:empty { display: none; }

    @media (max-width: 760px) { .pt-scene { opacity: .35; width: 300px; } }
    @media (prefers-reduced-motion: reduce) {
        .pt *, .pt *::before, .pt *::after { animation: none !important; }
    }
</style>

<div class="pt">
    <!-- HERO: kotak panjang beranimasi -->
    <div class="pt-hero">
        <span class="pt-sun" aria-hidden="true"></span>
        <span class="pt-cloud c1" aria-hidden="true"></span>
        <span class="pt-cloud c2" aria-hidden="true"></span>

        <!-- adegan penerbangan -->
        <div class="pt-scene" aria-hidden="true">
            <svg id="ptScene" viewBox="0 0 380 130" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <path id="ptArc" class="arc" d="M20 112 C 110 -12, 270 -12, 352 104"/>
                <circle class="orig" cx="20" cy="112" r="6"/>
                <path class="dest" d="M352 84a11 11 0 0 0-11 11c0 8 11 17 11 17s11-9 11-17a11 11 0 0 0-11-11zm0 15a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/>
                <g class="jet">
                    <g transform="rotate(45) scale(1.2) translate(-12,-12)"><path d="{{ $planePath }}" fill="#fff"/></g>
                    <animateMotion dur="6.5s" repeatCount="indefinite" rotate="auto"><mpath href="#ptArc"/></animateMotion>
                </g>
            </svg>
        </div>

        <div class="pt-hero-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $planePath }}"/></svg>
        </div>
        <div class="pt-hero-text">
            <div class="pt-crumb"><span>Travel</span><i>/</i><span>Pengajuan Saya</span></div>
            <div class="pt-title">Perjalanan Dinas Saya</div>
            <div class="pt-sub">Ajukan dan pantau perjalanan dinas Anda dalam satu tempat</div>
            <a href="{{ route('pegawai.travel-requests.create') }}" class="pt-add">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                <span>Ajukan Perjalanan</span>
            </a>
        </div>
    </div>

    <!-- KARTU STATISTIK -->
    <div class="pt-stats">
        <div class="pt-stat t1">
            <span class="pt-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg></span>
            <div class="pt-stat-body">
                <div class="pt-stat-label">Total Pengajuan</div>
                <div class="pt-stat-val"><b class="pt-num" style="--to: {{ (int) $total }}"><span class="pt-sr">{{ (int) $total }}</span></b></div>
                <div class="pt-stat-hint">Seluruh pengajuan Anda</div>
            </div>
        </div>
        <div class="pt-stat t2">
            <span class="pt-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
            <div class="pt-stat-body">
                <div class="pt-stat-label">Menunggu Persetujuan</div>
                <div class="pt-stat-val"><b class="pt-num" style="--to: {{ (int) $pending }}"><span class="pt-sr">{{ (int) $pending }}</span></b></div>
                <div class="pt-stat-hint">Menunggu keputusan</div>
            </div>
        </div>
        <div class="pt-stat t3">
            <span class="pt-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.1V12a10 10 0 1 1-5.9-9.1"/><path d="m9 11 3 3L22 4"/></svg></span>
            <div class="pt-stat-body">
                <div class="pt-stat-label">Disetujui</div>
                <div class="pt-stat-val"><b class="pt-num" style="--to: {{ (int) $approved }}"><span class="pt-sr">{{ (int) $approved }}</span></b></div>
                <div class="pt-stat-hint">Siap berangkat</div>
            </div>
        </div>
        <div class="pt-stat t4">
            <span class="pt-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"/><path d="M6.36 17.4 4 17l-2-4 1.1-.55a2 2 0 0 1 1.8 0l.17.1a2 2 0 0 0 1.8 0L8 12 5 6l.9-.45a2 2 0 0 1 2.09.2l4.02 3a2 2 0 0 0 2.1.2l4.19-2.06a2.41 2.41 0 0 1 1.73-.17L21 7a1.4 1.4 0 0 1 .87 1.99l-.38.76c-.23.46-.6.84-1.07 1.08L7.58 17.2a2 2 0 0 1-1.22.18Z"/></svg></span>
            <div class="pt-stat-body">
                <div class="pt-stat-label">Keberangkatan Berikutnya</div>
                @if($next)
                    <div class="pt-stat-val sm" data-no-i18n>{{ $next->departure_at->format('d-m-Y') }}</div>
                    <div class="pt-stat-hint" data-no-i18n>{{ $next->destination }}</div>
                @else
                    <div class="pt-stat-val sm" data-no-i18n>-</div>
                    <div class="pt-stat-hint">Belum ada jadwal</div>
                @endif
            </div>
        </div>
    </div>

    <!-- TABEL (id container dipakai oleh AJAX pagination di layout) -->
    <div class="pt-card table-card" id="ajax-table-container">
        <div class="pt-card-head">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $planePath }}"/></svg>
            <h3>Daftar Pengajuan Saya</h3>
        </div>
        <div class="pt-scroll">
            <table>
                <thead>
                    <tr><th>No</th><th>Tujuan</th><th>Berangkat</th><th>Status</th></tr>
                </thead>
                <tbody>
                @forelse($requests as $r)
                    @php
                        $key = strtolower(trim((string) $r->status));
                        [$cls, $statusLabel] = $statusMap[$key] ?? ['off', \Illuminate\Support\Str::headline((string) $r->status)];
                        $dep  = $r->departure_at;
                        $days = $dep ? (int) now()->startOfDay()->diffInDays($dep->copy()->startOfDay(), false) : null;
                    @endphp
                    <tr>
                        <td>
                            <span class="pt-no" data-no-i18n>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>
                                {{ $r->request_number }}
                            </span>
                        </td>
                        <td>
                            <div class="pt-dest">
                                <span class="pt-route" aria-hidden="true">
                                    <i class="dot"></i>
                                    <span class="ln"><svg viewBox="0 0 24 24" fill="currentColor"><path d="{{ $planePath }}"/></svg></span>
                                    <svg class="pin" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a8 8 0 0 0-8 8c0 6 8 12 8 12s8-6 8-12a8 8 0 0 0-8-8zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
                                </span>
                                <span class="pt-dest-name" data-no-i18n>{{ $r->destination }}</span>
                            </div>
                        </td>
                        <td>
                            @if($dep)
                                <div class="pt-when" data-no-i18n>
                                    <span class="pt-when-d">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"/><path d="M6.36 17.4 4 17l-2-4 1.1-.55a2 2 0 0 1 1.8 0l.17.1a2 2 0 0 0 1.8 0L8 12 5 6l.9-.45a2 2 0 0 1 2.09.2l4.02 3a2 2 0 0 0 2.1.2l4.19-2.06a2.41 2.41 0 0 1 1.73-.17L21 7a1.4 1.4 0 0 1 .87 1.99l-.38.76c-.23.46-.6.84-1.07 1.08L7.58 17.2a2 2 0 0 1-1.22.18Z"/></svg>
                                        {{ $dep->format('d-m-Y') }}
                                    </span>
                                    <span class="pt-when-t">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                                        {{ $dep->format('H:i') }}
                                    </span>
                                </div>
                                @if($days === 0)
                                    <span class="pt-cd">Hari ini</span>
                                @elseif($days === 1)
                                    <span class="pt-cd">Besok</span>
                                @elseif($days !== null && $days > 1)
                                    <span class="pt-cd"><b data-no-i18n>{{ $days }}</b><span>hari lagi</span></span>
                                @endif
                            @else
                                <span data-no-i18n>-</span>
                            @endif
                        </td>
                        <td><span class="pt-badge {{ $cls }}">{{ $statusLabel }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:0">
                            <div class="pt-empty">
                                <div class="pt-empty-ico" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $planePath }}"/></svg>
                                </div>
                                <strong>Belum ada pengajuan.</strong>
                                <p>Buat pengajuan perjalanan dinas pertama Anda.</p>
                                <a href="{{ route('pegawai.travel-requests.create') }}" class="pt-add">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                    <span>Ajukan Perjalanan</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pt-pager">{{ method_exists($requests, 'links') ? $requests->links() : '' }}</div>
    </div>
</div>

<script>
/* Hentikan animasi pesawat (SMIL) bila perangkat meminta gerakan dikurangi */
(function () {
    try {
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            var s = document.getElementById('ptScene');
            if (s && s.pauseAnimations) s.pauseAnimations();
        }
    } catch (e) {}
})();

/* Kamus khusus halaman ini (aman dijalankan sebelum maupun sesudah I18N siap) */
(function (d) {
    window.I18N_EXTRA = Object.assign(window.I18N_EXTRA || {}, d);
    if (window.I18N && window.I18N.extend) window.I18N.extend(d);
})({
    'Perjalanan Dinas Saya': ['My Official Travel', 'سفراتي الرسمية', '我的公务出差'],
    'Pengajuan Saya':        ['My Requests', 'طلباتي', '我的申请'],
    'Ajukan dan pantau perjalanan dinas Anda dalam satu tempat': ['Submit and track your official travel in one place', 'قدّم رحلات عملك الرسمية وتابعها في مكان واحد', '在一个地方提交并跟踪您的公务出差'],
    'Ajukan Perjalanan':     ['Request Travel', 'تقديم طلب سفر', '申请出差'],
    'Total Pengajuan':       ['Total Requests', 'إجمالي الطلبات', '申请总数'],
    'Seluruh pengajuan Anda': ['All your requests', 'جميع طلباتك', '您的全部申请'],
    'Menunggu Persetujuan':  ['Awaiting Approval', 'بانتظار الموافقة', '等待审批'],
    'Menunggu keputusan':    ['Awaiting decision', 'بانتظار القرار', '等待决定'],
    'Disetujui':             ['Approved', 'تمت الموافقة', '已批准'],
    'Siap berangkat':        ['Ready to depart', 'جاهز للمغادرة', '可出发'],
    'Keberangkatan Berikutnya': ['Next Departure', 'المغادرة القادمة', '下次出发'],
    'Belum ada jadwal':      ['No schedule yet', 'لا يوجد موعد بعد', '暂无日程'],
    'Daftar Pengajuan Saya': ['My Requests List', 'قائمة طلباتي', '我的申请列表'],
    'Tujuan':    ['Destination', 'الوجهة', '目的地'],
    'Berangkat': ['Departure', 'المغادرة', '出发'],
    'Hari ini':  ['Today', 'اليوم', '今天'],
    'Besok':     ['Tomorrow', 'غداً', '明天'],
    'hari lagi': ['days to go', 'أيام متبقية', '天后出发'],
    'Belum ada pengajuan.': ['No requests yet.', 'لا توجد طلبات بعد.', '暂无申请。'],
    'Buat pengajuan perjalanan dinas pertama Anda.': ['Create your first official travel request.', 'أنشئ أول طلب سفر رسمي لك.', '创建您的第一个出差申请。'],

    /* nilai status */
    'Draf':             ['Draft', 'مسودة', '草稿'],
    'Diajukan':         ['Submitted', 'مُقدَّم', '已提交'],
    'Menunggu':         ['Pending', 'قيد الانتظار', '待处理'],
    'Dalam Perjalanan': ['On the Way', 'في الطريق', '出差途中'],
    'Selesai':          ['Completed', 'مكتمل', '已完成'],
    'Ditolak':          ['Rejected', 'مرفوض', '已拒绝'],
    'Dibatalkan':       ['Cancelled', 'ملغى', '已取消']
});
</script>
@endsection
