@php
$u = auth()->user();

/* Dashboard satu-satunya menu tunggal. Semua menu lain wajib masuk grup (submenu). */
$dashboard = ['label'=>'Dashboard','route'=>'dashboard'];

$sections = [
    'MENU UTAMA' => ['short'=>'Menu', 'groups'=>[
        'TUGAS' => [
            ['label'=>'Disposisi Saya','route'=>'pegawai.dispositions.index','show'=>$u?->hasAnyRole(['pegawai','admin_persuratan','kasubbag','kepala_bagian','super_admin'])],
            ['label'=>'Tugas Saya','route'=>'pengemudi.tasks.index','show'=>$u?->hasRole('pengemudi')],
            ['label'=>'Inbox Persetujuan','route'=>'pejabat.approvals.index','show'=>$u?->hasAnyRole(['kepala_bagian','kasubbag','super_admin'])],
        ],
        'LAPORAN' => [
            ['label'=>'Laporan','route'=>'reports.index','show'=>$u?->hasAnyRole(['super_admin','kepala_bagian','kasubbag','auditor'])],
            ['label'=>'Laporan Auditor','route'=>'auditor.reports.index','show'=>$u?->hasRole('auditor')],
        ],
    ]],
    'MODUL' => ['short'=>'Modul', 'groups'=>[
        'PERSURATAN' => [
            ['label'=>'Surat Masuk','route'=>'admin.persuratan.incoming-letters.index','show'=>$u?->hasAnyRole(['super_admin','admin_persuratan'])],
            ['label'=>'Surat Keluar','route'=>'admin.persuratan.outgoing-letters.index','show'=>$u?->hasAnyRole(['super_admin','admin_persuratan'])],
        ],
        'ARSIP' => [
            ['label'=>'Daftar Arsip','route'=>'admin.arsip.records.index','show'=>$u?->hasAnyRole(['super_admin','petugas_arsip'])],
            ['label'=>'Klasifikasi','route'=>'admin.arsip.classifications.index','show'=>$u?->hasAnyRole(['super_admin','petugas_arsip'])],
        ],
        'INVENTARIS' => [
            ['label'=>'Aset','route'=>'admin.inventaris.assets.index','show'=>$u?->hasAnyRole(['super_admin','admin_inventaris'])],
            ['label'=>'Peminjaman','route'=>'admin.inventaris.loans.index','show'=>$u?->hasAnyRole(['super_admin','admin_inventaris'])],
            ['label'=>'Item ATK','route'=>'admin.inventaris.supply-items.index','show'=>$u?->hasAnyRole(['super_admin','admin_inventaris'])],
            ['label'=>'Permintaan ATK','route'=>'admin.inventaris.stock-requests.index','show'=>$u?->hasAnyRole(['super_admin','admin_inventaris'])],
        ],
        'BMD' => [
            ['label'=>'Data BMD','route'=>'admin.barang-daerah.assets.index','show'=>$u?->hasAnyRole(['super_admin','pengurus_bmd'])],
        ],
        'KENDARAAN' => [
            ['label'=>'Kendaraan','route'=>'admin.kendaraan.vehicles.index','show'=>$u?->hasAnyRole(['super_admin','admin_kendaraan'])],
            ['label'=>'Permohonan','route'=>'admin.kendaraan.requests.index','show'=>$u?->hasAnyRole(['super_admin','admin_kendaraan'])],
        ],
        'FASILITAS' => [
            ['label'=>'Ruangan','route'=>'admin.fasilitas.rooms.index','show'=>$u?->hasAnyRole(['super_admin','admin_fasilitas'])],
            ['label'=>'Booking','route'=>'admin.fasilitas.bookings.index','show'=>$u?->hasAnyRole(['super_admin','admin_fasilitas'])],
        ],
        'PROTOKOL' => [
            ['label'=>'Kegiatan','route'=>'admin.protokol.events.index','show'=>$u?->hasAnyRole(['super_admin','admin_protokol'])],
        ],
        'TRAVEL' => [
            ['label'=>'Permohonan','route'=>'admin.perjalanan-dinas.requests.index','show'=>$u?->hasAnyRole(['super_admin','admin_perjalanan_dinas'])],
        ],
    ]],
    'AKUN' => ['short'=>'Akun', 'groups'=>[
        'PENGAJUAN SAYA' => [
            ['label'=>'Pinjam Aset','route'=>'pegawai.asset-loans.index','show'=>true],
            ['label'=>'Minta ATK','route'=>'pegawai.stock-requests.index','show'=>true],
            ['label'=>'Booking Ruangan','route'=>'pegawai.room-bookings.index','show'=>true],
            ['label'=>'Kendaraan','route'=>'pegawai.vehicle-requests.index','show'=>true],
            ['label'=>'Perjalanan Dinas','route'=>'pegawai.travel-requests.index','show'=>true],
        ],
        'ADMINISTRASI' => [
            ['label'=>'Kelola User','route'=>'super-admin.users.index','show'=>$u?->hasRole('super_admin')],
            ['label'=>'Kelola Role','route'=>'super-admin.roles.index','show'=>$u?->hasRole('super_admin')],
            ['label'=>'Audit Log','route'=>'super-admin.activity-log.index','show'=>$u?->hasRole('super_admin')],
        ],
    ]],
];

