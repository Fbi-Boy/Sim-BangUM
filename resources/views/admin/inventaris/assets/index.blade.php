@extends('layouts.app')
@section('title', 'Inventaris Aset')

@section('content')
@php
    /* Pilih warna badge berdasarkan kata kunci pada nilai status / kondisi. */
    $tone = function ($value) {
        $v = mb_strtolower((string) $value);
        if (preg_match('/(baik|good|tersedia|available|aktif)/', $v))           return 'badge-success';
        if (preg_match('/(pinjam|borrow|dipakai|digunakan|in_use|use)/', $v))   return 'badge-info';
        if (preg_match('/(ringan|perbaikan|maintenance|servis|service|repair)/', $v)) return 'badge-warning';
        if (preg_match('/(rusak|damaged|hilang|lost|berat|broken)/', $v))       return 'badge-danger';
        return 'badge-muted';
    };
    $label = fn($v) => ucwords(str_replace('_', ' ', (string) $v));

    $statuses = $assets->getCollection()->pluck('status')->filter()->unique()->values();
    $from = $assets->firstItem();
    $to   = $assets->lastItem();
@endphp

<style>
    .btn-primary-sm { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 12px; background: #2563eb; color: #fff; font-size: 13px; font-weight: 600; text-decoration: none; box-shadow: 0 8px 18px rgba(37,99,235,.25); transition: transform .2s, background .2s; }
    .btn-primary-sm svg { width: 15px; height: 15px; }
    .btn-primary-sm:hover { background: #1d4ed8; transform: translateY(-2px); }
    .asset-ico { width: 38px; height: 38px; border-radius: 12px; display: grid; place-items: center; flex-shrink: 0; background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .asset-ico svg { width: 18px; height: 18px; }
    .cell-asset { display: flex; align-items: center; gap: 12px; min-width: 240px; }
    .code { font-size: 12px; color: var(--text-muted); letter-spacing: .3px; }
    button:focus-visible, a:focus-visible { outline: 2px solid #3b82f6; outline-offset: 2px; }
    @media (max-width: 720px) { .btn-primary-sm { width: 100%; justify-content: center; } }
</style>

<div class="page-head">
    <div>
        <h2>Inventaris Aset</h2>
        <p>Kelola data aset kantor, kondisi, dan ketersediaannya.</p>
    </div>
    <a href="{{ route('admin.inventaris.assets.create') }}" class="btn-primary-sm">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Tambah Aset
    </a>
</div>

<!-- STATISTIK -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <div><div class="stat-val">{{ $assets->total() }}</div><div class="stat-lbl">Total aset terdaftar</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
        <div><div class="stat-val">{{ $assets->getCollection()->filter(fn($a) => preg_match('/(baik|good)/i', (string) $a->condition))->count() }}</div><div class="stat-lbl">Kondisi baik <small>di halaman ini</small></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4m0 4h.01M10.3 3.9L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg></div>
        <div><div class="stat-val">{{ $assets->getCollection()->filter(fn($a) => !preg_match('/(baik|good)/i', (string) $a->condition))->count() }}</div><div class="stat-lbl">Perlu perhatian <small>di halaman ini</small></div></div>
    </div>
</div>

<!-- TABEL -->
<div class="table-card">
    <div class="table-toolbar">
        <label class="tb-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="asSearch" placeholder="Cari kode, nama, atau kategori..." autocomplete="off" aria-label="Cari aset">
        </label>
        @if($statuses->count() > 1)
            <div class="chips" id="asChips">
                <button type="button" class="chip on" data-status="all">Semua</button>
                @foreach($statuses as $st)
                    <button type="button" class="chip" data-status="{{ mb_strtolower($st) }}">{{ $label($st) }}</button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Aset</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th class="col-action">Aksi</th>
                </tr>
            </thead>
            <tbody id="asBody">
                @foreach($assets as $a)
                    <tr data-href="{{ route('admin.inventaris.assets.show', $a) }}"
                        data-status="{{ mb_strtolower((string) $a->status) }}"
                        data-search="{{ mb_strtolower($a->asset_code.' '.$a->name.' '.optional($a->category)->name) }}">
                        <td>
                            <div class="cell-asset">
                                <span class="asset-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></span>
                                <div class="cell-main" style="min-width:0">
                                    <span class="cell-title">{{ $a->name }}</span>
                                    <span class="code">{{ $a->asset_code }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ optional($a->category)->name ?? '-' }}</td>
                        <td><span class="badge {{ $tone($a->condition) }}"><span class="dot"></span>{{ $label($a->condition) ?: '-' }}</span></td>
                        <td><span class="badge {{ $tone($a->status) }}">{{ $label($a->status) ?: '-' }}</span></td>
                        <td class="col-action">
                            <a href="{{ route('admin.inventaris.assets.show', $a) }}" class="btn-open">Detail
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="asEmpty" @if($assets->count()) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <h3 id="asEmptyTitle">{{ $assets->count() ? 'Aset tidak ditemukan' : 'Belum ada aset' }}</h3>
        <p id="asEmptyText">{{ $assets->count() ? 'Ubah kata kunci atau pilih status lain.' : 'Tambahkan aset pertama untuk mulai mencatat inventaris.' }}</p>
    </div>

    @if($assets->total() > 0)
        <div class="table-foot">
            <div class="foot-info" id="asInfo">Menampilkan {{ $from }}–{{ $to }} dari {{ $assets->total() }} aset</div>
            @if($assets->hasPages())
                <ul class="pager-list">
                    @foreach($assets->onEachSide(1)->linkCollection() as $i => $link)
                        @php
                            $isPrev = $loop->first;
                            $isNext = $loop->last;
                            $isDots = $link['url'] === null && $link['label'] === '...';
                        @endphp
                        @if($isDots)
                            <li class="dots"><span>…</span></li>
                        @elseif($link['active'])
                            <li class="active"><span aria-current="page">{{ $link['label'] }}</span></li>
                        @elseif($link['url'] === null)
                            <li class="disabled"><span>
                                @if($isPrev)<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
                                @elseif($isNext)<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                                @else{{ $link['label'] }}@endif
                            </span></li>
                        @else
                            <li><a href="{{ $link['url'] }}" @if($isPrev) aria-label="Sebelumnya" @elseif($isNext) aria-label="Berikutnya" @endif>
                                @if($isPrev)<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M15 6l-6 6 6 6"/></svg>
                                @elseif($isNext)<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                                @else{{ $link['label'] }}@endif
                            </a></li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
</div>

<script>
(function () {
    var $ = function (id) { return document.getElementById(id); };
    var rows = Array.prototype.slice.call(document.querySelectorAll('#asBody tr'));
    var status = 'all';

    /* Cari + filter status (hanya untuk data di halaman yang sedang tampil) */
    function apply() {
        var q = $('asSearch').value.trim().toLowerCase(), shown = 0;
        rows.forEach(function (tr) {
            var ok = (status === 'all' || tr.getAttribute('data-status') === status) &&
                     (!q || tr.getAttribute('data-search').indexOf(q) > -1);
            tr.hidden = !ok;
            if (ok) shown++;
        });
        if (rows.length) {
            $('asEmpty').hidden = shown > 0;
            $('asEmptyTitle').textContent = 'Aset tidak ditemukan';
            $('asEmptyText').textContent = 'Ubah kata kunci atau pilih status lain.';
        }
    }
    $('asSearch').addEventListener('input', apply);

    var chips = $('asChips');
    if (chips) chips.addEventListener('click', function (e) {
        var c = e.target.closest('.chip'); if (!c) return;
        status = c.getAttribute('data-status');
        chips.querySelectorAll('.chip').forEach(function (x) { x.classList.toggle('on', x === c); });
        apply();
    });

    /* Klik di baris membuka halaman detail */
    $('asBody').addEventListener('click', function (e) {
        if (e.target.closest('a, button')) return;
        var tr = e.target.closest('tr[data-href]');
        if (tr) window.location.href = tr.getAttribute('data-href');
    });
})();
</script>
@endsection
