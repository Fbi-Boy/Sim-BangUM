<!DOCTYPE html>
<html lang="id">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.ui')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;500;600;700&family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg-body: #f6f7fb; --bg-card: #ffffff; --bg-topbar: #ffffff; --bg-hover: #f3f5fb;
    --bg-soft: #f3f6fd; --text-main: #111827; --text-muted: #6b7280; --border-color: #e6e9f2;
    --table-header: #f9fafb; --accent: #2563eb; --accent-hover: #1d4ed8; --accent-soft: #eef3ff;
    --shadow-pop: 0 18px 50px rgba(20, 30, 80, .16);
    --font-main: 'Poppins', sans-serif;
}
body.dark-mode {
    --bg-body: #0f172a; --bg-card: #1e293b; --bg-topbar: #1e293b; --bg-hover: #334155;
    --bg-soft: rgba(255, 255, 255, .06); --text-main: #f8fafc; --text-muted: #94a3b8;
    --border-color: #334155; --table-header: #0f172a; --accent: #60a5fa; --accent-hover: #93c5fd;
    --accent-soft: rgba(59, 130, 246, .16); --shadow-pop: 0 18px 50px rgba(0, 0, 0, .45);
}
/* Font menyesuaikan bahasa */
body[data-lang="ar"] { --font-main: 'Noto Sans Arabic', 'Poppins', sans-serif; }
body[data-lang="zh"] { --font-main: 'Noto Sans SC', 'Poppins', sans-serif; }

* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: var(--font-main); background: var(--bg-body); color: var(--text-main); font-size: 14px; transition: background 0.3s, color 0.3s; }
button, input, select, textarea { font-family: inherit; }
.wrap { display: flex; min-height: 100vh; }

/* .main = kolom: topbar -> content (tumbuh) -> footer (selalu menempel di dasar) */
.main { flex: 1; display: flex; flex-direction: column; min-width: 0; min-height: 100vh; }
.topbar {
    position: sticky; top: 0; z-index: 50; height: 68px; padding: 0 28px; gap: 16px;
    display: flex; justify-content: space-between; align-items: center;
    background: var(--bg-topbar); border-bottom: 1px solid var(--border-color);
    transition: background .3s, border-color .3s;
}
.topbar-left { min-width: 0; }
.topbar-title { font-size: 18px; font-weight: 700; letter-spacing: -.2px; line-height: 1.2; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.topbar-date { display: flex; align-items: center; gap: 7px; margin-top: 3px; font-size: 11.5px; font-weight: 500; color: var(--text-muted); }
.topbar-date::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.18); }

.topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

.btn-icon {
    width: 42px; height: 42px; border-radius: 12px; border: 1px solid transparent; background: var(--bg-soft);
    color: var(--text-main); display: grid; place-items: center; cursor: pointer; transition: background .2s, color .2s, transform .2s;
    position: relative;
}
.btn-icon:hover { background: var(--accent-soft); color: var(--accent); }
.btn-icon:active { transform: scale(.94); }
.btn-icon svg { width: 19px; height: 19px; }
.btn-icon .i-sun { display: none; }
body.dark-mode .btn-icon .i-sun { display: block; }
body.dark-mode .btn-icon .i-moon { display: none; }

/* ===== TOMBOL BAHASA ===== */
.lang-box { position: relative; }
.lang-btn {
    height: 42px; padding: 0 12px 0 10px; border-radius: 12px; border: 1px solid transparent; background: var(--bg-soft);
    color: var(--text-main); display: flex; align-items: center; gap: 8px; cursor: pointer;
    transition: background .2s, color .2s, border-color .2s, transform .2s;
}
.lang-btn:hover, .lang-box.open .lang-btn { background: var(--accent-soft); color: var(--accent); }
.lang-box.open .lang-btn { border-color: var(--accent); }
.lang-btn:active { transform: scale(.96); }
.lang-btn svg { width: 18px; height: 18px; }
.lang-btn .lang-cur { font-size: 12.5px; font-weight: 700; letter-spacing: .6px; }

