@extends('layouts.app')
@section('title', 'Barang Milik Daerah')

@section('content')
<style>
    .code-tag {
        display: inline-block; padding: 3px 9px; border-radius: 8px; font-size: 12px; font-weight: 600;
        font-variant-numeric: tabular-nums; letter-spacing: .2px; background: var(--bg-soft, #f3f6fd); color: var(--text-main);
    }
    .tb-form { display: flex; align-items: center; gap: 8px; flex: 1; min-width: 260px; max-width: 460px; }
    .tb-form .tb-search { flex: 1; max-width: none; }
    .btn-search {
        height: 40px; padding: 0 16px; border: none; border-radius: 12px; cursor: pointer; font-family: inherit;
        font-size: 12.5px; font-weight: 600; background: var(--accent, #2563eb); color: #fff; transition: background .2s;
    }
    .btn-search:hover { background: #1d4ed8; }
    .btn-reset {
        display: inline-flex; align-items: center; height: 40px; padding: 0 12px; border-radius: 12px; text-decoration: none;
        font-size: 12.5px; font-weight: 500; color: var(--text-muted); border: 1px solid var(--border-color); transition: all .2s;
    }
    .btn-reset:hover { background: var(--bg-hover); color: var(--text-main); }
    .money { font-variant-numeric: tabular-nums; white-space: nowrap; font-weight: 600; }
</style>

@php
    $isPaginator = $items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $total  = $isPaginator ? $items->total() : $items->count();
    $onPage = $items->count();
    $q      = trim((string) request('q'));
    $sumPage = $items->sum(fn($i) => (float) ($i->acquisition_value ?? 0));
    $rupiah  = fn($v) => 'Rp ' . number_format($v, 0, ',', '.');
    $badgeOf = function ($status) {
        $s = strtolower((string) $status);
        if (preg_match('/(rusak berat|dihapus|hapus|hilang|musnah)/', $s)) return 'badge-danger';
        if (preg_match('/(rusak ringan|rusak|perbaikan|pending)/', $s))     return 'badge-warning';
        if (preg_match('/(baik|aktif|digunakan|dipakai)/', $s))             return 'badge-success';
        if ($s === '')                                                      return 'badge-muted';
        return 'badge-info';
    };
@endphp

<!-- ===== KEPALA HALAMAN ===== -->
<div class="page-head">
    <div>
        <h2 data-translate>Barang Milik Daerah</h2>
        <p data-translate>Daftar aset BMD beserta kode, tahun perolehan, nilai, dan statusnya.</p>
    </div>
    <a href="{{ route('admin.barang-daerah.assets.create') }}" class="btn-open">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        <span data-translate>Tambah BMD</span>
    </a>
</div>

<!-- ===== STATISTIK ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1M9 13h1m4 0h1M9 17h6"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl">
                <span data-translate>{{ $q !== '' ? 'Hasil pencarian' : 'Total BMD' }}</span>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
        <div>
            <div class="stat-val" style="font-size:19px" data-convert="digit">{{ $rupiah($sumPage) }}</div>
            <div class="stat-lbl"><span data-translate>Nilai perolehan</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<!-- ===== TABEL ===== -->
<div class="table-card">
    <div class="table-toolbar">
        <form method="GET" class="tb-form" role="search">
            <label class="tb-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, nama, kategori..." autocomplete="off">
            </label>
            <button type="submit" class="btn-search" data-translate>Cari</button>
            @if($q !== '')
                <a href="{{ url()->current() }}" class="btn-reset" data-translate>Reset</a>
            @endif
        </form>
        <div class="chips" id="statusChips"></div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>Kode BMD</th>
                    <th data-translate>Nama</th>
                    <th data-translate>Kategori</th>
                    <th data-translate>Tahun</th>
                    <th data-translate>Nilai</th>
                    <th data-translate>Status</th>
                    <th class="col-action"></th>
                </tr>
            </thead>
            <tbody id="bmdBody">
                @foreach($items as $i)
                    @php $href = route('admin.barang-daerah.assets.show', $i); @endphp
                    <tr data-href="{{ $href }}" data-status="{{ $i->bmd_status }}">
                        <td><span class="code-tag" data-convert="full">{{ $i->bmd_code }}</span></td>
                        <td><div class="cell-main" style="min-width:180px"><span class="cell-title">{{ $i->name }}</span></div></td>
                        <td>{{ $i->category ?? '-' }}</td>
                        <td data-convert="digit">{{ $i->year_acquired ?? '-' }}</td>
                        <td class="money" data-convert="digit">{{ $i->acquisition_value ? $rupiah($i->acquisition_value) : '-' }}</td>
                        <td>
                            @if($i->bmd_status)
                                <span class="badge {{ $badgeOf($i->bmd_status) }}"><span class="dot"></span><span data-translate>{{ $i->bmd_status }}</span></span>
                            @else
                                <span class="badge badge-muted">-</span>
                            @endif
                        </td>
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
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1M9 13h1m4 0h1M9 17h6"/></svg></div>
        @if($q !== '')
            <h3 data-translate>Data BMD tidak ditemukan</h3>
            <p data-translate>Coba kata kunci yang lain atau reset pencarian.</p>
        @else
            <h3 data-translate>Belum ada data BMD.</h3>
            <p data-translate>Klik "Tambah BMD" untuk menambahkan data pertama.</p>
        @endif
    </div>

    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Tidak ada data dengan status ini</h3>
        <p data-translate>Pilih status lain atau tampilkan semua.</p>
    </div>

    <div class="table-foot">
        <div class="foot-info">
            @if($isPaginator && $items->total() > 0)
                <span data-translate>Menampilkan</span>
                <span data-convert="digit">{{ $items->firstItem() }}–{{ $items->lastItem() }}</span>
                <span data-translate>dari</span>
                <span data-convert="digit">{{ number_format($items->total(), 0, ',', '.') }}</span>
                <span data-translate>BMD</span>
            @endif
        </div>
        @if($isPaginator && $items->hasPages())
            <div>{{ $items->withQueryString()->links() }}</div>
        @endif
    </div>
</div>

<script>
(function () {
    var rows = Array.prototype.slice.call(document.querySelectorAll('#bmdBody tr[data-status]'));
    if (!rows.length) return;

    var chipsEl = document.getElementById('statusChips');
    var emptyF = document.getElementById('emptyFilter');
    var active = '';
    var applyI18N = function () { if (window.I18N) window.I18N.apply(); };

    /* baris bisa diklik untuk membuka detail (kecuali klik pada link/tombol) */
    rows.forEach(function (r) {
        r.addEventListener('click', function (e) {
            if (e.target.closest('a, button')) return;
            window.location.href = r.getAttribute('data-href');
        });
    });

    /* chip status dibentuk dari data di halaman ini (pencarian tetap di server lewat ?q=) */
    var statuses = [];
    rows.forEach(function (r) { var s = r.getAttribute('data-status'); if (s && statuses.indexOf(s) < 0) statuses.push(s); });

    function renderChips() {
        chipsEl.innerHTML = '';
        if (statuses.length < 2) return;
        [''].concat(statuses).forEach(function (s) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'chip' + (s === active ? ' on' : '');
            var span = document.createElement('span'); span.setAttribute('data-translate', ''); span.textContent = s || 'Semua';
            b.appendChild(span);
            b.onclick = function () { active = s; renderChips(); filter(); };
            chipsEl.appendChild(b);
        });
        applyI18N();
    }

    function filter() {
        var shown = 0;
        rows.forEach(function (r) {
            var ok = !active || r.getAttribute('data-status') === active;
            r.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        emptyF.hidden = shown > 0;
    }

    renderChips();
})();
</script>
@endsection
