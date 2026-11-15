@props([
    'variant'       => 'primary',   // primary | secondary | soft | outline | ghost | danger | danger-soft | success | success-soft | warning | dark | link
    'size'          => 'md',        // xs | sm | md | lg | xl
    'href'          => null,        // diisi -> dirender sebagai <a>
    'type'          => 'button',    // button | submit | reset
    'iconOnly'      => false,       // tombol hanya ikon (wajib beri aria-label)
    'block'         => false,       // lebar penuh
    'pill'          => false,       // sudut bulat penuh
    'loading'       => false,       // tampilkan spinner & nonaktifkan
    'disabled'      => false,
    'submitLoading' => false,       // otomatis loading saat form di-submit (cegah klik ganda)
])

{{--
    SIM-BU Button (komponen mandiri: gaya + varian + ukuran + loading semua di sini)
    Lokasi: resources/views/components/button.blade.php

    Dasar:
        <x-button>Simpan</x-button>
        <x-button variant="secondary" size="sm">Batal</x-button>
        <x-button href="{{ route('dashboard') }}" variant="outline">Kembali</x-button>

    Dengan ikon (slot "icon" di kiri, "trailing" di kanan):
        <x-button>
            <x-slot name="icon"><svg ...></svg></x-slot>
            Tambah Data
        </x-button>

    Hanya ikon:
        <x-button icon-only variant="soft" aria-label="Hapus"><svg ...></svg></x-button>

    Lainnya:
        <x-button type="submit" submit-loading>Kirim</x-button>     (loading otomatis saat form dikirim)
        <x-button :loading="$isSaving">Menyimpan</x-button>
        <x-button block pill size="lg">Masuk</x-button>

    Grup tombol:  <div class="sbtn-group"> <x-button>..</x-button> <x-button>..</x-button> </div>
--}}
@php
    $classes = [
        'sbtn', 'sbtn-' . $variant, 'sbtn-' . $size,
        'sbtn-block' => $block,
        'sbtn-pill'  => $pill,
        'sbtn-icon'  => $iconOnly,
        'is-loading' => $loading,
    ];
    $off = $disabled || $loading;
@endphp

