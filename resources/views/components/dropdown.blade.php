@props([
    'name',
    'options'           => [],      // [['value' => 1, 'label' => 'B 1234 XY', 'sub' => 'Toyota Avanza · 7 orang'], ...] atau ['a' => 'Label A', ...]
    'selected'          => null,
    'placeholder'       => 'Pilih',
    'icon'              => null,    // car | user | null
    'size'              => 'md',    // sm | md
    'required'          => false,
    'searchable'        => null,    // null = otomatis aktif bila opsi lebih dari 7
    'searchPlaceholder' => 'Cari...',
    'emptyText'         => 'Tidak ada hasil',
])

{{--
    SIM-BU Dropdown (komponen mandiri: gaya + logika semua di sini)
    Lokasi: resources/views/components/dropdown.blade.php

    Pemakaian di dalam form (mengirim nilai lewat name seperti <select> biasa):
        <x-dropdown name="vehicle_id" :options="$vehicleOptions" placeholder="Pilih mobil" icon="car" required />
        <x-dropdown name="driver_user_id" :options="$driverOptions" placeholder="Pilih driver" icon="user" size="sm" />

    Format opsi:
        [['value' => 1, 'label' => 'B 1234 XY', 'sub' => 'Toyota Avanza']]    (sub opsional)
        ['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']                          (bentuk sederhana)

    Menu melayang (tidak terpotong tabel), bisa dicari, bisa dikendalikan keyboard (panah, Enter, Esc).
    Event "change" dikirim ke <select> asli sehingga skrip lain tetap bisa mendengarkan.
--}}
@php
    $opts = collect($options)->map(function ($o, $k) {
        if (is_array($o)) return ['value' => $o['value'] ?? $k, 'label' => (string) ($o['label'] ?? ''), 'sub' => $o['sub'] ?? null];
        return ['value' => $k, 'label' => (string) $o, 'sub' => null];
    })->values();

    $isSearch = $searchable ?? ($opts->count() > 7);
    $uid      = 'dd' . \Illuminate\Support\Str::random(6);
    $initials = fn($t) => mb_strtoupper(collect(preg_split('/\s+/', trim((string) $t)))->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));

    $svgCar  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>';
    $svgUser = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>';
@endphp

