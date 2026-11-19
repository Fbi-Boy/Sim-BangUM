@extends('layouts.app')
@section('title', 'Item ATK')

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
        font-variant-numeric: tabular-nums; letter-spacing: .2px; background: var(--bg-soft, #f3f6fd); color: var(--text-main);
    }
    .stock-cell { display: flex; flex-direction: column; gap: 6px; min-width: 120px; }
    .stock-num { font-weight: 700; font-variant-numeric: tabular-nums; color: var(--text-main); }
    .stock-num small { font-weight: 500; color: var(--text-muted); font-size: 11.5px; }
    .stock-num.low { color: #dc2626; }
    body.dark-mode .stock-num.low { color: #f87171; }
    .stock-bar { height: 5px; border-radius: 10px; background: var(--bg-soft, #f3f6fd); overflow: hidden; }
    .stock-bar i { display: block; height: 100%; border-radius: 10px; background: #10b981; width: 0; transition: width .8s cubic-bezier(.2,.8,.2,1); }
    .stock-bar i.low { background: #ef4444; }
    @media (prefers-reduced-motion: reduce) { .stock-bar i { transition: none; } }
</style>

@php
    $isPaginator = $items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $total  = $isPaginator ? $items->total() : $items->count();
    $onPage = $items->count();
    $isLow  = fn($i) => (int) $i->current_stock <= (int) $i->minimum_stock;
    $lowOnPage = $items->filter($isLow)->count();
@endphp

<!-- ===== KEPALA HALAMAN ===== -->
<div class="page-head">
    <div>
        <h2 data-translate>Item ATK</h2>
        <p data-translate>Daftar persediaan alat tulis kantor beserta stok dan batas minimumnya.</p>
    </div>
    <div class="head-actions">
        <a href="{{ route('admin.inventaris.stock-requests.index') }}" class="btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/></svg>
            <span data-translate>Permintaan ATK</span>
        </a>
        <a href="{{ route('admin.inventaris.supply-items.create') }}" class="btn-open">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            <span data-translate>Tambah</span>
        </a>
    </div>
</div>

<!-- ===== STATISTIK ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total item</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4m0 4h.01M10.3 3.9L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($lowOnPage, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Stok rendah</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($onPage - $lowOnPage, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Stok aman</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<!-- ===== TABEL ===== -->
<div class="table-card">
    <div class="table-toolbar">
        <label class="tb-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="itemSearch" placeholder="Cari SKU, nama, kategori..." autocomplete="off">
        </label>
        <div class="chips" id="stockChips">
            <button type="button" class="chip on" data-f="all"><span data-translate>Semua</span></button>
            <button type="button" class="chip" data-f="low"><span data-translate>Stok Rendah</span></button>
            <button type="button" class="chip" data-f="ok"><span data-translate>Aman</span></button>
        </div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>SKU</th>
                    <th data-translate>Nama</th>
                    <th data-translate>Kategori</th>
                    <th data-translate>Satuan</th>
                    <th data-translate>Stok</th>
                    <th data-translate>Min</th>
                    <th data-translate>Status</th>
                </tr>
            </thead>
            <tbody id="itemBody">
                @foreach($items as $i)
                    @php
                        $cur  = (int) $i->current_stock;
                        $min  = (int) $i->minimum_stock;
                        $low  = $isLow($i);
                        $cat  = $i->category?->name ?? '-';
                        /* bar: stok relatif terhadap 2x batas minimum (penuh bila >= 2x min) */
                        $pct  = $min > 0 ? min(100, (int) round($cur / ($min * 2) * 100)) : ($cur > 0 ? 100 : 0);
                    @endphp
                    <tr data-low="{{ $low ? 1 : 0 }}" data-search="{{ strtolower($i->sku . ' ' . $i->name . ' ' . $cat . ' ' . $i->unit) }}">
                        <td><span class="code-tag" data-convert="full">{{ $i->sku }}</span></td>
                        <td><div class="cell-main" style="min-width:180px"><span class="cell-title">{{ $i->name }}</span></div></td>
                        <td>{{ $cat }}</td>
                        <td>{{ $i->unit }}</td>
                        <td>
                            <div class="stock-cell">
                                <span class="stock-num {{ $low ? 'low' : '' }}"><span data-convert="digit">{{ number_format($cur, 0, ',', '.') }}</span> <small>{{ $i->unit }}</small></span>
                                <div class="stock-bar"><i class="{{ $low ? 'low' : '' }}" data-w="{{ $pct }}"></i></div>
                            </div>
                        </td>
                        <td data-convert="digit">{{ number_format($min, 0, ',', '.') }}</td>
                        <td>
                            @if($cur <= 0)
                                <span class="badge badge-danger"><span class="dot"></span><span data-translate>Habis</span></span>
                            @elseif($low)
                                <span class="badge badge-warning"><span class="dot"></span><span data-translate>Stok Rendah</span></span>
                            @else
                                <span class="badge badge-success"><span class="dot"></span><span data-translate>Aman</span></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="emptyServer" @if($onPage > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <h3 data-translate>Belum ada item.</h3>
        <p data-translate>Klik "Tambah" untuk menambahkan item ATK pertama.</p>
    </div>

    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Item tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter yang lain.</p>
    </div>

    <div class="table-foot">
        <div class="foot-info">
            @if($isPaginator && $items->total() > 0)
                <span data-translate>Menampilkan</span>
                <span data-convert="digit">{{ $items->firstItem() }}–{{ $items->lastItem() }}</span>
                <span data-translate>dari</span>
                <span data-convert="digit">{{ number_format($items->total(), 0, ',', '.') }}</span>
                <span data-translate>item</span>
            @endif
        </div>
        @if($isPaginator && $items->hasPages())
            <div>{{ $items->links() }}</div>
        @endif
    </div>
</div>

<p style="margin-top:4px">
    <a href="{{ route('dashboard') }}" class="btn-open">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="transform:rotate(180deg)"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        <span data-translate>Dashboard</span>
    </a>
</p>

<script>
(function () {
    var rows = Array.prototype.slice.call(document.querySelectorAll('#itemBody tr[data-search]'));
    if (!rows.length) return;

    /* isi bar stok dengan animasi */
    requestAnimationFrame(function () {
        document.querySelectorAll('.stock-bar i[data-w]').forEach(function (el) { el.style.width = el.getAttribute('data-w') + '%'; });
    });

    var search = document.getElementById('itemSearch');
    var chips = document.querySelectorAll('#stockChips .chip');
    var emptyF = document.getElementById('emptyFilter');
    var active = 'all';

    function filter() {
        var q = search.value.trim().toLowerCase(), shown = 0;
        rows.forEach(function (r) {
            var low = r.getAttribute('data-low') === '1';
            var okChip = active === 'all' || (active === 'low' && low) || (active === 'ok' && !low);
            var ok = okChip && (!q || r.getAttribute('data-search').indexOf(q) > -1);
            r.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        emptyF.hidden = shown > 0;
    }

    chips.forEach(function (c) {
        c.addEventListener('click', function () {
            active = c.getAttribute('data-f');
            chips.forEach(function (x) { x.classList.toggle('on', x === c); });
            filter();
        });
    });
    search.addEventListener('input', filter);
})();
</script>
@endsection
