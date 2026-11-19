@props([
    'paginator',
    'itemLabel'      => 'data',
    'perPageOptions' => [10, 25, 50, 100],
    'onEachSide'     => 1,
])

{{--
    SIM-BU Pagination (komponen mandiri: gaya + opsi jumlah data + tombol halaman semua di sini)
    Lokasi: resources/views/components/pagination.blade.php

    Pemakaian di halaman mana pun (taruh di dalam .table-card atau wadah lain):
        <x-pagination :paginator="$data" item-label="surat" />

    Opsional:
        <x-pagination :paginator="$data" item-label="surat" :per-page-options="[5, 10, 20]" :on-each-side="2" />
--}}
@php
    $current  = (int) $paginator->perPage();
    $pageName = $paginator->getPageName();
    $hasTotal = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /* daftar opsi jumlah data (angka saat ini selalu ikut agar terpilih) */
    $options = collect($perPageOptions)->map(fn($n) => (int) $n)->push($current)->unique()->sort()->values()->all();

    /* susun nomor halaman: 1 ... (sekitar halaman aktif) ... terakhir */
    $pageItems = [];
    if ($hasTotal) {
        $last = $paginator->lastPage();
        $cur  = $paginator->currentPage();
        $prev = 0;
        for ($p = 1; $p <= $last; $p++) {
            if ($p === 1 || $p === $last || abs($p - $cur) <= $onEachSide) {
                if ($prev && $p - $prev === 2)  { $pageItems[] = $prev + 1; }
                elseif ($prev && $p - $prev > 2) { $pageItems[] = '...'; }
                $pageItems[] = $p;
                $prev = $p;
            }
        }
    }
@endphp

@once
<style>
    /* ===== SIM-BU PAGINATION ===== */
    .sbp { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 14px 18px; border-top: 1px solid var(--border-color, #e6e9f2); }
    .sbp-left { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .sbp-info { font-size: 12.5px; color: var(--text-muted, #6b7280); }

    .sbp-per { display: inline-flex; align-items: center; gap: 8px; margin: 0; font-size: 12.5px; font-weight: 400; color: var(--text-muted, #6b7280); }
    .sbp-per select {
        width: auto; max-width: none; height: 34px; padding: 0 30px 0 12px; cursor: pointer;
        border: 1px solid var(--border-color, #e6e9f2); border-radius: 10px; background-color: var(--bg-soft, #f3f6fd);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a8fa3' stroke-width='2.6' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center;
        -webkit-appearance: none; appearance: none; font-family: inherit; font-size: 12.5px; font-weight: 600; color: var(--text-main, #111827);
        transition: border-color .2s, box-shadow .2s;
    }
    .sbp-per select:hover { border-color: var(--accent, #2563eb); }
    .sbp-per select:focus { outline: none; border-color: var(--accent, #2563eb); box-shadow: 0 0 0 3px var(--accent-soft, #eef3ff); }

    .sbp-list { display: flex; gap: 6px; list-style: none; margin: 0; padding: 0; }
    .sbp-list a, .sbp-list span {
        min-width: 34px; height: 34px; padding: 0 10px; display: grid; place-items: center; border-radius: 10px;
        font-size: 12.5px; font-weight: 500; text-decoration: none; color: var(--text-muted, #6b7280);
        border: 1px solid var(--border-color, #e6e9f2); transition: background .2s, color .2s, border-color .2s;
    }
    .sbp-list a:hover { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); border-color: transparent; }
    .sbp-list .active span { background: #2563eb; color: #fff; border-color: #2563eb; font-weight: 600; }
    body.dark-mode .sbp-list .active span { background: #60a5fa; border-color: #60a5fa; color: #0f172a; }
    .sbp-list .disabled span { opacity: .4; }
    .sbp-list .dots span { border: none; }
    .sbp-list svg { width: 14px; height: 14px; }

    @media (max-width: 720px) {
        .sbp { padding: 12px 14px; justify-content: center; }
        .sbp-left { justify-content: center; }
    }
</style>
@endonce

<div class="sbp">
    <div class="sbp-left">
        {{-- pilih jumlah data per halaman --}}
        <label class="sbp-per">
            <span>Tampilkan</span>
            <select class="ajax-per-page" aria-label="Jumlah data per halaman">
                @foreach ($options as $n)
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => $n, $pageName => 1]) }}" {{ $n === $current ? 'selected' : '' }} data-no-i18n>{{ $n }}</option>
                @endforeach
            </select>
            <span>{{ $itemLabel }}</span>
        </label>

        @if ($paginator->count())
            <span class="sbp-info">
                {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}@if ($hasTotal) dari {{ $paginator->total() }}@endif
            </span>
        @endif
    </div>

    @if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman">
        <ul class="sbp-list">
            {{-- sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true"><span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></a></li>
            @endif

            {{-- nomor halaman --}}
            @foreach ($pageItems as $item)
                @if ($item === '...')
                    <li class="dots" aria-disabled="true"><span>…</span></li>
                @elseif ($item === $paginator->currentPage())
                    <li class="active" aria-current="page"><span data-page-num="{{ $item }}">{{ $item }}</span></li>
                @else
                    <li><a href="{{ $paginator->url($item) }}" aria-label="Halaman {{ $item }}" data-page-num="{{ $item }}">{{ $item }}</a></li>
                @endif
            @endforeach

            {{-- berikutnya --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a></li>
            @else
                <li class="disabled" aria-disabled="true"><span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></span></li>
            @endif
        </ul>
    </nav>
    @endif
</div>
