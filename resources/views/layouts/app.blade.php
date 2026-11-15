<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --bg-body: #f6f7fb; --bg-card: #ffffff; --bg-topbar: #ffffff; --bg-hover: #f3f5fb;
    --bg-soft: #f3f6fd; --text-main: #111827; --text-muted: #6b7280; --border-color: #e6e9f2;
    --table-header: #f9fafb; --accent: #2563eb; --accent-hover: #1d4ed8; --accent-soft: #eef3ff;
    --shadow-pop: 0 18px 50px rgba(20, 30, 80, .16);
}
body.dark-mode {
    --bg-body: #0f172a; --bg-card: #1e293b; --bg-topbar: #1e293b; --bg-hover: #334155;
    --bg-soft: rgba(255, 255, 255, .06); --text-main: #f8fafc; --text-muted: #94a3b8;
    --border-color: #334155; --table-header: #0f172a; --accent: #60a5fa; --accent-hover: #93c5fd;
    --accent-soft: rgba(59, 130, 246, .16); --shadow-pop: 0 18px 50px rgba(0, 0, 0, .45);
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Poppins', sans-serif; background: var(--bg-body); color: var(--text-main); font-size: 14px; transition: background 0.3s, color 0.3s; }
button, input, select, textarea { font-family: inherit; }
.wrap { display: flex; min-height: 100vh; }

.main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
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

.lang-box { position: relative; }
.lang-menu {
    position: absolute; right: 0; top: calc(100% + 10px); width: 150px; padding: 6px; z-index: 70;
    background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; box-shadow: var(--shadow-pop);
    opacity: 0; transform: translateY(-6px) scale(.98); transform-origin: top right; pointer-events: none; transition: opacity .18s, transform .18s;
}
.lang-box.open .lang-menu { opacity: 1; transform: none; pointer-events: auto; }
.lang-item {
    display: flex; align-items: center; gap: 10px; width: 100%; padding: 8px 10px; border: none; border-radius: 8px;
    background: none; cursor: pointer; font-size: 13px; font-weight: 500; color: var(--text-main); text-align: left;
}
.lang-item:hover { background: var(--bg-hover); color: var(--accent); }

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
.user-name { font-size: 13px; font-weight: 600; color: var(--text-main); max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-role { font-size: 10.5px; font-weight: 500; color: var(--text-muted); max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-chev { width: 14px; height: 14px; color: var(--text-muted); transition: transform .25s; flex-shrink: 0; }
.user-box.open .user-chev { transform: rotate(180deg); color: var(--accent); }

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

.content { padding: 24px 28px; flex: 1; }
.footer { text-align: center; padding: 16px; color: var(--text-muted); font-size: 11.5px; border-top: 1px solid var(--border-color); }

.card { background: var(--bg-card); border-radius: 14px; padding: 20px; border: 1px solid var(--border-color); margin-bottom: 16px; }
table { width: 100%; border-collapse: collapse; background: var(--bg-card); font-size: 13.5px; }
th { padding: 10px 12px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); background: var(--table-header); }
td { padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-main); }
tr:hover td { background: var(--bg-hover); }
.btn { display: inline-block; padding: 8px 14px; background: #2563eb; color: #fff; border: none; border-radius: 10px; cursor: pointer; text-decoration: none; font-size: 13px; font-weight: 500; }

/* === RTL SUPPORT === */
body[dir="rtl"] { direction: rtl; }
body[dir="rtl"] .topbar-left { text-align: right; }
body[dir="rtl"] .lang-menu, body[dir="rtl"] .user-menu { right: auto; left: 0; transform-origin: top left; }
body[dir="rtl"] th, body[dir="rtl"] td { text-align: right; }
</style>
</head>
<body>
<script>
    try { if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode'); } catch (e) {}
    try { let lang = localStorage.getItem('lang') || 'id'; document.body.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr'); } catch (e) {}
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
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
                <div class="topbar-date" id="tb-date">SIM-BU</div>
            </div>

            <div class="topbar-right">
                <!-- BAHASA -->
                <div class="lang-box" id="langBox">
                    <button type="button" class="btn-icon" id="langBtn" title="Ubah Bahasa">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
                    </button>
                    <div class="lang-menu" role="menu">
                        <button class="lang-item" onclick="setLanguage('id')">🇮🇩 Indonesia</button>
                        <button class="lang-item" onclick="setLanguage('en')">🇬🇧 English</button>
                        <button class="lang-item" onclick="setLanguage('ar')">🇸🇦 العربية</button>
                        <button class="lang-item" onclick="setLanguage('zh')">🇨🇳 中文 (China)</button>
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
                        <span class="avatar">{{ $initials }}</span>
                        <span class="user-text">
                            <div class="user-name">{{ $authUser->name }}</div>
                            <div class="user-role">{{ $roleNames->join(', ') ?: 'Tanpa role' }}</div>
                        </span>
                        <svg class="user-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="user-menu" role="menu">
                        <div class="menu-head">
                            <span class="avatar lg">{{ $initials }}</span>
                            <div class="who"><strong>{{ $authUser->name }}</strong><span>{{ $authUser->email }}</span></div>
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
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">SIM-BU v1.0 &copy; {{ date('Y') }} Pemda · Bagian Umum</div>
    </div>
</div>
<script>
    function toggleTheme() { var dark = document.body.classList.toggle('dark-mode'); try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {} }
    var langBox = document.getElementById('langBox');
    document.getElementById('langBtn').addEventListener('click', function(e) { e.stopPropagation(); langBox.classList.toggle('open'); });
    document.addEventListener('click', function(e) { if (!langBox.contains(e.target)) langBox.classList.remove('open'); });
    function setLanguage(lang) { localStorage.setItem('lang', lang); document.body.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr'); langBox.classList.remove('open'); window.dispatchEvent(new Event('languageChanged')); }
    (function () { var el = document.getElementById('tb-date'); try { el.textContent = new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }); } catch (e) {} })();
    (function () { var box = document.getElementById('userBox'); var btn = document.getElementById('userBtn'); btn.addEventListener('click', function (e) { e.stopPropagation(); box.classList.toggle('open'); }); document.addEventListener('click', function (e) { if (!box.contains(e.target)) box.classList.remove('open'); }); })();
</script>
</body>
</html>