/* label tampilan + ikon header tiap grup */
$groupMeta = [
    'TUGAS'          => ['label'=>'Tugas & Persetujuan', 'icon'=>'clipboard-check'],
    'LAPORAN'        => ['label'=>'Laporan',             'icon'=>'chart'],
    'PERSURATAN'     => ['label'=>'Persuratan',          'icon'=>'mail'],
    'ARSIP'          => ['label'=>'Arsip',               'icon'=>'archive'],
    'INVENTARIS'     => ['label'=>'Inventaris',          'icon'=>'package'],
    'BMD'            => ['label'=>'BMD',                 'icon'=>'landmark'],
    'KENDARAAN'      => ['label'=>'Kendaraan',           'icon'=>'car'],
    'FASILITAS'      => ['label'=>'Fasilitas',           'icon'=>'building'],
    'PROTOKOL'       => ['label'=>'Protokol',            'icon'=>'calendar'],
    'TRAVEL'         => ['label'=>'Travel',              'icon'=>'plane'],
    'PENGAJUAN SAYA' => ['label'=>'Pengajuan Saya',      'icon'=>'file-plus'],
    'ADMINISTRASI'   => ['label'=>'Administrasi',        'icon'=>'shield'],
];

/* warna titik submenu (bergantian seperti di referensi) */
$dots = ['#3b82f6','#ef4444','#f59e0b','#8b5cf6','#10b981','#ec4899'];

$isActive = fn($route) => request()->routeIs($route) || request()->routeIs(preg_replace('/\.index$/', '.*', $route));

/* set ikon (gaya outline konsisten, 24x24) */
$icons = [
    'dashboard'       => '<rect x="3" y="3" width="7" height="9" rx="1.8"/><rect x="14" y="3" width="7" height="5" rx="1.8"/><rect x="14" y="12" width="7" height="9" rx="1.8"/><rect x="3" y="16" width="7" height="5" rx="1.8"/>',
    'clipboard-check' => '<rect x="8" y="2" width="8" height="4" rx="1.2"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/>',
    'chart'           => '<path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>',
    'mail'            => '<rect x="2" y="4" width="20" height="16" rx="2.5"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
    'archive'         => '<rect x="2" y="3" width="20" height="5" rx="1.4"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/>',
    'package'         => '<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="m7.5 4.27 9 5.15"/>',
    'landmark'        => '<path d="M3 21h18"/><path d="M4 18h16"/><path d="M6 18v-7M10 18v-7M14 18v-7M18 18v-7"/><path d="M12 3 3 8h18z"/>',
    'car'             => '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>',
    'building'        => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4M10 10h4M10 14h4M10 18h4"/>',
    'calendar'        => '<rect x="3" y="4" width="18" height="18" rx="2.5"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/>',
    'plane'           => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
    'file-plus'       => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M12 18v-6"/><path d="M9 15h6"/>',
    'shield'          => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
];
@endphp

