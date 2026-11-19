@extends('layouts.app')
@section('title','Kelola User')
@section('content')
@php
    /* Statistik kartu. Jika controller mengirim $stats (['total'=>,'no_role'=>,'super_admin'=>,'roles'=>]) dipakai apa adanya;
       jika tidak, dihitung dari data pada halaman yang sedang tampil. */
    $col = collect(method_exists($users, 'getCollection') ? $users->getCollection() : $users);
    $st  = isset($stats) && is_array($stats) ? $stats : [];

    $total      = $st['total']       ?? (method_exists($users, 'total') ? $users->total() : $col->count());
    $noRole     = $st['no_role']     ?? $col->filter(fn($x) => $x->roles->isEmpty())->count();
    $superAdmin = $st['super_admin'] ?? $col->filter(fn($x) => $x->roles->contains('name', 'super_admin'))->count();
    $roleKinds  = $st['roles']       ?? $col->flatMap(fn($x) => $x->roles->pluck('name'))->unique()->count();

    /* daftar role pada halaman ini (untuk chip filter) */
    $roleCounts = $col->flatMap(fn($x) => $x->roles->pluck('name'))->countBy()->sortDesc();

    /* warna chip role berdasarkan nama role */
    $roleTone = function ($n) {
        $n = strtolower($n);
        return match (true) {
            $n === 'super_admin'                      => 'rose',
            str_starts_with($n, 'admin')              => 'blue',
            in_array($n, ['kepala_bagian','kasubbag']) => 'violet',
            $n === 'pegawai'                          => 'green',
            $n === 'pengemudi'                        => 'orange',
            $n === 'auditor'                          => 'teal',
            default                                   => 'slate',
        };
    };

    $avatarTones = ['a1','a2','a3','a4','a5','a6'];
    $meId = auth()->id();
@endphp

