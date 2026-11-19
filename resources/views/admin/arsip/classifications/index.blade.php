@extends('layouts.app')
@section('title', 'Klasifikasi Arsip')

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

    /* ---------- form tambah ---------- */
    .form-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 18px 20px 20px; margin-bottom: 16px; }
    .form-card h3 { font-size: 15px; font-weight: 600; color: var(--text-main); }
    .form-card .hint { font-size: 12.5px; color: var(--text-muted); margin-top: 3px; }
    .cls-form { display: grid; grid-template-columns: 170px minmax(220px, 1fr) 180px auto; gap: 14px; align-items: end; margin-top: 16px; }
    .fld { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
    .fld label { font-size: 11.5px; font-weight: 600; color: var(--text-muted); letter-spacing: .2px; }
    .fld input {
        width: 100%; max-width: none; height: 40px; padding: 0 12px; border-radius: 12px; font-size: 13px; font-family: inherit;
        border: 1px solid var(--border-color); background: var(--bg-soft, #f3f6fd); color: var(--text-main); outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .fld input:focus { border-color: var(--accent, #2563eb); box-shadow: 0 0 0 3px var(--accent-soft, #eef3ff); }
    .fld input.invalid { border-color: #dc2626; }
    .fld .err { font-size: 11.5px; color: #dc2626; }
    body.dark-mode .fld .err { color: #f87171; }
    .btn-save {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px; height: 40px; padding: 0 20px;
        border: none; border-radius: 12px; cursor: pointer; font-family: inherit; font-size: 13px; font-weight: 600;
        background: var(--accent, #2563eb); color: #fff; transition: background .2s, transform .15s;
    }
    .btn-save svg { width: 15px; height: 15px; }
    .btn-save:hover { background: #1d4ed8; transform: translateY(-1px); }

    @media (max-width: 900px) {
        .cls-form { grid-template-columns: 1fr 1fr; }
        .cls-form .fld.grow { grid-column: 1 / -1; }
        .btn-save { grid-column: 1 / -1; }
    }
    @media (max-width: 520px) { .cls-form { grid-template-columns: 1fr; } }
</style>

@php
    $isPaginator = $items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $total  = $isPaginator ? $items->total() : $items->count();
    $recSum = $items->sum('records_count');
@endphp

<!-- ===== KEPALA HALAMAN ===== -->
<div class="page-head">
    <div>
        <h2 data-translate>Klasifikasi Arsip</h2>
        <p data-translate>Kelola kode klasifikasi dan masa retensi arsip.</p>
    </div>
    <div class="head-actions">
        <a href="{{ route('admin.arsip.records.index') }}" class="btn-ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="transform:rotate(180deg)"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            <span data-translate>Daftar Arsip</span>
        </a>
    </div>
</div>

<!-- ===== STATISTIK ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total klasifikasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8v13H3V8M1 3h22v5H1zM10 12h4"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($recSum, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Arsip terklasifikasi</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<!-- ===== FORM TAMBAH ===== -->
<div class="form-card">
    <h3 data-translate>Tambah Klasifikasi</h3>
    <p class="hint" data-translate>Masa retensi bersifat opsional; kosongkan bila belum ditetapkan.</p>

    <form method="POST" action="{{ route('admin.arsip.classifications.store') }}" class="cls-form">
        @csrf
        <div class="fld">
            <label for="f-code" data-translate>Kode</label>
            <input id="f-code" name="code" value="{{ old('code') }}" placeholder="mis: 005.2" required class="{{ $errors->has('code') ? 'invalid' : '' }}">
            @error('code') <span class="err">{{ $message }}</span> @enderror
        </div>
        <div class="fld grow">
            <label for="f-name" data-translate>Nama klasifikasi</label>
            <input id="f-name" name="name" value="{{ old('name') }}" placeholder="Nama klasifikasi" required class="{{ $errors->has('name') ? 'invalid' : '' }}">
            @error('name') <span class="err">{{ $message }}</span> @enderror
        </div>
        <div class="fld">
            <label for="f-ret" data-translate>Retensi (bulan)</label>
            <input id="f-ret" name="retention_active_months" type="number" min="0" value="{{ old('retention_active_months') }}" placeholder="mis: 24" class="{{ $errors->has('retention_active_months') ? 'invalid' : '' }}">
            @error('retention_active_months') <span class="err">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn-save">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>
            <span data-translate>Simpan</span>
        </button>
    </form>
</div>

<!-- ===== TABEL ===== -->
<div class="table-card">
    <div class="table-toolbar">
        <label class="tb-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="clsSearch" placeholder="Cari kode atau nama klasifikasi..." autocomplete="off">
        </label>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>Kode</th>
                    <th data-translate>Nama</th>
                    <th data-translate>Retensi (bulan)</th>
                    <th data-translate>Jumlah Arsip</th>
                </tr>
            </thead>
            <tbody id="clsBody">
                @foreach($items as $i)
                    <tr data-search="{{ strtolower($i->code . ' ' . $i->name) }}">
                        <td><span class="code-tag" data-convert="full">{{ $i->code }}</span></td>
                        <td><div class="cell-main"><span class="cell-title">{{ $i->name }}</span></div></td>
                        <td>
                            @if(!is_null($i->retention_active_months))
                                <span class="badge badge-info"><span data-convert="digit">{{ $i->retention_active_months }}</span>&nbsp;<span data-translate>bulan</span></span>
                            @else
                                <span class="badge badge-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $i->records_count > 0 ? 'badge-success' : 'badge-muted' }}">
                                <span data-convert="digit">{{ number_format($i->records_count, 0, ',', '.') }}</span>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="emptyServer" @if($items->count() > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/></svg></div>
        <h3 data-translate>Belum ada klasifikasi.</h3>
        <p data-translate>Tambahkan klasifikasi pertama lewat formulir di atas.</p>
    </div>

    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Klasifikasi tidak ditemukan</h3>
        <p data-translate>Coba kata kunci yang lain.</p>
    </div>

    <div class="table-foot">
        <div class="foot-info">
            @if($isPaginator && $items->total() > 0)
                <span data-translate>Menampilkan</span>
                <span data-convert="digit">{{ $items->firstItem() }}–{{ $items->lastItem() }}</span>
                <span data-translate>dari</span>
                <span data-convert="digit">{{ number_format($items->total(), 0, ',', '.') }}</span>
                <span data-translate>klasifikasi</span>
            @endif
        </div>
        @if($isPaginator && $items->hasPages())
            <div>{{ $items->links() }}</div>
        @endif
    </div>
</div>

<script>
(function () {
    var rows = Array.prototype.slice.call(document.querySelectorAll('#clsBody tr[data-search]'));
    if (!rows.length) return;
    var search = document.getElementById('clsSearch');
    var emptyF = document.getElementById('emptyFilter');
    search.addEventListener('input', function () {
        var q = search.value.trim().toLowerCase(), shown = 0;
        rows.forEach(function (r) {
            var ok = !q || r.getAttribute('data-search').indexOf(q) > -1;
            r.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        emptyF.hidden = shown > 0;
    });
})();
</script>
@endsection
