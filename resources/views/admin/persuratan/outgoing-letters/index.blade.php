@extends('layouts.app')
@section('title', 'Surat Keluar')

@section('content')
@include('partials.ui-extras')
@php
    $isPaginator = $letters instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $total   = $isPaginator ? $letters->total() : $letters->count();
    $onPage  = $letters->count();
    $badgeOf = function ($status) {
        $s = strtolower((string) $status);
        if (preg_match('/(draft|konsep)/', $s))                                   return 'badge-muted';
        if (preg_match('/(pending|menunggu|submit|diajukan|review|proses)/', $s)) return 'badge-warning';
        if (preg_match('/(approve|setuju|sent|kirim|selesai|final|terbit)/', $s)) return 'badge-success';
        if (preg_match('/(reject|tolak|batal|cancel)/', $s))                      return 'badge-danger';
        return 'badge-info';
    };
@endphp

<!-- ===== KEPALA HALAMAN ===== -->
<div class="page-head">
    <div>
        <h2 data-translate>Surat Keluar</h2>
        <p data-translate>Daftar surat keluar beserta nomor agenda dan statusnya.</p>
    </div>
    <a href="{{ route('admin.persuratan.outgoing-letters.create') }}" class="btn-open">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        <span data-translate>Tambah Surat</span>
    </a>
</div>

<x-report-toolbar />

<!-- ===== STATISTIK ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total surat keluar</div>
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
            <input type="text" id="letterSearch" placeholder="Cari nomor agenda, perihal, status..." autocomplete="off">
        </label>
        <div class="chips" id="statusChips"></div>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>Agenda</th>
                    <th data-translate>Perihal</th>
                    <th data-translate>Status</th>
                </tr>
            </thead>
            <tbody id="letterBody">
                @forelse($letters as $l)
                    @php $agenda = $l->register_year . '/' . str_pad($l->agenda_sequence, 4, '0', STR_PAD_LEFT); @endphp
                    <tr data-status="{{ $l->status }}" data-search="{{ strtolower($agenda . ' ' . $l->subject . ' ' . $l->status) }}">
                        <td><span class="cell-title" data-convert="full">{{ $agenda }}</span></td>
                        <td>
                            <div class="cell-main">
                                <span class="cell-title">{{ $l->subject }}</span>
                            </div>
                        </td>
                        <td><span class="badge {{ $badgeOf($l->status) }}"><span class="dot"></span><span data-translate>{{ $l->status }}</span></span></td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- kosong dari server --}}
    <div class="empty-state" @if($onPage > 0) hidden @endif id="emptyServer">
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
        <h3 data-translate>Belum ada surat.</h3>
        <p data-translate>Klik "Tambah Surat" untuk membuat surat keluar pertama.</p>
    </div>

    {{-- kosong hasil filter --}}
    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Surat tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter status yang lain.</p>
    </div>

    <div class="table-foot">
        <div class="foot-info">
            @if($isPaginator && $letters->total() > 0)
                <span data-translate>Menampilkan</span>
                <span data-convert="digit">{{ $letters->firstItem() }}–{{ $letters->lastItem() }}</span>
                <span data-translate>dari</span>
                <span data-convert="digit">{{ number_format($letters->total(), 0, ',', '.') }}</span>
                <span data-translate>surat</span>
            @endif
        </div>
        @if($isPaginator && $letters->hasPages())
            <x-pagination :paginator="$letters" />
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
    var rows = Array.prototype.slice.call(document.querySelectorAll('#letterBody tr[data-search]'));
    if (!rows.length) return;

    var search = document.getElementById('letterSearch');
    var chipsEl = document.getElementById('statusChips');
    var emptyF = document.getElementById('emptyFilter');
    var active = 'Semua';
    var applyI18N = function () { if (window.I18N) window.I18N.apply(); };

    /* chip status dibentuk dari data di halaman ini */
    var statuses = ['Semua'];
    rows.forEach(function (r) { var s = r.getAttribute('data-status'); if (s && statuses.indexOf(s) < 0) statuses.push(s); });

    function renderChips() {
        chipsEl.innerHTML = '';
        if (statuses.length <= 2) return; /* hanya satu status: chip tidak berguna */
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
