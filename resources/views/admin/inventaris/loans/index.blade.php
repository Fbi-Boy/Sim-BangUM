@extends('layouts.app')
@section('title', 'Peminjaman Aset')

@section('content')
<style>
    .code-tag {
        display: inline-block; padding: 3px 9px; border-radius: 8px; font-size: 12px; font-weight: 600;
        font-variant-numeric: tabular-nums; letter-spacing: .2px; background: var(--bg-soft, #f3f6fd); color: var(--text-main);
    }
    .act-form { display: inline-block; margin: 0; }
    .btn-act {
        display: inline-flex; align-items: center; gap: 6px; padding: 7px 13px; border-radius: 10px; border: none; cursor: pointer;
        font-family: inherit; font-size: 12.5px; font-weight: 600; transition: background .2s, color .2s, transform .15s;
    }
    .btn-act svg { width: 14px; height: 14px; }
    .btn-act:hover { transform: translateY(-1px); }
    .btn-act.approve { background: rgba(16,185,129,.13); color: #059669; }
    .btn-act.approve:hover { background: #059669; color: #fff; }
    .btn-act.return  { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .btn-act.return:hover { background: #2563eb; color: #fff; }
    body.dark-mode .btn-act.approve { color: #34d399; }
    body.dark-mode .btn-act.approve:hover { background: #10b981; color: #fff; }
    .no-act { color: var(--text-muted); font-size: 12px; }
</style>

@php
    $isPaginator = $loans instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $total     = $isPaginator ? $loans->total() : $loans->count();
    $onPage    = $loans->count();
    $submitted = $loans->where('status', 'submitted')->count();
    $issued    = $loans->where('status', 'issued')->count();

    $labels = [
        'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'issued' => 'Dipinjam',
        'returned'  => 'Dikembalikan', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan',
    ];
    $labelOf = fn($s) => $labels[strtolower((string) $s)] ?? $s;
    $badgeOf = function ($status) {
        return match (strtolower((string) $status)) {
            'submitted'            => 'badge-warning',
            'approved', 'issued'   => 'badge-info',
            'returned'             => 'badge-success',
            'rejected', 'cancelled'=> 'badge-danger',
            default                => 'badge-muted',
        };
    };
    $initialsOf = fn($name) => mb_strtoupper(collect(preg_split('/\s+/', trim((string) $name)))->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));
@endphp

<!-- ===== KEPALA HALAMAN ===== -->
<div class="page-head">
    <div>
        <h2 data-translate>Daftar Peminjaman Aset</h2>
        <p data-translate>Pantau pengajuan, persetujuan, dan pengembalian aset.</p>
    </div>
</div>

<!-- ===== STATISTIK ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total peminjaman</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($submitted, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Menunggu persetujuan</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h12M12 6l6 6-6 6"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($issued, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Sedang dipinjam</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<!-- ===== TABEL ===== -->
<div class="table-card">
    <div class="table-toolbar">
        <label class="tb-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="loanSearch" placeholder="Cari no. pinjaman, aset, peminjam..." autocomplete="off">
        </label>
        <div class="chips" id="statusChips"></div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>No Pinjaman</th>
                    <th data-translate>Aset</th>
                    <th data-translate>Peminjam</th>
                    <th data-translate>Tgl Mulai</th>
                    <th data-translate>Status</th>
                    <th class="col-action" data-translate>Aksi</th>
                </tr>
            </thead>
            <tbody id="loanBody">
                @foreach($loans as $l)
                    @php
                        $asset = $l->asset?->name ?? '-';
                        $who   = $l->requester?->name ?? '-';
                        $date  = $l->starts_at?->format('d-m-Y') ?? '-';
                        $label = $labelOf($l->status);
                    @endphp
                    <tr data-status="{{ $l->status }}" data-label="{{ $label }}"
                        data-search="{{ strtolower($l->loan_number . ' ' . $asset . ' ' . $who . ' ' . $label . ' ' . $l->status) }}">
                        <td><span class="code-tag" data-convert="full">{{ $l->loan_number }}</span></td>
                        <td><div class="cell-main" style="min-width:160px"><span class="cell-title">{{ $asset }}</span></div></td>
                        <td>
                            <div class="person">
                                <div class="avatar-sm">{{ $initialsOf($who) ?: '?' }}</div>
                                <span data-convert="full">{{ $who }}</span>
                            </div>
                        </td>
                        <td style="white-space:nowrap" data-convert="digit">{{ $date }}</td>
                        <td><span class="badge {{ $badgeOf($l->status) }}"><span class="dot"></span><span data-translate>{{ $label }}</span></span></td>
                        <td class="col-action">
                            @if($l->status === 'submitted')
                                <form method="POST" action="{{ route('admin.inventaris.loans.approve', $l) }}" class="act-form">
                                    @csrf
                                    <button type="submit" class="btn-act approve">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>
                                        <span data-translate>Setujui</span>
                                    </button>
                                </form>
                            @elseif($l->status === 'issued')
                                <form method="POST" action="{{ route('admin.inventaris.loans.return', $l) }}" class="act-form">
                                    @csrf
                                    <button type="submit" class="btn-act return">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 14L4 9l5-5M4 9h11a5 5 0 010 10h-3"/></svg>
                                        <span data-translate>Kembali</span>
                                    </button>
                                </form>
                            @else
                                <span class="no-act">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="emptyServer" @if($onPage > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <h3 data-translate>Belum ada peminjaman.</h3>
        <p data-translate>Pengajuan peminjaman aset akan muncul di sini.</p>
    </div>

    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Peminjaman tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter status yang lain.</p>
    </div>

    <div class="table-foot">
        <div class="foot-info">
            @if($isPaginator && $loans->total() > 0)
                <span data-translate>Menampilkan</span>
                <span data-convert="digit">{{ $loans->firstItem() }}–{{ $loans->lastItem() }}</span>
                <span data-translate>dari</span>
                <span data-convert="digit">{{ number_format($loans->total(), 0, ',', '.') }}</span>
                <span data-translate>peminjaman</span>
            @endif
        </div>
        @if($isPaginator && $loans->hasPages())
            <div>{{ $loans->links() }}</div>
        @endif
    </div>
</div>

<script>
(function () {
    var rows = Array.prototype.slice.call(document.querySelectorAll('#loanBody tr[data-search]'));
    if (!rows.length) return;

    var search = document.getElementById('loanSearch');
    var chipsEl = document.getElementById('statusChips');
    var emptyF = document.getElementById('emptyFilter');
    var active = '';   /* '' = semua; selain itu = nilai status mentah */
    var applyI18N = function () { if (window.I18N) window.I18N.apply(); };

    /* chip status dibentuk dari data di halaman ini */
    var statuses = [];
    rows.forEach(function (r) {
        var s = r.getAttribute('data-status');
        if (!statuses.some(function (x) { return x.v === s; })) statuses.push({ v: s, l: r.getAttribute('data-label') });
    });

    function renderChips() {
        chipsEl.innerHTML = '';
        if (statuses.length < 2) return;
        [{ v: '', l: 'Semua' }].concat(statuses).forEach(function (s) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'chip' + (s.v === active ? ' on' : '');
            var span = document.createElement('span'); span.setAttribute('data-translate', ''); span.textContent = s.l;
            b.appendChild(span);
            b.onclick = function () { active = s.v; renderChips(); filter(); };
            chipsEl.appendChild(b);
        });
        applyI18N();
    }

    function filter() {
        var q = search.value.trim().toLowerCase(), shown = 0;
        rows.forEach(function (r) {
            var ok = (!active || r.getAttribute('data-status') === active) &&
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
