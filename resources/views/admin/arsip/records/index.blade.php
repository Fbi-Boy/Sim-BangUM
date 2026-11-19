@extends('layouts.app')
@section('title', 'Arsip')

@section('content')
<style>
    .head-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .btn-ghost {
        display: inline-flex; align-items: center; gap: 7px; padding: 7px 13px; border-radius: 10px; text-decoration: none;
        border: 1px solid var(--border-color); background: transparent; color: var(--text-muted);
        font-size: 12.5px; font-weight: 600; transition: all .2s;
    }
    .btn-ghost svg { width: 14px; height: 14px; }
    .btn-ghost:hover { background: var(--bg-hover); color: var(--text-main); }
    .code-tag {
        display: inline-block; padding: 3px 9px; border-radius: 8px; font-size: 12px; font-weight: 600;
        font-variant-numeric: tabular-nums; letter-spacing: .2px;
        background: var(--bg-soft, #f3f6fd); color: var(--text-main);
    }
</style>

@php
    $isPaginator = $records instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $total  = $isPaginator ? $records->total() : $records->count();
    $onPage = $records->count();
    $badgeOf = function ($status) {
        $s = strtolower((string) $status);
        if (preg_match('/(musnah|dimusnahkan|reject|tolak)/', $s))        return 'badge-danger';
        if (preg_match('/(inaktif|pasif|pending|menunggu|proses)/', $s))  return 'badge-warning';
        if (preg_match('/(aktif|active|tersimpan|statis|permanen)/', $s)) return 'badge-success';
        if (preg_match('/(draft|konsep)/', $s))                           return 'badge-muted';
        return 'badge-info';
    };
@endphp

<!-- ===== KEPALA HALAMAN ===== -->
<div class="page-head">
    <div>
        <h2 data-translate>Arsip</h2>
        <p data-translate>Daftar arsip beserta kode, klasifikasi, dan statusnya.</p>
    </div>
    <div class="head-actions">
        <a href="{{ route('admin.arsip.classifications.index') }}" class="btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg>
            <span data-translate>Klasifikasi</span>
        </a>
        <a href="{{ route('admin.arsip.records.create') }}" class="btn-open">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            <span data-translate>Daftarkan Arsip</span>
        </a>
    </div>
</div>

<!-- ===== STATISTIK ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8v13H3V8M1 3h22v5H1zM10 12h4"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total arsip</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h10"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($onPage, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Ditampilkan</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<!-- ===== TABEL ===== -->
<div class="table-card">
    <div class="table-toolbar">
        <label class="tb-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="arsipSearch" placeholder="Cari kode, judul, klasifikasi..." autocomplete="off">
        </label>
        <div class="chips" id="statusChips"></div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>Kode</th>
                    <th data-translate>Judul</th>
                    <th data-translate>Klasifikasi</th>
                    <th data-translate>Tanggal</th>
                    <th data-translate>Status</th>
                    <th class="col-action"></th>
                </tr>
            </thead>
            <tbody id="arsipBody">
                @foreach($records as $r)
                    @php
                        $cls   = $r->classification;
                        $clsTx = $cls ? $cls->code . ' — ' . $cls->name : '-';
                        $date  = $r->record_date?->format('d-m-Y') ?? '-';
                        $href  = route('admin.arsip.records.show', $r);
                    @endphp
                    <tr data-href="{{ $href }}" data-status="{{ $r->archive_status }}"
                        data-search="{{ strtolower($r->archive_code . ' ' . $r->title . ' ' . $clsTx . ' ' . $r->archive_status) }}">
                        <td><span class="code-tag" data-convert="full">{{ $r->archive_code }}</span></td>
                        <td>
                            <div class="cell-main">
                                <span class="cell-title">{{ $r->title }}</span>
                            </div>
                        </td>
                        <td>
                            @if($cls)
                                <div class="cell-main" style="min-width:160px">
                                    <span class="cell-title" data-convert="full">{{ $cls->code }}</span>
                                    <span class="cell-sub">{{ $cls->name }}</span>
                                </div>
                            @else
                                <span class="cell-sub">-</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap" data-convert="digit">{{ $date }}</td>
                        <td><span class="badge {{ $badgeOf($r->archive_status) }}"><span class="dot"></span><span data-translate>{{ $r->archive_status }}</span></span></td>
                        <td class="col-action">
                            <a href="{{ $href }}" class="btn-open"><span data-translate>Detail</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="emptyServer" @if($onPage > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8v13H3V8M1 3h22v5H1zM10 12h4"/></svg></div>
        <h3 data-translate>Belum ada arsip.</h3>
        <p data-translate>Klik "Daftarkan Arsip" untuk menambahkan arsip pertama.</p>
    </div>

    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Arsip tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter status yang lain.</p>
    </div>

    <div class="table-foot">
        <div class="foot-info">
            @if($isPaginator && $records->total() > 0)
                <span data-translate>Menampilkan</span>
                <span data-convert="digit">{{ $records->firstItem() }}–{{ $records->lastItem() }}</span>
                <span data-translate>dari</span>
                <span data-convert="digit">{{ number_format($records->total(), 0, ',', '.') }}</span>
                <span data-translate>arsip</span>
            @endif
        </div>
        @if($isPaginator && $records->hasPages())
            <div>{{ $records->links() }}</div>
        @endif
    </div>
</div>

<script>
(function () {
    var rows = Array.prototype.slice.call(document.querySelectorAll('#arsipBody tr[data-search]'));
    if (!rows.length) return;

    var search = document.getElementById('arsipSearch');
    var chipsEl = document.getElementById('statusChips');
    var emptyF = document.getElementById('emptyFilter');
    var active = 'Semua';
    var applyI18N = function () { if (window.I18N) window.I18N.apply(); };

    /* baris bisa diklik untuk membuka detail (kecuali klik pada link/tombol) */
    rows.forEach(function (r) {
        r.addEventListener('click', function (e) {
            if (e.target.closest('a, button')) return;
            window.location.href = r.getAttribute('data-href');
        });
    });

    var statuses = ['Semua'];
    rows.forEach(function (r) { var s = r.getAttribute('data-status'); if (s && statuses.indexOf(s) < 0) statuses.push(s); });

    function renderChips() {
        chipsEl.innerHTML = '';
        if (statuses.length <= 2) return;
        statuses.forEach(function (s) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'chip' + (s === active ? ' on' : '');
            var span = document.createElement('span'); span.setAttribute('data-translate', ''); span.textContent = s;
            b.appendChild(span);
            b.onclick = function () { active = s; renderChips(); filter(); };
            chipsEl.appendChild(b);
        });
        applyI18N();
    }

    function filter() {
        var q = search.value.trim().toLowerCase(), shown = 0;
        rows.forEach(function (r) {
            var ok = (active === 'Semua' || r.getAttribute('data-status') === active) &&
                     (!q || r.getAttribute('data-search').indexOf(q) > -1);
            r.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        emptyF.hidden = shown > 0;
    }

    search.addEventListener('input', filter);
    renderChips();
})();
</script>
@endsection
