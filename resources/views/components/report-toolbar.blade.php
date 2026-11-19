@once
<style>
    .rt-row { display: flex; justify-content: flex-end; margin: -4px 0 16px; }
    .rt-form { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; justify-content: flex-end; margin: 0; }
    .rt-select { position: relative; display: inline-flex; align-items: center; }
    .rt-select .ic, .rt-select .caret { position: absolute; width: 15px; height: 15px; pointer-events: none; color: var(--text-muted); }
    .rt-select .ic { left: 12px; }
    .rt-select .caret { right: 11px; width: 13px; height: 13px; }
    .rt-select select {
        appearance: none; -webkit-appearance: none; width: auto; max-width: none; height: 38px; padding: 0 32px 0 34px;
        border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-main);
        font-size: 12.5px; font-weight: 500; font-family: inherit; cursor: pointer; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .rt-select select:focus { border-color: var(--accent, #2563eb); box-shadow: 0 0 0 3px var(--accent-soft, #eef3ff); }
    .rt-btn {
        display: inline-flex; align-items: center; gap: 7px; height: 38px; padding: 0 15px; border: none; border-radius: 12px;
        cursor: pointer; font-family: inherit; font-size: 12.5px; font-weight: 600; transition: background .2s, color .2s, transform .15s;
    }
    .rt-btn svg { width: 15px; height: 15px; }
    .rt-btn:hover { transform: translateY(-1px); }
    .rt-btn.report { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .rt-btn.report:hover { background: #2563eb; color: #fff; }
    .rt-btn.sheet { background: rgba(16,185,129,.13); color: #059669; }
    .rt-btn.sheet:hover { background: #059669; color: #fff; }
    body.dark-mode .rt-btn.sheet { color: #34d399; }
    body.dark-mode .rt-btn.sheet:hover { background: #10b981; color: #fff; }
    @media print { .rt-row { display: none !important; } }
</style>
@endonce

<div class="rt-row">
    <form method="GET" action="{{ url()->current() }}" class="rt-form">
        @foreach(request()->except(['period', 'report', 'export', 'page']) as $k => $v)
            @if(is_scalar($v))
                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
            @endif
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