.lang-menu {
    position: absolute; right: 0; top: calc(100% + 10px); width: 190px; padding: 6px; z-index: 70;
    background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; box-shadow: var(--shadow-pop);
    opacity: 0; transform: translateY(-6px) scale(.98); transform-origin: top right; pointer-events: none; transition: opacity .18s, transform .18s;
}
.lang-box.open .lang-menu { opacity: 1; transform: none; pointer-events: auto; }
.lang-item {
    display: flex; align-items: center; gap: 10px; width: 100%; padding: 8px 10px; border: none; border-radius: 8px;
    background: none; cursor: pointer; font-size: 13px; font-weight: 500; color: var(--text-main); text-align: left;
}
.lang-item:hover { background: var(--bg-hover); color: var(--accent); }
.lang-item.active { background: var(--accent-soft); color: var(--accent); font-weight: 600; }
.lang-item .lang-name { flex: 1; }
.lang-item .lang-check { width: 15px; height: 15px; opacity: 0; color: var(--accent); }
.lang-item.active .lang-check { opacity: 1; }
.lang-code {
    min-width: 34px; padding: 3px 0; border-radius: 7px; text-align: center; color: #fff;
    font-size: 10.5px; font-weight: 700; letter-spacing: .6px; flex-shrink: 0;
}
.lang-code.c-id { background: #dc2626; }
.lang-code.c-en { background: #1d4ed8; }
.lang-code.c-ar { background: #059669; }
.lang-code.c-zh { background: #d97706; }

.user-box { position: relative; }
.user-chip {
    display: flex; align-items: center; gap: 10px; padding: 4px 12px 4px 4px; border-radius: 14px;
    background: var(--bg-soft); border: 1px solid transparent; cursor: pointer; color: var(--text-main); text-align: left;
    transition: background .2s, border-color .2s, box-shadow .2s;
}
.user-chip:hover, .user-box.open .user-chip { background: var(--accent-soft); }
.user-box.open .user-chip { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }
.avatar {
    width: 36px; height: 36px; border-radius: 11px; flex-shrink: 0; display: grid; place-items: center;
    background: linear-gradient(135deg, #2433d6 0%, #4f6bff 60%, #6aa0ff 100%); color: #fff;
    font-size: 12.5px; font-weight: 700; letter-spacing: .5px; box-shadow: 0 6px 14px rgba(47,63,224,.28);
}
.avatar.lg { width: 44px; height: 44px; border-radius: 13px; font-size: 14px; }
.user-text { min-width: 0; line-height: 1.25; }
.user-name { font-size: 13px; font-weight: 600; color: var(--text-main); max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-role {
    font-size: 10.5px;
    font-weight: 500;
    color: var(--text-muted);
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-chev { width: 14px; height: 14px; color: var(--text-muted); transition: transform .25s; flex-shrink: 0; }
.user-box.open .user-chev { transform: rotate(180deg); color: var(--accent); }

/* RTL: beri ruang lebih untuk teks Arab (biasanya lebih lebar) */
body[dir="rtl"] .user-text { max-width: 200px; }
body[dir="rtl"] .user-name { max-width: 200px; }
body[dir="rtl"] .user-role { max-width: 200px; }

.user-menu {
    position: absolute; right: 0; top: calc(100% + 10px); width: 270px; padding: 8px; z-index: 70;
    background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; box-shadow: var(--shadow-pop);
    opacity: 0; transform: translateY(-6px) scale(.98); transform-origin: top right; pointer-events: none; transition: opacity .18s, transform .18s;
}
.user-box.open .user-menu { opacity: 1; transform: none; pointer-events: auto; }
.menu-head { display: flex; align-items: center; gap: 12px; padding: 10px 10px 12px; }
.menu-head .who { min-width: 0; }
.menu-head .who strong { display: block; font-size: 13.5px; font-weight: 600; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.menu-head .who span { display: block; font-size: 11.5px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.role-chips { display: flex; flex-wrap: wrap; gap: 6px; padding: 0 10px 12px; }
.role-chip { font-size: 10.5px; font-weight: 600; padding: 3px 9px; border-radius: 20px; background: var(--accent-soft); color: var(--accent); }
.menu-sep { height: 1px; background: var(--border-color); margin: 2px 4px 6px; }
.menu-logout {
    width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: none; border-radius: 11px;
    background: none; cursor: pointer; font-size: 13px; font-weight: 500; color: var(--text-muted); text-align: left; transition: background .2s, color .2s;
}
.menu-logout:hover { background: #fee2e2; color: #b91c1c; }
body.dark-mode .menu-logout:hover { background: rgba(239,68,68,.16); color: #fca5a5; }
.menu-logout svg { width: 18px; height: 18px; }

/* konten tumbuh mengisi ruang agar footer selalu di dasar; min-width:0 mencegah tabel lebar mendorong footer keluar */
.content { padding: 24px 28px; flex: 1 0 auto; min-width: 0; max-width: 100%; transition: opacity .2s; }

.card { background: var(--bg-card); border-radius: 14px; padding: 20px; border: 1px solid var(--border-color); margin-bottom: 16px; }
table { width: 100%; border-collapse: collapse; background: var(--bg-card); font-size: 13.5px; }
th { padding: 10px 12px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); background: var(--table-header); }
td { padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-main); }
tr:hover td { background: var(--bg-hover); }
.btn { display: inline-block; padding: 8px 14px; background: #2563eb; color: #fff; border: none; border-radius: 10px; cursor: pointer; text-decoration: none; font-size: 13px; font-weight: 500; }

/* Loading bar di atas */
#nprogress-bar {
    position: fixed; top: 0; left: 0; height: 3px; width: 0%;
    background: var(--accent); z-index: 9999; transition: width 0.3s ease;
}

/* === RTL SUPPORT === */
body[dir="rtl"] { direction: rtl; text-align: right; }
body[dir="rtl"] #nprogress-bar { left: auto; right: 0; }
body[dir="rtl"] .topbar-left { text-align: right; }
body[dir="rtl"] .lang-menu, body[dir="rtl"] .user-menu { right: auto; left: 0; transform-origin: top left; }
body[dir="rtl"] .lang-item, body[dir="rtl"] .menu-logout, body[dir="rtl"] .user-chip { text-align: right; }
body[dir="rtl"] .user-chip { padding: 4px 4px 4px 12px; }
body[dir="rtl"] th, body[dir="rtl"] td { text-align: right; }
body[dir="rtl"] input, body[dir="rtl"] select, body[dir="rtl"] textarea { text-align: right; }
body[dir="rtl"] input[type="email"], body[dir="rtl"] input[type="url"], body[dir="rtl"] input[type="number"] { direction: ltr; text-align: right; }
body[dir="rtl"] .sidebar { border-right: none; border-left: 1px solid var(--border-color); }

/* --- Penguatan RTL: arah dibuat eksplisit agar sidebar PASTI pindah ke kanan --- */
html[dir="rtl"], html[dir="rtl"] body { direction: rtl; }
html[dir="ltr"], html[dir="ltr"] body { direction: ltr; }
body[dir="rtl"] .wrap, body[dir="rtl"] .main, body[dir="rtl"] .topbar, body[dir="rtl"] .content, body[dir="rtl"] .footer { direction: rtl; }
body[dir="ltr"] .wrap, body[dir="ltr"] .main, body[dir="ltr"] .topbar, body[dir="ltr"] .content, body[dir="ltr"] .footer { direction: ltr; }
body[dir="rtl"] .menu-head, body[dir="rtl"] .role-chips, body[dir="rtl"] .menu-logout { direction: rtl; }
/* panah pagination ikut berbalik arah */
body[dir="rtl"] .sbp-list svg { transform: scaleX(-1); }
/* data berhuruf Latin (email, kode, IP, nomor) tetap terbaca kiri-ke-kanan di tengah teks Arab */
body[dir="rtl"] input[type="email"], body[dir="rtl"] input[type="url"], body[dir="rtl"] input[type="tel"],
body[dir="rtl"] a[href^="mailto:"], body[dir="rtl"] code,
body[dir="rtl"] .rm-code, body[dir="rtl"] .ev-no, body[dir="rtl"] .tv-no, body[dir="rtl"] .pt-no,
body[dir="rtl"] .ku-mail > span, body[dir="rtl"] .al-ip, body[dir="rtl"] .al-act, body[dir="rtl"] .al-time b {
    direction: ltr; unicode-bidi: isolate;
}
/* (gaya footer ada di layouts/footer.blade.php) */
</style>
</head>
<body>
<div id="nprogress-bar"></div>

<script>
    // Terapkan tema, bahasa & arah tulisan sedini mungkin (mencegah kedipan)
    try { if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode'); } catch (e) {}
    try {
        var _l = localStorage.getItem('lang') || 'id';
        document.body.setAttribute('dir', _l === 'ar' ? 'rtl' : 'ltr');
        document.body.setAttribute('data-lang', _l);
        document.documentElement.setAttribute('lang', _l === 'zh' ? 'zh-CN' : _l);
        document.documentElement.setAttribute('dir', _l === 'ar' ? 'rtl' : 'ltr');
    } catch (e) {}
</script>

@php
    $authUser = auth()->user();
    $initials = mb_strtoupper(collect(preg_split('/\s+/', trim($authUser->name)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));
    $roleNames = $authUser->roles->pluck('name')->map(fn($r) => \Illuminate\Support\Str::headline($r));
@endphp

<div class="wrap">
    @include('layouts.sidebar')
    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <div class="topbar-title" id="topbar-title">@yield('title', 'Dashboard')</div>
                <div class="topbar-date" id="tb-date" data-no-i18n>SIM-BU</div>
            </div>

            <div class="topbar-right">
                <!-- BAHASA -->
                <div class="lang-box" id="langBox">
                    <button type="button" class="lang-btn" id="langBtn" title="Ubah Bahasa">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
                        <span class="lang-cur" id="langCur" data-no-i18n>ID</span>
                    </button>
                    <div class="lang-menu" role="menu" data-no-i18n>
                        <button type="button" class="lang-item" data-lang="id" onclick="setLanguage('id')">
                            <span class="lang-code c-id">ID</span><span class="lang-name">Indonesia</span>
                            <svg class="lang-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                        <button type="button" class="lang-item" data-lang="en" onclick="setLanguage('en')">
                            <span class="lang-code c-en">EN</span><span class="lang-name">English</span>
                            <svg class="lang-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                        <button type="button" class="lang-item" data-lang="ar" onclick="setLanguage('ar')">
                            <span class="lang-code c-ar">AR</span><span class="lang-name">العربية</span>
                            <svg class="lang-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                        <button type="button" class="lang-item" data-lang="zh" onclick="setLanguage('zh')">
                            <span class="lang-code c-zh">ZH</span><span class="lang-name">中文 (China)</span>
                            <svg class="lang-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                    </div>
                </div>

                <!-- TEMA -->
                <button type="button" class="btn-icon" onclick="toggleTheme()" id="theme-btn" title="Ubah tema">
                    <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                    <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                </button>

                <!-- USER -->
                <div class="user-box" id="userBox">
                    <button type="button" class="user-chip" id="userBtn">
                        <span class="avatar" data-no-i18n>{{ $initials }}</span>
                        <span class="user-text">
                            <div class="user-name" data-no-i18n>{{ $authUser->name }}</div>
                            <div class="user-role">{{ $roleNames->join(', ') ?: 'Tanpa role' }}</div>
                        </span>
                        <svg class="user-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="user-menu" role="menu">
                        <div class="menu-head">
                            <span class="avatar lg" data-no-i18n>{{ $initials }}</span>
                            <div class="who" data-no-i18n><strong>{{ $authUser->name }}</strong><span>{{ $authUser->email }}</span></div>
                        </div>
                        <div class="role-chips">
                            @forelse($roleNames as $rn) <span class="role-chip">{{ $rn }}</span> @empty <span class="role-chip">Tanpa role</span> @endforelse
                        </div>
                        <div class="menu-sep"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="menu-logout" role="menuitem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- KONTEN UTAMA -->
        <div class="content" id="app-content">
            @yield('content')
        </div>

        <!-- FOOTER (file terpisah: resources/views/layouts/footer.blade.php) -->
        @include('layouts.footer')
    </div>
</div>

<!-- PANGGIL FILE JS DI SINI -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