@once
<style>
    /* ===== SIM-BU BUTTON ===== */
    .sbtn {
        --b-h: 40px; --b-px: 16px; --b-fs: 13.5px; --b-r: 11px; --b-ico: 16px; --b-gap: 8px;
        position: relative; display: inline-flex; align-items: center; justify-content: center; gap: var(--b-gap);
        height: var(--b-h); padding: 0 var(--b-px); font-family: inherit; font-size: var(--b-fs); font-weight: 600; letter-spacing: .1px;
        line-height: 1; white-space: nowrap; text-decoration: none; cursor: pointer; user-select: none; -webkit-tap-highlight-color: transparent;
        border-radius: var(--b-r); border: 1.5px solid var(--b-bd, transparent);
        background: var(--b-bg); color: var(--b-fg); box-shadow: var(--b-sh, none);
        transition: background .2s, color .2s, border-color .2s, box-shadow .2s, transform .15s;
    }
    .sbtn:hover { background: var(--b-bg-h, var(--b-bg)); box-shadow: var(--b-sh-h, var(--b-sh, none)); transform: translateY(-1px); }
    .sbtn:active { transform: translateY(0) scale(.98); box-shadow: var(--b-sh, none); }
    .sbtn:focus-visible { outline: 2px solid var(--accent, #2563eb); outline-offset: 2px; }
    .sbtn svg { width: var(--b-ico); height: var(--b-ico); flex-shrink: 0; }
    .sbtn-ico { display: inline-flex; align-items: center; justify-content: center; }
    .sbtn-label { overflow: hidden; text-overflow: ellipsis; }

    /* ---------- ukuran ---------- */
    .sbtn-xs { --b-h: 28px; --b-px: 10px; --b-fs: 11.5px; --b-r: 8px;  --b-ico: 13px; --b-gap: 5px; }
    .sbtn-sm { --b-h: 34px; --b-px: 13px; --b-fs: 12.5px; --b-r: 10px; --b-ico: 15px; --b-gap: 6px; }
    .sbtn-md { --b-h: 40px; --b-px: 17px; --b-fs: 13.5px; --b-r: 11px; --b-ico: 17px; --b-gap: 8px; }
    .sbtn-lg { --b-h: 48px; --b-px: 22px; --b-fs: 14.5px; --b-r: 13px; --b-ico: 19px; --b-gap: 9px; }
    .sbtn-xl { --b-h: 56px; --b-px: 28px; --b-fs: 16px;   --b-r: 15px; --b-ico: 21px; --b-gap: 10px; }

    .sbtn-block { display: flex; width: 100%; }
    .sbtn-pill  { border-radius: 999px; }
    .sbtn-icon  { width: var(--b-h); padding: 0; }

    /* ---------- varian ---------- */
    .sbtn-primary {
        --b-bg: linear-gradient(135deg, #3f73ff 0%, #2563eb 100%); --b-bg-h: linear-gradient(135deg, #5a86ff 0%, #2f6df0 100%);
        --b-fg: #fff; --b-sh: 0 8px 18px rgba(37,99,235,.30); --b-sh-h: 0 12px 24px rgba(37,99,235,.40);
    }
    .sbtn-secondary {
        --b-bg: var(--bg-card, #fff); --b-bg-h: var(--bg-hover, #f3f5fb); --b-fg: var(--text-main, #111827); --b-bd: var(--border-color, #e6e9f2);
        --b-sh: 0 1px 2px rgba(20,30,80,.06); --b-sh-h: 0 4px 10px rgba(20,30,80,.09);
    }
    .sbtn-soft { --b-bg: var(--accent-soft, #eef3ff); --b-bg-h: rgba(37,99,235,.18); --b-fg: var(--accent, #2563eb); }
    body.dark-mode .sbtn-soft { --b-bg-h: rgba(96,165,250,.28); }
    .sbtn-outline { --b-bg: transparent; --b-bg-h: var(--accent-soft, #eef3ff); --b-fg: var(--accent, #2563eb); --b-bd: var(--accent, #2563eb); }
    .sbtn-ghost { --b-bg: transparent; --b-bg-h: var(--bg-hover, #f3f5fb); --b-fg: var(--text-muted, #6b7280); }
    .sbtn-ghost:hover { color: var(--text-main, #111827); }
    .sbtn-danger {
        --b-bg: linear-gradient(135deg, #f26462 0%, #dc2626 100%); --b-bg-h: linear-gradient(135deg, #f77c7a 0%, #e53535 100%);
        --b-fg: #fff; --b-sh: 0 8px 18px rgba(220,38,38,.28); --b-sh-h: 0 12px 24px rgba(220,38,38,.38);
    }
    .sbtn-danger-soft { --b-bg: rgba(239,68,68,.12); --b-bg-h: rgba(239,68,68,.22); --b-fg: #dc2626; }
    body.dark-mode .sbtn-danger-soft { --b-fg: #f87171; }
    .sbtn-success {
        --b-bg: linear-gradient(135deg, #2bc796 0%, #059669 100%); --b-bg-h: linear-gradient(135deg, #46d6a8 0%, #0aa676 100%);
        --b-fg: #fff; --b-sh: 0 8px 18px rgba(5,150,105,.28); --b-sh-h: 0 12px 24px rgba(5,150,105,.38);
    }
    .sbtn-success-soft { --b-bg: rgba(16,185,129,.13); --b-bg-h: rgba(16,185,129,.24); --b-fg: #059669; }
    body.dark-mode .sbtn-success-soft { --b-fg: #34d399; }
    .sbtn-warning {
        --b-bg: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%); --b-bg-h: linear-gradient(135deg, #fde27a 0%, #fbab1d 100%);
        --b-fg: #3b2300; --b-sh: 0 8px 18px rgba(245,158,11,.30); --b-sh-h: 0 12px 24px rgba(245,158,11,.40);
    }
    .sbtn-dark { --b-bg: linear-gradient(135deg, #2b3445 0%, #111827 100%); --b-bg-h: linear-gradient(135deg, #384358 0%, #1c2536 100%); --b-fg: #fff; --b-sh: 0 8px 18px rgba(17,24,39,.28); }
    body.dark-mode .sbtn-dark { --b-bg: #f8fafc; --b-bg-h: #e2e8f0; --b-fg: #0f172a; --b-sh: 0 8px 18px rgba(0,0,0,.35); }
    .sbtn.sbtn-link { --b-bg: transparent; --b-fg: var(--accent, #2563eb); height: auto; padding: 2px 0; border-radius: 6px; box-shadow: none; }
    .sbtn.sbtn-link:hover { text-decoration: underline; text-underline-offset: 3px; transform: none; box-shadow: none; }

    /* ---------- status ---------- */
    .sbtn:disabled, .sbtn[aria-disabled="true"] { opacity: .5; cursor: not-allowed; pointer-events: none; box-shadow: none; transform: none; }
    .sbtn.is-loading { cursor: progress; pointer-events: none; }
    .sbtn.is-loading .sbtn-ico { display: none; }
    .sbtn-spin {
        width: var(--b-ico); height: var(--b-ico); flex-shrink: 0; border-radius: 50%;
        border: 2px solid currentColor; border-right-color: transparent; animation: sbtn-rot .65s linear infinite;
    }
    @keyframes sbtn-rot { to { transform: rotate(360deg); } }

    /* ---------- grup tombol ---------- */
    .sbtn-group { display: inline-flex; }
    .sbtn-group .sbtn { border-radius: 0; }
    .sbtn-group .sbtn + .sbtn { margin-left: -1.5px; }
    .sbtn-group .sbtn:first-child { border-radius: 11px 0 0 11px; }
    .sbtn-group .sbtn:last-child  { border-radius: 0 11px 11px 0; }
    .sbtn-group .sbtn:hover, .sbtn-group .sbtn:focus-visible { z-index: 1; }

    @media (prefers-reduced-motion: reduce) {
        .sbtn { transition: none; }
        .sbtn-spin { animation-duration: 1.6s; }
    }
</style>

<script>
    /* tombol dengan "submit-loading": otomatis loading saat form dikirim (cegah klik ganda) */
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || !form.querySelectorAll) return;
        [].slice.call(form.querySelectorAll('.sbtn[data-submit-loading]')).forEach(function (b) {
            if (b.classList.contains('is-loading')) return;
            b.classList.add('is-loading');
            b.setAttribute('aria-busy', 'true');
            var sp = document.createElement('span');
            sp.className = 'sbtn-spin';
            sp.setAttribute('aria-hidden', 'true');
            b.insertBefore(sp, b.firstChild);
        });
    });
</script>
@endonce

@if ($href)
    <a href="{{ $off ? '#' : $href }}"
       {{ $attributes->class($classes) }}
       @if ($off) aria-disabled="true" tabindex="-1" @endif
       @if ($loading) aria-busy="true" @endif>
        @if ($loading)
            <span class="sbtn-spin" aria-hidden="true"></span>
        @elseif ($iconOnly && !isset($icon))
            <span class="sbtn-ico">{{ $slot }}</span>
        @elseif (isset($icon))
            <span class="sbtn-ico">{{ $icon }}</span>
        @endif
        @if (!$iconOnly)<span class="sbtn-label">{{ $slot }}</span>@endif
        @isset($trailing)<span class="sbtn-ico">{{ $trailing }}</span>@endisset
    </a>
@else
    <button type="{{ $type }}"
            {{ $attributes->class($classes) }}
            @if ($off) disabled @endif
            @if ($loading) aria-busy="true" @endif
            @if ($submitLoading) data-submit-loading @endif>
        @if ($loading)
            <span class="sbtn-spin" aria-hidden="true"></span>
        @elseif ($iconOnly && !isset($icon))
            <span class="sbtn-ico">{{ $slot }}</span>
        @elseif (isset($icon))
            <span class="sbtn-ico">{{ $icon }}</span>
        @endif
        @if (!$iconOnly)<span class="sbtn-label">{{ $slot }}</span>@endif
        @isset($trailing)<span class="sbtn-ico">{{ $trailing }}</span>@endisset
    </button>
@endif