<style>
    /* ================= SIDEBAR ================= */
    .sidebar {
        --sb-accent: #2563eb;
        --sb-accent-soft: #eef3ff;
        --sb-search: #f3f6fd;
        --sb-line: #e6e9f2;
        --sb-text: var(--text-main, #1d2136);
        --sb-muted: var(--text-muted, #7b8197);
        width: 256px; flex-shrink: 0;
        background: var(--bg-topbar, #fff);
        border-right: 1px solid var(--border-color, #e6e9f2);
        display: flex; flex-direction: column;
        position: sticky; top: 0; height: 100vh; overflow: hidden;
        transition: width .28s cubic-bezier(.4,0,.2,1), background .3s;
    }
    body.dark-mode .sidebar {
        --sb-accent: #60a5fa;
        --sb-accent-soft: rgba(59,130,246,.16);
        --sb-search: rgba(255,255,255,.06);
        --sb-line: rgba(255,255,255,.10);
    }
    .sidebar [hidden] { display: none !important; }

    /* ---------- brand ---------- */
    .sidebar-brand { display: flex; align-items: center; gap: 12px; padding: 20px 18px 12px; }
    .sidebar-brand-icon {
        width: 42px; height: 42px; flex-shrink: 0; border-radius: 14px;
        background: linear-gradient(135deg, #2433d6 0%, #4f6bff 60%, #6aa0ff 100%);
        display: grid; place-items: center;
        box-shadow: 0 10px 22px rgba(47,63,224,.32), inset 0 1px 0 rgba(255,255,255,.35);
    }
    .sidebar-brand-icon svg { width: 24px; height: 24px; }
    .sb-brand-text { min-width: 0; }
    .sidebar-brand-title { font-size: 18px; font-weight: 800; letter-spacing: .6px; color: var(--sb-text); line-height: 1.05; }
    .sidebar-brand-title span { color: var(--sb-accent); }
    .sidebar-brand-sub { font-size: 10.5px; font-weight: 500; letter-spacing: .3px; color: var(--sb-muted); margin-top: 4px; white-space: nowrap; }
    .sb-toggle {
        margin-left: auto; width: 24px; height: 24px; border-radius: 50%; flex-shrink: 0;
        border: 1px solid var(--sb-line); background: var(--bg-topbar, #fff); color: var(--sb-muted);
        display: grid; place-items: center; cursor: pointer; box-shadow: 0 2px 6px rgba(20,30,80,.08);
        transition: color .2s, transform .28s;
    }
    .sb-toggle:hover { color: var(--sb-accent); }
    .sb-toggle svg { width: 13px; height: 13px; }

    /* ---------- search ---------- */
    .sb-tools { display: flex; gap: 8px; padding: 6px 16px 6px; }
    .sb-search {
        flex: 1; min-width: 0; height: 40px; display: flex; align-items: center; gap: 9px; padding: 0 12px;
        background: var(--sb-search); border: 1px solid transparent; border-radius: 12px; color: var(--sb-text);
        transition: border-color .2s, box-shadow .2s;
    }
    .sb-search:focus-within { border-color: var(--sb-accent); box-shadow: 0 0 0 3px var(--sb-accent-soft); }
    .sb-search svg { width: 17px; height: 17px; flex-shrink: 0; }
    .sb-search input { flex: 1; min-width: 0; border: none; outline: none; background: transparent; font: inherit; font-size: 12.5px; font-weight: 500; color: var(--sb-text); }
    .sb-search input::placeholder { color: var(--sb-muted); }
    .sb-allbtn {
        width: 40px; height: 40px; flex-shrink: 0; border: none; border-radius: 12px; cursor: pointer;
        background: var(--sb-search); color: var(--sb-text); display: grid; place-items: center; transition: background .2s, color .2s;
    }
    .sb-allbtn:hover, .sb-allbtn.on { background: var(--sb-accent-soft); color: var(--sb-accent); }
    .sb-allbtn svg { width: 17px; height: 17px; }

    /* ---------- nav ---------- */
    .sidebar-nav { flex: 1; min-height: 0; overflow-y: auto; padding: 0 14px 20px; scrollbar-width: none; -ms-overflow-style: none; }
    .sidebar-nav::-webkit-scrollbar { display: none; }

    .sidebar-section { font-size: 10px; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: var(--sb-muted); opacity: .85; padding: 18px 12px 8px; }

    .sb-ico { width: 20px; height: 20px; flex-shrink: 0; }

    /* item tunggal (Dashboard) */
    .sidebar-item {
        display: flex; align-items: center; gap: 12px; padding: 10px 12px; margin-bottom: 2px; border-radius: 12px;
        color: var(--sb-muted); text-decoration: none; font-size: 13.5px; font-weight: 500;
        transition: background .2s, color .2s;
    }
    .sidebar-item:hover { background: var(--bg-hover, #f3f5fb); color: var(--sb-text); }
    .sidebar-item.active { background: var(--sb-accent-soft); color: var(--sb-accent); font-weight: 600; }

    /* ---------- grup (domain) ---------- */
    .sb-group { margin-bottom: 2px; }
    .sb-group-btn {
        width: 100%; display: flex; align-items: center; gap: 12px; padding: 10px 12px; border: none; border-radius: 12px;
        background: none; cursor: pointer; text-align: left; font-family: inherit; font-size: 13.5px; font-weight: 500;
        color: var(--sb-muted); transition: background .2s, color .2s;
    }
    .sb-group-btn:hover { background: var(--bg-hover, #f3f5fb); color: var(--sb-text); }
    .sb-group-label { flex: 1; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sb-count {
        min-width: 24px; height: 20px; padding: 0 7px; border-radius: 20px; display: grid; place-items: center;
        background: var(--sb-accent); color: #fff; font-size: 10.5px; font-weight: 600;
        box-shadow: 0 4px 10px rgba(37,99,235,.32); opacity: 0; transform: scale(.7); transition: opacity .25s, transform .25s;
    }
    .sb-chevron { width: 14px; height: 14px; flex-shrink: 0; opacity: .5; transition: transform .3s ease, opacity .2s; }

    .sb-group.open > .sb-group-btn { color: var(--sb-accent); font-weight: 600; }
    .sb-group.open .sb-count { opacity: 1; transform: none; }
    .sb-group.open .sb-chevron { transform: rotate(180deg); opacity: 1; }

    /* submenu (muncul di bawah domain) */
    .sb-sub { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .3s ease; }
    .sb-group.open .sb-sub { grid-template-rows: 1fr; }
    .sb-sub-inner { position: relative; min-height: 0; overflow: hidden; visibility: hidden; transition: visibility .3s; margin: 0 0 0 21px; padding: 0 0 0 14px; }
    .sb-group.open .sb-sub-inner { visibility: visible; padding-top: 4px; padding-bottom: 8px; }
    .sb-sub-inner::before { content: ''; position: absolute; left: 0; top: 4px; bottom: 8px; width: 1.5px; border-radius: 2px; background: var(--sb-line); }
    .sb-fly-title { display: none; }

    .sb-subitem {
        position: relative; display: flex; align-items: center; padding: 9px 12px 9px 28px; margin: 2px 0; border-radius: 11px;
        font-size: 13px; font-weight: 500; color: var(--sb-muted); text-decoration: none;
        opacity: 0; transform: translateX(-6px);
        transition: opacity .3s ease, transform .3s ease, background .2s, color .2s;
    }
    .sb-group.open .sb-subitem { opacity: 1; transform: none; transition-delay: .06s, .06s, 0s, 0s; }
    .sb-subitem::before {
        content: ''; position: absolute; left: 12px; top: 50%; width: 5px; height: 5px; margin-top: -2.5px;
        border-radius: 50%; background: var(--dot, #3b82f6);
    }
    .sb-subitem:hover { background: var(--bg-hover, #f3f5fb); color: var(--sb-text); }
    .sb-subitem.active { background: var(--sb-accent-soft); color: var(--sb-text); font-weight: 600; }
    .sb-subitem.active::after {
        content: ''; position: absolute; left: -14px; top: 50%; width: 2px; height: 20px; margin-top: -10px;
        border-radius: 2px; background: var(--sb-text);
    }

    .sb-empty { display: none; text-align: center; font-size: 12.5px; color: var(--sb-muted); padding: 28px 12px; }

    .sidebar a:focus-visible, .sidebar button:focus-visible { outline: 2px solid var(--sb-accent); outline-offset: 2px; }

    /* ================= MODE COLLAPSE (ikon saja + flyout) ================= */
    .sidebar.collapsed { width: 76px; }
    .sidebar.collapsed .sidebar-brand { flex-direction: column; padding: 18px 0 8px; gap: 10px; }
    .sidebar.collapsed .sb-brand-text,
    .sidebar.collapsed .sb-search input,
    .sidebar.collapsed .sb-allbtn,
    .sidebar.collapsed .sb-group-label,
    .sidebar.collapsed .sb-count,
    .sidebar.collapsed .sb-chevron,
    .sidebar.collapsed .sidebar-item span,
    .sidebar.collapsed .sidebar-section span { display: none; }
    .sidebar.collapsed .sb-toggle { margin-left: 0; transform: rotate(180deg); }
    .sidebar.collapsed .sb-tools { padding: 6px 14px; }
    .sidebar.collapsed .sb-search { justify-content: center; padding: 0; cursor: pointer; }
    .sidebar.collapsed .sidebar-nav { padding: 0 12px 20px; }
    .sidebar.collapsed .sidebar-section { text-align: center; padding: 14px 0 6px; }
    .sidebar.collapsed .sidebar-section::before { content: attr(data-short); font-size: 8.5px; letter-spacing: .8px; }
    .sidebar.collapsed .sidebar-item,
    .sidebar.collapsed .sb-group-btn { justify-content: center; padding: 11px 0; }
    .sidebar.collapsed .sb-group.has-active > .sb-group-btn,
    .sidebar.collapsed .sb-group.fly > .sb-group-btn { background: var(--sb-accent-soft); color: var(--sb-accent); }
    .sidebar.collapsed .sb-group.open > .sb-group-btn { font-weight: 500; }

    .sidebar.collapsed .sb-sub {
        display: block; position: fixed; z-index: 80; min-width: 218px;
        background: var(--bg-topbar, #fff); border: 1px solid var(--sb-line); border-radius: 16px; padding: 10px 10px 8px;
        box-shadow: 0 18px 50px rgba(20,30,80,.20);
        opacity: 0; pointer-events: none; transform: translateX(-6px); transition: opacity .18s, transform .18s;
    }
    .sidebar.collapsed .sb-group.fly .sb-sub { opacity: 1; pointer-events: auto; transform: none; }
    .sidebar.collapsed .sb-sub-inner { overflow: visible; visibility: visible; margin: 0 0 0 8px; padding: 0 0 0 14px; }
    .sidebar.collapsed .sb-sub-inner::before { top: 3px; bottom: 3px; left: 0; }
    .sidebar.collapsed .sb-subitem { opacity: 1; transform: none; }
    .sidebar.collapsed .sb-fly-title {
        display: block; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase;
        color: var(--sb-muted); padding: 2px 8px 8px;
    }

    @media (prefers-reduced-motion: reduce) {
        .sidebar, .sidebar * , .sidebar *::before { transition: none !important; }
    }
</style>

<aside class="sidebar" aria-label="Navigasi utama">
    <!-- BRAND -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M7 6v7a5 5 0 0 0 10 0V6" stroke="#fff" stroke-width="2.8" stroke-linecap="round"/>
                <circle cx="18.6" cy="5.4" r="1.8" fill="#ffe58f"/>
            </svg>
        </div>
        <div class="sb-brand-text">
            <div class="sidebar-brand-title">SIM-<span>BU</span></div>
            <div class="sidebar-brand-sub">Pemda · Bagian Umum</div>
        </div>
        <button type="button" class="sb-toggle" id="sbToggle" aria-label="Ciutkan sidebar" title="Ciutkan / lebarkan sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </button>
    </div>

    <!-- SEARCH -->
    <div class="sb-tools">
        <label class="sb-search" id="sbSearchWrap" title="Cari menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="sbSearch" placeholder="Cari menu..." autocomplete="off">
        </label>
        <button type="button" class="sb-allbtn" id="sbAll" aria-label="Buka semua grup" title="Buka / tutup semua grup">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>
        </button>
    </div>

    <!-- NAV -->
    <nav class="sidebar-nav">
        @foreach($sections as $secLabel => $sec)
            @php
                $secGroups = [];
                foreach ($sec['groups'] as $gk => $items) {
                    $vis = collect($items)->filter(fn($i) => $i['show'])->values();
                    if ($vis->count() > 0) $secGroups[$gk] = $vis;
                }
            @endphp

            @if(count($secGroups) > 0 || $loop->first)
                <div data-section>
                    <div class="sidebar-section" data-short="{{ $sec['short'] }}"><span>{{ $secLabel }}</span></div>

                    @if($loop->first)
                        @php $dashActive = $isActive($dashboard['route']); @endphp
                        <a href="{{ route($dashboard['route']) }}" class="sidebar-item {{ $dashActive ? 'active' : '' }}" data-dashboard title="Dashboard">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="sb-ico">{!! $icons['dashboard'] !!}</svg>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    @foreach($secGroups as $groupKey => $visible)
                        @php
                            $meta      = $groupMeta[$groupKey] ?? ['label' => ucfirst(strtolower($groupKey)), 'icon' => 'archive'];
                            $hasActive = $visible->contains(fn($i) => $isActive($i['route']));
                        @endphp
                        <div class="sb-group {{ $hasActive ? 'open has-active' : '' }}" data-group data-label="{{ strtolower($meta['label']) }}" @if($hasActive) data-initial-open @endif>
                            <button type="button" class="sb-group-btn" aria-expanded="{{ $hasActive ? 'true' : 'false' }}" title="{{ $meta['label'] }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="sb-ico">{!! $icons[$meta['icon']] ?? '' !!}</svg>
                                <span class="sb-group-label">{{ $meta['label'] }}</span>
                                <span class="sb-count">{{ $visible->count() }}</span>
                                <svg class="sb-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>

                            <div class="sb-sub">
                                <div class="sb-fly-title">{{ $meta['label'] }}</div>
                                <div class="sb-sub-inner">
                                    @foreach($visible as $it)
                                        @php $active = $isActive($it['route']); @endphp
                                        <a href="{{ route($it['route']) }}" class="sb-subitem {{ $active ? 'active' : '' }}" style="--dot: {{ $dots[$loop->index % count($dots)] }}">
                                            <span>{{ $it['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach

        <div class="sb-empty" id="sbEmpty">Menu tidak ditemukan</div>
    </nav>
</aside>

<script>
    (function () {
        var sb = document.querySelector('.sidebar');
        if (!sb) return;

        var groups    = [].slice.call(sb.querySelectorAll('[data-group]'));
        var sections  = [].slice.call(sb.querySelectorAll('[data-section]'));
        var dash      = sb.querySelector('[data-dashboard]');
        var search    = document.getElementById('sbSearch');
        var searchWrap= document.getElementById('sbSearchWrap');
        var toggleBtn = document.getElementById('sbToggle');
        var allBtn    = document.getElementById('sbAll');
        var empty     = document.getElementById('sbEmpty');
        var collapsed = false;
        var allOpen   = false;

        /* ---------- buka / tutup grup ---------- */
        function setOpen(g, open) {
            g.classList.toggle('open', open);
            g.querySelector('.sb-group-btn').setAttribute('aria-expanded', open ? 'true' : 'false');
        }

        /* ---------- flyout (mode collapse) ---------- */
        function closeFly(except) {
            groups.forEach(function (g) { if (g !== except) g.classList.remove('fly'); });
        }
        function openFly(g) {
            clearTimeout(g._t);
            closeFly(g);
            g.classList.add('fly');
            var btn = g.querySelector('.sb-group-btn').getBoundingClientRect();
            var sub = g.querySelector('.sb-sub');
            sub.style.left = (sb.getBoundingClientRect().right + 6) + 'px';
            sub.style.top  = btn.top + 'px';
            var maxTop = window.innerHeight - sub.offsetHeight - 12;
            if (btn.top > maxTop) sub.style.top = Math.max(12, maxTop) + 'px';
        }

        groups.forEach(function (g) {
            var btn = g.querySelector('.sb-group-btn');

            btn.addEventListener('click', function () {
                if (collapsed) {
                    g.classList.contains('fly') ? g.classList.remove('fly') : openFly(g);
                    return;
                }
                var willOpen = !g.classList.contains('open');
                if (!allOpen) {
                    groups.forEach(function (o) { if (o !== g) setOpen(o, false); }); /* mode accordion */
                }
                setOpen(g, willOpen);
                if (willOpen) setTimeout(function () { btn.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); }, 320);
            });

            g.addEventListener('mouseenter', function () { if (collapsed) openFly(g); });
            g.addEventListener('mouseleave', function () {
                if (!collapsed) return;
                g._t = setTimeout(function () { g.classList.remove('fly'); }, 180);
            });
        });

        document.addEventListener('click', function (e) { if (collapsed && !e.target.closest('.sb-group')) closeFly(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeFly(); });

        /* ---------- ciutkan / lebarkan sidebar ---------- */
        function applyCollapsed(v) {
            collapsed = v;
            sb.classList.toggle('collapsed', v);
            closeFly();
            toggleBtn.setAttribute('aria-label', v ? 'Lebarkan sidebar' : 'Ciutkan sidebar');
            try { localStorage.setItem('simbu_sb_collapsed', v ? '1' : '0'); } catch (e) {}
        }
        toggleBtn.addEventListener('click', function () { applyCollapsed(!collapsed); });
        searchWrap.addEventListener('click', function () {
            if (collapsed) { applyCollapsed(false); setTimeout(function () { search.focus(); }, 290); }
        });
        var saved = false;
        try { saved = localStorage.getItem('simbu_sb_collapsed') === '1'; } catch (e) {}
        applyCollapsed(saved);

        /* ---------- buka / tutup semua ---------- */
        allBtn.addEventListener('click', function () {
            allOpen = !allOpen;
            allBtn.classList.toggle('on', allOpen);
            allBtn.setAttribute('aria-label', allOpen ? 'Tutup semua grup' : 'Buka semua grup');
            groups.forEach(function (g) { setOpen(g, allOpen); });
        });

        /* ---------- cari menu ---------- */
        function filter(q) {
            q = q.trim().toLowerCase();

            groups.forEach(function (g) {
                var groupMatch = q && g.getAttribute('data-label').indexOf(q) > -1;
                var count = 0;
                [].slice.call(g.querySelectorAll('.sb-subitem')).forEach(function (a) {
                    var m = !q || groupMatch || a.textContent.toLowerCase().indexOf(q) > -1;
                    a.hidden = !m;
                    if (m) count++;
                });
                g.hidden = !!q && count === 0;
                if (q && count > 0) setOpen(g, true);
                if (!q) setOpen(g, g.hasAttribute('data-initial-open'));
            });

            if (dash) dash.hidden = !!q && 'dashboard'.indexOf(q) === -1;

            var anyVisible = false;
            sections.forEach(function (sec) {
                var has = sec.querySelector('[data-group]:not([hidden])') || sec.querySelector('[data-dashboard]:not([hidden])');
                sec.hidden = !has;
                if (has) anyVisible = true;
            });
            empty.style.display = anyVisible ? 'none' : 'block';
            if (!q) { allOpen = false; allBtn.classList.remove('on'); }
        }
        search.addEventListener('input', function () { filter(search.value); });
    })();
</script>
