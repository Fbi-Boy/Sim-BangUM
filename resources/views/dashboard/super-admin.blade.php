@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<style>
    /* === LAYOUT === */
    .dash-grid {
        display: grid; grid-template-columns: 1fr 360px; gap: 0;
        margin: -24px -28px; height: calc(100vh - 118px); overflow: hidden;
    }
    .left-side, .right-side { overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none; }
    .left-side::-webkit-scrollbar, .right-side::-webkit-scrollbar { display: none; }
    .left-side { padding: 24px 28px 30px; scroll-behavior: smooth; }
    .alert ~ .dash-grid { margin-top: 0; height: auto; overflow: visible; }
    .alert ~ .dash-grid .left-side, .alert ~ .dash-grid .right-side { overflow: visible; }

    .section-header { display: flex; justify-content: space-between; align-items: center; margin: 26px 0 12px; }
    .section-title { font-size: 15px; font-weight: 600; color: var(--text-main); }
    .btn-view { padding: 6px 14px; border-radius: 8px; border: none; background: var(--accent-soft); color: var(--accent); font-size: 11.5px; font-weight: 600; text-decoration: none; cursor: pointer; font-family: inherit; }
    .btn-view:hover { background: var(--accent); color: #fff; }

    /* === BANNER === */
    .banner {
        position: relative; overflow: hidden; border-radius: 24px; padding: 30px 34px 26px; color: #fff;
        background:
            radial-gradient(circle at 85% 15%, rgba(167,139,250,.55) 0, transparent 45%),
            radial-gradient(circle at 10% 110%, rgba(56,189,248,.45) 0, transparent 50%),
            linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
        box-shadow: 0 18px 36px rgba(37, 99, 235, .32);
    }
    .banner::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.16) 1.2px, transparent 1.2px); background-size: 20px 20px; opacity: .55; -webkit-mask-image: linear-gradient(90deg, transparent 35%, #000 100%); mask-image: linear-gradient(90deg, transparent 35%, #000 100%); }
    .banner-body { position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; gap: 20px; }
    .banner-text { max-width: 58%; }
    .banner-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; background: rgba(255,255,255,.18); backdrop-filter: blur(6px); font-size: 11px; font-weight: 500; margin-bottom: 12px; }
    .banner-tag i { width: 6px; height: 6px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 3px rgba(52,211,153,.35); }
    .banner-text h1 { font-size: 28px; font-weight: 700; line-height: 1.2; margin-bottom: 8px; letter-spacing: -.3px; }
    .banner-text p { font-size: 12.5px; opacity: .92; line-height: 1.65; margin-bottom: 18px; }
    .banner-btn { display: inline-flex; align-items: center; gap: 8px; background: #fff; color: #2563eb; padding: 9px 18px; border: none; border-radius: 10px; font-size: 12px; font-weight: 600; text-decoration: none; cursor: pointer; font-family: inherit; box-shadow: 0 8px 18px rgba(0,0,0,.15); transition: transform .2s; }
    .banner-btn:hover { transform: translateY(-2px); }
    .banner-art { flex: 0 0 250px; height: 170px; position: relative; }
    .banner-art svg { width: 100%; height: 100%; overflow: visible; }
    .float { animation: floaty 5s ease-in-out infinite; }
    .float.d2 { animation-delay: -1.8s; }
    .float.d3 { animation-delay: -3.2s; }
    @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }

    .banner-stats { position: relative; z-index: 2; display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 22px; }
    .stat-chip { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 14px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22); backdrop-filter: blur(8px); color: #fff; text-align: left; cursor: pointer; font-family: inherit; transition: background .2s, transform .2s; }
    .stat-chip:hover { background: rgba(255,255,255,.26); transform: translateY(-2px); }
    .stat-chip.on { background: rgba(255,255,255,.3); border-color: rgba(255,255,255,.7); }
    .stat-ic { width: 34px; height: 34px; border-radius: 10px; background: rgba(255,255,255,.22); display: grid; place-items: center; flex-shrink: 0; }
    .stat-ic svg { width: 17px; height: 17px; }
    .stat-chip b { display: block; font-size: 19px; line-height: 1.1; font-weight: 700; }
    .stat-chip span { font-size: 10.5px; opacity: .88; }

    /* === SEARCH === */
    .search-wrap { position: relative; margin: 18px 0 0; }
    .search-input { width: 100%; max-width: none; padding: 14px 90px 14px 48px; border: 1px solid var(--border-color); border-radius: 14px; background: var(--bg-card); color: var(--text-main); font-size: 13px; outline: none; box-shadow: 0 6px 18px rgba(37,99,235,.06); transition: border-color .2s, box-shadow .2s; }
    .search-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,.14); }
    .search-wrap .s-ic { position: absolute; left: 17px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--accent); }
    .search-wrap kbd { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-family: inherit; font-size: 10.5px; color: var(--text-muted); background: var(--bg-soft); border: 1px solid var(--border-color); padding: 3px 8px; border-radius: 6px; }
    .empty-note { display: none; font-size: 12.5px; color: var(--text-muted); padding: 14px 4px; }

    /* === AKSES CEPAT === */
    .scroll-controls { display: flex; gap: 6px; }
    .btn-scroll { width: 28px; height: 28px; border-radius: 50%; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-muted); cursor: pointer; font-weight: bold; font-family: inherit; }
    .btn-scroll:hover { background: var(--accent); color: #fff; border-color: var(--accent); }
    .quick-scroll-container { display: flex; gap: 14px; overflow-x: auto; scroll-behavior: smooth; padding: 6px 2px 14px; scrollbar-width: none; }
    .quick-scroll-container::-webkit-scrollbar { display: none; }
    .quick-card { position: relative; flex: 0 0 148px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 16px; text-decoration: none; display: flex; flex-direction: column; overflow: hidden; transition: transform .22s, box-shadow .22s, border-color .22s; }
    .quick-card::after { content: ''; position: absolute; right: -22px; top: -22px; width: 80px; height: 80px; border-radius: 50%; background: var(--tint); opacity: .12; transition: transform .3s; }
    .quick-card:hover { transform: translateY(-5px); box-shadow: 0 14px 28px rgba(37,99,235,.14); border-color: var(--tint); }
    .quick-card:hover::after { transform: scale(1.6); }
    .quick-icon { width: 42px; height: 42px; border-radius: 13px; display: grid; place-items: center; margin-bottom: 14px; color: #fff; background: var(--tint); box-shadow: 0 8px 16px color-mix(in srgb, var(--tint) 40%, transparent); }
    .quick-icon svg { width: 20px; height: 20px; }
    .qc-1 { --tint: #2563eb; } .qc-2 { --tint: #f97316; } .qc-3 { --tint: #d946ef; } .qc-4 { --tint: #10b981; } .qc-5 { --tint: #ef4444; }
    .quick-card strong { font-size: 13px; color: var(--text-main); margin-bottom: 2px; }
    .quick-card small { font-size: 11px; color: var(--text-muted); }

    /* === TABEL === */
    .filter-row { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 6px; }
    .f-chip { padding: 6px 14px; border-radius: 20px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-muted); font-size: 11.5px; font-weight: 500; cursor: pointer; font-family: inherit; transition: .15s; }
    .f-chip:hover { border-color: var(--accent); color: var(--accent); }
    .f-chip.on { background: var(--accent); border-color: var(--accent); color: #fff; }
    .table-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 10px 16px 16px; }
    .tbl { width: 100%; border-collapse: separate; border-spacing: 0 3px; background: transparent; }
    .tbl th { background: none; border: none; text-transform: none; font-size: 11.5px; font-weight: 500; color: var(--text-muted); padding: 10px 14px; }
    .tbl td { border: none; font-size: 12.5px; padding: 13px 14px; background: transparent; }
    .tbl tbody tr { cursor: pointer; }
    .tbl tbody td { transition: background-color .15s; }
    .tbl tbody tr:hover td { background: rgba(37,99,235,.07); }
    .tbl tbody tr td:first-child { border-radius: 10px 0 0 10px; font-weight: 500; }
    .tbl tbody tr td:last-child { border-radius: 0 10px 10px 0; text-align: right; }
    .tbl tbody tr.selected td { background: rgba(37,99,235,.14); }
    .dots { border: none; background: none; color: inherit; font-size: 18px; line-height: 1; cursor: pointer; padding: 2px 8px; border-radius: 6px; font-family: inherit; opacity: .7; }
    .dots:hover { opacity: 1; background: rgba(127,127,127,.18); }
    .st { display: inline-flex; align-items: center; gap: 8px; }
    .st i { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
    .tbl-empty { text-align: center; color: var(--text-muted); padding: 26px 0 !important; }
    .pagination { display: flex; justify-content: flex-end; gap: 6px; margin-top: 12px; }
    .page-btn { padding: 5px 11px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main); cursor: pointer; font-size: 12px; font-family: inherit; }
    .page-btn.active { background: #2563eb; color: #fff; border-color: #2563eb; }

    /* === PANEL KANAN === */
    .right-side { background: var(--bg-card); border-left: 1px solid var(--border-color); padding: 22px 26px 10px; }
    .r-sec { padding: 20px 0; border-top: 1px solid var(--border-color); }
    .r-sec:first-child { border-top: none; padding-top: 0; }
    .r-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
    .r-head .section-title { font-size: 14px; }

    .profile-mini { position: relative; display: flex; align-items: center; gap: 12px; }
    .avatar-ph { width: 46px; height: 46px; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #7c3aed); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 14px; box-shadow: 0 0 0 4px var(--accent-soft); flex-shrink: 0; }
    .profile-mini .info { min-width: 0; flex: 1; }
    .profile-mini h4 { font-size: 14px; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .profile-mini p { font-size: 11px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .notif { position: relative; width: 36px; height: 36px; border-radius: 50%; border: none; background: var(--bg-soft); display: grid; place-items: center; color: var(--accent); flex-shrink: 0; cursor: pointer; }
    .notif:hover { background: var(--accent-soft); }
    .notif svg { width: 17px; height: 17px; }
    .notif .badge { position: absolute; top: 7px; right: 8px; width: 8px; height: 8px; border-radius: 50%; background: #ef4444; border: 2px solid var(--bg-card); }

    .notif-pop { position: fixed; z-index: 160; width: 360px; max-width: calc(100vw - 24px); max-height: min(70vh, 480px); display: none; flex-direction: column; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; box-shadow: var(--shadow-pop); overflow: hidden; }
    .notif-pop.open { display: flex; animation: pop .18s ease; }
    .np-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px; border-bottom: 1px solid var(--border-color); }
    .np-title { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: var(--text-main); }
    .np-count { font-size: 10.5px; font-weight: 700; background: #ef4444; color: #fff; padding: 1px 8px; border-radius: 20px; }
    .np-link { border: none; background: none; color: var(--accent); font-size: 11.5px; font-weight: 600; cursor: pointer; font-family: inherit; padding: 5px 8px; border-radius: 8px; }
    .np-link:hover { background: var(--accent-soft); }
    .np-list { overflow-y: auto; padding: 6px; }
    .np-item { display: grid; grid-template-columns: 36px minmax(0, 1fr) 10px; gap: 12px; align-items: start; width: 100%; text-align: left; padding: 12px 10px; border: none; background: none; border-radius: 12px; cursor: pointer; font-family: inherit; }
    .np-item:hover { background: var(--bg-hover); }
    .np-item.unread { background: var(--accent-soft); }
    .np-ic { width: 36px; height: 36px; border-radius: 11px; display: grid; place-items: center; color: #fff; }
    .np-ic svg { width: 17px; height: 17px; }
    .np-tx { min-width: 0; }
    .np-tx b { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-main); line-height: 1.35; overflow-wrap: anywhere; }
    .np-tx span { display: block; margin-top: 2px; font-size: 11.5px; color: var(--text-muted); line-height: 1.45; overflow-wrap: anywhere; }
    .np-tx small { display: block; margin-top: 5px; font-size: 10.5px; color: var(--text-muted); opacity: .85; }
    .np-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); margin-top: 6px; visibility: hidden; }
    .np-item.unread .np-dot { visibility: visible; }
    .np-empty { padding: 34px 16px; text-align: center; color: var(--text-muted); font-size: 12.5px; }
    .np-foot { border-top: 1px solid var(--border-color); padding: 6px; text-align: center; }

    .cal-month { font-size: 11.5px; color: var(--accent); font-weight: 600; background: var(--accent-soft); padding: 4px 12px; border-radius: 20px; }
    .calendar-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; }
    .cal-item { text-align: center; padding: 8px 0; border-radius: 14px; border: none; background: none; cursor: pointer; font-family: inherit; transition: background .2s; }
    .cal-item:hover { background: var(--accent-soft); }
    .cal-day { font-size: 10.5px; color: var(--text-muted); }
    .cal-date { font-size: 15px; font-weight: 700; color: var(--text-main); margin-top: 2px; }
    .cal-item.today .cal-date::after { content: ''; display: block; width: 4px; height: 4px; border-radius: 50%; background: #2563eb; margin: 3px auto 0; }
    .cal-item.active { background: linear-gradient(160deg, #3b82f6, #2563eb); box-shadow: 0 10px 18px rgba(37,99,235,.35); }
    .cal-item.active .cal-day, .cal-item.active .cal-date { color: #fff; }
    .cal-item.active.today .cal-date::after { background: #fff; }

    /* placeholder empty untuk akhir pekan */
    .tl-empty { font-size: 12px; color: var(--text-muted); padding: 6px 0; }

    /* HUBUNGI */
    .row-item { display: flex; align-items: center; gap: 12px; padding: 9px 0; }
    .row-item .av { width: 36px; height: 36px; border-radius: 50%; display: grid; place-items: center; color: #fff; font-size: 11.5px; font-weight: 700; flex-shrink: 0; }
    .row-item .tx { flex: 1; min-width: 0; }
    .row-item h5 { font-size: 12.5px; color: var(--text-main); font-weight: 600; }
    .row-item p { font-size: 10.5px; color: var(--text-muted); }
    .wa-btn { width: 30px; height: 30px; border-radius: 50%; background: var(--accent-soft); color: var(--accent); display: grid; place-items: center; text-decoration: none; transition: .2s; flex-shrink: 0; }
    .wa-btn:hover { background: #10b981; color: #fff; }
    .wa-btn svg { width: 14px; height: 14px; }

    /* MAGANG */
    .train-wrap { display: flex; gap: 14px; overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; padding-bottom: 6px; }
    .train-wrap::-webkit-scrollbar { display: none; }
    .magang-card { flex: 0 0 118px; text-align: center; padding: 4px 0; }
    .magang-card .av { width: 54px; height: 54px; border-radius: 50%; color: #fff; display: grid; place-items: center; margin: 0 auto 8px; font-weight: 700; font-size: 14px; box-shadow: 0 0 0 4px var(--accent-soft); }
    .magang-card h5 { font-size: 12px; color: var(--text-main); font-weight: 600; line-height: 1.25; }
    .magang-card p { font-size: 10.5px; color: var(--text-muted); margin-bottom: 10px; }
    .btn-detail { width: 100%; padding: 6px 0; border: none; border-radius: 8px; background: var(--accent); color: #fff; font-size: 11px; font-weight: 600; cursor: pointer; font-family: inherit; transition: background .2s, transform .15s; }
    .btn-detail:hover { background: #1d4ed8; transform: translateY(-1px); }

    /* MODAL, MENU, TOAST */
    .modal-ov { position: fixed; inset: 0; z-index: 200; background: rgba(15,23,42,.55); backdrop-filter: blur(3px); display: none; align-items: center; justify-content: center; padding: 20px; }
    .modal-ov.open { display: flex; }
    .modal-box { width: 100%; max-width: 420px; background: var(--bg-card); border-radius: 20px; padding: 24px; box-shadow: var(--shadow-pop); animation: pop .2s ease; }
    @keyframes pop { from { transform: translateY(10px) scale(.97); opacity: 0; } to { transform: none; opacity: 1; } }
    .m-top { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
    .m-top .av { width: 56px; height: 56px; border-radius: 50%; color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 16px; flex-shrink: 0; }
    .m-top h3 { font-size: 16px; color: var(--text-main); }
    .m-top p { font-size: 12px; color: var(--text-muted); }
    .m-row { display: flex; justify-content: space-between; gap: 16px; padding: 10px 0; border-top: 1px solid var(--border-color); font-size: 12.5px; }
    .m-row span { color: var(--text-muted); }
    .m-row b { color: var(--text-main); font-weight: 600; text-align: right; }
    .m-note { font-size: 12.5px; color: var(--text-muted); line-height: 1.6; margin-bottom: 8px; }
    .m-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 16px; }
    .m-btn { padding: 9px 18px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main); font-size: 12.5px; font-weight: 500; cursor: pointer; font-family: inherit; text-decoration: none; }
    .m-btn.primary { background: #2563eb; border-color: #2563eb; color: #fff; }

    .row-menu { position: fixed; z-index: 150; min-width: 170px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: var(--shadow-pop); padding: 6px; display: none; }
    .row-menu.open { display: block; }
    .row-menu button { display: block; width: 100%; text-align: left; padding: 9px 12px; border: none; background: none; border-radius: 8px; font-size: 12.5px; color: var(--text-main); cursor: pointer; font-family: inherit; }
    .row-menu button:hover { background: var(--bg-hover); }

    .toast { position: fixed; left: 50%; bottom: 28px; transform: translate(-50%, 20px); background: #111827; color: #fff; padding: 10px 18px; border-radius: 10px; font-size: 12.5px; opacity: 0; pointer-events: none; transition: .25s; z-index: 300; }
    .toast.show { opacity: 1; transform: translate(-50%, 0); }

    button:focus-visible, a:focus-visible, .search-input:focus-visible { outline: 2px solid #3b82f6; outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .float, .modal-box, .notif-pop.open { animation: none !important; } .quick-card, .stat-chip, .btn-detail { transition: none !important; } }

    @media (max-width: 1024px) {
        .dash-grid { grid-template-columns: 1fr; margin: 0; height: auto; overflow: visible; }
        .left-side { padding: 0 0 10px; }
        .right-side { border: 1px solid var(--border-color); border-radius: 20px; margin-top: 20px; }
        .banner-art { display: none; }
        .banner-text { max-width: 100%; }
    }
    @media (max-width: 600px) { .banner-stats { grid-template-columns: 1fr; } }
</style>

@php
    $u = auth()->user();
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $initials = mb_strtoupper(collect(preg_split('/\s+/', trim($u->name)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));
    $waIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>';
    $canApprove = $u->hasAnyRole(['super_admin','kepala_bagian','kasubbag']) && \Illuminate\Support\Facades\Route::has('pejabat.approvals.index');
    $todayIdx = now()->dayOfWeekIso - 1; // 0 = Senin ... 6 = Minggu

    /* ============ JADWAL MINGGUAN (data untuk komponen x-timeline) ============ */
    $schedule = [
        0 => [ // Senin
            ['time' => '08:00', 'title' => 'Apel Pagi & Briefing',      'place' => 'Halaman Kantor',    'unit' => 'Seluruh Bagian'],
            ['time' => '10:00', 'title' => 'Verifikasi Surat Masuk',     'place' => 'Ruang Persuratan',  'unit' => 'Persuratan'],
            ['time' => '14:00', 'title' => 'Cek Stok ATK',               'place' => 'Gudang',            'unit' => 'Inventaris'],
        ],
        1 => [ // Selasa
            ['time' => '09:00', 'title' => 'Rapat Anggaran',             'place' => 'Ruang Rapat Utama', 'unit' => 'Keuangan'],
            ['time' => '11:00', 'title' => 'Pemeliharaan Kendaraan Dinas','place' => 'Garasi',            'unit' => 'Bagian Umum'],
            ['time' => '15:00', 'title' => 'Bimbingan Peserta Magang',   'place' => 'Ruang Kepegawaian', 'unit' => 'Kepegawaian'],
        ],
        2 => [ // Rabu
            ['time' => '08:00', 'title' => 'Rapat Koordinasi',           'place' => 'Ruang Rapat Utama', 'unit' => 'Bagian Umum'],
            ['time' => '10:30', 'title' => 'Evaluasi Kinerja',           'place' => 'Aula Serbaguna',    'unit' => 'Kepegawaian'],
            ['time' => '13:00', 'title' => 'Penyerahan SK',              'place' => 'Lobi Utama',        'unit' => 'Protokol'],
        ],
        3 => [ // Kamis
            ['time' => '09:30', 'title' => 'Audiensi Tamu Instansi',     'place' => 'Lobi Utama',        'unit' => 'Protokol'],
            ['time' => '13:30', 'title' => 'Serah Terima Aset',          'place' => 'Gudang',            'unit' => 'Inventaris'],
        ],
        4 => [ // Jumat
            ['time' => '08:30', 'title' => 'Senam Pagi',                 'place' => 'Halaman Kantor'],
            ['time' => '10:00', 'title' => 'Rekap Laporan Mingguan',     'place' => 'Ruang Umum',        'unit' => 'Bagian Umum'],
            ['time' => '14:00', 'title' => 'Evaluasi Pekan Ini',         'place' => 'Ruang Rapat Utama'],
        ],
    ];
    $startOfWeek = now()->startOfWeek();
    $dates = [];
    for ($i = 0; $i < 5; $i++) {
        $dates[$i] = $startOfWeek->copy()->addDays($i);
    }
@endphp

<div class="dash-grid">
    <!-- ================= KIRI ================= -->
    <div class="left-side" id="leftSide">

        <!-- BANNER -->
        <div class="banner">
            <div class="banner-body">
                <div class="banner-text">
                    <div class="banner-tag"><i></i> SIM-BU · Bagian Umum</div>
                    <h1>{{ $greeting }}, {{ explode(' ', $u->name)[0] }}! 👋</h1>
                    <p>Selamat datang di SIM-BU. Ada beberapa pengajuan dan disposisi baru yang menunggu tindakan Anda hari ini.</p>
                    @if($canApprove)
                        <a href="{{ route('pejabat.approvals.index') }}" class="banner-btn">Tinjau Sekarang
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    @else
                        <button type="button" class="banner-btn" onclick="gotoTable('Menunggu')">Tinjau Sekarang
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </button>
                    @endif
                </div>

                <div class="banner-art" aria-hidden="true">
                    <svg viewBox="0 0 250 170" fill="none">
                        <circle cx="130" cy="88" r="70" fill="rgba(255,255,255,.10)"/>
                        <circle cx="130" cy="88" r="48" fill="rgba(255,255,255,.10)"/>
                        <g class="float d2"><rect x="58" y="34" width="92" height="112" rx="12" fill="rgba(255,255,255,.55)" transform="rotate(-9 104 90)"/></g>
                        <g class="float">
                            <rect x="88" y="26" width="96" height="120" rx="13" fill="#fff"/>
                            <rect x="102" y="42" width="40" height="8" rx="4" fill="#2563eb"/>
                            <rect x="102" y="60" width="68" height="6" rx="3" fill="#cbd5e1"/>
                            <rect x="102" y="74" width="58" height="6" rx="3" fill="#cbd5e1"/>
                            <rect x="102" y="88" width="64" height="6" rx="3" fill="#cbd5e1"/>
                            <rect x="102" y="108" width="34" height="16" rx="8" fill="#10b981"/>
                            <path d="M110 116l4 4 8-8" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <g class="float d3">
                            <rect x="170" y="92" width="62" height="42" rx="9" fill="#fbbf24"/>
                            <path d="M174 98l27 20 27-20" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <g class="float d2">
                            <circle cx="64" cy="118" r="20" fill="#34d399"/>
                            <path d="M55 118l7 7 12-14" stroke="#fff" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <circle cx="196" cy="40" r="5" fill="#fff" opacity=".8"/>
                        <circle cx="38" cy="58" r="3.5" fill="#fff" opacity=".7"/>
                        <circle cx="222" cy="66" r="3" fill="#fff" opacity=".6"/>
                    </svg>
                </div>
            </div>

            <div class="banner-stats">
                <button type="button" class="stat-chip" data-filter="Menunggu">
                    <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
                    <div><b id="cnt-Menunggu">0</b><span>Menunggu</span></div>
                </button>
                <button type="button" class="stat-chip" data-filter="Disetujui">
                    <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
                    <div><b id="cnt-Disetujui">0</b><span>Disetujui</span></div>
                </button>
                <button type="button" class="stat-chip" data-filter="Ditolak">
                    <div class="stat-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9 9l6 6M15 9l-6 6"/></svg></div>
                    <div><b id="cnt-Ditolak">0</b><span>Ditolak</span></div>
                </button>
            </div>
        </div>

        <!-- SEARCH -->
        <div class="search-wrap">
            <svg class="s-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="searchQuick" class="search-input" placeholder="Cari fitur, misalnya surat, inventaris, ruangan... (Enter untuk membuka)" autocomplete="off">
            <kbd>Ctrl K</kbd>
        </div>

        <!-- AKSES CEPAT -->
        <div class="section-header">
            <div class="section-title">Akses Cepat</div>
            <div class="scroll-controls">
                <button type="button" class="btn-scroll" onclick="scrollBox('quickScroll', -200)">&lt;</button>
                <button type="button" class="btn-scroll" onclick="scrollBox('quickScroll', 200)">&gt;</button>
            </div>
        </div>

        <div class="quick-scroll-container" id="quickScroll">
            @php
                $quickMenus = [];
                if($u->hasAnyRole(['super_admin','admin_persuratan'])) {
                    $quickMenus[] = ['name'=>'Surat Masuk', 'desc'=>'Persuratan', 'url'=>route('admin.persuratan.incoming-letters.index'), 'class'=>'qc-1', 'icon'=>'<path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>'];
                    $quickMenus[] = ['name'=>'Surat Keluar', 'desc'=>'Persuratan', 'url'=>route('admin.persuratan.outgoing-letters.index'), 'class'=>'qc-1', 'icon'=>'<path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>'];
                }
                if($u->hasAnyRole(['super_admin','admin_inventaris'])) {
                    $quickMenus[] = ['name'=>'Inventaris', 'desc'=>'Aset & ATK', 'url'=>route('admin.inventaris.assets.index'), 'class'=>'qc-2', 'icon'=>'<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>'];
                }
                if($u->hasAnyRole(['super_admin','admin_fasilitas'])) {
                    $quickMenus[] = ['name'=>'Ruangan', 'desc'=>'Fasilitas', 'url'=>route('admin.fasilitas.rooms.index'), 'class'=>'qc-3', 'icon'=>'<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>'];
                }
                if($u->hasAnyRole(['super_admin','kepala_bagian','kasubbag'])) {
                    $quickMenus[] = ['name'=>'Persetujuan', 'desc'=>'Inbox Pejabat', 'url'=>route('pejabat.approvals.index'), 'class'=>'qc-4', 'icon'=>'<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'];
                }
                $quickMenus[] = ['name'=>'Laporan', 'desc'=>'Rekap Sistem', 'url'=>route('reports.index'), 'class'=>'qc-5', 'icon'=>'<path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>'];
                $quickMenus[] = ['name'=>'Bantuan', 'desc'=>'Panduan', 'url'=>'#', 'class'=>'qc-1', 'icon'=>'<path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'];
            @endphp

            @foreach($quickMenus as $qm)
                <a href="{{ $qm['url'] }}" class="quick-card {{ $qm['class'] }}" data-name="{{ strtolower($qm['name'].' '.$qm['desc']) }}" @if($qm['url'] === '#') data-help="1" @endif>
                    <div class="quick-icon">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">{!! $qm['icon'] !!}</svg>
                    </div>
                    <strong>{{ $qm['name'] }}</strong>
                    <small>{{ $qm['desc'] }}</small>
                </a>
            @endforeach
        </div>
        <div class="empty-note" id="emptyNote">Fitur tidak ditemukan.</div>

        <!-- TABEL -->
        <div class="section-header" id="pengajuan">
            <div class="section-title">Pengajuan Terbaru</div>
            <button type="button" class="btn-view" id="btnAll">Lihat Semua</button>
        </div>
        <div class="filter-row" id="filterRow"></div>
        <div class="table-card">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>No. Pengajuan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
            <div class="pagination" id="tablePagination"></div>
        </div>
    </div>

    <!-- ================= KANAN ================= -->
    <div class="right-side">

        <!-- PROFIL + NOTIFIKASI -->
        <div class="r-sec">
            <div class="profile-mini">
                <div class="avatar-ph">{{ $initials }}</div>
                <div class="info">
                    <h4>{{ $u->name }}</h4>
                    <p>{{ $u->roles->pluck('name')->join(', ') ?: 'Tanpa Role' }}</p>
                </div>
                <button type="button" class="notif" id="notifBtn" aria-label="Notifikasi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.9 1.9 0 0 0 3.4 0"/></svg>
                    <span class="badge" id="notifBadge"></span>
                </button>
            </div>
        </div>

        <!-- KALENDER -->
        <div class="r-sec">
            <div class="r-head">
                <div class="section-title">Kalender Minggu Ini</div>
                <div class="cal-month" id="calMonth"></div>
            </div>
            <div class="calendar-row" id="calRow">
                @foreach(['Sen','Sel','Rab','Kam','Jum'] as $i => $d)
                    @php $dt = now()->startOfWeek()->addDays($i); @endphp
                    <button type="button" class="cal-item {{ $i === $todayIdx ? 'today' : '' }}" data-idx="{{ $i }}" data-date="{{ $dt->format('Y-m-d') }}">
                        <div class="cal-day">{{ $d }}</div>
                        <div class="cal-date">{{ $dt->format('d') }}</div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- JADWAL (x-timeline server-rendered) -->
        <div class="r-sec">
            <div style="text-align:right;">
                <button type="button" class="btn-view" id="btnToday" style="display:none; margin-bottom:12px;" data-translate>Hari Ini</button>
            </div>

            @if($todayIdx <= 4)
                @for($i = 0; $i < 5; $i++)
                    @php
                        $isToday = ($i === $todayIdx);
                        $title = $isToday ? 'Jadwal Hari Ini' : 'Jadwal ' . $dates[$i]->translatedFormat('l, j M');
                    @endphp
                    <div class="timeline-day" data-day="{{ $i }}" style="display: none;">
                        <x-timeline
                            :items="$schedule[$i]"
                            :date="$dates[$i]"
                            :title="$title"
                            unit="kegiatan"
                            empty-text="Tidak ada jadwal." />
                    </div>
                @endfor
            @endif

            {{-- Empty state (weekend) --}}
            <div class="timeline-day" data-day="null" style="display: none;">
                <div class="tl-empty" data-translate>Tidak ada jadwal hari ini. Pilih tanggal di kalender untuk melihat jadwal hari lain.</div>
            </div>
        </div>

        <!-- HUBUNGI BAGIAN -->
        <div class="r-sec">
            <div class="r-head">
                <div class="section-title">Hubungi Bagian</div>
            </div>
            @foreach([
                ['IT Support','Bantuan sistem','62812345678','#2563eb','IT'],
                ['Bagian Umum','Persuratan & aset','62812345679','#10b981','BU'],
                ['Kepegawaian','Data pegawai','62812345680','#f59e0b','KP'],
                ['Keuangan','Anggaran','62812345681','#ec4899','KU'],
            ] as $c)
                <div class="row-item">
                    <div class="av" style="background:{{ $c[3] }}">{{ $c[4] }}</div>
                    <div class="tx"><h5>{{ $c[0] }}</h5><p>{{ $c[1] }}</p></div>
                    <a href="https://wa.me/{{ $c[2] }}?text={{ rawurlencode('Halo '.$c[0].', saya '.$u->name.' dari SIM-BU.') }}" target="_blank" rel="noopener" class="wa-btn" title="Hubungi {{ $c[0] }} via WhatsApp">{!! $waIcon !!}</a>
                </div>
            @endforeach
        </div>

        <!-- MAGANG -->
        <div class="r-sec">
            <div class="r-head">
                <div class="section-title">Magang</div>
                <div class="scroll-controls">
                    <button type="button" class="btn-scroll" onclick="scrollBox('magangScroll', -150)">&lt;</button>
                    <button type="button" class="btn-scroll" onclick="scrollBox('magangScroll', 150)">&gt;</button>
                </div>
            </div>
            <div class="train-wrap" id="magangScroll"></div>
        </div>

    </div>
</div>

<!-- MODAL, MENU BARIS, TOAST -->
<div class="modal-ov" id="modal"><div class="modal-box" id="modalBox"></div></div>
<div class="row-menu" id="rowMenu">
    <button type="button" id="rmDetail">Lihat Detail</button>
    <button type="button" id="rmCopy">Salin No. Pengajuan</button>
</div>
<div class="toast" id="toast"></div>

<div class="notif-pop" id="notifMenu" role="dialog" aria-label="Notifikasi">
    <div class="np-head">
        <div class="np-title">Notifikasi <span class="np-count" id="npCount"></span></div>
        <button type="button" class="np-link" id="markAll">Tandai semua dibaca</button>
    </div>
    <div class="np-list" id="notifList"></div>
    <div class="np-foot"><button type="button" class="np-link" id="clearAll">Hapus semua</button></div>
</div>

<script>
    (function () {
    var $ = function (id) { return document.getElementById(id); };
    var esc = function (s) { return String(s).replace(/[&<>"]/g, function (c) { return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]; }); };
    var applyI18N = function () { if (window.I18N) window.I18N.apply(); };

    /* ---------- util ---------- */
    window.scrollBox = function (id, amount) { $(id).scrollBy({ left: amount, behavior: 'smooth' }); };
    function toast(msg) { var t = $('toast'); t.textContent = msg; t.classList.add('show'); applyI18N(); clearTimeout(toast._t); toast._t = setTimeout(function () { t.classList.remove('show'); }, 2000); }

    function openModal(html) { $('modalBox').innerHTML = html; $('modal').classList.add('open'); applyI18N(); }
    function closeModal() { $('modal').classList.remove('open'); }
    $('modal').addEventListener('click', function (e) { if (e.target === this) closeModal(); });
    $('modalBox').addEventListener('click', function (e) { if (e.target.closest('[data-close]')) closeModal(); });

    /* ---------- SEARCH AKSES CEPAT ---------- */
    var searchEl = $('searchQuick');
    function visibleCards() { return Array.prototype.filter.call(document.querySelectorAll('#quickScroll .quick-card'), function (c) { return c.style.display !== 'none'; }); }
    searchEl.addEventListener('input', function (e) {
        var q = e.target.value.trim(), shown = 0;
        document.querySelectorAll('#quickScroll .quick-card').forEach(function (card) {
            var ok = window.textMatches(card, q);
            card.style.display = ok ? 'flex' : 'none';
            if (ok) shown++;
        });
        $('emptyNote').style.display = shown ? 'none' : 'block';
    });
    searchEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { var v = visibleCards(); if (v.length) v[0].click(); }
    });
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); searchEl.focus(); searchEl.select(); }
        if (e.key === 'Escape') { closeModal(); $('rowMenu').classList.remove('open'); $('notifMenu').classList.remove('open'); }
    });

    /* Bantuan */
    document.querySelectorAll('#quickScroll .quick-card[data-help]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            e.preventDefault();
            openModal(
                '<div class="m-top"><div class="av" style="background:#2563eb">?</div><div><h3>Panduan Singkat</h3><p>Cara memakai dashboard SIM-BU</p></div></div>' +
                '<p class="m-note">1. Gunakan kolom pencarian (Ctrl K) untuk menemukan fitur, lalu tekan Enter untuk membukanya.</p>' +
                '<p class="m-note">2. Klik angka Menunggu / Disetujui / Ditolak untuk menyaring tabel pengajuan.</p>' +
                '<p class="m-note">3. Klik tanggal di kalender untuk melihat jadwal hari tersebut.</p>' +
                '<p class="m-note">4. Hubungi bagian terkait lewat tombol telepon (WhatsApp) di panel kanan.</p>' +
                '<div class="m-actions"><button type="button" class="m-btn primary" data-close>Mengerti</button></div>'
            );
        });
    });

    /* ---------- TABEL PENGAJUAN ---------- */
    var C = { Menunggu: '#3b82f6', Disetujui: '#10b981', Ditolak: '#ef4444' };
    var tableData = [
        { no: 'REQ-2026-001', cat: 'Peminjaman Aset',  date: '2026-11-10', status: 'Menunggu',  who: 'Andi Firmansyah', note: 'Peminjaman proyektor dan laptop untuk kegiatan sosialisasi.' },
        { no: 'VR-2026-002',  cat: 'Kendaraan Dinas',  date: '2026-11-09', status: 'Disetujui', who: 'Siti Rahma',      note: 'Perjalanan dinas ke kantor wilayah, 1 hari.' },
        { no: 'ATK-2026-003', cat: 'Permintaan ATK',   date: '2026-11-08', status: 'Ditolak',   who: 'Budi Pratama',    note: 'Permintaan ATK melebihi kuota bulanan bagian.' },
        { no: 'RB-2026-004',  cat: 'Booking Ruangan',  date: '2026-11-07', status: 'Menunggu',  who: 'Dewi Lestari',    note: 'Booking Ruang Rapat Utama untuk rapat koordinasi.' },
        { no: 'TR-2026-005',  cat: 'Perjalanan Dinas', date: '2026-11-06', status: 'Disetujui', who: 'Andi Firmansyah', note: 'Perjalanan dinas 2 hari untuk pelatihan.' },
        { no: 'REQ-2026-006', cat: 'Peminjaman Aset',  date: '2026-11-05', status: 'Disetujui', who: 'Siti Rahma',      note: 'Peminjaman kamera dokumentasi.' },
        { no: 'VR-2026-007',  cat: 'Kendaraan Dinas',  date: '2026-11-04', status: 'Menunggu',  who: 'Budi Pratama',    note: 'Kendaraan untuk antar-jemput tamu instansi.' }
    ];
    var state = { filter: 'Semua', page: 1, per: 4, all: false, selected: null, menuNo: null };
    var fmtDate = function (iso) { var p = iso.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }); };
    var filtered = function () { return tableData.filter(function (r) { return state.filter === 'Semua' || r.status === state.filter; }); };

    function renderCounts() {
        ['Menunggu', 'Disetujui', 'Ditolak'].forEach(function (s) {
            $('cnt-' + s).textContent = tableData.filter(function (r) { return r.status === s; }).length;
        });
        document.querySelectorAll('.stat-chip').forEach(function (c) { c.classList.toggle('on', c.getAttribute('data-filter') === state.filter); });
    }

    function renderFilters() {
        var row = $('filterRow'); row.innerHTML = '';
        ['Semua', 'Menunggu', 'Disetujui', 'Ditolak'].forEach(function (s) {
            var n = s === 'Semua' ? tableData.length : tableData.filter(function (r) { return r.status === s; }).length;
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'f-chip' + (state.filter === s ? ' on' : '');
            b.innerHTML = '<span data-translate>' + s + '</span> (' + n + ')';
            b.onclick = function () { setFilter(s); };
            row.appendChild(b);
        });
        applyI18N();
    }

    function setFilter(s) { state.filter = (state.filter === s && s !== 'Semua') ? 'Semua' : s; state.page = 1; renderTable(); }

    function renderTable() {
        renderCounts(); renderFilters();
        var data = filtered(), tbody = $('tableBody'), pag = $('tablePagination');
        var pages = Math.max(1, Math.ceil(data.length / state.per));
        if (state.page > pages) state.page = pages;
        var rows = state.all ? data : data.slice((state.page - 1) * state.per, state.page * state.per);
        tbody.innerHTML = '';
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="5" class="tbl-empty">Tidak ada pengajuan.</td></tr>'; }
        rows.forEach(function (row) {
            var tr = document.createElement('tr');
            if (row.no === state.selected) tr.className = 'selected';
            tr.setAttribute('data-search', (row.no + ' ' + row.cat + ' ' + row.status + ' ' + row.who + ' ' + fmtDate(row.date)).toLowerCase());

            tr.innerHTML =
                '<td data-convert="full">' + esc(row.no) + '</td>' +
                '<td data-translate>' + esc(row.cat) + '</td>' +
                '<td data-convert="digit">' + esc(fmtDate(row.date)) + '</td>' +
                '<td><span class="st"><i style="background:' + C[row.status] + '"></i><span data-translate>' + row.status + '</span></span></td>' +
                '<td><button type="button" class="dots" aria-label="Menu">&#8942;</button></td>';

            tr.addEventListener('click', function (e) {
                if (e.target.closest('.dots')) { e.stopPropagation(); showRowMenu(e.target.closest('.dots'), row.no); return; }
                state.selected = row.no; renderTable();
            });
            tbody.appendChild(tr);
        });
        pag.innerHTML = '';
        if (!state.all) {
            for (var i = 1; i <= pages; i++) {
                (function (i) {
                    var b = document.createElement('button');
                    b.type = 'button'; b.className = 'page-btn' + (i === state.page ? ' active' : ''); b.textContent = i;
                    b.onclick = function () { state.page = i; renderTable(); };
                    pag.appendChild(b);
                })(i);
            }
        }
        $('btnAll').textContent = state.all ? 'Ringkas' : 'Lihat Semua';
        applyI18N();
    }

    function showRowMenu(btn, no) {
        state.menuNo = no;
        var r = btn.getBoundingClientRect(), m = $('rowMenu');
        m.classList.add('open');
        m.style.top = Math.min(r.bottom + 4, window.innerHeight - 110) + 'px';
        m.style.left = Math.max(8, r.right - 170) + 'px';
    }
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#rowMenu') && !e.target.closest('.dots')) $('rowMenu').classList.remove('open');
        if (!e.target.closest('#notifMenu') && !e.target.closest('#notifBtn')) $('notifMenu').classList.remove('open');
    });

    $('rmDetail').onclick = function () {
        $('rowMenu').classList.remove('open');
        var r = tableData.filter(function (x) { return x.no === state.menuNo; })[0]; if (!r) return;
        openModal(
            '<div class="m-top"><div class="av" style="background:' + C[r.status] + '">' + r.status.charAt(0) + '</div><div><h3 data-convert="full">' + esc(r.no) + '</h3><p data-translate>' + esc(r.cat) + '</p></div></div>' +
            '<p class="m-note" data-translate>' + esc(r.note) + '</p>' +
            '<div class="m-row"><span>Pemohon</span><b data-convert="full">' + esc(r.who) + '</b></div>' +
            '<div class="m-row"><span>Tanggal</span><b data-convert="digit">' + esc(fmtDate(r.date)) + '</b></div>' +
            '<div class="m-row"><span>Status</span><b data-translate>' + r.status + '</b></div>' +
            '<div class="m-actions"><button type="button" class="m-btn" data-close>Tutup</button></div>'
        );
    };
    $('rmCopy').onclick = function () {
        $('rowMenu').classList.remove('open');
        var txt = state.menuNo;
        function ok() { toast('Nomor ' + txt + ' disalin'); }
        if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(txt).then(ok, ok); }
        else { var t = document.createElement('textarea'); t.value = txt; document.body.appendChild(t); t.select(); try { document.execCommand('copy'); } catch (e) {} document.body.removeChild(t); ok(); }
    };
    $('btnAll').onclick = function () { state.all = !state.all; renderTable(); };
    document.querySelectorAll('.stat-chip').forEach(function (c) { c.addEventListener('click', function () { setFilter(c.getAttribute('data-filter')); $('pengajuan').scrollIntoView({ behavior: 'smooth', block: 'start' }); }); });
    window.gotoTable = function (s) { state.filter = s; state.page = 1; renderTable(); $('pengajuan').scrollIntoView({ behavior: 'smooth', block: 'start' }); };

    /* ---------- KALENDER + JADWAL (x-timeline) ---------- */
    var todayIdx = {{ $todayIdx }};
    var selDay = todayIdx <= 4 ? todayIdx : null;

    function updateCalActive() {
        document.querySelectorAll('.cal-item').forEach(function (b) {
            b.classList.toggle('active', +b.getAttribute('data-idx') === selDay);
        });
    }

    function showTimeline(day) {
        document.querySelectorAll('.timeline-day').forEach(function (el) {
            var match = day === null
                ? el.getAttribute('data-day') === 'null'
                : el.getAttribute('data-day') === String(day);
            el.style.display = match ? 'block' : 'none';
        });
        var btnToday = $('btnToday');
        if (btnToday) {
            var showBtn = todayIdx <= 4 && day !== todayIdx;
            btnToday.style.display = showBtn ? '' : 'none';
        }
        applyI18N();
    }

    document.querySelectorAll('.cal-item').forEach(function (b) {
        b.addEventListener('click', function () {
            selDay = +b.getAttribute('data-idx');
            updateCalActive();
            showTimeline(selDay);
        });
    });

    $('btnToday').addEventListener('click', function () {
        selDay = todayIdx <= 4 ? todayIdx : null;
        updateCalActive();
        showTimeline(selDay);
    });

    $('calMonth').textContent = new Date().toLocaleDateString('id-ID', { month: 'long' });

    /* ---------- NOTIFIKASI ---------- */
    var notifs = [
        { k: 'approve', t: 'Pengajuan menunggu persetujuan', d: 'REQ-2026-001 (Peminjaman Aset) diajukan oleh Andi Firmansyah.', s: '2 menit lalu', r: false },
        { k: 'mail',    t: 'Disposisi surat baru', d: 'Surat dari Kepala Bagian menunggu tindak lanjut Anda.', s: '1 jam lalu', r: false },
        { k: 'room',    t: 'Booking ruangan diajukan', d: 'RB-2026-004 · Ruang Rapat Utama untuk rapat koordinasi.', s: 'Kemarin', r: true }
    ];
    var nIcons = {
        approve: ['#10b981', '<path d="M5 12l5 5 9-10"/>'],
        mail:    ['#2563eb', '<path d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/>'],
        room:    ['#d946ef', '<path d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 8h1M14 8h1M9 12h1M14 12h1"/>']
    };
    function renderNotifs() {
        var l = $('notifList'); l.innerHTML = '';
        if (!notifs.length) l.innerHTML = '<div class="np-empty" data-translate>Tidak ada notifikasi.</div>';
        notifs.forEach(function (n) {
            var ic = nIcons[n.k], b = document.createElement('button');
            b.type = 'button'; b.className = 'np-item' + (n.r ? '' : ' unread');
            b.innerHTML = '<span class="np-ic" style="background:' + ic[0] + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">' + ic[1] + '</svg></span>' +
                '<span class="np-tx"><b data-translate>' + esc(n.t) + '</b><span data-translate>' + esc(n.d) + '</span><small>' + esc(n.s) + '</small></span><i class="np-dot"></i>';
            b.onclick = function () { n.r = true; renderNotifs(); };
            l.appendChild(b);
        });
        var u = notifs.filter(function (n) { return !n.r; }).length;
        $('notifBadge').style.display = u ? 'block' : 'none';
        $('npCount').style.display = u ? 'inline-block' : 'none';
        $('npCount').textContent = u + ' baru';
        $('markAll').style.visibility = u ? 'visible' : 'hidden';
        $('clearAll').parentNode.style.display = notifs.length ? 'block' : 'none';
        applyI18N();
    }
    function closeNotif() { $('notifMenu').classList.remove('open'); }
    function placeNotif() {
        var r = $('notifBtn').getBoundingClientRect(), m = $('notifMenu'), w = m.offsetWidth || 360;
        m.style.left = Math.min(Math.max(12, r.right - w), window.innerWidth - w - 12) + 'px';
        m.style.top = (r.bottom + 10) + 'px';
    }
    $('notifBtn').onclick = function (e) {
        e.stopPropagation();
        var m = $('notifMenu');
        if (m.classList.contains('open')) { closeNotif(); return; }
        m.classList.add('open'); placeNotif(); applyI18N();
    };
    $('notifMenu').addEventListener('click', function (e) { e.stopPropagation(); });
    $('markAll').onclick = function () { notifs.forEach(function (n) { n.r = true; }); renderNotifs(); toast('Semua notifikasi ditandai dibaca'); };
    $('clearAll').onclick = function () { notifs = []; renderNotifs(); closeNotif(); toast('Notifikasi dihapus'); };
    window.addEventListener('resize', closeNotif);
    document.querySelector('.right-side').addEventListener('scroll', closeNotif);

    /* ---------- MAGANG ---------- */
    var magang = [
        { n: 'Andi Firmansyah', p: 'Informatika', c: '#2563eb', i: 'AF', k: 'Universitas Jember', u: 'Bagian Umum (IT)', t: '1 Sep – 28 Feb 2027', m: 'Pak Hendra' },
        { n: 'Siti Rahma', p: 'Manajemen', c: '#10b981', i: 'SR', k: 'Universitas Brawijaya', u: 'Kepegawaian', t: '1 Okt – 31 Jan 2027', m: 'Bu Lestari' },
        { n: 'Budi Pratama', p: 'Akuntansi', c: '#f59e0b', i: 'BP', k: 'Universitas Airlangga', u: 'Keuangan', t: '15 Okt – 15 Feb 2027', m: 'Pak Agus' },
        { n: 'Dewi Lestari', p: 'Hukum', c: '#8b5cf6', i: 'DL', k: 'Universitas Negeri Malang', u: 'Persuratan', t: '1 Nov – 30 Apr 2027', m: 'Bu Ratna' }
    ];
    var mw = $('magangScroll');
    magang.forEach(function (m, idx) {
        var el = document.createElement('div');
        el.className = 'magang-card';
        el.setAttribute('data-search', (m.n + ' ' + m.p + ' ' + m.k + ' ' + m.u).toLowerCase());
        el.innerHTML = '<div class="av" style="background:' + m.c + '">' + m.i + '</div>' +
            '<h5 data-convert="full">' + esc(m.n) + '</h5>' +
            '<p data-translate>' + esc(m.p) + '</p>' +
            '<button type="button" class="btn-detail" data-i="' + idx + '">Detail</button>';
        mw.appendChild(el);
    });
    mw.addEventListener('click', function (e) {
        var b = e.target.closest('.btn-detail'); if (!b) return;
        var m = magang[+b.getAttribute('data-i')];
        openModal(
            '<div class="m-top"><div class="av" style="background:' + m.c + '">' + m.i + '</div><div><h3 data-convert="full">' + esc(m.n) + '</h3><p><span data-translate>Peserta Magang</span> · <span data-translate>' + esc(m.p) + '</span></p></div></div>' +
            '<div class="m-row"><span>Asal Kampus</span><b data-no-i18n>' + esc(m.k) + '</b></div>' +
            '<div class="m-row"><span>Penempatan</span><b data-translate>' + esc(m.u) + '</b></div>' +
            '<div class="m-row"><span>Periode</span><b data-no-i18n>' + esc(m.t) + '</b></div>' +
            '<div class="m-row"><span>Pembimbing</span><b data-no-i18n>' + esc(m.m) + '</b></div>' +
            '<div class="m-actions"><button type="button" class="m-btn" data-close>Tutup</button></div>'
        );
    });

    /* ---------- INIT ---------- */
    renderTable();
    renderNotifs();
    updateCalActive();
    showTimeline(selDay);

    var g = document.querySelector('.dash-grid');
    if (g && g.previousElementSibling && g.previousElementSibling.classList.contains('alert')) { g.style.marginTop = '0'; }
    })();
</script>
@endsection