<style>
    /* ===== Modul Administrasi · Kelola User (tema keamanan / indigo) ===== */
    .ku { --mod: #4f46e5; --mod-2: #818cf8; --mod-soft: rgba(99,102,241,.12); }
    body.dark-mode .ku { --mod: #818cf8; --mod-soft: rgba(129,140,248,.16); }
    .ku [hidden] { display: none !important; }

    @property --pn { syntax: '<integer>'; initial-value: 0; inherits: false; }
    .ku-num { --pn: var(--to); counter-reset: kun var(--pn); animation: kucount 1.1s ease-out both; }
    .ku-num::after { content: counter(kun); }
    @keyframes kucount { from { --pn: 0; } to { --pn: var(--to); } }
    .ku-sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }

    /* ---------- HERO ---------- */
    .ku-hero {
        position: relative; overflow: hidden; border-radius: 20px; padding: 26px 28px; min-height: 160px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap; color: #fff;
        background: linear-gradient(150deg, #1e1b4b 0%, #4338ca 55%, #818cf8 100%);
        box-shadow: 0 16px 38px rgba(67,56,202,.30);
    }
    .ku-grid { position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.14) 1px, transparent 1px); background-size: 18px 18px; opacity: .6; }
    .ku-scan {
        position: absolute; top: 0; bottom: 0; width: 110px; inset-inline-start: -120px; pointer-events: none;
        background: linear-gradient(90deg, rgba(255,255,255,0), rgba(167,243,208,.22), rgba(255,255,255,0));
        animation: kuscan 7s linear infinite;
    }
    @keyframes kuscan { from { inset-inline-start: -120px; } to { inset-inline-start: 105%; } }

    .ku-float { position: absolute; width: 30px; height: 30px; border-radius: 10px; display: grid; place-items: center; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.25); animation: kufloat 6s ease-in-out infinite; }
    .ku-float svg { width: 16px; height: 16px; }
    .ku-float.f1 { top: 16px; inset-inline-start: 46%; }
    .ku-float.f2 { bottom: 16px; inset-inline-start: 56%; animation-delay: -2s; }
    .ku-float.f3 { top: 22px; inset-inline-start: 66%; animation-delay: -4s; }
    @keyframes kufloat { 0%,100% { transform: translateY(0) rotate(0); } 50% { transform: translateY(-9px) rotate(8deg); } }

    .ku-scene { position: absolute; inset-inline-end: 20px; top: 50%; margin-top: -80px; width: 260px; height: 160px; pointer-events: none; }
    .ku-scene .ring { fill: none; stroke: rgba(255,255,255,.55); stroke-width: 1.5; transform-box: fill-box; transform-origin: center; animation: kuping 3.6s ease-out infinite; opacity: 0; }
    .ku-scene .ring.r2 { animation-delay: 1.2s; } .ku-scene .ring.r3 { animation-delay: 2.4s; }
    @keyframes kuping { 0% { transform: scale(.55); opacity: .75; } 100% { transform: scale(1.25); opacity: 0; } }
    .ku-scene .orbit { fill: none; stroke: rgba(255,255,255,.25); stroke-width: 1; stroke-dasharray: 3 5; }
    .ku-scene .shield { filter: drop-shadow(0 8px 14px rgba(0,0,0,.28)); animation: kubreath 4s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }
    @keyframes kubreath { 0%,100% { transform: scale(1); } 50% { transform: scale(1.05); } }

    .ku-hero-ico { width: 58px; height: 58px; border-radius: 18px; flex-shrink: 0; display: grid; place-items: center; position: relative; z-index: 2; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.36); backdrop-filter: blur(4px); }
    .ku-hero-ico svg { width: 29px; height: 29px; }
    .ku-hero-text { flex: 1; min-width: 220px; max-width: 520px; position: relative; z-index: 2; }
    .ku-crumb { display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 500; opacity: .88; margin-bottom: 4px; }
    .ku-crumb i { font-style: normal; opacity: .6; }
    .ku-title { font-size: 23px; font-weight: 700; letter-spacing: -.2px; line-height: 1.2; }
    .ku-sub { font-size: 12.5px; opacity: .92; margin-top: 4px; }

    /* ---------- STATISTIK ---------- */
    .ku-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-bottom: 18px; }
    .ku-stat { position: relative; overflow: hidden; display: flex; align-items: center; gap: 14px; padding: 16px 18px; border-radius: 16px; background: var(--bg-card); border: 1px solid var(--border-color); animation: kurise .55s cubic-bezier(.2,.8,.2,1) both; transition: transform .25s, box-shadow .25s; }
    .ku-stat:nth-child(2) { animation-delay: .08s; } .ku-stat:nth-child(3) { animation-delay: .16s; } .ku-stat:nth-child(4) { animation-delay: .24s; }
    .ku-stat:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(20,30,80,.12); }
    .ku-stat::after { content: ''; position: absolute; inset-inline-end: -26px; bottom: -26px; width: 90px; height: 90px; border-radius: 50%; background: var(--tone-soft); }
    @keyframes kurise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    .ku-stat-ico { width: 46px; height: 46px; border-radius: 14px; flex-shrink: 0; display: grid; place-items: center; color: #fff; background: var(--tone); box-shadow: 0 8px 16px var(--tone-soft); position: relative; z-index: 1; }
    .ku-stat-ico svg { width: 22px; height: 22px; }
    .ku-stat-body { min-width: 0; position: relative; z-index: 1; }
    .ku-stat-label { font-size: 12px; font-weight: 500; color: var(--text-muted); }
    .ku-stat-val { font-size: 24px; font-weight: 700; line-height: 1.2; color: var(--text-main); }
    .ku-stat-hint { font-size: 11.5px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
    .ku-stat.t1 { --tone: linear-gradient(135deg,#4338ca,#818cf8); --tone-soft: rgba(99,102,241,.16); }
    .ku-stat.t2 { --tone: linear-gradient(135deg,#d97706,#fbbf24); --tone-soft: rgba(245,158,11,.16); }
    .ku-stat.t3 { --tone: linear-gradient(135deg,#be123c,#fb7185); --tone-soft: rgba(244,63,94,.16); }
    .ku-stat.t4 { --tone: linear-gradient(135deg,#0f766e,#2dd4bf); --tone-soft: rgba(20,184,166,.16); }

    /* ---------- KARTU TABEL ---------- */
    .ku-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; animation: kurise .6s .3s cubic-bezier(.2,.8,.2,1) both; }
    .ku-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; padding: 14px 18px; border-bottom: 1px solid var(--border-color); }
    .ku-bar h3 { font-size: 14px; font-weight: 600; color: var(--text-main); display: flex; align-items: center; gap: 10px; margin-inline-end: auto; }
    .ku-bar h3::before { content: ''; width: 4px; height: 18px; border-radius: 3px; background: linear-gradient(var(--mod-2), var(--mod)); }
    .ku-search { display: flex; align-items: center; gap: 8px; height: 38px; padding: 0 12px; min-width: 240px; border-radius: 11px; background: var(--bg-soft); border: 1px solid transparent; color: var(--text-muted); transition: border-color .2s, box-shadow .2s; }
    .ku-search:focus-within { border-color: var(--mod); box-shadow: 0 0 0 3px var(--mod-soft); }
    .ku-search svg { width: 16px; height: 16px; flex-shrink: 0; }
    .ku-search input { flex: 1; min-width: 0; border: none; outline: none; background: transparent; font: inherit; font-size: 12.5px; color: var(--text-main); }
    .ku-search kbd { font: 600 10px/1 ui-monospace, Menlo, monospace; padding: 3px 6px; border-radius: 5px; border: 1px solid var(--border-color); color: var(--text-muted); background: var(--bg-card); }

    .ku-chips { display: flex; gap: 8px; flex-wrap: wrap; padding: 12px 18px; border-bottom: 1px dashed var(--border-color); }
    .ku-chip { display: inline-flex; align-items: center; gap: 7px; padding: 5px 12px; border-radius: 20px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-muted); font-size: 12px; font-weight: 500; cursor: pointer; transition: all .2s; }
    .ku-chip:hover { border-color: var(--mod-2); color: var(--mod); }
    .ku-chip.on { background: var(--mod); border-color: var(--mod); color: #fff; box-shadow: 0 6px 14px var(--mod-soft); }
    body.dark-mode .ku-chip.on { color: #0f172a; }
    .ku-chip em { font-style: normal; font-size: 10.5px; font-weight: 700; min-width: 18px; padding: 1px 6px; border-radius: 10px; text-align: center; background: var(--mod-soft); color: var(--mod); }
    .ku-chip.on em { background: rgba(255,255,255,.25); color: inherit; }

    .ku-scroll { overflow-x: auto; }
    .ku table { margin: 0; }
    .ku th { white-space: nowrap; }
    .ku td { vertical-align: middle; }
    .ku tbody tr[data-user] { animation: kurise .45s both; }
    .ku tbody tr:hover td { background: var(--mod-soft); }
    .ku tr.norole td:first-child { box-shadow: inset 3px 0 0 #f59e0b; }
    body[dir="rtl"] .ku tr.norole td:first-child { box-shadow: inset -3px 0 0 #f59e0b; }

    .ku-user { display: flex; align-items: center; gap: 12px; min-width: 190px; }
    .ku-avatar { width: 40px; height: 40px; border-radius: 13px; flex-shrink: 0; display: grid; place-items: center; color: #fff; font-size: 13px; font-weight: 700; letter-spacing: .5px; box-shadow: 0 6px 14px rgba(0,0,0,.14); }
    .ku-avatar.a1 { background: linear-gradient(135deg,#4338ca,#818cf8); } .ku-avatar.a2 { background: linear-gradient(135deg,#0f766e,#2dd4bf); }
    .ku-avatar.a3 { background: linear-gradient(135deg,#be123c,#fb7185); } .ku-avatar.a4 { background: linear-gradient(135deg,#c2410c,#fb923c); }
    .ku-avatar.a5 { background: linear-gradient(135deg,#0369a1,#38bdf8); } .ku-avatar.a6 { background: linear-gradient(135deg,#6d28d9,#c084fc); }
    .ku-uname { font-weight: 600; color: var(--text-main); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .ku-me { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; color: #fff; background: linear-gradient(135deg,#4338ca,#818cf8); }

    .ku-mail { display: inline-flex; align-items: center; gap: 8px; color: var(--text-main); }
    .ku-copy { width: 28px; height: 28px; border-radius: 8px; border: 1px solid transparent; background: var(--bg-soft); color: var(--text-muted); display: grid; place-items: center; cursor: pointer; transition: background .2s, color .2s, transform .2s; }
    .ku-copy:hover { background: var(--mod-soft); color: var(--mod); }
    .ku-copy:active { transform: scale(.9); }
    .ku-copy svg { width: 14px; height: 14px; }

    .ku-roles { display: flex; flex-wrap: wrap; gap: 6px; }
    .ku-role { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; white-space: nowrap; }
    .ku-role::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .ku-role.rose   { background: rgba(244,63,94,.14);  color: #e11d48; }
    .ku-role.blue   { background: rgba(59,130,246,.14); color: #2563eb; }
    .ku-role.violet { background: rgba(139,92,246,.15); color: #7c3aed; }
    .ku-role.green  { background: rgba(16,185,129,.14); color: #059669; }
    .ku-role.orange { background: rgba(249,115,22,.15); color: #c2410c; }
    .ku-role.teal   { background: rgba(20,184,166,.15); color: #0f766e; }
    .ku-role.slate  { background: rgba(100,116,139,.16); color: #475569; }
    .ku-role.none   { background: transparent; color: #b45309; border: 1px dashed #f59e0b; }
    body.dark-mode .ku-role.rose   { color: #fb7185; } body.dark-mode .ku-role.blue  { color: #60a5fa; }
    body.dark-mode .ku-role.violet { color: #c4b5fd; } body.dark-mode .ku-role.green { color: #34d399; }
    body.dark-mode .ku-role.orange { color: #fb923c; } body.dark-mode .ku-role.teal  { color: #2dd4bf; }
    body.dark-mode .ku-role.slate  { color: #cbd5e1; } body.dark-mode .ku-role.none  { color: #fbbf24; }

    .ku-act { display: inline-flex; align-items: center; gap: 7px; padding: 7px 13px; border-radius: 10px; text-decoration: none; white-space: nowrap; font-size: 12.5px; font-weight: 600; color: var(--mod); background: var(--mod-soft); transition: background .2s, color .2s, transform .2s; }
    .ku-act:hover { background: var(--mod); color: #fff; transform: translateY(-1px); }
    body.dark-mode .ku-act:hover { color: #0f172a; }
    .ku-act svg { width: 15px; height: 15px; }

    .ku-empty { text-align: center; padding: 44px 16px; }
    .ku-empty-ico { width: 64px; height: 64px; margin: 0 auto 14px; border-radius: 20px; display: grid; place-items: center; background: var(--mod-soft); color: var(--mod); }
    .ku-empty-ico svg { width: 30px; height: 30px; }
    .ku-empty strong { display: block; font-size: 14px; font-weight: 600; color: var(--text-main); }
    .ku-empty p { font-size: 12.5px; color: var(--text-muted); margin-top: 4px; }

    .ku-pager { padding: 14px 18px; border-top: 1px dashed var(--border-color); }
    .ku-pager:empty { display: none; }

    .ku-toast { position: fixed; bottom: 24px; inset-inline-start: 50%; transform: translateX(-50%) translateY(20px); z-index: 9999; display: flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 12px; font-size: 13px; font-weight: 500; color: #fff; background: #111827; box-shadow: 0 12px 30px rgba(0,0,0,.3); opacity: 0; transition: opacity .25s, transform .25s; pointer-events: none; }
    .ku-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    body[dir="rtl"] .ku-toast { transform: translateX(50%) translateY(20px); }
    body[dir="rtl"] .ku-toast.show { transform: translateX(50%) translateY(0); }
    .ku-toast svg { width: 16px; height: 16px; color: #34d399; }

    @media (max-width: 820px) { .ku-scene, .ku-float { display: none; } }
    @media (prefers-reduced-motion: reduce) { .ku *, .ku *::before, .ku *::after { animation: none !important; } }
</style>

<div class="ku">
    <!-- HERO -->
    <div class="ku-hero">
        <span class="ku-grid" aria-hidden="true"></span>
        <span class="ku-scan" aria-hidden="true"></span>
        <span class="ku-float f1" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
        <span class="ku-float f2" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="4.5"/><path d="m10.7 12.3 9.8-9.8M16 7l3 3M14 9l2 2"/></svg></span>
        <span class="ku-float f3" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 11 2 2 4-4"/></svg></span>

        <div class="ku-scene" aria-hidden="true">
            <svg id="kuScene" viewBox="0 0 260 160" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <circle class="ring r1" cx="130" cy="80" r="52"/>
                <circle class="ring r2" cx="130" cy="80" r="52"/>
                <circle class="ring r3" cx="130" cy="80" r="52"/>
                <circle class="orbit" cx="130" cy="80" r="68"/>
                <path id="kuOrbit" d="M62 80a68 68 0 1 1 136 0a68 68 0 1 1 -136 0" fill="none"/>
                <g class="shield" transform="translate(78,28) scale(4.3)">
                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" fill="rgba(255,255,255,.16)" stroke="#fff" stroke-width="1.2" stroke-linejoin="round"/>
                    <path d="m9 12 2 2 4-4" fill="none" stroke="#a7f3d0" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <circle r="5" fill="#fde68a"><animateMotion dur="9s" repeatCount="indefinite"><mpath href="#kuOrbit"/></animateMotion></circle>
                <circle r="3.5" fill="#a7f3d0"><animateMotion dur="9s" begin="-4.5s" repeatCount="indefinite"><mpath href="#kuOrbit"/></animateMotion></circle>
            </svg>
        </div>

        <div class="ku-hero-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <div class="ku-hero-text">
            <div class="ku-crumb"><span>Administrasi</span><i>/</i><span>Kelola User</span></div>
            <div class="ku-title">Kelola User</div>
            <div class="ku-sub">Atur peran dan hak akses setiap pengguna sistem</div>
        </div>
    </div>

    <!-- KARTU STATISTIK -->
    <div class="ku-stats">
        <div class="ku-stat t1">
            <span class="ku-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
            <div class="ku-stat-body">
                <div class="ku-stat-label">Total User</div>
                <div class="ku-stat-val"><b class="ku-num" style="--to: {{ (int) $total }}"><span class="ku-sr">{{ (int) $total }}</span></b></div>
                <div class="ku-stat-hint">Pengguna terdaftar</div>
            </div>
        </div>
        <div class="ku-stat t2">
            <span class="ku-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3z"/><path d="M12 9v4M12 17h.01"/></svg></span>
            <div class="ku-stat-body">
                <div class="ku-stat-label">Tanpa Role</div>
                <div class="ku-stat-val"><b class="ku-num" style="--to: {{ (int) $noRole }}"><span class="ku-sr">{{ (int) $noRole }}</span></b></div>
                <div class="ku-stat-hint">Perlu diberi hak akses</div>
            </div>
        </div>
        <div class="ku-stat t3">
            <span class="ku-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg></span>
            <div class="ku-stat-body">
                <div class="ku-stat-label">Super Admin</div>
                <div class="ku-stat-val"><b class="ku-num" style="--to: {{ (int) $superAdmin }}"><span class="ku-sr">{{ (int) $superAdmin }}</span></b></div>
                <div class="ku-stat-hint">Akses penuh sistem</div>
            </div>
        </div>
        <div class="ku-stat t4">
            <span class="ku-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="4.5"/><path d="m10.7 12.3 9.8-9.8M16 7l3 3M14 9l2 2"/></svg></span>
            <div class="ku-stat-body">
                <div class="ku-stat-label">Jenis Role</div>
                <div class="ku-stat-val"><b class="ku-num" style="--to: {{ (int) $roleKinds }}"><span class="ku-sr">{{ (int) $roleKinds }}</span></b></div>
                <div class="ku-stat-hint">Peran berbeda dipakai</div>
            </div>
        </div>
    </div>

    <!-- TABEL (id container dipakai oleh AJAX pagination di layout) -->
    <div class="ku-card table-card" id="ajax-table-container">
        <div class="ku-bar">
            <h3>Daftar User</h3>
            <label class="ku-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="kuSearch" placeholder="Cari nama atau email..." autocomplete="off">
                <kbd data-no-i18n>/</kbd>
            </label>
        </div>

        @if($col->count() > 0)
        <div class="ku-chips" id="kuChips">
            <button type="button" class="ku-chip on" data-f="all"><span>Semua</span><em data-no-i18n>{{ $col->count() }}</em></button>
            @if($noRole > 0 && $col->filter(fn($x) => $x->roles->isEmpty())->count() > 0)
                <button type="button" class="ku-chip" data-f="none"><span>Tanpa Role</span><em data-no-i18n>{{ $col->filter(fn($x) => $x->roles->isEmpty())->count() }}</em></button>
            @endif
            @foreach($roleCounts as $rn => $cnt)
                <button type="button" class="ku-chip" data-f="{{ $rn }}"><span>{{ \Illuminate\Support\Str::headline($rn) }}</span><em data-no-i18n>{{ $cnt }}</em></button>
            @endforeach
        </div>
        @endif

        <div class="ku-scroll">
            <table>
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>Role Aktif</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($users as $i => $usr)
                    @php
                        $initials = mb_strtoupper(collect(preg_split('/\s+/', trim((string) $usr->name)))->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));
                        $tone     = $avatarTones[abs(crc32((string) $usr->email)) % count($avatarTones)];
                        $roleList = $usr->roles->pluck('name');
                    @endphp
                    <tr data-user class="{{ $roleList->isEmpty() ? 'norole' : '' }}"
                        data-search="{{ strtolower($usr->name.' '.$usr->email) }}"
                        data-roles="{{ $roleList->isEmpty() ? '' : '|'.$roleList->join('|').'|' }}"
                        style="animation-delay: {{ min($i, 12) * 0.04 + 0.35 }}s">
                        <td>
                            <div class="ku-user">
                                <span class="ku-avatar {{ $tone }}" data-no-i18n>{{ $initials ?: '?' }}</span>
                                <div class="ku-uname">
                                    <span data-no-i18n>{{ $usr->name }}</span>
                                    @if($usr->id === $meId)<span class="ku-me">Anda</span>@endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="ku-mail">
                                <span data-no-i18n>{{ $usr->email }}</span>
                                <button type="button" class="ku-copy" data-copy="{{ $usr->email }}" title="Salin email" aria-label="Salin email">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                </button>
                            </span>
                        </td>
                        <td>
                            <div class="ku-roles">
                                @forelse($roleList as $rn)
                                    <span class="ku-role {{ $roleTone($rn) }}">{{ \Illuminate\Support\Str::headline($rn) }}</span>
                                @empty
                                    <span class="ku-role none">Tanpa role</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('super-admin.users.edit',$usr) }}" class="ku-act">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="4.5"/><path d="m10.7 12.3 9.8-9.8M16 7l3 3M14 9l2 2"/></svg>
                                <span>Atur Role</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:0">
                            <div class="ku-empty">
                                <div class="ku-empty-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                                <strong>Belum ada user.</strong>
                                <p>Pengguna yang terdaftar akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                    <tr id="kuNoMatch" hidden>
                        <td colspan="4" style="padding:0">
                            <div class="ku-empty">
                                <div class="ku-empty-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></div>
                                <strong>Tidak ada user yang cocok.</strong>
                                <p>Coba kata kunci atau filter role yang lain.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="ku-pager">{{ method_exists($users, 'links') ? $users->links() : '' }}</div>
    </div>
</div>

<script>
/* Hentikan animasi SMIL bila perangkat meminta gerakan dikurangi */
(function () {
    try {
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            var s = document.getElementById('kuScene');
            if (s && s.pauseAnimations) s.pauseAnimations();
        }
    } catch (e) {}
})();

/* Pencarian, filter role, salin email, pintasan "/" (event delegation, hanya dipasang sekali) */
(function () {
    if (window.__kuBound) return;
    window.__kuBound = true;

    function root() { return document.querySelector('.ku'); }

    function apply() {
        var r = root(); if (!r) return;
        var input = r.querySelector('#kuSearch');
        var q = input ? input.value.trim().toLowerCase() : '';
        var chip = r.querySelector('.ku-chip.on');
        var f = chip ? chip.getAttribute('data-f') : 'all';
        var rows = r.querySelectorAll('tbody tr[data-user]');
        var shown = 0;
        rows.forEach(function (tr) {
            var okQ = !q || tr.getAttribute('data-search').indexOf(q) > -1;
            var roles = tr.getAttribute('data-roles') || '';
            var okF = f === 'all' || (f === 'none' ? roles === '' : roles.indexOf('|' + f + '|') > -1);
            var show = okQ && okF;
            tr.hidden = !show;
            if (show) shown++;
        });
        var none = r.querySelector('#kuNoMatch');
        if (none) none.hidden = !(rows.length > 0 && shown === 0);
    }

    function toast(msg) {
        var t = document.createElement('div');
        t.className = 'ku-toast';
        t.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg><span></span>';
        t.lastChild.textContent = msg;
        document.body.appendChild(t);
        requestAnimationFrame(function () { t.classList.add('show'); });
        setTimeout(function () { t.classList.remove('show'); setTimeout(function () { t.remove(); }, 300); }, 1800);
    }

    function copyText(text, done) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(done, function () { fallback(); });
        } else { fallback(); }
        function fallback() {
            var ta = document.createElement('textarea');
            ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
            document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy'); done(); } catch (e) {}
            ta.remove();
        }
    }

    document.addEventListener('input', function (e) { if (e.target && e.target.id === 'kuSearch') apply(); });

    document.addEventListener('click', function (e) {
        var chip = e.target.closest && e.target.closest('.ku-chip');
        if (chip && chip.closest('.ku')) {
            chip.parentNode.querySelectorAll('.ku-chip').forEach(function (c) { c.classList.remove('on'); });
            chip.classList.add('on');
            apply();
            return;
        }
        var cp = e.target.closest && e.target.closest('.ku-copy');
        if (cp) {
            copyText(cp.getAttribute('data-copy'), function () {
                toast(window.I18N ? window.I18N.t('Email disalin') : 'Email disalin');
            });
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== '/' || e.ctrlKey || e.metaKey || e.altKey) return;
        var tag = (e.target && e.target.tagName) || '';
        if (/INPUT|TEXTAREA|SELECT/.test(tag) || (e.target && e.target.isContentEditable)) return;
        var i = document.getElementById('kuSearch');
        if (i) { e.preventDefault(); i.focus(); }
    });
})();

/* Kamus khusus halaman ini (aman dijalankan sebelum maupun sesudah I18N siap) */
(function (d) {
    window.I18N_EXTRA = Object.assign(window.I18N_EXTRA || {}, d);
    if (window.I18N && window.I18N.extend) window.I18N.extend(d);
})({
    'Atur peran dan hak akses setiap pengguna sistem': ['Manage the roles and access rights of every system user', 'إدارة أدوار وصلاحيات كل مستخدم في النظام', '管理每位系统用户的角色和访问权限'],
    'Total User':           ['Total Users', 'إجمالي المستخدمين', '用户总数'],
    'Pengguna terdaftar':   ['Registered users', 'المستخدمون المسجلون', '已注册用户'],
    'Tanpa Role':           ['No Role', 'بدون دور', '无角色'],
    'Perlu diberi hak akses': ['Need access rights', 'بحاجة إلى صلاحيات', '需分配权限'],
    'Akses penuh sistem':   ['Full system access', 'صلاحية كاملة للنظام', '系统完全访问权限'],
    'Jenis Role':           ['Role Types', 'أنواع الأدوار', '角色类型'],
    'Peran berbeda dipakai': ['Different roles in use', 'أدوار مختلفة قيد الاستخدام', '正在使用的不同角色'],
    'Daftar User':          ['User List', 'قائمة المستخدمين', '用户列表'],
    'Cari nama atau email...': ['Search name or email...', 'ابحث بالاسم أو البريد الإلكتروني...', '搜索姓名或邮箱...'],
    'Semua':                ['All', 'الكل', '全部'],
    'Role Aktif':           ['Active Roles', 'الأدوار النشطة', '当前角色'],
    'Atur Role':            ['Set Role', 'تعيين الدور', '设置角色'],
    'Anda':                 ['You', 'أنت', '您'],
    'Salin email':          ['Copy email', 'نسخ البريد الإلكتروني', '复制邮箱'],
    'Email disalin':        ['Email copied', 'تم نسخ البريد الإلكتروني', '邮箱已复制'],
    'Belum ada user.':      ['No users yet.', 'لا يوجد مستخدمون بعد.', '暂无用户。'],
    'Pengguna yang terdaftar akan muncul di sini.': ['Registered users will appear here.', 'سيظهر المستخدمون المسجلون هنا.', '已注册的用户将显示在这里。'],
    'Tidak ada user yang cocok.': ['No matching users.', 'لا يوجد مستخدمون مطابقون.', '没有匹配的用户。'],
    'Coba kata kunci atau filter role yang lain.': ['Try a different keyword or role filter.', 'جرّب كلمة بحث أو تصفية دور أخرى.', '请尝试其他关键词或角色筛选。'],

    /* nama role */
    'Super Admin':            ['Super Admin', 'المشرف الأعلى', '超级管理员'],
    'Admin Persuratan':       ['Correspondence Admin', 'مسؤول المراسلات', '公文管理员'],
    'Admin Inventaris':       ['Inventory Admin', 'مسؤول المخزون', '库存管理员'],
    'Admin Kendaraan':        ['Vehicle Admin', 'مسؤول المركبات', '车辆管理员'],
    'Admin Fasilitas':        ['Facilities Admin', 'مسؤول المرافق', '设施管理员'],
    'Admin Protokol':         ['Protocol Admin', 'مسؤول المراسم', '礼宾管理员'],
    'Admin Perjalanan Dinas': ['Official Travel Admin', 'مسؤول السفر الرسمي', '公务出差管理员'],
    'Kepala Bagian':          ['Head of Division', 'رئيس القسم', '部门负责人'],
    'Kasubbag':               ['Sub-Division Head', 'رئيس القسم الفرعي', '科长'],
    'Pegawai':                ['Employee', 'موظف', '员工'],
    'Pengemudi':              ['Driver', 'سائق', '司机'],
    'Auditor':                ['Auditor', 'مدقق', '审计员'],
    'Petugas Arsip':          ['Archive Officer', 'موظف الأرشيف', '档案专员'],
    'Pengurus BMD':           ['Regional Asset Manager', 'مدير ممتلكات الإقليم', '地方资产管理员']
});
</script>
@endsection
