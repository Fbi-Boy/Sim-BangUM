<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: -apple-system, system-ui, sans-serif; background: #f9fafb; color: #111827; font-size: 14px; }
.wrap { display: flex; min-height: 100vh; }

/* === SIDEBAR === */
.sidebar { width: 248px; background: #ffffff; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; overflow-y: auto; }
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 2px; }
.sidebar-brand { display: flex; align-items: center; gap: 10px; padding: 20px 20px 24px; border-bottom: 1px solid #f3f4f6; }
.sidebar-brand-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #1e40af, #3b82f6); color: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; letter-spacing: .5px; }
.sidebar-brand-title { font-weight: 700; font-size: 15px; color: #111827; letter-spacing: .3px; }
.sidebar-brand-sub { font-size: 11px; color: #9ca3af; margin-top: 1px; }
.sidebar-nav { flex: 1; padding: 12px 8px; }
.sidebar-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 6px; color: #4b5563; text-decoration: none; font-size: 13.5px; font-weight: 500; margin-bottom: 2px; transition: all .15s; }
.sidebar-item:hover { background: #f3f4f6; color: #111827; }
.sidebar-item.active { background: #eff6ff; color: #1e40af; font-weight: 600; }
.sidebar-item.active .sidebar-icon { color: #1e40af; }
.sidebar-icon { width: 18px; height: 18px; flex-shrink: 0; color: #9ca3af; }
.sidebar-item:hover .sidebar-icon { color: #4b5563; }
.sidebar-section { font-size: 10.5px; font-weight: 600; color: #9ca3af; letter-spacing: .8px; padding: 16px 12px 6px; text-transform: uppercase; }

/* === MAIN === */
.main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
.topbar { background: #fff; padding: 12px 28px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; height: 56px; }
.topbar-title { font-weight: 600; font-size: 15px; color: #111827; }
.topbar-user { display: flex; align-items: center; gap: 12px; font-size: 13px; }
.topbar-user-name { color: #6b7280; }
.topbar-user-name strong { color: #111827; font-weight: 600; }
.btn-logout { padding: 6px 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; color: #6b7280; font-size: 12px; cursor: pointer; font-weight: 500; transition: all .15s; }
.btn-logout:hover { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }
.content { padding: 24px 28px; flex: 1; }
.footer { text-align: center; padding: 16px; color: #9ca3af; font-size: 11.5px; border-top: 1px solid #f3f4f6; }

/* === COMPONENTS === */
.card { background: #fff; border-radius: 10px; padding: 20px; border: 1px solid #e5e7eb; margin-bottom: 16px; }
.card h2 { font-size: 18px; margin-bottom: 12px; color: #111827; }
.card h3 { font-size: 15px; margin-bottom: 12px; color: #111827; }
table { width: 100%; border-collapse: collapse; background: #fff; font-size: 13.5px; }
th { padding: 10px 12px; text-align: left; font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: .3px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #f3f4f6; color: #374151; }
tr:hover td { background: #fafbfc; }
.btn { display: inline-block; padding: 8px 14px; background: #1e40af; color: #fff; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 13px; font-weight: 500; transition: background .15s; }
.btn:hover { background: #1e3a8a; }
.btn-sm { padding: 5px 10px; font-size: 12px; }
.btn-danger { background: #dc2626; }
.btn-danger:hover { background: #b91c1c; }
.btn-secondary { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
.btn-secondary:hover { background: #f9fafb; }
input, select, textarea { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13.5px; width: 100%; max-width: 480px; font-family: inherit; transition: all .15s; }
input:focus, select:focus, textarea:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }
label { font-size: 12.5px; font-weight: 500; color: #374151; margin-bottom: 4px; display: block; }
.alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 13.5px; }
.alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; }
.grid a { background: #fff; padding: 18px; border-radius: 10px; text-decoration: none; color: #111827; border: 1px solid #e5e7eb; transition: all .15s; }
.grid a:hover { border-color: #93c5fd; box-shadow: 0 4px 12px rgba(30,64,175,.08); transform: translateY(-1px); }
.grid a strong { display: block; margin-bottom: 4px; color: #1e40af; font-size: 14px; }
.grid a small { color: #6b7280; font-size: 12.5px; }
</style>
</head>
<body>
<div class="wrap">
    @include('layouts.sidebar')
    <div class="main">
        <div class="topbar">
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-user">
                <span class="topbar-user-name"><strong>{{ auth()->user()->name }}</strong> · {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'no role' }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf
                    <button class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
        <div class="content">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif
            @yield('content')
        </div>
        <div class="footer">SIM-BU v1.0 &copy; {{ date('Y') }} Bagian Umum Setda</div>
    </div>
</div>
</body>
</html>