@once
<style>
    /* ===== SIM-BU DROPDOWN ===== */
    .dd { --dd-h: 42px; --dd-fs: 13.5px; position: relative; display: inline-block; min-width: 0; vertical-align: middle; }
    .dd.dd-sm { --dd-h: 36px; --dd-fs: 12.5px; }
    .dd-native { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; pointer-events: none; }

    .dd-trigger {
        width: 100%; height: var(--dd-h); display: flex; align-items: center; gap: 9px; padding: 0 10px 0 12px;
        border: 1px solid var(--border-color, #e6e9f2); border-radius: 12px; background: var(--bg-soft, #f3f6fd);
        color: var(--text-muted, #6b7280); font: inherit; font-size: var(--dd-fs); text-align: left; cursor: pointer;
        transition: border-color .2s, box-shadow .2s, background .2s, color .2s;
    }
    .dd.has-ico .dd-trigger { padding-left: 6px; }
    .dd-trigger:hover { border-color: var(--accent, #2563eb); }
    .dd.open .dd-trigger, .dd-trigger:focus-visible {
        outline: none; border-color: var(--accent, #2563eb); background: var(--bg-card, #fff); box-shadow: 0 0 0 3px var(--accent-soft, #eef3ff);
    }
    .dd.has-value .dd-trigger { background: var(--bg-card, #fff); color: var(--text-main, #111827); font-weight: 600; }

    .dd-ico {
        width: 26px; height: 26px; flex-shrink: 0; border-radius: 9px; display: grid; place-items: center;
        background: var(--bg-card, #fff); color: var(--text-muted, #6b7280); box-shadow: 0 1px 3px rgba(20,30,80,.12); transition: background .2s, color .2s;
    }
    .dd-ico svg { width: 14px; height: 14px; }
    .dd.has-value .dd-ico, .dd.open .dd-ico { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .dd-text { flex: 1; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dd-chev { width: 14px; height: 14px; flex-shrink: 0; opacity: .6; transition: transform .25s, opacity .2s; }
    .dd.open .dd-chev { transform: rotate(180deg); opacity: 1; color: var(--accent, #2563eb); }

    .dd.invalid .dd-trigger { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.15); animation: dd-shake .35s; }
    @keyframes dd-shake { 25% { transform: translateX(-3px); } 50% { transform: translateX(3px); } 75% { transform: translateX(-2px); } }

    /* menu melayang (dipindah ke <body> agar tidak terpotong tabel) */
    .dd-menu {
        position: fixed; z-index: 1000; min-width: 220px; max-width: min(340px, calc(100vw - 16px)); padding: 6px;
        background: var(--bg-card, #fff); border: 1px solid var(--border-color, #e6e9f2); border-radius: 16px;
        box-shadow: var(--shadow-pop, 0 18px 50px rgba(20,30,80,.18));
        opacity: 0; transform: translateY(-6px) scale(.98); transform-origin: top left; transition: opacity .15s, transform .15s;
    }
    .dd-menu[hidden] { display: none !important; }
    .dd-menu.up { transform-origin: bottom left; transform: translateY(6px) scale(.98); }
    .dd-menu.show { opacity: 1; transform: none; }

    .dd-search { display: flex; align-items: center; gap: 8px; height: 38px; margin: 2px 2px 6px; padding: 0 10px; border-radius: 11px; background: var(--bg-soft, #f3f6fd); color: var(--text-muted, #6b7280); }
    .dd-search svg { width: 15px; height: 15px; flex-shrink: 0; }
    .dd-search input, .dd-search input:focus {
        flex: 1; width: 100%; max-width: none; padding: 0; border: none; border-radius: 0; outline: none; box-shadow: none;
        background: transparent; font: inherit; font-size: 13px; color: var(--text-main, #111827);
    }

    .dd-list { max-height: 248px; overflow-y: auto; scrollbar-width: thin; }
    .dd-opt {
        width: 100%; display: flex; align-items: center; gap: 10px; padding: 8px 10px; border: none; border-radius: 11px;
        background: none; color: var(--text-main, #111827); font: inherit; text-align: left; cursor: pointer; transition: background .15s;
    }
    .dd-opt:hover, .dd-opt.active { background: var(--bg-hover, #f3f5fb); }
    .dd-opt.selected { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .dd-av {
        width: 32px; height: 32px; flex-shrink: 0; border-radius: 10px; display: grid; place-items: center;
        background: linear-gradient(135deg, #2433d6, #6aa0ff); color: #fff; font-size: 11.5px; font-weight: 700; letter-spacing: .3px;
    }
    .dd-av.car { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .dd-av svg { width: 17px; height: 17px; }
    .dd-ot { flex: 1; min-width: 0; display: flex; flex-direction: column; line-height: 1.3; }
    .dd-ot b { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dd-ot small { font-size: 11.5px; font-weight: 400; color: var(--text-muted, #6b7280); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dd-opt.mono .dd-ot b { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; letter-spacing: .8px; }
    .dd-check { width: 16px; height: 16px; flex-shrink: 0; color: var(--accent, #2563eb); opacity: 0; }
    .dd-opt.selected .dd-check { opacity: 1; }
    .dd-empty { padding: 18px 12px; text-align: center; font-size: 12.5px; color: var(--text-muted, #6b7280); }
    .dd-empty[hidden] { display: none; }

    @media (prefers-reduced-motion: reduce) { .dd-menu, .dd-trigger, .dd-chev { transition: none; } .dd.invalid .dd-trigger { animation: none; } }
</style>
<noscript><style>.dd-native { position: static; opacity: 1; pointer-events: auto; } .dd-trigger { display: none; }</style></noscript>

<script>
(function () {
    var cur = null;
    var $ = function (r, s) { return r.querySelector(s); };
    var allOpts = function (m) { return [].slice.call(m.querySelectorAll('.dd-opt')); };
    var visOpts = function (m) { return allOpts(m).filter(function (o) { return !o.hidden; }); };

    function sync(root) {
        var sel = $(root, '.dd-native'), menu = root._menu, hit = null;
        allOpts(menu).forEach(function (o) {
            var on = sel.value !== '' && o.getAttribute('data-value') === sel.value;
            o.classList.toggle('selected', on);
            o.setAttribute('aria-selected', on ? 'true' : 'false');
            if (on) hit = o;
        });
        var ph = $(root, '.dd-ph'), val = $(root, '.dd-val');
        if (hit) { val.textContent = hit.getAttribute('data-label'); val.hidden = false; ph.hidden = true; }
        else { val.hidden = true; ph.hidden = false; }
        root.classList.toggle('has-value', !!hit);
    }

    function setActive(menu, opt) {
        allOpts(menu).forEach(function (o) { o.classList.remove('active'); });
        if (opt) { opt.classList.add('active'); opt.scrollIntoView({ block: 'nearest' }); }
    }

    function filter(menu, q) {
        q = q.trim().toLowerCase();
        var shown = 0;
        allOpts(menu).forEach(function (o) {
            var ok = !q || o.getAttribute('data-search').indexOf(q) > -1;
            o.hidden = !ok;
            if (ok) shown++;
        });
        var em = $(menu, '.dd-empty');
        if (em) em.hidden = shown > 0;
        setActive(menu, visOpts(menu)[0]);
    }

    function place(root) {
        var t = $(root, '.dd-trigger').getBoundingClientRect(), m = root._menu;
        m.style.minWidth = Math.max(t.width, 220) + 'px';
        m.hidden = false;
        var h = m.offsetHeight, w = m.offsetWidth;
        var below = window.innerHeight - t.bottom;
        var up = below < h + 14 && t.top > below;
        m.style.left = Math.min(Math.max(8, t.left), window.innerWidth - w - 8) + 'px';
        m.style.top = (up ? Math.max(8, t.top - h - 6) : t.bottom + 6) + 'px';
        m.classList.toggle('up', up);
    }

    function openDd(root) {
        if (cur && cur !== root) closeDd(cur, false);
        cur = root;
        root.classList.add('open');
        $(root, '.dd-trigger').setAttribute('aria-expanded', 'true');
        var m = root._menu, s = $(m, '.dd-search input');
        if (s) { s.value = ''; filter(m, ''); }
        place(root);
        requestAnimationFrame(function () { m.classList.add('show'); });
        setActive(m, $(m, '.dd-opt.selected') || visOpts(m)[0]);
        if (s) setTimeout(function () { s.focus(); }, 30);
    }

    function closeDd(root, focus) {
        root.classList.remove('open');
        var m = root._menu, trg = $(root, '.dd-trigger');
        trg.setAttribute('aria-expanded', 'false');
        m.classList.remove('show');
        setTimeout(function () { if (!root.classList.contains('open')) m.hidden = true; }, 160);
        if (cur === root) cur = null;
        if (focus) trg.focus();
    }

    function pick(root, opt) {
        if (!opt) return;
        var sel = $(root, '.dd-native');
        sel.value = opt.getAttribute('data-value');
        sel.dispatchEvent(new Event('change', { bubbles: true }));
        sync(root);
        root.classList.remove('invalid');
        closeDd(root, true);
    }

    function enhance(root) {
        if (root._menu) return;
        var menu = $(root, '.dd-menu'), sel = $(root, '.dd-native');
        root._menu = menu; menu._root = root;
        document.body.appendChild(menu);
        sel.addEventListener('invalid', function () { root.classList.add('invalid'); });
        if (sel.form) sel.form.addEventListener('reset', function () { setTimeout(function () { sync(root); }, 0); });
        sync(root);
    }

    function init() { [].forEach.call(document.querySelectorAll('[data-dd]'), enhance); }
    window.SimbuDropdown = { init: init };

    document.addEventListener('click', function (e) {
        var trg = e.target.closest('.dd-trigger');
        if (trg) {
            var root = trg.closest('[data-dd]');
            if (!root._menu) return;
            root.classList.contains('open') ? closeDd(root, false) : openDd(root);
            return;
        }
        var opt = e.target.closest('.dd-opt');
        if (opt) { pick(opt.closest('.dd-menu')._root, opt); return; }
        if (cur && !e.target.closest('.dd-menu')) closeDd(cur, false);
    });

    document.addEventListener('input', function (e) {
        var s = e.target.closest ? e.target.closest('.dd-search') : null;
        if (s) filter(s.closest('.dd-menu'), e.target.value);
    });

    document.addEventListener('keydown', function (e) {
        if (!cur) {
            var tr = e.target.closest ? e.target.closest('.dd-trigger') : null;
            if (tr && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) { e.preventDefault(); openDd(tr.closest('[data-dd]')); }
            return;
        }
        var m = cur._menu, list = visOpts(m), idx = list.indexOf($(m, '.dd-opt.active'));
        if (e.key === 'Escape')      { e.preventDefault(); closeDd(cur, true); }
        else if (e.key === 'ArrowDown') { e.preventDefault(); setActive(m, list[Math.min(list.length - 1, idx + 1)]); }
        else if (e.key === 'ArrowUp')   { e.preventDefault(); setActive(m, list[Math.max(0, idx - 1)]); }
        else if (e.key === 'Home')      { e.preventDefault(); setActive(m, list[0]); }
        else if (e.key === 'End')       { e.preventDefault(); setActive(m, list[list.length - 1]); }
        else if (e.key === 'Enter')     { if (idx > -1) { e.preventDefault(); pick(cur, list[idx]); } }
        else if (e.key === 'Tab')       { closeDd(cur, false); }
    });

    window.addEventListener('resize', function () { if (cur) closeDd(cur, false); });
    window.addEventListener('scroll', function (e) {
        if (!cur) return;
        if (e.target && e.target.nodeType === 1 && e.target.closest('.dd-menu')) return;
        closeDd(cur, false);
    }, true);

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
</script>
@endonce

<div {{ $attributes->class(['dd', 'dd-sm' => $size === 'sm', 'has-ico' => (bool) $icon]) }} data-dd>
    {{-- <select> asli: tetap dikirim bersama form dan dipakai untuk validasi "required" --}}
    <select name="{{ $name }}" class="dd-native" tabindex="-1" aria-hidden="true" @if($required) required @endif>
        <option value="">{{ $placeholder }}</option>
        @foreach ($opts as $o)
            <option value="{{ $o['value'] }}" @if ((string) $o['value'] === (string) $selected && $selected !== null) selected @endif>{{ $o['label'] }}</option>
        @endforeach
    </select>

    <button type="button" class="dd-trigger" aria-haspopup="listbox" aria-expanded="false" aria-controls="{{ $uid }}">
        @if ($icon)
            <span class="dd-ico">{!! $icon === 'user' ? $svgUser : $svgCar !!}</span>
        @endif
        <span class="dd-text">
            <span class="dd-ph" data-translate>{{ $placeholder }}</span>
            <span class="dd-val" hidden></span>
        </span>
        <svg class="dd-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
    </button>

    <div class="dd-menu" id="{{ $uid }}" role="listbox" hidden>
        @if ($isSearch)
            <div class="dd-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" placeholder="{{ $searchPlaceholder }}" autocomplete="off" aria-label="{{ $searchPlaceholder }}">
            </div>
        @endif

        <div class="dd-list">
            @foreach ($opts as $o)
                <button type="button" class="dd-opt {{ $icon === 'car' ? 'mono' : '' }}" role="option" aria-selected="false"
                        data-value="{{ $o['value'] }}" data-label="{{ $o['label'] }}"
                        data-search="{{ mb_strtolower($o['label'] . ' ' . ($o['sub'] ?? '')) }}">
                    @if ($icon === 'user')
                        <span class="dd-av">{{ $initials($o['label']) ?: '?' }}</span>
                    @elseif ($icon === 'car')
                        <span class="dd-av car">{!! $svgCar !!}</span>
                    @endif
                    <span class="dd-ot">
                        <b>{{ $o['label'] }}</b>
                        @if (!empty($o['sub']))<small>{{ $o['sub'] }}</small>@endif
                    </span>
                    <svg class="dd-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>
                </button>
            @endforeach
        </div>

        <div class="dd-empty" data-translate hidden>{{ $emptyText }}</div>
    </div>
</div>
