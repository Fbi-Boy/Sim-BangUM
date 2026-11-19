@extends('layouts.app')
@section('title', 'Kendaraan Dinas')

@section('content')
@php
    /* Warna aksen per status (dipilih dari kata kunci nilai status). */
    $tone = function ($value) {
        $v = mb_strtolower((string) $value);
        if (preg_match('/(tersedia|available|aktif|ready)/', $v))                 return ['badge-success', '#10b981'];
        if (preg_match('/(digunakan|dipakai|dipinjam|in_use|booked|jalan)/', $v)) return ['badge-info',    '#2563eb'];
        if (preg_match('/(perawatan|maintenance|servis|service|repair)/', $v))    return ['badge-warning', '#f59e0b'];
        if (preg_match('/(rusak|nonaktif|inactive|damaged|retired)/', $v))        return ['badge-danger',  '#ef4444'];
        return ['badge-muted', '#64748b'];
    };
    $label = fn($v) => ucwords(str_replace('_', ' ', (string) $v));

    /* Ilustrasi kendaraan (badan mengikuti warna status lewat currentColor). __CLS__ diganti kelas CSS. */
    $sedanTpl = <<<'SVG'
<svg class="__CLS__" viewBox="0 0 200 90" fill="none" aria-hidden="true">
  <ellipse cx="100" cy="82" rx="86" ry="4.5" fill="#0f172a" opacity=".22"/>
  <path class="vh-beam" d="M188 50L252 33V73Z" fill="#fde68a" opacity="0"/>
  <g class="vh-body">
    <path d="M14 60V54q0-7 8-9l32-7 18-20q4-4 10-4h46q6 0 10 4l16 18 28 6q8 2 8 10v8q0 4-4 4H18q-4 0-4-4z" fill="currentColor"/>
    <path d="M14 56h176v4q0 4-4 4H18q-4 0-4-4z" fill="#000" opacity=".16"/>
    <path d="M58 38l14-16h26v16zM104 22h28l14 16h-42z" fill="#dff1ff"/>
    <path d="M67 38l9-11h7l-9 11zM111 38l9-11h6l-9 11z" fill="#fff" opacity=".5"/>
    <rect x="98" y="22" width="6" height="32" rx="1" fill="currentColor"/>
    <path d="M22 50q60-9 150-4" stroke="#fff" stroke-opacity=".38" stroke-width="2" stroke-linecap="round"/>
    <rect x="82" y="45" width="9" height="2.4" rx="1.2" fill="#fff" opacity=".7"/>
    <rect x="122" y="45" width="9" height="2.4" rx="1.2" fill="#fff" opacity=".7"/>
    <rect class="vh-light" x="176" y="48" width="14" height="6" rx="3" fill="#fde68a"/>
    <rect x="14" y="48" width="8" height="6" rx="2" fill="#fca5a5"/>
    <rect x="178" y="58" width="12" height="3" rx="1.5" fill="#cbd5e1"/>
  </g>
  <circle cx="54" cy="64" r="15" fill="#0f172a" opacity=".55"/>
  <circle cx="150" cy="64" r="15" fill="#0f172a" opacity=".55"/>
  <g class="wh"><circle cx="54" cy="64" r="12" fill="#1e293b"/><circle cx="54" cy="64" r="7" fill="#cbd5e1"/><path d="M54 57v14M47 64h14M49 59l10 10M59 59L49 69" stroke="#64748b" stroke-width="1.6" stroke-linecap="round"/><circle cx="54" cy="64" r="2.4" fill="#1e293b"/></g>
  <g class="wh"><circle cx="150" cy="64" r="12" fill="#1e293b"/><circle cx="150" cy="64" r="7" fill="#cbd5e1"/><path d="M150 57v14M143 64h14M145 59l10 10M155 59L145 69" stroke="#64748b" stroke-width="1.6" stroke-linecap="round"/><circle cx="150" cy="64" r="2.4" fill="#1e293b"/></g>
</svg>
SVG;
    $vanTpl = <<<'SVG'
