@extends('layouts.app')
@section('title', 'Inbox Persetujuan')

@section('content')
@php
    /* ============ KONFIGURASI KATEGORI ============ */
    $icon = [
        'asset'   => '<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
        'vehicle' => '<path d="M5 17h14M5 17a2 2 0 1 0 4 0M15 17a2 2 0 1 0 4 0M3 17V9l2-4h11l3 4h2v8"/>',
        'room'    => '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
        'atk'     => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/>',
        'travel'  => '<path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>',
    ];

    $sections = [
        ['key' => 'asset-loan',      'label' => 'Peminjaman Aset',   'ico' => 'asset',   'badge' => 'badge-info',    'items' => $asset_loans,       'title' => fn($i) => $i->asset->name,                          'sub' => fn($i) => 'Peminjaman aset'],
        ['key' => 'vehicle-request', 'label' => 'Kendaraan Dinas',   'ico' => 'vehicle', 'badge' => 'badge-warning', 'items' => $vehicle_requests,  'title' => fn($i) => $i->destination,                         'sub' => fn($i) => 'Tujuan perjalanan'],
        ['key' => 'room-booking',    'label' => 'Booking Ruangan',   'ico' => 'room',    'badge' => 'badge-success', 'items' => $room_bookings,     'title' => fn($i) => $i->room->name,                           'sub' => fn($i) => $i->purpose],
        ['key' => 'stock-request',   'label' => 'Permintaan ATK',    'ico' => 'atk',     'badge' => 'badge-danger',  'items' => $stock_requests,    'title' => fn($i) => $i->purpose,                              'sub' => fn($i) => 'Permintaan barang'],
        ['key' => 'travel-request',  'label' => 'Perjalanan Dinas',  'ico' => 'travel',  'badge' => 'badge-muted',   'items' => $travel_requests,   'title' => fn($i) => $i->destination,                         'sub' => fn($i) => 'Tujuan perjalanan'],
    ];

    $initials = fn($name) => mb_strtoupper(collect(preg_split('/\s+/', trim($name)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));

    /* ============ GABUNGKAN SEMUA JENIS JADI SATU DAFTAR (terlama di atas) ============ */
    $rows = collect();
    foreach ($sections as $s) {
        foreach ($s['items'] as $item) {
            $rows->push([
                'cat'     => $s['key'],
                'label'   => $s['label'],
                'badge'   => $s['badge'],
                'ico'     => $s['ico'],
                'id'      => $item->id,
                'title'   => $s['title']($item),
                'sub'     => $s['sub']($item),
                'by'      => $item->requester->name,
                'created' => $item->created_at,
                'url'     => route('pejabat.approvals.decide', [$s['key'], $item->id]),
            ]);
        }
    }
    $rows  = $rows->sortBy('created')->values();
    $total = $rows->count();
    $today = $rows->filter(fn($r) => $r['created']->isToday())->count();
    $late  = $rows->filter(fn($r) => $r['created']->lt(now()->subDays(3)))->count();
@endphp

<style>
    /* Gaya khusus halaman ini (modal & tombol keputusan). Selebihnya memakai UI kit. */
    .alert-box { display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; border-radius: 12px; font-size: 13px; margin-bottom: 16px; }
    .alert-box.ok  { background: rgba(16,185,129,.13); color: #059669; }
    .alert-box.err { background: rgba(239,68,68,.13);  color: #dc2626; }
    body.dark-mode .alert-box.ok  { color: #34d399; }
    body.dark-mode .alert-box.err { color: #f87171; }

    .cat-ico { width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; flex-shrink: 0; background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .cat-ico svg { width: 17px; height: 17px; }
    .cell-cat { display: flex; align-items: center; gap: 10px; }

    .age { display: inline-flex; flex-direction: column; gap: 2px; white-space: nowrap; }
    .age b { font-weight: 500; font-size: 13px; }
    .age span { font-size: 11px; color: var(--text-muted); }
    .age.late b, .age.late span { color: #dc2626; font-weight: 600; }
    body.dark-mode .age.late b, body.dark-mode .age.late span { color: #f87171; }

    .decide { display: inline-flex; gap: 8px; }
    .btn-act { display: inline-flex; align-items: center; gap: 6px; padding: 7px 13px; border-radius: 10px; border: 1px solid transparent; font-size: 12.5px; font-weight: 600; font-family: inherit; cursor: pointer; transition: background .2s, color .2s, border-color .2s; }
    .btn-act svg { width: 14px; height: 14px; }
    .btn-act.approve { background: rgba(16,185,129,.13); color: #059669; }
    .btn-act.approve:hover { background: #10b981; color: #fff; }
    .btn-act.reject { background: transparent; color: #dc2626; border-color: rgba(239,68,68,.35); }
    .btn-act.reject:hover { background: #ef4444; color: #fff; border-color: #ef4444; }
    body.dark-mode .btn-act.approve { color: #34d399; }
    body.dark-mode .btn-act.reject { color: #f87171; }

    .modal-ov { position: fixed; inset: 0; z-index: 200; background: rgba(15,23,42,.55); backdrop-filter: blur(3px); display: none; align-items: center; justify-content: center; padding: 20px; }
    .modal-ov.open { display: flex; }
    .modal-box { width: 100%; max-width: 440px; background: var(--bg-card); border-radius: 20px; padding: 24px; box-shadow: var(--shadow-pop); animation: pop .2s ease; }
    @keyframes pop { from { transform: translateY(10px) scale(.97); opacity: 0; } to { transform: none; opacity: 1; } }
    .m-top { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
    .m-top .m-ico { width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center; color: #fff; flex-shrink: 0; }
    .m-top .m-ico svg { width: 22px; height: 22px; }
    .m-top h3 { font-size: 16px; color: var(--text-main); }
    .m-top p { font-size: 12.5px; color: var(--text-muted); margin-top: 2px; }
    .m-summary { padding: 12px 14px; border-radius: 12px; background: var(--bg-soft, #f3f6fd); font-size: 13px; line-height: 1.5; margin-bottom: 14px; color: var(--text-main); }
    .m-summary small { display: block; font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }
    .m-label { display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 6px; color: var(--text-main); }
    .m-label em { font-style: normal; font-weight: 400; color: var(--text-muted); }
    .m-text { width: 100%; min-height: 92px; max-width: none; resize: vertical; padding: 11px 13px; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main); font: inherit; font-size: 13px; outline: none; transition: border-color .2s, box-shadow .2s; }
    .m-text:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,.14); }
    .m-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px; }
    .m-btn { padding: 9px 18px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main); font-size: 12.5px; font-weight: 500; cursor: pointer; font-family: inherit; }
    .m-btn.primary { background: #10b981; border-color: #10b981; color: #fff; font-weight: 600; }
    .m-btn.primary.danger { background: #ef4444; border-color: #ef4444; }

    button:focus-visible, a:focus-visible { outline: 2px solid #3b82f6; outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .modal-box { animation: none !important; } }
    @media (max-width: 720px) { .decide { flex-direction: column; } }
</style>

<div class="page-head">
    <div>
        <h2>Inbox Persetujuan</h2>
        <p>Tinjau pengajuan yang menunggu keputusan Anda. Pengajuan terlama ditampilkan paling atas.</p>
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
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <div><div class="stat-val">{{ $total }}</div><div class="stat-lbl">Menunggu keputusan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg></div>
        <div><div class="stat-val">{{ $today }}</div><div class="stat-lbl">Masuk hari ini</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico {{ $late ? 'red' : 'amber' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4m0 4h.01M10.3 3.9L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg></div>
        <div><div class="stat-val">{{ $late }}</div><div class="stat-lbl">Lebih dari 3 hari <small>perlu segera diputuskan</small></div></div>
    </div>
</div>

<!-- TABEL -->
<div class="table-card">
    <div class="table-toolbar">
        <label class="tb-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="apSearch" placeholder="Cari ringkasan atau nama pemohon..." autocomplete="off" aria-label="Cari pengajuan">
        </label>
        <div class="chips" id="apChips">
            <button type="button" class="chip on" data-cat="all">Semua ({{ $total }})</button>
            @foreach($sections as $s)
                <button type="button" class="chip" data-cat="{{ $s['key'] }}">{{ $s['label'] }} ({{ $s['items']->count() }})</button>
            @endforeach
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Ringkasan</th>
                    <th>Pemohon</th>
                    <th>Diajukan</th>
                    <th class="col-action">Keputusan</th>
                </tr>
            </thead>
            <tbody id="apBody">
                @foreach($rows as $r)
                    @php $isLate = $r['created']->lt(now()->subDays(3)); @endphp
                    <tr data-cat="{{ $r['cat'] }}" data-search="{{ mb_strtolower($r['label'].' '.$r['title'].' '.$r['sub'].' '.$r['by']) }}">
                        <td>
                            <div class="cell-cat">
                                <span class="cat-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icon[$r['ico']] !!}</svg></span>
                                <span class="badge {{ $r['badge'] }}">{{ $r['label'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="cell-main">
                                <span class="cell-title">{{ $r['title'] }}</span>
                                <span class="cell-sub">{{ $r['sub'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="person">
                                <span class="avatar-sm">{{ $initials($r['by']) }}</span>
                                <span>{{ $r['by'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="age {{ $isLate ? 'late' : '' }}">
                                <b>{{ $r['created']->translatedFormat('j M Y') }}</b>
                                <span>{{ $r['created']->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="col-action">
                            <div class="decide">
                                <button type="button" class="btn-act approve" data-decision="approve" data-url="{{ $r['url'] }}" data-title="{{ $r['title'] }}" data-by="{{ $r['by'] }}" data-label="{{ $r['label'] }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>Setujui
                                </button>
                                <button type="button" class="btn-act reject" data-decision="reject" data-url="{{ $r['url'] }}" data-title="{{ $r['title'] }}" data-by="{{ $r['by'] }}" data-label="{{ $r['label'] }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="apEmpty" @if($total) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <h3 id="apEmptyTitle">{{ $total ? 'Tidak ada hasil' : 'Semua sudah diputuskan' }}</h3>
        <p id="apEmptyText">{{ $total ? 'Ubah kata kunci atau pilih kategori lain.' : 'Belum ada pengajuan baru yang menunggu persetujuan Anda.' }}</p>
    </div>

    <div class="table-foot" @if(!$total) hidden @endif>
        <div class="foot-info" id="apInfo"></div>
    </div>
</div>

<!-- MODAL KEPUTUSAN -->
<div class="modal-ov" id="apModal" role="dialog" aria-modal="true" aria-labelledby="apMTitle">
    <form class="modal-box" id="apForm" method="POST" action="">
        @csrf
        <input type="hidden" name="decision" id="apDecision">
        <div class="m-top">
            <div class="m-ico" id="apMIco"></div>
            <div><h3 id="apMTitle"></h3><p id="apMSub"></p></div>
        </div>
        <div class="m-summary" id="apMSummary"></div>
        <label class="m-label" for="apNote" id="apMLabel"></label>
        <textarea class="m-text" name="note" id="apNote" maxlength="500"></textarea>
        <div class="m-actions">
            <button type="button" class="m-btn" id="apCancel">Batal</button>
            <button type="submit" class="m-btn primary" id="apSubmit"></button>
        </div>
    </form>
</div>

<script>
(function () {
    var $ = function (id) { return document.getElementById(id); };
    var rows = Array.prototype.slice.call(document.querySelectorAll('#apBody tr'));
    var total = rows.length, cat = 'all';

    /* ---------- filter + cari ---------- */
    function apply() {
        var q = $('apSearch').value.trim().toLowerCase(), shown = 0;
        rows.forEach(function (tr) {
            var ok = (cat === 'all' || tr.getAttribute('data-cat') === cat) &&
                     (!q || tr.getAttribute('data-search').indexOf(q) > -1);
            tr.hidden = !ok;
            if (ok) shown++;
        });
        var empty = $('apEmpty');
        if (total) {
            empty.hidden = shown > 0;
            $('apEmptyTitle').textContent = 'Tidak ada hasil';
            $('apEmptyText').textContent = 'Ubah kata kunci atau pilih kategori lain.';
        }
        $('apInfo').textContent = 'Menampilkan ' + shown + ' dari ' + total + ' pengajuan';
    }
    $('apSearch').addEventListener('input', apply);
    $('apChips').addEventListener('click', function (e) {
        var c = e.target.closest('.chip'); if (!c) return;
        cat = c.getAttribute('data-cat');
        document.querySelectorAll('#apChips .chip').forEach(function (x) { x.classList.toggle('on', x === c); });
        apply();
    });

    /* ---------- modal keputusan ---------- */
    var okIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>';
    var noIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>';
    var lastBtn = null;

    function openModal(btn) {
        lastBtn = btn;
        var approve = btn.getAttribute('data-decision') === 'approve';
        $('apForm').setAttribute('action', btn.getAttribute('data-url'));
        $('apDecision').value = btn.getAttribute('data-decision');
        $('apMIco').style.background = approve ? '#10b981' : '#ef4444';
        $('apMIco').innerHTML = approve ? okIcon : noIcon;
        $('apMTitle').textContent = approve ? 'Setujui pengajuan' : 'Tolak pengajuan';
        $('apMSub').textContent = btn.getAttribute('data-label');
        $('apMSummary').innerHTML = '';
        var b = document.createElement('b'); b.textContent = btn.getAttribute('data-title');
        var s = document.createElement('small'); s.textContent = 'Diajukan oleh ' + btn.getAttribute('data-by');
        $('apMSummary').appendChild(b); $('apMSummary').appendChild(s);

        var note = $('apNote');
        note.value = '';
        note.required = !approve;
        note.placeholder = approve ? 'Tambahkan catatan jika perlu' : 'Tulis alasan penolakan';
        $('apMLabel').innerHTML = approve ? 'Catatan <em>(opsional)</em>' : 'Alasan penolakan <em>(wajib diisi)</em>';
        $('apSubmit').textContent = approve ? 'Setujui' : 'Tolak';
        $('apSubmit').classList.toggle('danger', !approve);

        $('apModal').classList.add('open');
        setTimeout(function () { note.focus(); }, 30);
    }
    function closeModal() {
        $('apModal').classList.remove('open');
        if (lastBtn) lastBtn.focus();
    }

    $('apBody').addEventListener('click', function (e) {
        var b = e.target.closest('.btn-act'); if (b) openModal(b);
    });
    $('apCancel').addEventListener('click', closeModal);
    $('apModal').addEventListener('click', function (e) { if (e.target === this) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    /* cegah klik ganda saat kirim */
    $('apForm').addEventListener('submit', function () {
        var s = $('apSubmit'); s.disabled = true; s.textContent = 'Memproses...';
    });

    apply();
})();
</script>
@endsection
