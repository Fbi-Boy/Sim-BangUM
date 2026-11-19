@extends('layouts.app')
@section('title', 'Kelola Role')

@section('content')
@include('partials.ui-extras')
<style>
    /* ===== Banner ===== */
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
    .hero-text { max-width: 58%; }
    .hero-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; background: rgba(255,255,255,.18); backdrop-filter: blur(6px); font-size: 11px; font-weight: 500; margin-bottom: 12px; }
    .hero-tag i { width: 6px; height: 6px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 3px rgba(52,211,153,.35); }
    .hero h1 { font-size: 26px; font-weight: 700; line-height: 1.2; margin: 0 0 8px; letter-spacing: -.3px; color: #fff; }
    .hero p { font-size: 12.5px; opacity: .92; line-height: 1.65; margin: 0 0 18px; color: #fff; }
    .hero-btn {
        display: inline-flex; align-items: center; gap: 8px; background: #fff; color: #2563eb; padding: 9px 18px; border-radius: 10px;
        font-size: 12px; font-weight: 600; text-decoration: none; box-shadow: 0 8px 18px rgba(0,0,0,.15); transition: transform .2s;
    }
    .hero-btn:hover { transform: translateY(-2px); }
    .hero-art { flex: 0 0 260px; height: 150px; }
    .hero-art svg { width: 100%; height: 100%; overflow: visible; }

    /* animasi bertema keamanan: perisai berdenyut, pengguna mengorbit, gembok/kunci melayang */
    .hero .shield { transform-box: fill-box; transform-origin: center; animation: shieldPulse 3s ease-in-out infinite; }
    .hero .ring   { transform-box: fill-box; transform-origin: center; animation: ringPulse 3s ease-out infinite; }
    .hero .ring.r2 { animation-delay: -1.5s; }
    .hero .orbit  { transform-origin: 130px 75px; animation: orbitSpin 16s linear infinite; }
    .hero .orbit .dot { transform-box: fill-box; transform-origin: center; animation: orbitCounter 16s linear infinite; }
    .hero .key    { animation: keyFloat 4.2s ease-in-out infinite; }
    .hero .spark  { transform-box: fill-box; transform-origin: center; animation: sparkle 2.4s ease-in-out infinite; }
    .hero .spark.s2 { animation-delay: -.8s; }
    .hero .spark.s3 { animation-delay: -1.6s; }
    @keyframes shieldPulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.05); } }
    @keyframes ringPulse   { 0% { transform: scale(.7); opacity: .55; } 100% { transform: scale(1.5); opacity: 0; } }
    @keyframes orbitSpin   { to { transform: rotate(360deg); } }
    @keyframes orbitCounter { to { transform: rotate(-360deg); } }
    @keyframes keyFloat    { 0%,100% { transform: translateY(0) rotate(-8deg); } 50% { transform: translateY(-8px) rotate(6deg); } }
    @keyframes sparkle     { 0%,100% { transform: scale(.4); opacity: .2; } 50% { transform: scale(1); opacity: 1; } }
    @media (prefers-reduced-motion: reduce) { .hero .shield, .hero .ring, .hero .orbit, .hero .orbit .dot, .hero .key, .hero .spark { animation: none; } }
    @media (max-width: 760px) { .hero-art { display: none; } .hero-text { max-width: 100%; } .hero { padding: 24px 22px; } }

    /* ===== Pengalih tampilan Kartu / Tabel ===== */
    .view-toggle { display: inline-flex; padding: 3px; gap: 2px; border-radius: 12px; background: var(--bg-soft, #f3f6fd); border: 1px solid var(--border-color); }
    .view-toggle button {
        display: inline-flex; align-items: center; gap: 6px; height: 32px; padding: 0 12px; border: none; border-radius: 9px; cursor: pointer;
        background: transparent; color: var(--text-muted); font-family: inherit; font-size: 12px; font-weight: 600; transition: all .2s;
    }
    .view-toggle button svg { width: 14px; height: 14px; }
    .view-toggle button.on { background: var(--bg-card); color: var(--accent, #2563eb); box-shadow: 0 2px 8px rgba(20,30,80,.10); }

    /* ===== Kartu role ===== */
    .role-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px; padding: 18px; }
    .role-card {
        position: relative; display: flex; flex-direction: column; gap: 12px; overflow: hidden; padding: 18px;
        background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px;
        transition: transform .22s, box-shadow .22s, border-color .22s;
    }
    .role-card::after {
        content: ''; position: absolute; right: -26px; top: -26px; width: 94px; height: 94px; border-radius: 50%;
        background: var(--tint); opacity: .10; transition: transform .3s; pointer-events: none;
    }
    .role-card:hover { transform: translateY(-4px); box-shadow: 0 14px 28px rgba(37,99,235,.14); border-color: var(--tint); }
    .role-card:hover::after { transform: scale(1.6); }
    .role-top { display: flex; align-items: center; gap: 12px; position: relative; z-index: 1; }
    .role-ico {
        width: 44px; height: 44px; border-radius: 13px; display: grid; place-items: center; flex-shrink: 0; color: #fff; background: var(--tint);
        box-shadow: 0 8px 16px color-mix(in srgb, var(--tint) 40%, transparent);
    }
    .role-ico svg { width: 21px; height: 21px; }
    .role-id { min-width: 0; }
    .role-id h3 { font-size: 14.5px; font-weight: 600; color: var(--text-main); line-height: 1.25; overflow-wrap: anywhere; }
    .role-id .code-tag { margin-top: 4px; font-size: 11px; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
    .role-flag {
        margin-left: auto; display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; white-space: nowrap;
        font-size: 10.5px; font-weight: 700; background: rgba(245,158,11,.16); color: #b45309;
    }
    body.dark-mode .role-flag { color: #fbbf24; }
    .role-flag svg { width: 11px; height: 11px; }
    .role-desc {
        position: relative; z-index: 1; margin: 0; font-size: 12.5px; line-height: 1.55; color: var(--text-muted); min-height: 38px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .role-foot { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .avatars { display: flex; align-items: center; }
    .av-mini {
        width: 26px; height: 26px; border-radius: 50%; display: grid; place-items: center; margin-left: -8px; flex-shrink: 0;
        background: var(--tint); color: #fff; border: 2px solid var(--bg-card); font-size: 9.5px; font-weight: 700;
    }
    .av-mini:first-child { margin-left: 0; }
    .av-mini svg { width: 12px; height: 12px; }
    .av-mini.more { background: var(--bg-soft, #f3f6fd); color: var(--text-muted); }
    .av-mini.empty { background: transparent; color: var(--text-muted); border: 1.5px dashed var(--border-color); }
    .role-count { font-size: 12px; color: var(--text-muted); }
    .role-count b { font-size: 15px; font-weight: 700; color: var(--text-main); font-variant-numeric: tabular-nums; }
    .role-bar { position: relative; z-index: 1; height: 5px; border-radius: 10px; background: var(--bg-soft, #f3f6fd); overflow: hidden; }
    .role-bar i { display: block; height: 100%; width: 0; border-radius: 10px; background: var(--tint); transition: width .9s cubic-bezier(.2,.8,.2,1); }

    /* ===== Tampilan tabel ===== */
    .user-meter { display: flex; align-items: center; gap: 10px; min-width: 130px; }
    .user-meter .bar { flex: 1; height: 5px; border-radius: 10px; background: var(--bg-soft, #f3f6fd); overflow: hidden; }
    .user-meter .bar i { display: block; height: 100%; width: 0; border-radius: 10px; background: var(--tint); transition: width .9s cubic-bezier(.2,.8,.2,1); }
    .desc-cell { max-width: 380px; color: var(--text-muted); line-height: 1.5; }
    .code-mono { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
    @media (prefers-reduced-motion: reduce) { .role-card, .role-bar i, .user-meter .bar i { transition: none !important; } }
</style>

@php
    $onPage  = $roles->count();
    $total   = $roles instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $roles->total() : $onPage;
    $users   = (int) $roles->sum('users_count');
    $empty   = $roles->filter(fn($r) => (int) $r->users_count === 0)->count();
    $top     = $roles->sortByDesc('users_count')->first();
    $maxUsr  = max(1, (int) $roles->max('users_count'));

    $palette = ['#2563eb', '#f97316', '#d946ef', '#10b981', '#ef4444', '#8b5cf6'];
    $icons = [
        '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/>',
        '<circle cx="8" cy="15" r="4"/><path d="M11 12l9-9M16 7l3 3"/>',
        '<circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6M17 5a3.5 3.5 0 010 6.5M19 14c2.2.8 3.5 2.6 3.5 5"/>',
        '<path d="M12 2l2.4 2.1 3.2-.3.9 3.1 2.7 1.8-1.2 3 1.2 3-2.7 1.8-.9 3.1-3.2-.3L12 22l-2.4-2.1-3.2.3-.9-3.1L2.8 15.3 4 12.3 2.8 9.3l2.7-1.8.9-3.1 3.2.3z"/><path d="M8.5 12l2.5 2.5L15.5 9.5"/>',
    ];
    $userIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.5-7 8-7s8 3 8 7"/></svg>';
@endphp

<!-- ===== KOTAK PANJANG (BANNER) ===== -->
<div class="hero">
    <div class="hero-body">
        <div class="hero-text">
            <div class="hero-tag"><i></i> <span data-translate>SIM-BU · Super Admin</span></div>
            <h1 data-translate>Kelola Role</h1>
            <p data-translate>Atur peran pengguna dan hak akses sistem. Setiap role menentukan menu dan tindakan yang boleh dilakukan pengguna.</p>
            <a href="{{ route('super-admin.roles.create') }}" class="hero-btn">
                <span data-translate>Tambah Role</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            </a>
        </div>

        <div class="hero-art" aria-hidden="true">
            <svg viewBox="0 0 260 150" fill="none">
                <circle class="ring"    cx="130" cy="75" r="44" stroke="#fff" stroke-width="2" opacity=".5"/>
                <circle class="ring r2" cx="130" cy="75" r="44" stroke="#fff" stroke-width="2" opacity=".5"/>
                <circle cx="130" cy="75" r="62" stroke="rgba(255,255,255,.25)" stroke-width="1.4" stroke-dasharray="3 6"/>

                <!-- pengguna yang mengorbit perisai -->
                <g class="orbit">
                    <g class="dot"><circle cx="130" cy="13" r="10" fill="#34d399"/><circle cx="130" cy="10.5" r="3.2" fill="#fff"/><path d="M123.5 19.5c.8-3 3-4.5 6.5-4.5s5.7 1.5 6.5 4.5" stroke="#fff" stroke-width="2" stroke-linecap="round"/></g>
                    <g class="dot"><circle cx="192" cy="75" r="10" fill="#fbbf24"/><circle cx="192" cy="72.5" r="3.2" fill="#fff"/><path d="M185.5 81.5c.8-3 3-4.5 6.5-4.5s5.7 1.5 6.5 4.5" stroke="#fff" stroke-width="2" stroke-linecap="round"/></g>
                    <g class="dot"><circle cx="68" cy="75" r="10" fill="#f472b6"/><circle cx="68" cy="72.5" r="3.2" fill="#fff"/><path d="M61.5 81.5c.8-3 3-4.5 6.5-4.5s5.7 1.5 6.5 4.5" stroke="#fff" stroke-width="2" opacity="1" stroke-linecap="round"/></g>
                </g>

                <!-- perisai -->
                <g class="shield">
                    <path d="M130 40l30 11v22c0 19-13 31-30 36-17-5-30-17-30-36V51z" fill="#fff"/>
                    <path d="M130 48l22 8v17c0 14-9.5 23-22 27-12.5-4-22-13-22-27V56z" fill="#2563eb"/>
                    <path d="M120 74l8 8 14-16" stroke="#fff" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                </g>

                <!-- kunci melayang -->
                <g class="key">
                    <circle cx="218" cy="34" r="9" fill="#fbbf24"/><circle cx="218" cy="34" r="3.4" fill="#4338ca"/>
                    <path d="M224 40l16 16M234 50l5-5M230 46l4-4" stroke="#fbbf24" stroke-width="4" stroke-linecap="round"/>
                </g>

                <!-- kilau -->
                <path class="spark"    d="M40 36l2.4 5.6L48 44l-5.6 2.4L40 52l-2.4-5.6L32 44l5.6-2.4z" fill="#fff"/>
                <path class="spark s2" d="M222 112l1.8 4.2 4.2 1.8-4.2 1.8-1.8 4.2-1.8-4.2-4.2-1.8 4.2-1.8z" fill="#fff"/>
                <path class="spark s3" d="M52 118l1.8 4.2 4.2 1.8-4.2 1.8-1.8 4.2-1.8-4.2-4.2-1.8 4.2-1.8z" fill="#fff"/>
            </svg>
        </div>
    </div>
</div>

<!-- ===== KARTU STATISTIK ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total role</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6M17 5a3.5 3.5 0 010 6.5M19 14c2.2.8 3.5 2.6 3.5 5"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($users, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Pengguna terdaftar</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12h8"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($empty, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Role belum dipakai</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3 6.5 7 .9-5.1 4.9 1.3 7L12 17.8 5.8 21.3l1.3-7L2 9.4l7-.9z"/></svg></div>
        <div>
            <div class="stat-val" style="font-size:17px">{{ $top && $top->users_count > 0 ? $top->name : '-' }}</div>
            <div class="stat-lbl"><span data-translate>Role terpopuler</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<!-- ===== DAFTAR ROLE ===== -->
<div class="table-card">
    <x-table-toolbar target="roleViews" placeholder="Cari kode, nama, atau deskripsi role..." filter-label="Semua Role"
        :add-url="route('super-admin.roles.create')" add-label="Tambah Role" :report="false">
        <div class="view-toggle" role="group" aria-label="Tampilan">
            <button type="button" data-view="cards" class="on">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                <span data-translate>Kartu</span>
            </button>
            <button type="button" data-view="table">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                <span data-translate>Tabel</span>
            </button>
        </div>
    </x-table-toolbar>

    <div id="roleViews">
        {{-- ===== tampilan kartu ===== --}}
        <div class="role-grid" id="viewCards">
            @foreach($roles as $r)
                @php
                    $idx   = abs(crc32((string) $r->code)) % count($palette);
                    $tint  = $palette[$idx];
                    $cnt   = (int) $r->users_count;
                    $pct   = (int) round($cnt / $maxUsr * 100);
                    $desc  = $r->description ?: null;
                    $full  = in_array(strtolower((string) $r->code), ['super_admin', 'superadmin'], true);
                    $fv    = $cnt > 0 ? 'berpengguna' : 'kosong';
                    $fl    = $cnt > 0 ? 'Berpengguna' : 'Belum ada pengguna';
                @endphp
                <article class="role-card" style="--tint: {{ $tint }}" data-filter="{{ $fv }}" data-filter-label="{{ $fl }}"
                         data-search="{{ strtolower($r->code . ' ' . $r->name . ' ' . ($r->description ?? '')) }}">
                    <div class="role-top">
                        <div class="role-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$idx % count($icons)] !!}</svg></div>
                        <div class="role-id">
                            <h3>{{ $r->name }}</h3>
                            <code class="code-tag">{{ $r->code }}</code>
                        </div>
                        @if($full)
                            <span class="role-flag"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3 6.5 7 .9-5.1 4.9 1.3 7L12 17.8 5.8 21.3l1.3-7L2 9.4l7-.9z"/></svg><span data-translate>Akses Penuh</span></span>
                        @endif
                    </div>

                    <p class="role-desc" @if(!$desc) style="font-style:italic;opacity:.7" @endif>{{ $desc ?? 'Belum ada deskripsi.' }}</p>

                    <div class="role-foot">
                        <div class="avatars">
                            @if($cnt === 0)
                                <span class="av-mini empty">0</span>
                            @else
                                @for($k = 0; $k < min(3, $cnt); $k++)
                                    <span class="av-mini">{!! $userIcon !!}</span>
                                @endfor
                                @if($cnt > 3)
                                    <span class="av-mini more" data-convert="digit">+{{ $cnt - 3 }}</span>
                                @endif
                            @endif
                        </div>
                        <div class="role-count"><b data-convert="digit">{{ number_format($cnt, 0, ',', '.') }}</b> <span data-translate>pengguna</span></div>
                    </div>
                    <div class="role-bar"><i data-w="{{ $pct }}"></i></div>
                </article>
            @endforeach
        </div>

        {{-- ===== tampilan tabel ===== --}}
        <div class="table-wrap" id="viewTable" hidden>
            <table class="data-table">
                <thead>
                    <tr>
                        <th data-translate>Kode</th>
                        <th data-translate>Nama</th>
                        <th data-translate>Deskripsi</th>
                        <th data-translate>Jumlah User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $r)
                        @php
                            $idx = abs(crc32((string) $r->code)) % count($palette);
                            $cnt = (int) $r->users_count;
                            $fv  = $cnt > 0 ? 'berpengguna' : 'kosong';
                            $fl  = $cnt > 0 ? 'Berpengguna' : 'Belum ada pengguna';
                        @endphp
                        <tr style="--tint: {{ $palette[$idx] }}" data-filter="{{ $fv }}" data-filter-label="{{ $fl }}"
                            data-search="{{ strtolower($r->code . ' ' . $r->name . ' ' . ($r->description ?? '')) }}">
                            <td><code class="code-tag code-mono">{{ $r->code }}</code></td>
                            <td><div class="cell-main" style="min-width:150px"><span class="cell-title">{{ $r->name }}</span></div></td>
                            <td class="desc-cell">{{ $r->description ?? '-' }}</td>
                            <td>
                                <div class="user-meter">
                                    <span class="badge {{ $cnt > 0 ? 'badge-success' : 'badge-muted' }}"><span data-convert="digit">{{ number_format($cnt, 0, ',', '.') }}</span></span>
                                    <div class="bar"><i data-w="{{ (int) round($cnt / $maxUsr * 100) }}"></i></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="empty-state" id="emptyServer" @if($onPage > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/></svg></div>
        <h3 data-translate>Belum ada role.</h3>
        <p data-translate>Klik "Tambah Role" untuk membuat role pertama.</p>
    </div>
    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Role tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter yang lain.</p>
    </div>

    @if($roles instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <x-table-foot :paginator="$roles" noun="role" />
    @endif
</div>

<script>
(function () {
    var cards = document.getElementById('viewCards');
    var table = document.getElementById('viewTable');
    var btns  = document.querySelectorAll('.view-toggle button');
    var KEY   = 'simbu.roles.view';

    function setView(v) {
        var isCards = v !== 'table';
        cards.hidden = !isCards;
        table.hidden = isCards;
        btns.forEach(function (b) { b.classList.toggle('on', b.getAttribute('data-view') === (isCards ? 'cards' : 'table')); });
        try { localStorage.setItem(KEY, isCards ? 'cards' : 'table'); } catch (e) {}
        fillBars();
    }

    /* isi bar dengan animasi (hanya pada tampilan yang sedang terlihat) */
    function fillBars() {
        requestAnimationFrame(function () {
            document.querySelectorAll('#roleViews i[data-w]').forEach(function (el) {
                if (el.offsetParent !== null) el.style.width = el.getAttribute('data-w') + '%';
            });
        });
    }

    btns.forEach(function (b) { b.addEventListener('click', function () { setView(b.getAttribute('data-view')); }); });

    var saved = 'cards';
    try { saved = localStorage.getItem(KEY) || 'cards'; } catch (e) {}
    setView(saved);
})();
</script>
@endsection
