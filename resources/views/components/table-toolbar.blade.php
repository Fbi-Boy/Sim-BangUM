{{-- resources/views/components/table-toolbar.blade.php
     Baris 1 : [kolom cari] [kotak filter dropdown] ............ [slot tombol] [Tambah Data]
     Baris 2 : garis pemisah + [dropdown periode] [Laporan] [Export Spreadsheet]
     Pakai di dalam <div class="table-card">, tbody harus punya id = target dan tiap <tr> memiliki
     data-search (teks pencarian), data-filter (nilai filter), data-filter-label (teks opsi), data-href (opsional, baris bisa diklik). --}}
@props([
    'target'       => 'dataBody',
    'empty'        => 'emptyFilter',
    'placeholder'  => 'Cari...',
    'filterLabel'  => 'Semua Status',
    'addUrl'       => null,
    'addLabel'     => 'Tambah Data',
    'serverSearch' => false,
    'report'       => true,
])

@once
<style>
    .tt-bar { gap: 10px; }
    .tt-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; flex: 1; min-width: 0; }
    .tt-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-left: auto; }
    .tt-right .btn-ghost { height: 40px; border-radius: 12px; padding: 0 14px; }
    .tt-search-form { display: flex; margin: 0; flex: 1; min-width: 230px; max-width: 340px; }
    .tt-search-form .tb-search { flex: 1; max-width: none; }

    .tt-filter { position: relative; display: inline-flex; align-items: center; margin: 0; }
    .tt-filter .ic, .tt-filter .caret { position: absolute; pointer-events: none; color: var(--text-muted); }
    .tt-filter .ic { left: 12px; width: 16px; height: 16px; }
    .tt-filter .caret { right: 11px; width: 13px; height: 13px; }
    .tt-filter select {
        appearance: none; -webkit-appearance: none; width: auto; min-width: 150px; max-width: 230px; height: 40px; padding: 0 32px 0 35px;
        border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-soft, #f3f6fd); color: var(--text-main);
        font-size: 13px; font-family: inherit; cursor: pointer; outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .tt-filter select:focus { border-color: var(--accent, #2563eb); box-shadow: 0 0 0 3px var(--accent-soft, #eef3ff); }

    .tt-add {
        display: inline-flex; align-items: center; gap: 7px; height: 40px; padding: 0 16px; border-radius: 12px; text-decoration: none;
        background: var(--accent, #2563eb); color: #fff; font-size: 13px; font-weight: 600; white-space: nowrap;
        transition: background .2s, transform .15s;
    }
    .tt-add svg { width: 15px; height: 15px; }
    .tt-add:hover { background: #1d4ed8; transform: translateY(-1px); }

    /* baris laporan di bawah kolom cari, dipisah garis seperti header tabel */
    .rt-strip { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; padding: 12px 18px; border-bottom: 1px solid var(--border-color); }
    .rt-label { font-size: 12px; font-weight: 600; color: var(--text-muted); letter-spacing: .3px; }
    .rt-form { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin: 0; }
    .rt-select { position: relative; display: inline-flex; align-items: center; margin: 0; }
    .rt-select .ic, .rt-select .caret { position: absolute; pointer-events: none; color: var(--text-muted); }
    .rt-select .ic { left: 12px; width: 15px; height: 15px; }
    .rt-select .caret { right: 11px; width: 13px; height: 13px; }
    .rt-select select {
        appearance: none; -webkit-appearance: none; width: auto; max-width: none; height: 38px; padding: 0 32px 0 34px;
        border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);
        font-size: 12.5px; font-weight: 500; font-family: inherit; cursor: pointer; outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .rt-select select:focus { border-color: var(--accent, #2563eb); box-shadow: 0 0 0 3px var(--accent-soft, #eef3ff); }
    .rt-btn {
        display: inline-flex; align-items: center; gap: 7px; height: 38px; padding: 0 15px; border: none; border-radius: 12px; cursor: pointer;
        font-family: inherit; font-size: 12.5px; font-weight: 600; transition: background .2s, color .2s, transform .15s;
    }
    .rt-btn svg { width: 15px; height: 15px; }
    .rt-btn:hover { transform: translateY(-1px); }
    .rt-btn.report { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .rt-btn.report:hover { background: #2563eb; color: #fff; }
    .rt-btn.sheet { background: rgba(16,185,129,.13); color: #059669; }
    .rt-btn.sheet:hover { background: #059669; color: #fff; }
    body.dark-mode .rt-btn.sheet { color: #34d399; }
    body.dark-mode .rt-btn.sheet:hover { background: #10b981; color: #fff; }
    .data-table tr[data-href] { cursor: pointer; }

    @media (max-width: 720px) { .tt-right { margin-left: 0; width: 100%; } .tt-search-form { max-width: none; } }
    @media print { .tt-bar, .rt-strip { display: none !important; } }
</style>
@endonce

@php
    $keepSearch = collect(request()->except(['q', 'page', 'period', 'report', 'export']))->filter(fn ($v) => is_scalar($v));
    $keepReport = collect(request()->except(['page', 'period', 'report', 'export']))->filter(fn ($v) => is_scalar($v));
@endphp

<div data-tt data-target="{{ $target }}" data-empty="{{ $empty }}" data-server="{{ $serverSearch ? 1 : 0 }}">
    <div class="table-toolbar tt-bar">
        <div class="tt-left">
            @if($serverSearch)
                <form method="GET" action="{{ url()->current() }}" class="tt-search-form" role="search">
                    @foreach($keepSearch as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <label class="tb-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}" autocomplete="off" data-tt-search>
                    </label>
                </form>
            @else
                <label class="tb-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" placeholder="{{ $placeholder }}" autocomplete="off" data-tt-search>
                </label>
            @endif

            <label class="tt-filter">
                <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5h18l-7 8v6l-4 2v-8z"/></svg>
                <select data-tt-filter aria-label="Filter">
                    <option value="" data-translate>{{ $filterLabel }}</option>
                </select>
                <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </label>
        </div>

        <div class="tt-right">
            {{ $slot }}
            @if($addUrl)
                <a href="{{ $addUrl }}" class="tt-add">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    <span data-translate>{{ $addLabel }}</span>
                </a>
            @endif
        </div>
    </div>

    @if($report)
        <div class="rt-strip">
            <span class="rt-label" data-translate>Laporan & Ekspor</span>
            <form method="GET" action="{{ url()->current() }}" class="rt-form">
                @foreach($keepReport as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach

                <label class="rt-select">
                    <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                    <select name="period" aria-label="Periode laporan">
                        @foreach(['weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan'] as $val => $lbl)
                            <option value="{{ $val }}" {{ request('period', 'monthly') === $val ? 'selected' : '' }} data-translate>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                </label>

                <button type="submit" name="report" value="1" formtarget="_blank" class="rt-btn report">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V8zM14 3v5h5M9 13h6M9 17h6"/></svg>
                    <span data-translate>Laporan</span>
                </button>
                <button type="submit" name="export" value="xlsx" class="rt-btn sheet">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2zM3 10h18M3 15h18M9 3v18"/></svg>
                    <span data-translate>Export Spreadsheet</span>
                </button>
            </form>
        </div>
    @endif
</div>

@once
<script>
(function () {
    var applyI18N = function () { if (window.I18N) window.I18N.apply(); };

    document.querySelectorAll('[data-tt]').forEach(function (root) {
        var body = document.getElementById(root.getAttribute('data-target'));
        if (!body) return;
        var empty  = document.getElementById(root.getAttribute('data-empty'));
        var rows   = Array.prototype.slice.call(body.querySelectorAll('[data-search]'));
        var search = root.querySelector('[data-tt-search]');
        var sel    = root.querySelector('[data-tt-filter]');
        var server = root.getAttribute('data-server') === '1';

        /* baris bisa diklik bila punya data-href (kecuali klik pada link/tombol/form) */
        rows.forEach(function (r) {
            if (!r.getAttribute('data-href')) return;
            r.addEventListener('click', function (e) {
                if (e.target.closest('a, button, input, select, textarea, form, label')) return;
                window.location.href = r.getAttribute('data-href');
            });
        });

        /* isi opsi filter dari nilai data-filter yang ada di tabel */
        if (sel) {
            var seen = {};
            rows.forEach(function (r) {
                var v = r.getAttribute('data-filter');
                if (!v || seen[v]) return;
                seen[v] = true;
                var o = document.createElement('option');
                o.value = v;
                o.textContent = r.getAttribute('data-filter-label') || v;
                o.setAttribute('data-translate', '');
                sel.appendChild(o);
            });
            if (!Object.keys(seen).length) sel.parentNode.style.display = 'none';
        }

        function apply() {
            var q = (search && !server) ? search.value.trim().toLowerCase() : '';
            var f = sel ? sel.value : '', shown = 0;
            rows.forEach(function (r) {
                var ok = (!f || r.getAttribute('data-filter') === f) &&
                         (!q || (r.getAttribute('data-search') || '').indexOf(q) > -1);
                r.style.display = ok ? '' : 'none';
                if (ok) shown++;
            });
            if (empty) empty.hidden = shown > 0 || rows.length === 0;
        }

        if (search && !server) search.addEventListener('input', apply);
        if (sel) sel.addEventListener('change', apply);

        /* pencarian server: kirim form 500 ms setelah berhenti mengetik, fokus dikembalikan setelah muat ulang */
        if (server && search) {
            var form = search.closest('form'), timer, last = search.value.trim();
            search.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(function () { if (search.value.trim() !== last) form.submit(); }, 500);
            });
            if (last !== '') { search.focus(); search.setSelectionRange(search.value.length, search.value.length); }
        }

        applyI18N();
    });
})();
</script>
@endonce
