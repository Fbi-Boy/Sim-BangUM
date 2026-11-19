@extends('layouts.app')
@section('title','Audit Log')
@section('content')
@php
    $col = collect(method_exists($logs, 'getCollection') ? $logs->getCollection() : $logs);
    $st  = isset($stats) && is_array($stats) ? $stats : [];

    /* Statistik kartu. Jika controller mengirim $stats (['total'=>,'today'=>,'actors'=>,'actions'=>]) dipakai apa adanya;
       jika tidak, dihitung dari data pada halaman yang sedang tampil. */
    $total   = $st['total']   ?? (method_exists($logs, 'total') ? $logs->total() : $col->count());
    $today   = $st['today']   ?? $col->filter(fn($l) => $l->created_at->isToday())->count();
    $actors  = $st['actors']  ?? $col->map(fn($l) => optional($l->actor)->id ?? 'system')->unique()->count();
    $actions = $st['actions'] ?? $col->pluck('action')->unique()->count();

    /* ikon (path SVG 24x24) */
    $ico = [
        'plus'   => 'M12 5v14M5 12h14',
        'check'  => 'M20 6 9 17l-5-5',
        'pencil' => 'M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z',
        'trash'  => 'M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2',
        'x'      => 'M18 6 6 18M6 6l12 12',
        'login'  => 'M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3',
        'logout' => 'M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9',
        'dot'    => 'M12 12h.01',
    ];
    /* nada warna + ikon berdasarkan isi nama aksi */
    $tone = function ($a) {
        $a = strtolower((string) $a);
        $has = fn(array $w) => collect($w)->contains(fn($x) => str_contains($a, $x));
        return match (true) {
            $has(['delete','destroy','remove','hapus'])      => ['bad',    'trash'],
            $has(['reject','tolak','fail','gagal'])          => ['bad',    'x'],
            $has(['create','store','add','tambah','upload']) => ['ok',     'plus'],
            $has(['approve','setuju','verify'])              => ['ok',     'check'],
            $has(['update','edit','ubah','change','assign']) => ['info',   'pencil'],
            $has(['logout','keluar'])                        => ['slate',  'logout'],
            $has(['login','masuk'])                          => ['teal',   'login'],
            default                                          => ['violet', 'dot'],
        };
    };

    $avatarTones = ['a1','a2','a3','a4','a5','a6'];
    $initialsOf  = fn($n) => mb_strtoupper(collect(preg_split('/\s+/', trim((string) $n)))->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));

    /* baris "terminal" di banner: diambil dari log nyata terbaru */
    $stream = $col->take(7)->map(fn($l) => [
        't' => $l->created_at->format('H:i:s'),
        'u' => optional($l->actor)->name ?? 'system',
        'a' => (string) $l->action,
        'c' => $tone($l->action)[0],
    ])->values();

    /* kelompok per hari untuk tampilan timeline */
    $groups = $col->groupBy(fn($l) => $l->created_at->format('Y-m-d'));
    $dayLabel = function ($key) {
        $d = \Carbon\Carbon::parse($key);
        return $d->isToday() ? 'Hari ini' : ($d->isYesterday() ? 'Kemarin' : $d->format('d-m-Y'));
    };

    $fAction = request('action');
    $fUser   = request('user');
    $hasFilter = filled($fAction) || filled($fUser);
    $quick = ['login','logout','create','update','delete'];
@endphp

