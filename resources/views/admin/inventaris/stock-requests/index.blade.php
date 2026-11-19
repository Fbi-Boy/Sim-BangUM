@extends('layouts.app')
@section('title', 'Permintaan ATK')

@section('content')
@php
    /* Nama & warna badge per status. Sesuaikan jika nilai status di database berbeda. */
    $statusMap = [
        'submitted' => ['Menunggu',    'badge-warning'],
        'approved'  => ['Disetujui',   'badge-info'],
        'issued'    => ['Dikeluarkan', 'badge-success'],
        'completed' => ['Selesai',     'badge-success'],
        'rejected'  => ['Ditolak',     'badge-danger'],
    ];
    $statusInfo = fn($s) => $statusMap[$s] ?? [ucwords(str_replace('_', ' ', (string) $s)), 'badge-muted'];
    $num = fn($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');

    $initials = fn($name) => mb_strtoupper(collect(preg_split('/\s+/', trim($name)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));

    $col       = $requests->getCollection();
    $waiting   = $col->where('status', 'submitted')->count();
    $ready     = $col->where('status', 'approved')->count();
    $statuses  = $col->pluck('status')->filter()->unique()->values();
@endphp

<style>
    .alert-box { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: 12px; font-size: 13px; margin-bottom: 16px; }
    .alert-box.ok  { background: rgba(16,185,129,.13); color: #059669; }
    .alert-box.err { background: rgba(239,68,68,.13);  color: #dc2626; }
    body.dark-mode .alert-box.ok  { color: #34d399; }
    body.dark-mode .alert-box.err { color: #f87171; }

    .head-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-ghost { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main); font-size: 13px; font-weight: 500; text-decoration: none; transition: border-color .2s, color .2s; }
    .btn-ghost svg { width: 15px; height: 15px; }
    .btn-ghost:hover { border-color: var(--accent, #2563eb); color: var(--accent, #2563eb); }

    .num { font-weight: 600; white-space: nowrap; }
    .req-sub { font-size: 11.5px; color: var(--text-muted); }

    .items { display: flex; flex-direction: column; gap: 9px; min-width: 260px; }
    .it-name { font-weight: 600; font-size: 13px; color: var(--text-main); }
    .it-meta { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
    .tag { font-size: 11px; padding: 2px 8px; border-radius: 6px; background: var(--bg-soft, #f3f6fd); color: var(--text-muted); white-space: nowrap; }
    .tag b { color: var(--text-main); font-weight: 600; }

    .decide { display: inline-flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
    .btn-act { display: inline-flex; align-items: center; gap: 6px; padding: 7px 13px; border-radius: 10px; border: 1px solid transparent; font-size: 12.5px; font-weight: 600; font-family: inherit; cursor: pointer; transition: background .2s, color .2s, border-color .2s; }
    .btn-act svg { width: 14px; height: 14px; }
    .btn-act.approve { background: rgba(16,185,129,.13); color: #059669; }
    .btn-act.approve:hover { background: #10b981; color: #fff; }
    .btn-act.reject { background: transparent; color: #dc2626; border-color: rgba(239,68,68,.35); }
    .btn-act.reject:hover { background: #ef4444; color: #fff; border-color: #ef4444; }
    .btn-act.issue { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .btn-act.issue:hover { background: #2563eb; color: #fff; }
    body.dark-mode .btn-act.approve { color: #34d399; }
    body.dark-mode .btn-act.reject { color: #f87171; }
    .no-act { color: var(--text-muted); font-size: 12.5px; }

    .modal-ov { position: fixed; inset: 0; z-index: 200; background: rgba(15,23,42,.55); backdrop-filter: blur(3px); display: none; align-items: center; justify-content: center; padding: 20px; }
    .modal-ov.open { display: flex; }
    .modal-box { width: 100%; max-width: 460px; max-height: calc(100vh - 40px); overflow-y: auto; background: var(--bg-card); border-radius: 20px; padding: 24px; box-shadow: var(--shadow-pop); animation: pop .2s ease; }
    @keyframes pop { from { transform: translateY(10px) scale(.97); opacity: 0; } to { transform: none; opacity: 1; } }
    .m-top { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
    .m-ico { width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center; color: #fff; flex-shrink: 0; }
    .m-ico svg { width: 22px; height: 22px; }
    .m-top h3 { font-size: 16px; color: var(--text-main); }
    .m-top p { font-size: 12.5px; color: var(--text-muted); margin-top: 2px; }
    .m-summary { padding: 12px 14px; border-radius: 12px; background: var(--bg-soft, #f3f6fd); font-size: 13px; line-height: 1.5; margin-bottom: 14px; color: var(--text-main); }
    .m-summary small { display: block; font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }
    .m-label { display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 6px; color: var(--text-main); }
    .m-label em { font-style: normal; font-weight: 400; color: var(--text-muted); }
    .m-text, .q-input { width: 100%; max-width: none; padding: 10px 13px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main); font: inherit; font-size: 13px; outline: none; transition: border-color .2s, box-shadow .2s; }
    .m-text { min-height: 92px; resize: vertical; }
    .m-text:focus, .q-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,.14); }
    .q-row { display: grid; grid-template-columns: minmax(0,1fr) 110px; gap: 12px; align-items: center; padding: 8px 0; border-top: 1px solid var(--border-color); }
    .q-row:first-child { border-top: none; }
    .q-name { font-size: 13px; font-weight: 600; color: var(--text-main); }
    .q-name small { display: block; font-size: 11.5px; font-weight: 400; color: var(--text-muted); margin-top: 1px; }
    .q-input { text-align: right; padding: 8px 10px; }
    .m-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px; }
    .m-btn { padding: 9px 18px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main); font-size: 12.5px; font-weight: 500; cursor: pointer; font-family: inherit; }
    .m-btn.primary { background: #10b981; border-color: #10b981; color: #fff; font-weight: 600; }
    .m-btn.primary.danger { background: #ef4444; border-color: #ef4444; }
    .m-btn.primary.blue { background: #2563eb; border-color: #2563eb; }

    button:focus-visible, a:focus-visible { outline: 2px solid #3b82f6; outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .modal-box { animation: none !important; } }
    @media (max-width: 720px) { .decide { flex-direction: column; align-items: stretch; } .items { min-width: 220px; } }
</style>

<div class="page-head">
    <div>
        <h2>Permintaan ATK</h2>
        <p>Setujui, tolak, dan keluarkan stok berdasarkan permintaan dari pegawai.</p>
    </div>
    <div class="head-actions">
        <a href="{{ route('admin.inventaris.supply-items.index') }}" class="btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
            Item ATK
        </a>
    </div>
</div>

@if(session('success') || session('status'))
    <div class="alert-box ok" role="status">{{ session('success') ?? session('status') }}</div>
@endif
@if($errors->any())
    <div class="alert-box err" role="alert">{{ $errors->first() }}</div>
@endif

<!-- STATISTIK -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg></div>
        <div><div class="stat-val">{{ $requests->total() }}</div><div class="stat-lbl">Total permintaan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <div><div class="stat-val">{{ $waiting }}</div><div class="stat-lbl">Menunggu persetujuan <small>di halaman ini</small></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
        <div><div class="stat-val">{{ $ready }}</div><div class="stat-lbl">Siap dikeluarkan <small>di halaman ini</small></div></div>
    </div>
</div>

<!-- TABEL -->
<div class="table-card">
    <div class="table-toolbar">
        <label class="tb-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="srSearch" placeholder="Cari nomor, pemohon, keperluan, atau item..." autocomplete="off" aria-label="Cari permintaan">
        </label>
        @if($statuses->count() > 1)
            <div class="chips" id="srChips">
                <button type="button" class="chip on" data-status="all">Semua</button>
                @foreach($statuses as $st)
                    <button type="button" class="chip" data-status="{{ $st }}">{{ $statusInfo($st)[0] }}</button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Permintaan</th>
                    <th>Pemohon</th>
                    <th>Keperluan</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th class="col-action">Aksi</th>
                </tr>
            </thead>
            <tbody id="srBody">
                @foreach($requests as $r)
                    @php
                        [$stLabel, $stClass] = $statusInfo($r->status);
                        $itemsData = $r->items->map(fn($it) => [
                            'id'        => $it->id,
                            'name'      => $it->supplyItem->name,
                            'unit'      => $it->supplyItem->unit,
                            'requested' => (float) $it->requested_quantity,
                            'approved'  => $it->approved_quantity === null ? null : (float) $it->approved_quantity,
                        ])->values();
                        $searchText = mb_strtolower($r->request_number.' '.$r->requester->name.' '.$r->purpose.' '.$r->items->map(fn($it) => $it->supplyItem->name)->join(' '));
                    @endphp
                    <tr data-status="{{ $r->status }}" data-search="{{ $searchText }}">
                        <td>
                            <div class="cell-main" style="min-width:140px">
                                <span class="num">{{ $r->request_number }}</span>
                                <span class="req-sub">{{ optional($r->created_at)->translatedFormat('j M Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="person">
                                <span class="avatar-sm">{{ $initials($r->requester->name) }}</span>
                                <span>{{ $r->requester->name }}</span>
                            </div>
                        </td>
                        <td><div class="cell-main" style="min-width:160px"><span class="cell-title" style="font-weight:500">{{ $r->purpose }}</span></div></td>
                        <td>
                            <div class="items">
                                @foreach($r->items as $it)
                                    <div>
                                        <div class="it-name">{{ $it->supplyItem->name }}</div>
                                        <div class="it-meta">
                                            <span class="tag">Minta <b>{{ $num($it->requested_quantity) }}</b></span>
                                            <span class="tag">Setuju <b>{{ $it->approved_quantity === null ? '-' : $num($it->approved_quantity) }}</b></span>
                                            <span class="tag">Keluar <b>{{ $num($it->issued_quantity) }}</b></span>
                                            <span class="tag">{{ $it->supplyItem->unit }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td><span class="badge {{ $stClass }}"><span class="dot"></span>{{ $stLabel }}</span></td>
                        <td class="col-action">
                            @if($r->status === 'submitted')
                                <div class="decide">
                                    <button type="button" class="btn-act approve" data-mode="approve"
                                            data-url="{{ route('admin.inventaris.stock-requests.approve', $r) }}"
                                            data-no="{{ $r->request_number }}" data-by="{{ $r->requester->name }}" data-purpose="{{ $r->purpose }}"
                                            data-items="{{ json_encode($itemsData) }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>Setujui
                                    </button>
                                    <button type="button" class="btn-act reject" data-mode="reject"
                                            data-url="{{ route('admin.inventaris.stock-requests.reject', $r) }}"
                                            data-no="{{ $r->request_number }}" data-by="{{ $r->requester->name }}" data-purpose="{{ $r->purpose }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>Tolak
                                    </button>
                                </div>
                            @elseif($r->status === 'approved')
                                <div class="decide">
                                    <button type="button" class="btn-act issue" data-mode="issue"
                                            data-url="{{ route('admin.inventaris.stock-requests.issue', $r) }}"
                                            data-no="{{ $r->request_number }}" data-by="{{ $r->requester->name }}" data-purpose="{{ $r->purpose }}"
                                            data-items="{{ json_encode($itemsData) }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8zM3 8l9 5 9-5M12 13v8"/></svg>Keluarkan Stok
                                    </button>
                                </div>
                            @else
                                <span class="no-act">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="srEmpty" @if($requests->count()) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg></div>
        <h3 id="srEmptyTitle">{{ $requests->count() ? 'Permintaan tidak ditemukan' : 'Belum ada permintaan' }}</h3>
        <p id="srEmptyText">{{ $requests->count() ? 'Ubah kata kunci atau pilih status lain.' : 'Permintaan ATK dari pegawai akan muncul di sini.' }}</p>
    </div>

    @if($requests->total() > 0)
        <div class="table-foot">
            <div class="foot-info">Menampilkan {{ $requests->firstItem() }}–{{ $requests->lastItem() }} dari {{ $requests->total() }} permintaan</div>
            @if($requests->hasPages())
                <ul class="pager-list">
                    @foreach($requests->onEachSide(1)->linkCollection() as $link)
                        @php
                            $isPrev = $loop->first;
                            $isNext = $loop->last;
                            $svgPrev = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>';
                            $svgNext = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>';
                            $inner = $isPrev ? $svgPrev : ($isNext ? $svgNext : e($link['label']));
                        @endphp
                        @if($link['url'] === null && $link['label'] === '...')
                            <li class="dots"><span>…</span></li>
                        @elseif($link['active'])
                            <li class="active"><span aria-current="page">{{ $link['label'] }}</span></li>
                        @elseif($link['url'] === null)
                            <li class="disabled"><span>{!! $inner !!}</span></li>
                        @else
                            <li><a href="{{ $link['url'] }}" @if($isPrev) aria-label="Sebelumnya" @elseif($isNext) aria-label="Berikutnya" @endif>{!! $inner !!}</a></li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
</div>

<!-- MODAL AKSI -->
<div class="modal-ov" id="srModal" role="dialog" aria-modal="true" aria-labelledby="srMTitle">
    <form class="modal-box" id="srForm" method="POST" action="">
        @csrf
        <div class="m-top">
            <div class="m-ico" id="srMIco"></div>
            <div><h3 id="srMTitle"></h3><p id="srMSub"></p></div>
        </div>
        <div class="m-summary" id="srMSummary"></div>
        <div id="srMBody"></div>
        <div class="m-actions">
            <button type="button" class="m-btn" id="srCancel">Batal</button>
            <button type="submit" class="m-btn primary" id="srSubmit"></button>
        </div>
    </form>
</div>

<script>
(function () {
    var $ = function (id) { return document.getElementById(id); };
    var rows = Array.prototype.slice.call(document.querySelectorAll('#srBody tr'));
    var status = 'all';

    function el(tag, cls, text) {
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (text !== undefined) n.textContent = text;
        return n;
    }
    function fmt(n) { return Number(n).toLocaleString('id-ID', { maximumFractionDigits: 2 }); }

    /* ---------- cari + filter status (halaman yang sedang tampil) ---------- */
    function apply() {
        var q = $('srSearch').value.trim().toLowerCase(), shown = 0;
        rows.forEach(function (tr) {
            var ok = (status === 'all' || tr.getAttribute('data-status') === status) &&
                     (!q || tr.getAttribute('data-search').indexOf(q) > -1);
            tr.hidden = !ok;
            if (ok) shown++;
        });
        if (rows.length) {
            $('srEmpty').hidden = shown > 0;
            $('srEmptyTitle').textContent = 'Permintaan tidak ditemukan';
            $('srEmptyText').textContent = 'Ubah kata kunci atau pilih status lain.';
        }
    }
    $('srSearch').addEventListener('input', apply);
    var chips = $('srChips');
    if (chips) chips.addEventListener('click', function (e) {
        var c = e.target.closest('.chip'); if (!c) return;
        status = c.getAttribute('data-status');
        chips.querySelectorAll('.chip').forEach(function (x) { x.classList.toggle('on', x === c); });
        apply();
    });

    /* ---------- modal ---------- */
    var icons = {
        approve: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>',
        reject:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>',
        issue:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8zM3 8l9 5 9-5M12 13v8"/></svg>'
    };
    var cfg = {
        approve: { color: '#10b981', title: 'Setujui permintaan', submit: 'Setujui', cls: '' },
        reject:  { color: '#ef4444', title: 'Tolak permintaan',   submit: 'Tolak',   cls: 'danger' },
        issue:   { color: '#2563eb', title: 'Keluarkan stok',     submit: 'Keluarkan Stok', cls: 'blue' }
    };
    var lastBtn = null;

    function openModal(btn) {
        lastBtn = btn;
        var mode = btn.getAttribute('data-mode'), c = cfg[mode];
        var items = btn.getAttribute('data-items') ? JSON.parse(btn.getAttribute('data-items')) : [];

        $('srForm').setAttribute('action', btn.getAttribute('data-url'));
        $('srMIco').style.background = c.color;
        $('srMIco').innerHTML = icons[mode];
        $('srMTitle').textContent = c.title;
        $('srMSub').textContent = btn.getAttribute('data-no');

        var sum = $('srMSummary'); sum.innerHTML = '';
        sum.appendChild(el('b', '', btn.getAttribute('data-purpose')));
        sum.appendChild(el('small', '', 'Diajukan oleh ' + btn.getAttribute('data-by')));

        var body = $('srMBody'); body.innerHTML = '';
        var first = null;

        if (mode === 'approve') {
            var lbl = el('span', 'm-label'); lbl.innerHTML = 'Jumlah disetujui <em>(boleh diubah)</em>';
            body.appendChild(lbl);
            items.forEach(function (it) {
                var row = el('div', 'q-row');
                var name = el('div', 'q-name', it.name);
                name.appendChild(el('small', '', 'Diminta ' + fmt(it.requested) + ' ' + (it.unit || '')));
                var inp = el('input', 'q-input');
                inp.type = 'number'; inp.step = '0.01'; inp.min = '0';
                inp.name = 'approved_qty[' + it.id + ']';
                inp.value = it.requested;
                inp.setAttribute('aria-label', 'Jumlah disetujui ' + it.name);
                row.appendChild(name); row.appendChild(inp); body.appendChild(row);
                if (!first) first = inp;
            });
        } else if (mode === 'reject') {
            var l = el('label', 'm-label'); l.setAttribute('for', 'srReason');
            l.innerHTML = 'Alasan penolakan <em>(wajib diisi)</em>';
            var ta = el('textarea', 'm-text');
            ta.id = 'srReason'; ta.name = 'rejection_reason'; ta.required = true; ta.maxLength = 500;
            ta.placeholder = 'Tulis alasan penolakan';
            body.appendChild(l); body.appendChild(ta);
            first = ta;
        } else {
            var l2 = el('span', 'm-label', 'Stok yang akan dikeluarkan');
            body.appendChild(l2);
            items.forEach(function (it) {
                var row = el('div', 'q-row');
                var name = el('div', 'q-name', it.name);
                row.appendChild(name);
                var qty = it.approved === null ? it.requested : it.approved;
                var v = el('div', 'q-name', fmt(qty) + ' ' + (it.unit || ''));
                v.style.textAlign = 'right';
                row.appendChild(v); body.appendChild(row);
            });
            body.appendChild(el('p', 'req-sub', 'Stok gudang akan berkurang dan tindakan ini tidak bisa dibatalkan dari halaman ini.')).style.marginTop = '10px';
        }

        var sb = $('srSubmit');
        sb.disabled = false;
        sb.textContent = c.submit;
        sb.className = 'm-btn primary ' + c.cls;

        $('srModal').classList.add('open');
        setTimeout(function () { (first || sb).focus(); }, 30);
    }
    function closeModal() {
        $('srModal').classList.remove('open');
        if (lastBtn) lastBtn.focus();
    }

    $('srBody').addEventListener('click', function (e) {
        var b = e.target.closest('.btn-act'); if (b) openModal(b);
    });
    $('srCancel').addEventListener('click', closeModal);
    $('srModal').addEventListener('click', function (e) { if (e.target === this) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
    $('srForm').addEventListener('submit', function () {
        var s = $('srSubmit'); s.disabled = true; s.textContent = 'Memproses...';
    });

    apply();
})();
</script>
@endsection