<svg class="__CLS__" viewBox="0 0 200 90" fill="none" aria-hidden="true">
  <ellipse cx="100" cy="82" rx="88" ry="4.5" fill="#0f172a" opacity=".22"/>
  <path class="vh-beam" d="M190 52L252 35V75Z" fill="#fde68a" opacity="0"/>
  <g class="vh-body">
    <path d="M10 62V28q0-8 8-8h112q6 0 10 5l24 22 14 5q8 2 8 9v1q0 4-4 4H14q-4 0-4-4z" fill="currentColor"/>
    <path d="M10 56h180v6q0 4-4 4H14q-4 0-4-4z" fill="#000" opacity=".16"/>
    <g fill="#dff1ff">
      <rect x="22" y="30" width="26" height="18" rx="3"/>
      <rect x="54" y="30" width="26" height="18" rx="3"/>
      <rect x="86" y="30" width="26" height="18" rx="3"/>
      <path d="M118 30h10l16 18h-26z"/>
    </g>
    <path d="M26 46l8-14h6l-8 14zM58 46l8-14h6l-8 14zM90 46l8-14h6l-8 14z" fill="#fff" opacity=".45"/>
    <path d="M14 52q80-8 168-2" stroke="#fff" stroke-opacity=".34" stroke-width="2" stroke-linecap="round"/>
    <rect class="vh-light" x="180" y="52" width="10" height="6" rx="3" fill="#fde68a"/>
    <rect x="10" y="46" width="6" height="8" rx="2" fill="#fca5a5"/>
  </g>
  <circle cx="50" cy="64" r="15" fill="#0f172a" opacity=".55"/>
  <circle cx="150" cy="64" r="15" fill="#0f172a" opacity=".55"/>
  <g class="wh"><circle cx="50" cy="64" r="12" fill="#1e293b"/><circle cx="50" cy="64" r="7" fill="#cbd5e1"/><path d="M50 57v14M43 64h14M45 59l10 10M55 59L45 69" stroke="#64748b" stroke-width="1.6" stroke-linecap="round"/><circle cx="50" cy="64" r="2.4" fill="#1e293b"/></g>
  <g class="wh"><circle cx="150" cy="64" r="12" fill="#1e293b"/><circle cx="150" cy="64" r="7" fill="#cbd5e1"/><path d="M150 57v14M143 64h14M145 59l10 10M155 59L145 69" stroke="#64748b" stroke-width="1.6" stroke-linecap="round"/><circle cx="150" cy="64" r="2.4" fill="#1e293b"/></g>
</svg>
SVG;
    $car = fn(bool $isVan, string $cls = 'vh-car') => str_replace('__CLS__', $cls, $isVan ? $vanTpl : $sedanTpl);

    /* Siluet kota (dekorasi latar) */
    $skyline = '<svg class="vh-city" viewBox="0 0 300 60" preserveAspectRatio="none" aria-hidden="true">';
    foreach ([[0,20,22],[22,14,36],[38,18,28],[58,12,44],[72,20,30],[94,16,38],[112,22,24],[136,14,46],[152,20,32],[174,16,40],[192,22,26],[216,14,36],[232,20,30],[254,16,42],[272,28,24]] as [$bx, $bw, $bh]) {
        $skyline .= '<rect x="' . $bx . '" y="' . (60 - $bh) . '" width="' . $bw . '" height="' . $bh . '"/>';
    }
    $skyline .= '</svg>';

    $person = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6z"/></svg>';

    $col       = $vehicles->getCollection();
    $available = $col->filter(fn($v) => $tone($v->status)[0] === 'badge-success')->count();
    $inUse     = $col->filter(fn($v) => $tone($v->status)[0] === 'badge-info')->count();
    $seats     = $col->sum(fn($v) => (int) $v->passenger_capacity);
    $statuses  = $col->pluck('status')->filter()->unique()->values();
@endphp