<style>
    /* ===== Modul Administrasi · Audit Log (tema forensik / cyan) ===== */
    .al { --mod: #0891b2; --mod-2: #22d3ee; --mod-soft: rgba(8,145,178,.12); }
    body.dark-mode .al { --mod: #22d3ee; --mod-soft: rgba(34,211,238,.14); }
    .al [hidden] { display: none !important; }

    @property --pn { syntax: '<integer>'; initial-value: 0; inherits: false; }
    .al-num { --pn: var(--to); counter-reset: aln var(--pn); animation: alcount 1.1s ease-out both; }
    .al-num::after { content: counter(aln); }
    @keyframes alcount { from { --pn: 0; } to { --pn: var(--to); } }
    .al-sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }

    /* ---------- HERO ---------- */
    .al-hero {
        position: relative; overflow: hidden; border-radius: 20px; padding: 26px 28px 48px; min-height: 190px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap; color: #fff;
        background: linear-gradient(140deg, #0b1220 0%, #0e3a52 52%, #0891b2 100%);
        box-shadow: 0 16px 38px rgba(8,145,178,.28);
    }
    .al-grid { position: absolute; inset: 0; opacity: .5; background-image: linear-gradient(rgba(255,255,255,.07) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.07) 1px, transparent 1px); background-size: 28px 28px; }
    .al-glow { position: absolute; inset-inline-start: -60px; top: -80px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(34,211,238,.35), rgba(34,211,238,0) 70%); animation: alpulse 5s ease-in-out infinite; }
    @keyframes alpulse { 0%,100% { transform: scale(1); opacity: .7; } 50% { transform: scale(1.12); opacity: 1; } }

    .al-ecg { position: absolute; inset-inline: 0; bottom: 0; height: 46px; opacity: .85; }
    body[dir="rtl"] .al-ecg { transform: scaleX(-1); }
    .al-ecg path { fill: none; stroke: #67e8f9; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; stroke-dasharray: 600; stroke-dashoffset: 600; animation: alecg 4.2s linear infinite; filter: drop-shadow(0 0 4px rgba(103,232,249,.8)); }
    .al-ecg .base { stroke: rgba(255,255,255,.14); stroke-dasharray: none; animation: none; filter: none; stroke-width: 1; }
    @keyframes alecg { 0% { stroke-dashoffset: 600; } 55% { stroke-dashoffset: 0; } 100% { stroke-dashoffset: -600; } }

    .al-hero-ico { width: 58px; height: 58px; border-radius: 18px; flex-shrink: 0; display: grid; place-items: center; position: relative; z-index: 2; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.3); backdrop-filter: blur(4px); }
    .al-hero-ico svg { width: 29px; height: 29px; }
    .al-hero-text { flex: 1; min-width: 230px; max-width: 480px; position: relative; z-index: 2; }
    .al-crumb { display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 500; opacity: .88; margin-bottom: 4px; }
    .al-crumb i { font-style: normal; opacity: .6; }
    .al-title { font-size: 23px; font-weight: 700; letter-spacing: -.2px; line-height: 1.2; }
    .al-sub { font-size: 12.5px; opacity: .9; margin-top: 4px; }
    .al-rec { display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; padding: 5px 12px; border-radius: 20px; font-size: 11.5px; font-weight: 600; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.24); }
    .al-rec::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: #f87171; box-shadow: 0 0 0 0 rgba(248,113,113,.7); animation: alrec 1.6s infinite; }
    @keyframes alrec { 70% { box-shadow: 0 0 0 9px rgba(248,113,113,0); } 100% { box-shadow: 0 0 0 0 rgba(248,113,113,0); } }

    /* terminal log (dari data nyata) */
    .al-term { position: absolute; inset-inline-end: 24px; top: 22px; width: 370px; z-index: 1; direction: ltr; border-radius: 14px; overflow: hidden; background: rgba(2,8,23,.55); border: 1px solid rgba(255,255,255,.16); backdrop-filter: blur(6px); box-shadow: 0 14px 30px rgba(0,0,0,.3); }
    .al-term-head { display: flex; align-items: center; gap: 6px; padding: 8px 12px; border-bottom: 1px solid rgba(255,255,255,.1); font: 600 10.5px/1 ui-monospace, Menlo, Consolas, monospace; color: rgba(255,255,255,.6); }
    .al-term-head i { width: 9px; height: 9px; border-radius: 50%; background: #ef4444; } .al-term-head i:nth-child(2) { background: #f59e0b; } .al-term-head i:nth-child(3) { background: #22c55e; }
    .al-term-head span { margin-inline-start: 8px; }
    .al-term-body { height: 112px; overflow: hidden; position: relative; -webkit-mask-image: linear-gradient(transparent, #000 18%, #000 82%, transparent); mask-image: linear-gradient(transparent, #000 18%, #000 82%, transparent); }
    .al-term-roll { animation: alroll 14s linear infinite; }
    @keyframes alroll { from { transform: translateY(0); } to { transform: translateY(-50%); } }
    .al-line { height: 22px; display: flex; align-items: center; gap: 9px; padding: 0 14px; font: 500 11.5px/1 ui-monospace, Menlo, Consolas, monospace; color: rgba(255,255,255,.82); white-space: nowrap; overflow: hidden; }
    .al-line .t { color: #67e8f9; } .al-line .u { color: #e2e8f0; max-width: 110px; overflow: hidden; text-overflow: ellipsis; }
    .al-line .a { padding: 1px 7px; border-radius: 5px; font-weight: 700; font-size: 10.5px; }
    .al-line .a.ok { background: rgba(34,197,94,.22); color: #86efac; } .al-line .a.bad { background: rgba(239,68,68,.25); color: #fca5a5; }
    .al-line .a.info { background: rgba(59,130,246,.25); color: #93c5fd; } .al-line .a.teal { background: rgba(20,184,166,.25); color: #5eead4; }
    .al-line .a.slate { background: rgba(148,163,184,.25); color: #cbd5e1; } .al-line .a.violet { background: rgba(139,92,246,.28); color: #c4b5fd; }
    .al-cursor { display: inline-block; width: 7px; height: 13px; background: #67e8f9; animation: alblink 1s steps(1) infinite; }
    @keyframes alblink { 50% { opacity: 0; } }

    /* ---------- STATISTIK ---------- */
    .al-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-bottom: 18px; }
    .al-stat { position: relative; overflow: hidden; display: flex; align-items: center; gap: 14px; padding: 16px 18px; border-radius: 16px; background: var(--bg-card); border: 1px solid var(--border-color); animation: alrise .55s cubic-bezier(.2,.8,.2,1) both; transition: transform .25s, box-shadow .25s; }
    .al-stat:nth-child(2) { animation-delay: .08s; } .al-stat:nth-child(3) { animation-delay: .16s; } .al-stat:nth-child(4) { animation-delay: .24s; }
    .al-stat:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(20,30,80,.12); }
    .al-stat::after { content: ''; position: absolute; inset-inline-end: -26px; bottom: -26px; width: 90px; height: 90px; border-radius: 50%; background: var(--tone-soft); }
    @keyframes alrise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    .al-stat-ico { width: 46px; height: 46px; border-radius: 14px; flex-shrink: 0; display: grid; place-items: center; color: #fff; background: var(--tone); box-shadow: 0 8px 16px var(--tone-soft); position: relative; z-index: 1; }
    .al-stat-ico svg { width: 22px; height: 22px; }
    .al-stat-body { min-width: 0; position: relative; z-index: 1; }
    .al-stat-label { font-size: 12px; font-weight: 500; color: var(--text-muted); }
    .al-stat-val { font-size: 24px; font-weight: 700; line-height: 1.2; color: var(--text-main); }
    .al-stat-hint { font-size: 11.5px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
    .al-stat.t1 { --tone: linear-gradient(135deg,#0e7490,#22d3ee); --tone-soft: rgba(8,145,178,.16); }
    .al-stat.t2 { --tone: linear-gradient(135deg,#059669,#34d399); --tone-soft: rgba(16,185,129,.16); }
    .al-stat.t3 { --tone: linear-gradient(135deg,#4338ca,#818cf8); --tone-soft: rgba(99,102,241,.16); }
    .al-stat.t4 { --tone: linear-gradient(135deg,#c2410c,#fb923c); --tone-soft: rgba(249,115,22,.16); }

    /* ---------- KARTU UTAMA ---------- */
    .al-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; animation: alrise .6s .3s cubic-bezier(.2,.8,.2,1) both; }
    .al-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; padding: 14px 18px; border-bottom: 1px solid var(--border-color); }
    .al-bar h3 { font-size: 14px; font-weight: 600; color: var(--text-main); display: flex; align-items: center; gap: 10px; margin-inline-end: auto; }
    .al-bar h3::before { content: ''; width: 4px; height: 18px; border-radius: 3px; background: linear-gradient(var(--mod-2), var(--mod)); }

    .al-filter { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .al-field { display: flex; align-items: center; gap: 8px; height: 38px; padding: 0 12px; border-radius: 11px; background: var(--bg-soft); border: 1px solid transparent; color: var(--text-muted); transition: border-color .2s, box-shadow .2s; }
    .al-field:focus-within { border-color: var(--mod); box-shadow: 0 0 0 3px var(--mod-soft); }
    .al-field svg { width: 16px; height: 16px; flex-shrink: 0; }
    .al-field input { width: 150px; border: none !important; outline: none; background: transparent !important; padding: 0 !important; margin: 0; font: inherit; font-size: 12.5px; color: var(--text-main); box-shadow: none !important; }
    .al-btn { height: 38px; display: inline-flex; align-items: center; gap: 7px; padding: 0 16px; border: none; border-radius: 11px; cursor: pointer; text-decoration: none; font: inherit; font-size: 12.5px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #0e7490, #0891b2); box-shadow: 0 8px 16px rgba(8,145,178,.25); transition: transform .2s, box-shadow .2s; }
    .al-btn:hover { transform: translateY(-1px); box-shadow: 0 12px 20px rgba(8,145,178,.3); }
    .al-btn svg { width: 15px; height: 15px; }
    .al-btn.ghost { color: var(--text-main); background: var(--bg-soft); box-shadow: none; }
    .al-btn.ghost:hover { background: var(--mod-soft); color: var(--mod); box-shadow: none; }

    .al-view { display: inline-flex; padding: 3px; border-radius: 12px; background: var(--bg-soft); gap: 2px; }
    .al-vbtn { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border: none; border-radius: 9px; cursor: pointer; font: inherit; font-size: 12px; font-weight: 600; color: var(--text-muted); background: transparent; transition: background .2s, color .2s; }
    .al-vbtn svg { width: 14px; height: 14px; }
    .al[data-view="table"] .al-vbtn[data-v="table"], .al[data-view="timeline"] .al-vbtn[data-v="timeline"] { background: var(--bg-card); color: var(--mod); box-shadow: 0 2px 8px rgba(20,30,80,.12); }

    .al-quick { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; padding: 11px 18px; border-bottom: 1px dashed var(--border-color); }
    .al-quick > span { font-size: 11.5px; font-weight: 600; color: var(--text-muted); }
    .al-q { padding: 4px 11px; border-radius: 20px; border: 1px solid var(--border-color); background: var(--bg-card); font: 600 11.5px/1.4 ui-monospace, Menlo, Consolas, monospace; color: var(--text-muted); text-decoration: none; transition: all .2s; }
    .al-q:hover { border-color: var(--mod-2); color: var(--mod); }
    .al-q.on { background: var(--mod); border-color: var(--mod); color: #fff; }
    body.dark-mode .al-q.on { color: #0f172a; }
    .al-active { display: inline-flex; align-items: center; gap: 6px; padding: 4px 6px 4px 11px; border-radius: 20px; font-size: 11.5px; font-weight: 600; color: var(--mod); background: var(--mod-soft); }
    .al-active a { width: 18px; height: 18px; border-radius: 50%; display: grid; place-items: center; color: inherit; background: rgba(0,0,0,.08); text-decoration: none; }
    .al-active a:hover { background: var(--mod); color: #fff; }
    .al-active a svg { width: 10px; height: 10px; }

    /* ---------- TABEL ---------- */
    .al[data-view="timeline"] .al-tablewrap { display: none; }
    .al[data-view="table"] .al-timeline { display: none; }
    .al-scroll { overflow-x: auto; }
    .al table { margin: 0; }
    .al th { white-space: nowrap; }
    .al td { vertical-align: middle; }
    .al tbody tr { animation: alrise .4s both; }
    .al tbody tr:hover td { background: var(--mod-soft); }

    .al-time { display: flex; flex-direction: column; gap: 3px; white-space: nowrap; }
    .al-time b { font: 600 12.5px/1.2 ui-monospace, Menlo, Consolas, monospace; color: var(--text-main); }
    .al-time small { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--text-muted); }
    .al-time small svg { width: 11px; height: 11px; color: var(--mod); }

    .al-actor { display: flex; align-items: center; gap: 10px; min-width: 130px; font-weight: 600; color: var(--text-main); }
    .al-avatar { width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0; display: grid; place-items: center; color: #fff; font-size: 11px; font-weight: 700; letter-spacing: .4px; }
    .al-avatar.a1 { background: linear-gradient(135deg,#4338ca,#818cf8); } .al-avatar.a2 { background: linear-gradient(135deg,#0f766e,#2dd4bf); }
    .al-avatar.a3 { background: linear-gradient(135deg,#be123c,#fb7185); } .al-avatar.a4 { background: linear-gradient(135deg,#c2410c,#fb923c); }
    .al-avatar.a5 { background: linear-gradient(135deg,#0369a1,#38bdf8); } .al-avatar.a6 { background: linear-gradient(135deg,#6d28d9,#c084fc); }
    .al-avatar.sys { background: linear-gradient(135deg,#334155,#64748b); }
    .al-avatar svg { width: 16px; height: 16px; }

    .al-act { display: inline-flex; align-items: center; gap: 7px; padding: 4px 11px 4px 8px; border-radius: 9px; font: 600 12px/1.3 ui-monospace, Menlo, Consolas, monospace; white-space: nowrap; }
    .al-act svg { width: 13px; height: 13px; stroke-width: 2.4; }
    .al-act.ok     { background: rgba(16,185,129,.14); color: #059669; } .al-act.bad    { background: rgba(239,68,68,.14);  color: #dc2626; }
    .al-act.info   { background: rgba(59,130,246,.14); color: #2563eb; } .al-act.teal   { background: rgba(20,184,166,.15); color: #0f766e; }
    .al-act.slate  { background: rgba(100,116,139,.16); color: #475569; } .al-act.violet { background: rgba(139,92,246,.15); color: #7c3aed; }
    body.dark-mode .al-act.ok { color: #34d399; } body.dark-mode .al-act.bad { color: #f87171; } body.dark-mode .al-act.info { color: #60a5fa; }
    body.dark-mode .al-act.teal { color: #2dd4bf; } body.dark-mode .al-act.slate { color: #cbd5e1; } body.dark-mode .al-act.violet { color: #c4b5fd; }

    .al-desc { color: var(--text-main); min-width: 200px; max-width: 420px; line-height: 1.5; }
    .al-ip { display: inline-flex; align-items: center; gap: 6px; padding: 3px 9px; border-radius: 8px; font: 500 11.5px/1.3 ui-monospace, Menlo, Consolas, monospace; color: var(--text-muted); background: var(--bg-soft); border: 1px solid var(--border-color); white-space: nowrap; }
    .al-ip svg { width: 12px; height: 12px; color: var(--mod); }

    /* ---------- TIMELINE ---------- */
    .al-timeline { padding: 8px 22px 22px; }
    .al-day { display: flex; align-items: center; gap: 12px; margin: 18px 0 12px; font-size: 12px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--mod); }
    .al-day i { flex: 1; height: 1px; background: linear-gradient(90deg, var(--mod-2), transparent); }
    body[dir="rtl"] .al-day i { background: linear-gradient(270deg, var(--mod-2), transparent); }
    .al-evs { position: relative; padding-inline-start: 34px; }
    .al-evs::before { content: ''; position: absolute; inset-inline-start: 13px; top: 6px; bottom: 6px; width: 2px; border-radius: 2px; background: linear-gradient(var(--mod-2), var(--border-color)); }
    .al-ev { position: relative; margin-bottom: 12px; animation: alrise .45s both; }
    .al-node { position: absolute; inset-inline-start: -34px; top: 10px; width: 28px; height: 28px; border-radius: 50%; display: grid; place-items: center; color: #fff; border: 3px solid var(--bg-card); box-shadow: 0 4px 10px rgba(0,0,0,.15); background: var(--nc); }
    .al-node svg { width: 13px; height: 13px; stroke-width: 2.6; }
    .al-ev.ok { --nc: #10b981; } .al-ev.bad { --nc: #ef4444; } .al-ev.info { --nc: #3b82f6; } .al-ev.teal { --nc: #14b8a6; } .al-ev.slate { --nc: #64748b; } .al-ev.violet { --nc: #8b5cf6; }
    .al-evc { padding: 12px 16px; border-radius: 14px; background: var(--bg-soft); border: 1px solid var(--border-color); border-inline-start: 3px solid var(--nc); transition: transform .2s, box-shadow .2s; }
    .al-evc:hover { transform: translateX(3px); box-shadow: 0 10px 22px rgba(20,30,80,.10); }
    body[dir="rtl"] .al-evc:hover { transform: translateX(-3px); }
    .al-evtop { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .al-evtop .al-actor { min-width: 0; font-size: 13px; }
    .al-evtop .al-rel { margin-inline-start: auto; font-size: 11.5px; color: var(--text-muted); }
    .al-evdesc { margin-top: 8px; font-size: 13px; color: var(--text-main); line-height: 1.5; }
    .al-evfoot { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 8px; font: 500 11.5px/1 ui-monospace, Menlo, Consolas, monospace; color: var(--text-muted); }

    /* ---------- KOSONG & PAGER ---------- */
    .al-empty { text-align: center; padding: 46px 16px; }
    .al-empty-ico { width: 66px; height: 66px; margin: 0 auto 14px; border-radius: 21px; display: grid; place-items: center; background: var(--mod-soft); color: var(--mod); }
    .al-empty-ico svg { width: 31px; height: 31px; }
    .al-empty strong { display: block; font-size: 14px; font-weight: 600; color: var(--text-main); }
    .al-empty p { font-size: 12.5px; color: var(--text-muted); margin: 4px 0 14px; }
    .al-pager { padding: 14px 18px; border-top: 1px dashed var(--border-color); }
    .al-pager:empty { display: none; }

    @media (max-width: 900px) { .al-term { display: none; } }
    @media (prefers-reduced-motion: reduce) { .al *, .al *::before, .al *::after { animation: none !important; } .al-ecg path { stroke-dashoffset: 0 !important; } }
</style>

<div class="al" data-view="table" id="alRoot">
    <!-- HERO -->
    <div class="al-hero">
        <span class="al-grid" aria-hidden="true"></span>
        <span class="al-glow" aria-hidden="true"></span>

        <!-- terminal: menampilkan aktivitas nyata terbaru -->
        <div class="al-term" aria-hidden="true" data-no-i18n>
            <div class="al-term-head"><i></i><i></i><i></i><span>tail -f audit.log</span></div>
            <div class="al-term-body">
                @if($stream->count() > 0)
                    <div class="al-term-roll">
                        @for($k = 0; $k < 2; $k++)
                            @foreach($stream as $s)
                                <div class="al-line"><span class="t">{{ $s['t'] }}</span><span class="u">{{ $s['u'] }}</span><span class="a {{ $s['c'] }}">{{ $s['a'] }}</span></div>
                            @endforeach
                        @endfor
                    </div>
                @else
                    <div class="al-line"><span class="t">--:--:--</span><span class="u">waiting for activity</span><span class="al-cursor"></span></div>
                @endif
            </div>
        </div>

        <!-- denyut jantung (ECG) -->
        <svg class="al-ecg" viewBox="0 0 600 46" preserveAspectRatio="none" aria-hidden="true">
            <path class="base" d="M0 28 H600"/>
            <path d="M0 28 H70 L82 28 L92 8 L104 44 L114 20 L122 28 H210 L222 28 L232 12 L244 42 L254 22 L262 28 H350 L362 28 L372 6 L384 44 L394 18 L402 28 H490 L502 28 L512 14 L524 40 L534 24 L542 28 H600" pathLength="600"/>
        </svg>

        <div class="al-hero-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h5"/></svg>
        </div>
        <div class="al-hero-text">
            <div class="al-crumb"><span>Administrasi</span><i>/</i><span>Audit Log</span></div>
            <div class="al-title">Audit Log</div>
            <div class="al-sub">Jejak seluruh aktivitas pengguna dan sistem secara otomatis</div>
            <span class="al-rec">Terekam otomatis</span>
        </div>
    </div>

    <!-- KARTU STATISTIK -->
    <div class="al-stats">
        <div class="al-stat t1">
            <span class="al-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h5"/></svg></span>
            <div class="al-stat-body">
                <div class="al-stat-label">Total Log</div>
                <div class="al-stat-val"><b class="al-num" style="--to: {{ (int) $total }}"><span class="al-sr">{{ (int) $total }}</span></b></div>
                <div class="al-stat-hint">Aktivitas tercatat</div>
            </div>
        </div>
        <div class="al-stat t2">
            <span class="al-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></span>
            <div class="al-stat-body">
                <div class="al-stat-label">Aktivitas Hari Ini</div>
                <div class="al-stat-val"><b class="al-num" style="--to: {{ (int) $today }}"><span class="al-sr">{{ (int) $today }}</span></b></div>
                <div class="al-stat-hint">Sejak tengah malam</div>
            </div>
        </div>
        <div class="al-stat t3">
            <span class="al-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
            <div class="al-stat-body">
                <div class="al-stat-label">Pengguna Aktif</div>
                <div class="al-stat-val"><b class="al-num" style="--to: {{ (int) $actors }}"><span class="al-sr">{{ (int) $actors }}</span></b></div>
                <div class="al-stat-hint">Pelaku berbeda</div>
            </div>
        </div>
        <div class="al-stat t4">
            <span class="al-stat-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/></svg></span>
            <div class="al-stat-body">
                <div class="al-stat-label">Jenis Aksi</div>
                <div class="al-stat-val"><b class="al-num" style="--to: {{ (int) $actions }}"><span class="al-sr">{{ (int) $actions }}</span></b></div>
                <div class="al-stat-hint">Aksi berbeda</div>
            </div>
        </div>
    </div>

    <!-- KARTU UTAMA (id container dipakai oleh AJAX pagination di layout) -->
    <div class="al-card table-card" id="ajax-table-container">
        <div class="al-bar">
            <h3>Daftar Aktivitas</h3>

            <form method="GET" class="al-filter">
                <label class="al-field">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/></svg>
                    <input name="action" placeholder="Filter aksi..." value="{{ $fAction }}" autocomplete="off">
                </label>
                <label class="al-field">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input name="user" placeholder="Filter user..." value="{{ $fUser }}" autocomplete="off">
                </label>
                <button class="al-btn" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 3H2l8 9.46V19l4 2v-8.54z"/></svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('super-admin.activity-log.index') }}" class="al-btn ghost"><span>Reset</span></a>
            </div>

            <div class="al-view" role="group">
                <button type="button" class="al-vbtn" data-v="table">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg><span>Tabel</span>
                </button>
                <button type="button" class="al-vbtn" data-v="timeline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><circle cx="12" cy="7" r="2.5" fill="currentColor"/><circle cx="12" cy="17" r="2.5" fill="currentColor"/><path d="M15 7h6M3 17h6"/></svg><span>Timeline</span>
                </button>
            </div>
        </div>

        <div class="al-quick">
            <span>Cepat:</span>
            @foreach($quick as $qv)
                <a class="al-q {{ strtolower((string) $fAction) === $qv ? 'on' : '' }}" data-no-i18n href="{{ route('super-admin.activity-log.index', array_filter(['action' => $qv, 'user' => $fUser])) }}">{{ $qv }}</a>
            @endforeach
            @if($hasFilter)
                @if(filled($fAction))
                    <span class="al-active"><span>Aksi:</span><span data-no-i18n>{{ $fAction }}</span>
                        <a href="{{ route('super-admin.activity-log.index', array_filter(['user' => $fUser])) }}" title="Hapus filter" aria-label="Hapus filter"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></a></span>
                @endif
                @if(filled($fUser))
                    <span class="al-active"><span>User:</span><span data-no-i18n>{{ $fUser }}</span>
                        <a href="{{ route('super-admin.activity-log.index', array_filter(['action' => $fAction])) }}" title="Hapus filter" aria-label="Hapus filter"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></a></span>
                @endif
            @endif
        </div>

        @if($col->isEmpty())
            <div class="al-empty">
                <div class="al-empty-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h5"/></svg></div>
                @if($hasFilter)
                    <strong>Tidak ada hasil untuk filter ini.</strong>
                    <p>Coba ubah atau reset filter.</p>
                    <a href="{{ route('super-admin.activity-log.index') }}" class="al-btn"><span>Reset</span></a>
                @else
                    <strong>Belum ada aktivitas.</strong>
                    <p>Aktivitas pengguna akan tercatat otomatis di sini.</p>
                @endif
            </div>
        @else
            <!-- TAMPILAN TABEL -->
            <div class="al-tablewrap">
                <div class="al-scroll">
                    <table>
                        <thead>
                            <tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Deskripsi</th><th>IP</th></tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $i => $l)
                            @php
                                [$tn, $ik] = $tone($l->action);
                                $name  = optional($l->actor)->name;
                                $avTone = $name ? $avatarTones[abs(crc32((string) $name)) % count($avatarTones)] : 'sys';
                            @endphp
                            <tr style="animation-delay: {{ min($i, 14) * 0.03 + 0.35 }}s">
                                <td>
                                    <div class="al-time" data-no-i18n>
                                        <b>{{ $l->created_at->format('d-m-Y H:i:s') }}</b>
                                        <small><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><time class="al-rel" data-ts="{{ $l->created_at->toIso8601String() }}"></time></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="al-actor">
                                        <span class="al-avatar {{ $avTone }}" data-no-i18n>
                                            @if($name){{ $initialsOf($name) ?: '?' }}@else<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M12 8V4M8 14h.01M16 14h.01M9 18h6"/></svg>@endif
                                        </span>
                                        @if($name)<span data-no-i18n>{{ $name }}</span>@else<span>Sistem</span>@endif
                                    </div>
                                </td>
                                <td>
                                    <span class="al-act {{ $tn }}" data-no-i18n>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $ico[$ik] }}"/></svg>{{ $l->action }}
                                    </span>
                                </td>
                                <td><div class="al-desc" data-no-i18n>{{ $l->description ?? '-' }}</div></td>
                                <td>
                                    <span class="al-ip" data-no-i18n>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>{{ $l->ip_address ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAMPILAN TIMELINE -->
            <div class="al-timeline">
                @foreach($groups as $day => $items)
                    <div class="al-day"><span>{{ $dayLabel($day) }}</span><i></i></div>
                    <div class="al-evs">
                        @foreach($items as $j => $l)
                            @php
                                [$tn, $ik] = $tone($l->action);
                                $name  = optional($l->actor)->name;
                                $avTone = $name ? $avatarTones[abs(crc32((string) $name)) % count($avatarTones)] : 'sys';
                            @endphp
                            <div class="al-ev {{ $tn }}" style="animation-delay: {{ min($j, 12) * 0.04 }}s">
                                <span class="al-node" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $ico[$ik] }}"/></svg></span>
                                <div class="al-evc">
                                    <div class="al-evtop">
                                        <div class="al-actor">
                                            <span class="al-avatar {{ $avTone }}" data-no-i18n>
                                                @if($name){{ $initialsOf($name) ?: '?' }}@else<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M12 8V4M8 14h.01M16 14h.01M9 18h6"/></svg>@endif
                                            </span>
                                            @if($name)<span data-no-i18n>{{ $name }}</span>@else<span>Sistem</span>@endif
                                        </div>
                                        <span class="al-act {{ $tn }}" data-no-i18n>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $ico[$ik] }}"/></svg>{{ $l->action }}
                                        </span>
                                        <time class="al-rel" data-ts="{{ $l->created_at->toIso8601String() }}" data-no-i18n></time>
                                    </div>
                                    @if(filled($l->description))<div class="al-evdesc" data-no-i18n>{{ $l->description }}</div>@endif
                                    <div class="al-evfoot" data-no-i18n>
                                        <span>{{ $l->created_at->format('d-m-Y H:i:s') }}</span>
                                        <span class="al-ip"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>{{ $l->ip_address ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        <div class="al-pager">{{ method_exists($logs, 'links') ? $logs->links() : '' }}</div>

        <!-- skrip di dalam kontainer: dijalankan ulang setelah pagination AJAX untuk memperbarui "x menit lalu" -->
        <script>
        (function () {
            var LOC = { id: 'id-ID', en: 'en-US', ar: 'ar-EG', zh: 'zh-CN' };
            function lang() {
                try { if (window.I18N && window.I18N.get) return window.I18N.get(); return localStorage.getItem('lang') || 'id'; } catch (e) { return 'id'; }
            }
            function rel(ts) {
                var s = (new Date(ts).getTime() - Date.now()) / 1000, a = Math.abs(s);
                var f = new Intl.RelativeTimeFormat(LOC[lang()] || 'id-ID', { numeric: 'auto' });
                if (a < 60) return f.format(Math.round(s), 'second');
                if (a < 3600) return f.format(Math.round(s / 60), 'minute');
                if (a < 86400) return f.format(Math.round(s / 3600), 'hour');
                if (a < 2592000) return f.format(Math.round(s / 86400), 'day');
                if (a < 31536000) return f.format(Math.round(s / 2592000), 'month');
                return f.format(Math.round(s / 31536000), 'year');
            }
            window.__alUpdateRel = function () {
                try {
                    document.querySelectorAll('.al-rel[data-ts]').forEach(function (el) {
                        var t = rel(el.getAttribute('data-ts'));
                        if (el.textContent !== t) el.textContent = t;
                    });
                } catch (e) {}
            };
            window.__alUpdateRel();

            if (!window.__alBound) {
                window.__alBound = true;
                window.addEventListener('languageChanged', function () { window.__alUpdateRel(); });
                setInterval(function () { window.__alUpdateRel(); }, 30000);

                /* ganti tampilan Tabel / Timeline (disimpan di browser) */
                document.addEventListener('click', function (e) {
                    var b = e.target.closest && e.target.closest('.al-vbtn');
                    if (!b) return;
                    var root = document.getElementById('alRoot');
                    if (!root) return;
                    root.setAttribute('data-view', b.getAttribute('data-v'));
                    try { localStorage.setItem('simbu_al_view', b.getAttribute('data-v')); } catch (err) {}
                });
            }
        })();
        </script>
    </div>
</div>

<script>
/* Pulihkan pilihan tampilan terakhir (tabel / timeline) */
(function () {
    try {
        var v = localStorage.getItem('simbu_al_view');
        var root = document.getElementById('alRoot');
        if (root && (v === 'table' || v === 'timeline')) root.setAttribute('data-view', v);
    } catch (e) {}
})();

/* Kamus khusus halaman ini (aman dijalankan sebelum maupun sesudah I18N siap) */
(function (d) {
    window.I18N_EXTRA = Object.assign(window.I18N_EXTRA || {}, d);
    if (window.I18N && window.I18N.extend) window.I18N.extend(d);
})({
    'Jejak seluruh aktivitas pengguna dan sistem secara otomatis': ['An automatic trail of all user and system activity', 'سجل تلقائي لجميع أنشطة المستخدمين والنظام', '自动记录所有用户和系统活动'],
    'Terekam otomatis':      ['Recorded automatically', 'يُسجَّل تلقائياً', '自动记录'],
    'Total Log':             ['Total Logs', 'إجمالي السجلات', '日志总数'],
    'Aktivitas tercatat':    ['Recorded activities', 'الأنشطة المسجلة', '已记录的活动'],
    'Aktivitas Hari Ini':    ['Today\'s Activity', 'أنشطة اليوم', '今日活动'],
    'Sejak tengah malam':    ['Since midnight', 'منذ منتصف الليل', '自午夜起'],
    'Pengguna Aktif':        ['Active Users', 'المستخدمون النشطون', '活跃用户'],
    'Pelaku berbeda':        ['Distinct actors', 'منفذون مختلفون', '不同的操作者'],
    'Jenis Aksi':            ['Action Types', 'أنواع الإجراءات', '操作类型'],
    'Aksi berbeda':          ['Distinct actions', 'إجراءات مختلفة', '不同的操作'],
    'Daftar Aktivitas':      ['Activity List', 'قائمة الأنشطة', '活动列表'],
    'Filter aksi...':        ['Filter action...', 'تصفية الإجراء...', '筛选操作...'],
    'Filter user...':        ['Filter user...', 'تصفية المستخدم...', '筛选用户...'],
    'Tabel':                 ['Table', 'جدول', '表格'],
    'Timeline':              ['Timeline', 'الخط الزمني', '时间线'],
    'Cepat:':                ['Quick:', 'سريع:', '快捷:'],
    'Aksi:':                 ['Action:', 'الإجراء:', '操作:'],
    'User:':                 ['User:', 'المستخدم:', '用户:'],
    'User':                  ['User', 'المستخدم', '用户'],
    'IP':                    ['IP', 'IP', 'IP'],
    'Hapus filter':          ['Remove filter', 'إزالة التصفية', '移除筛选'],
    'Hari ini':              ['Today', 'اليوم', '今天'],
    'Kemarin':               ['Yesterday', 'أمس', '昨天'],
    'Sistem':                ['System', 'النظام', '系统'],
    'Belum ada aktivitas.':  ['No activity yet.', 'لا توجد أنشطة بعد.', '暂无活动。'],
    'Aktivitas pengguna akan tercatat otomatis di sini.': ['User activity will be recorded here automatically.', 'سيتم تسجيل أنشطة المستخدمين هنا تلقائياً.', '用户活动将自动记录在这里。'],
    'Tidak ada hasil untuk filter ini.': ['No results for this filter.', 'لا توجد نتائج لهذه التصفية.', '此筛选条件无结果。'],
    'Coba ubah atau reset filter.': ['Try changing or resetting the filter.', 'جرّب تغيير التصفية أو إعادة تعيينها.', '请尝试修改或重置筛选。']
});
</script>
@endsection