<style>
    /* ================= HERO ================= */
    .vh-hero {
        position: relative; overflow: hidden; display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr); align-items: stretch;
        gap: 12px; margin-bottom: 20px; border-radius: 26px; color: #fff;
        background: linear-gradient(120deg, #172a6e 0%, #2150d8 52%, #3aa0f5 115%);
        box-shadow: 0 22px 46px rgba(30, 64, 175, .28);
    }
    .vh-hero::before { /* titik halus */
        content: ''; position: absolute; inset: 0; pointer-events: none; opacity: .5;
        background-image: radial-gradient(rgba(255,255,255,.22) 1.2px, transparent 1.2px); background-size: 22px 22px;
        -webkit-mask-image: linear-gradient(100deg, #000 0, transparent 60%); mask-image: linear-gradient(100deg, #000 0, transparent 60%);
    }
    .vh-hero::after { /* lingkaran dekor */
        content: ''; position: absolute; width: 320px; height: 320px; right: -90px; top: -150px; border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.22), transparent 68%); pointer-events: none;
    }
    .vh-hero-text { position: relative; z-index: 2; padding: 32px 0 30px 34px; display: flex; flex-direction: column; justify-content: center; gap: 12px; }
    .vh-pill { align-self: flex-start; display: inline-flex; align-items: center; gap: 7px; padding: 5px 12px; border-radius: 20px; background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.22); font-size: 11.5px; font-weight: 600; letter-spacing: .4px; backdrop-filter: blur(6px); }
    .vh-pill svg { width: 14px; height: 14px; }
    .vh-hero h2 { font-size: 28px; line-height: 1.15; font-weight: 800; letter-spacing: -.4px; }
    .vh-hero p { max-width: 430px; font-size: 13.5px; line-height: 1.6; color: rgba(255,255,255,.82); }
    .vh-hero-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 12px; margin-top: 6px; }
    .vh-btn {
        display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; border-radius: 13px; background: #fff; color: #1d4ed8;
        font-size: 13px; font-weight: 700; text-decoration: none; box-shadow: 0 10px 24px rgba(8,20,80,.28); transition: transform .2s, box-shadow .2s;
    }
    .vh-btn svg { width: 15px; height: 15px; }
    .vh-btn:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(8,20,80,.34); }
    .vh-chip-row { display: flex; gap: 8px; flex-wrap: wrap; }
    .vh-chip { padding: 6px 12px; border-radius: 20px; background: rgba(255,255,255,.14); font-size: 12px; font-weight: 600; }
    .vh-chip b { font-weight: 800; }

    /* adegan jalan */
    .hs { position: relative; z-index: 1; min-height: 210px; overflow: hidden; }
    .hs-sun { position: absolute; top: 26px; right: 56px; width: 54px; height: 54px; border-radius: 50%; background: #fde68a; box-shadow: 0 0 0 12px rgba(253,230,138,.18), 0 0 60px 20px rgba(253,230,138,.35); animation: hs-pulse 4s ease-in-out infinite; }
    .hs-cloud { position: absolute; height: 16px; border-radius: 20px; background: rgba(255,255,255,.7); animation: vh-drift 22s linear infinite; }
    .hs-cloud::before { content: ''; position: absolute; left: 14px; top: -9px; width: 22px; height: 22px; border-radius: 50%; background: inherit; }
    .hs-cloud.c1 { top: 40px; width: 56px; left: -80px; }
    .hs-cloud.c2 { top: 78px; width: 40px; left: -80px; animation-duration: 30s; animation-delay: -12s; opacity: .7; }
    .hs-cloud.c3 { top: 22px; width: 34px; left: -80px; animation-duration: 38s; animation-delay: -22s; opacity: .55; }
    .hs-city { position: absolute; left: 0; right: 0; bottom: 46px; height: 78px; color: #fff; opacity: .16; }
    .hs-city .vh-city { width: 100%; height: 100%; position: static; opacity: 1; }
    .hs-road { position: absolute; left: 0; right: 0; bottom: 0; height: 52px; background: #1a2547; border-top: 3px solid rgba(255,255,255,.25); }
    .hs-road::after { content: ''; position: absolute; left: 0; right: 0; top: 24px; height: 4px; background: linear-gradient(90deg, #fff 50%, transparent 50%) 0 0 / 44px 4px repeat-x; opacity: .8; animation: road-run .9s linear infinite; }
    .hs-van { position: absolute; bottom: 30px; left: 105%; width: 104px; color: #bfdbfe; opacity: .92; animation: hs-cross 17s linear infinite; }
    .hs-car { position: absolute; left: 50%; bottom: 2px; width: 226px; color: #f8fafc; transform: translateX(-46%); }
    .hs-car .vh-body { animation: vh-bounce .7s ease-in-out infinite; }
    .hs-car .vh-beam { opacity: .3; }
    .hs-svg { display: block; width: 100%; height: auto; overflow: visible; filter: drop-shadow(0 12px 12px rgba(5,10,40,.35)); }
    .hs-line { position: absolute; height: 3px; border-radius: 3px; background: rgba(255,255,255,.55); animation: hs-line 1.1s linear infinite; }
    .hs-line.l1 { left: 4%; bottom: 40px; width: 44px; }
    .hs-line.l2 { left: 9%; bottom: 26px; width: 66px; animation-delay: -.4s; }
    .hs-line.l3 { left: 2%; bottom: 14px; width: 36px; animation-delay: -.8s; }

    /* ================= TOOLBAR ================= */
    .vh-toolbar-card { margin-bottom: 18px; }
    .vh-toolbar-card .table-toolbar { border-bottom: none; }
    .tb-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .seg { display: inline-flex; padding: 3px; border-radius: 12px; background: var(--bg-soft, #f3f6fd); }
    .seg button { display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; border: none; border-radius: 9px; background: transparent; color: var(--text-muted); font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; transition: background .2s, color .2s; }
    .seg button svg { width: 15px; height: 15px; }
    .seg button.on { background: var(--bg-card); color: var(--accent, #2563eb); box-shadow: 0 2px 8px rgba(20,30,80,.12); }

    /* ================= KARTU KENDARAAN ================= */
    .vh-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(268px, 1fr)); gap: 20px; }
    .vh-card {
        position: relative; overflow: hidden; border-radius: 22px; background: var(--bg-card); border: 1px solid var(--border-color);
        transition: transform .25s, box-shadow .25s, border-color .25s;
    }
    .vh-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; z-index: 3; background: linear-gradient(90deg, var(--tint), color-mix(in srgb, var(--tint) 30%, #fff)); }
    .vh-card:hover { transform: translateY(-6px); border-color: var(--tint); box-shadow: 0 20px 36px color-mix(in srgb, var(--tint) 26%, transparent); }

    .vh-stage {
        position: relative; height: 178px; overflow: hidden; color: var(--tint);
        background: linear-gradient(180deg, color-mix(in srgb, var(--tint) 22%, #d9ecff) 0%, color-mix(in srgb, var(--tint) 7%, #f6faff) 100%);
    }
    body.dark-mode .vh-stage { background: linear-gradient(180deg, color-mix(in srgb, var(--tint) 26%, #0d1a3d) 0%, color-mix(in srgb, var(--tint) 9%, #17223f) 100%); }
    .vh-stage::before { /* titik halus */
        content: ''; position: absolute; inset: 0; opacity: .45; pointer-events: none;
        background-image: radial-gradient(color-mix(in srgb, var(--tint) 40%, transparent) 1.1px, transparent 1.1px); background-size: 18px 18px;
        -webkit-mask-image: linear-gradient(200deg, #000 0, transparent 55%); mask-image: linear-gradient(200deg, #000 0, transparent 55%);
    }
    .vh-cloud { position: absolute; height: 13px; border-radius: 20px; background: rgba(255,255,255,.8); animation: vh-drift 20s linear infinite; }
    .vh-cloud::before { content: ''; position: absolute; left: 11px; top: -7px; width: 18px; height: 18px; border-radius: 50%; background: inherit; }
    .vh-cloud.c1 { top: 46px; width: 44px; left: -70px; }
    .vh-cloud.c2 { top: 76px; width: 32px; left: -70px; animation-duration: 28s; animation-delay: -11s; opacity: .7; }
    body.dark-mode .vh-cloud { background: rgba(255,255,255,.2); }
    .vh-city { position: absolute; left: 0; right: 0; bottom: 30px; width: 100%; height: 58px; color: var(--tint); opacity: .17; }
    .vh-city rect, .hs-city rect { fill: currentColor; }
    .vh-road { position: absolute; left: 0; right: 0; bottom: 0; height: 30px; background: #2f3b52; border-top: 2px solid rgba(255,255,255,.22); }
    .vh-road::after { content: ''; position: absolute; left: 0; right: 0; top: 13px; height: 3px; background: linear-gradient(90deg, #fff 50%, transparent 50%) 0 0 / 36px 3px repeat-x; opacity: .75; animation: road-run 1s linear infinite paused; }
    .vh-card:hover .vh-road::after { animation-play-state: running; }

    .vh-car { position: absolute; left: 50%; bottom: -3px; width: 178px; overflow: visible; transform: translateX(-50%); transition: transform .6s cubic-bezier(.2,.8,.2,1); filter: drop-shadow(0 8px 8px rgba(15,23,42,.28)); }
    .vh-card:hover .vh-car { transform: translateX(-43%); }
    .vh-card:hover .vh-beam { opacity: .4; transition: opacity .3s .1s; }
    .vh-card:hover .vh-body { animation: vh-bounce .5s ease-in-out infinite; }
    .wh { transform-box: fill-box; transform-origin: center; }
    .vh-card:hover .wh, .hs-car .wh, .hs-van .wh { animation: vh-spin .6s linear infinite; }
    .hs-van .wh { animation-duration: .9s; }

    .vh-status { position: absolute; top: 14px; left: 12px; z-index: 2; backdrop-filter: blur(6px); }
    .vh-cap { position: absolute; top: 14px; right: 12px; z-index: 2; display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: rgba(15,23,42,.55); color: #fff; backdrop-filter: blur(6px); }
    .vh-cap svg { width: 12px; height: 12px; }

    .vh-body-card { padding: 16px 18px 18px; display: flex; flex-direction: column; gap: 14px; }
    .vh-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .vh-name { min-width: 0; }
    .vh-name h3 { font-size: 15.5px; font-weight: 700; color: var(--text-main); line-height: 1.25; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .vh-name p { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

    /* Plat nomor dinas (merah, tulisan putih) */
    .plate {
        display: inline-block; flex-shrink: 0; padding: 5px 13px; border-radius: 8px; white-space: nowrap;
        background: linear-gradient(180deg, #dc2626, #b91c1c); color: #fff; font-weight: 800; font-size: 13px; letter-spacing: 1.6px;
        box-shadow: inset 0 0 0 2px rgba(255,255,255,.88), 0 6px 12px rgba(185,28,28,.28);
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    }
    .plate.sm { font-size: 12px; padding: 4px 11px; }

    .vh-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding-top: 14px; border-top: 1px dashed var(--border-color); }
    .vh-meta div span { display: block; font-size: 10.5px; text-transform: uppercase; letter-spacing: .7px; color: var(--text-muted); }
    .vh-meta div b { display: block; margin-top: 2px; font-size: 13px; font-weight: 600; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .seats { display: flex; align-items: center; gap: 3px; color: var(--tint); flex-wrap: wrap; }
    .seats svg { width: 15px; height: 15px; }
    .seats small { margin-left: 6px; font-size: 11.5px; color: var(--text-muted); }

    /* ================= TABEL ================= */
    .tbl-car { position: relative; width: 56px; height: 38px; border-radius: 10px; display: grid; place-items: center; flex-shrink: 0; color: var(--tint); background: color-mix(in srgb, var(--tint) 13%, transparent); }
    .tbl-car .vh-car { position: static; width: 42px; transform: none; transition: none; filter: none; }
    .cell-veh { display: flex; align-items: center; gap: 12px; }

    /* ================= KOSONG ================= */
    .vh-empty-art { position: relative; width: 190px; height: 104px; margin: 0 auto 14px; color: #2563eb; overflow: hidden; border-radius: 18px; background: linear-gradient(180deg, #dbeafe, #f1f7ff); }
    body.dark-mode .vh-empty-art { background: linear-gradient(180deg, #14234a, #1a2745); }
    .vh-empty-art .vh-road { height: 22px; }
    .vh-empty-art .vh-road::after { top: 9px; animation-play-state: running; }
    .vh-empty-art .vh-car { width: 118px; bottom: -2px; transform: translateX(-50%); }
    .vh-empty-art .wh { animation: vh-spin 1.2s linear infinite; }

    .pager-wrap { margin-top: 18px; }
    button:focus-visible, a:focus-visible { outline: 2px solid #3b82f6; outline-offset: 2px; }

    /* ================= ANIMASI ================= */
    @keyframes vh-drift  { from { transform: translateX(0); } to { transform: translateX(560px); } }
    @keyframes vh-spin   { to { transform: rotate(360deg); } }
    @keyframes vh-bounce { 50% { transform: translateY(-1.6px); } }
    @keyframes road-run  { to { background-position: -44px 0; } }
    @keyframes hs-cross  { from { left: 105%; } to { left: -32%; } }
    @keyframes hs-line   { 0% { transform: translateX(14px); opacity: 0; } 30% { opacity: .8; } 100% { transform: translateX(-26px); opacity: 0; } }
    @keyframes hs-pulse  { 50% { transform: scale(1.06); } }

    @media (max-width: 820px) {
        .vh-hero { grid-template-columns: 1fr; }
        .vh-hero-text { padding: 26px 24px 0; }
        .vh-hero h2 { font-size: 24px; }
        .hs { min-height: 190px; }
    }
    @media (max-width: 720px) { .tb-right { width: 100%; justify-content: space-between; } .vh-btn { width: 100%; justify-content: center; } }
    @media (prefers-reduced-motion: reduce) {
        .vh-card, .vh-car { transition: none !important; }
        .vh-card:hover { transform: none; } .vh-card:hover .vh-car { transform: translateX(-50%); }
        .hs-sun, .hs-cloud, .vh-cloud, .hs-van, .hs-line, .hs-road::after, .vh-road::after, .hs-car .vh-body, .wh, .vh-card:hover .vh-body { animation: none !important; }
    }
</style>

<!-- HERO -->
<section class="vh-hero">
    <div class="vh-hero-text">
        <span class="vh-pill">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14M5 17a2 2 0 1 0 4 0M15 17a2 2 0 1 0 4 0M3 17V9l2-4h11l3 4h2v8"/></svg>
            Manajemen Armada
        </span>
        <h2>Kendaraan Dinas</h2>
        <p>Daftar armada kantor beserta kapasitas dan ketersediaannya, siap digunakan untuk perjalanan dinas dan kegiatan operasional.</p>
        <div class="vh-hero-actions">
            <a href="{{ route('admin.kendaraan.vehicles.create') }}" class="vh-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Kendaraan
            </a>
            <div class="vh-chip-row">
                <span class="vh-chip"><b>{{ $vehicles->total() }}</b> unit</span>
                <span class="vh-chip"><b>{{ $available }}</b> tersedia</span>
            </div>
        </div>
    </div>

    <div class="hs" aria-hidden="true">
        <span class="hs-sun"></span>
        <span class="hs-cloud c1"></span><span class="hs-cloud c2"></span><span class="hs-cloud c3"></span>
        <div class="hs-city">{!! $skyline !!}</div>
        <div class="hs-road"></div>
        <div class="hs-van">{!! $car(true, 'hs-svg') !!}</div>
        <span class="hs-line l1"></span><span class="hs-line l2"></span><span class="hs-line l3"></span>
        <div class="hs-car">{!! $car(false, 'hs-svg') !!}</div>
    </div>
</section>

<!-- STATISTIK -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14M5 17a2 2 0 1 0 4 0M15 17a2 2 0 1 0 4 0M3 17V9l2-4h11l3 4h2v8"/></svg></div>
        <div><div class="stat-val">{{ $vehicles->total() }}</div><div class="stat-lbl">Total kendaraan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
        <div><div class="stat-val">{{ $available }}</div><div class="stat-lbl">Tersedia <small>di halaman ini</small></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l18-8-8 18-2-8z"/></svg></div>
        <div><div class="stat-val">{{ $inUse }}</div><div class="stat-lbl">Sedang digunakan <small>di halaman ini</small></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6z"/></svg></div>
        <div><div class="stat-val">{{ $seats }}</div><div class="stat-lbl">Total kursi penumpang <small>di halaman ini</small></div></div>
    </div>
</div>

@if($vehicles->count())
    <!-- TOOLBAR -->
    <div class="table-card vh-toolbar-card">
        <div class="table-toolbar">
            <label class="tb-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="vhSearch" placeholder="Cari no. polisi, merek, atau model..." autocomplete="off" aria-label="Cari kendaraan">
            </label>
            <div class="tb-right">
                @if($statuses->count() > 1)
                    <div class="chips" id="vhChips">
                        <button type="button" class="chip on" data-status="all">Semua</button>
                        @foreach($statuses as $st)
                            <button type="button" class="chip" data-status="{{ mb_strtolower($st) }}">{{ $label($st) }}</button>
                        @endforeach
                    </div>
                @endif
                <div class="seg" id="vhView" role="group" aria-label="Tampilan">
                    <button type="button" class="on" data-view="grid" aria-label="Tampilan kartu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"><rect x="4" y="4" width="6.5" height="6.5" rx="1.5"/><rect x="13.5" y="4" width="6.5" height="6.5" rx="1.5"/><rect x="4" y="13.5" width="6.5" height="6.5" rx="1.5"/><rect x="13.5" y="13.5" width="6.5" height="6.5" rx="1.5"/></svg>Kartu
                    </button>
                    <button type="button" data-view="table" aria-label="Tampilan tabel">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>Tabel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TAMPILAN KARTU -->
    <div class="vh-grid" id="vhGrid">
        @foreach($vehicles as $v)
            @php
                [$bClass, $tint] = $tone($v->status);
                $cap   = (int) $v->passenger_capacity;
                $isVan = $cap >= 8;
                $show  = min($cap, 8);
            @endphp
            <article class="vh-card" data-vh data-status="{{ mb_strtolower((string) $v->status) }}"
                     data-search="{{ mb_strtolower($v->license_plate.' '.$v->make.' '.$v->model.' '.$v->status) }}"
                     style="--tint: {{ $tint }}">
                <div class="vh-stage">
                    <span class="vh-cloud c1"></span><span class="vh-cloud c2"></span>
                    {!! $skyline !!}
                    <span class="vh-road"></span>
                    <span class="badge {{ $bClass }} vh-status" style="background: var(--bg-card)"><span class="dot"></span>{{ $label($v->status) ?: '-' }}</span>
                    <span class="vh-cap">{!! $person !!}{{ $cap }}</span>
                    {!! $car($isVan) !!}
                </div>
                <div class="vh-body-card">
                    <div class="vh-top">
                        <div class="vh-name">
                            <h3>{{ $v->make }} {{ $v->model }}</h3>
                            <p>Kendaraan dinas</p>
                        </div>
                        <span class="plate">{{ $v->license_plate }}</span>
                    </div>
                    <div class="vh-meta">
                        <div><span>Merek</span><b>{{ $v->make }}</b></div>
                        <div><span>Model</span><b>{{ $v->model }}</b></div>
                    </div>
                    <div class="seats" title="Kapasitas {{ $cap }} orang">
                        @for($i = 0; $i < $show; $i++){!! $person !!}@endfor
                        <small>{{ $cap }} penumpang @if($cap > 8)(+{{ $cap - 8 }})@endif</small>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <!-- TAMPILAN TABEL -->
    <div class="table-card" id="vhTableCard" hidden>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kendaraan</th>
                        <th>No. Polisi</th>
                        <th>Merek</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $v)
                        @php [$bClass, $tint] = $tone($v->status); $cap = (int) $v->passenger_capacity; @endphp
                        <tr data-vh data-status="{{ mb_strtolower((string) $v->status) }}"
                            data-search="{{ mb_strtolower($v->license_plate.' '.$v->make.' '.$v->model.' '.$v->status) }}"
                            style="--tint: {{ $tint }}">
                            <td>
                                <div class="cell-veh">
                                    <span class="tbl-car">{!! $car($cap >= 8) !!}</span>
                                    <div class="cell-main" style="min-width:0">
                                        <span class="cell-title">{{ $v->make }} {{ $v->model }}</span>
                                        <span class="cell-sub">Kendaraan dinas</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="plate sm">{{ $v->license_plate }}</span></td>
                            <td>{{ $v->make }}</td>
                            <td><span class="badge badge-muted">{{ $cap }} orang</span></td>
                            <td><span class="badge {{ $bClass }}"><span class="dot"></span>{{ $label($v->status) ?: '-' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- KOSONG -->
<div class="table-card" @if($vehicles->count()) hidden @endif>
    <div class="empty-state" id="vhEmpty" @if($vehicles->count()) hidden @endif>
        <div class="vh-empty-art" style="--tint:#2563eb">
            <span class="vh-road"></span>
            {!! $car(false) !!}
        </div>
        <h3 id="vhEmptyTitle">{{ $vehicles->count() ? 'Kendaraan tidak ditemukan' : 'Belum ada kendaraan' }}</h3>
        <p id="vhEmptyText">{{ $vehicles->count() ? 'Ubah kata kunci atau pilih status lain.' : 'Tambahkan kendaraan dinas pertama untuk mulai mengelola armada.' }}</p>
    </div>
</div>

<!-- PAGINASI (komponen) -->
@if($vehicles->total() > 0)
    <div class="table-card pager-wrap">
        <x-pagination :paginator="$vehicles" unit="kendaraan" />
    </div>
@endif

<script>
(function () {
    var $ = function (id) { return document.getElementById(id); };
    var items = Array.prototype.slice.call(document.querySelectorAll('[data-vh]'));
    var status = 'all', view = 'grid';
    var empty = $('vhEmpty');
    if (!$('vhGrid')) return;

    function apply() {
        var q = $('vhSearch').value.trim().toLowerCase(), shown = 0;
        items.forEach(function (el) {
            var ok = (status === 'all' || el.getAttribute('data-status') === status) &&
                     (!q || el.getAttribute('data-search').indexOf(q) > -1);
            el.hidden = !ok;
            /* hitung berdasarkan tampilan kartu saja agar tidak dobel */
            if (ok && el.tagName === 'ARTICLE') shown++;
        });
        empty.hidden = shown > 0;
        empty.closest('.table-card').hidden = shown > 0 ? true : false;
    }

    function setView(v) {
        view = v;
        $('vhGrid').hidden = v !== 'grid';
        $('vhTableCard').hidden = v !== 'table';
        document.querySelectorAll('#vhView button').forEach(function (b) { b.classList.toggle('on', b.getAttribute('data-view') === v); });
        try { localStorage.setItem('simbu.vehicles.view', v); } catch (e) {}
    }

    $('vhSearch').addEventListener('input', apply);
    var chips = $('vhChips');
    if (chips) chips.addEventListener('click', function (e) {
        var c = e.target.closest('.chip'); if (!c) return;
        status = c.getAttribute('data-status');
        chips.querySelectorAll('.chip').forEach(function (x) { x.classList.toggle('on', x === c); });
        apply();
    });
    $('vhView').addEventListener('click', function (e) {
        var b = e.target.closest('button'); if (b) setView(b.getAttribute('data-view'));
    });

    var saved = null;
    try { saved = localStorage.getItem('simbu.vehicles.view'); } catch (e) {}
    setView(saved === 'table' ? 'table' : 'grid');
    apply();
})();
</script>
@endsection
