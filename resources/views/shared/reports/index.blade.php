@extends('layouts.app')
@section('title', 'Laporan & Rekap')

@section('content')
<style>
    /* ===== Laporan: hanya gaya tambahan, selebihnya memakai UI Kit SIM-BU ===== */
    .rpt-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
    .rpt-card {
        position: relative; display: flex; flex-direction: column; overflow: hidden;
        background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; padding: 18px 20px 16px;
        transition: transform .22s, box-shadow .22s, border-color .22s;
    }
    .rpt-card::after {
        content: ''; position: absolute; right: -28px; top: -28px; width: 96px; height: 96px; border-radius: 50%;
        background: var(--tint); opacity: .10; transition: transform .3s; pointer-events: none;
    }
    .rpt-card:hover { transform: translateY(-3px); box-shadow: 0 14px 28px rgba(20,30,80,.10); border-color: var(--tint); }
    .rpt-card:hover::after { transform: scale(1.5); }

    .rpt-head { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .rpt-ico {
        width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; flex-shrink: 0;
        color: #fff; background: var(--tint); box-shadow: 0 8px 16px color-mix(in srgb, var(--tint) 38%, transparent);
    }
    .rpt-ico svg { width: 19px; height: 19px; }
    .rpt-head h3 { font-size: 14.5px; font-weight: 600; color: var(--text-main); line-height: 1.25; }
    .rpt-head small { display: block; font-size: 11px; color: var(--text-muted); margin-top: 2px; }
    .rpt-head .badge { margin-left: auto; }

    .rpt-rows { list-style: none; margin: 0; padding: 0; }
    .rpt-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 9px 0; border-top: 1px dashed var(--border-color); font-size: 12.5px; }
    .rpt-row:first-child { border-top: none; }
    .rpt-row span { color: var(--text-muted); }
    .rpt-row b { font-size: 14px; font-weight: 700; color: var(--text-main); font-variant-numeric: tabular-nums; }
    .rpt-row b.warn { color: #d97706; }
    .rpt-row b.danger { color: #dc2626; }
    .rpt-row b.ok { color: #059669; }
    body.dark-mode .rpt-row b.warn { color: #fbbf24; }
    body.dark-mode .rpt-row b.danger { color: #f87171; }
    body.dark-mode .rpt-row b.ok { color: #34d399; }

    .rpt-bar-wrap { margin: 4px 0 10px; }
    .rpt-bar-top { display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); margin-bottom: 6px; }
    .rpt-bar { height: 7px; border-radius: 10px; background: var(--bg-soft, #f3f6fd); overflow: hidden; }
    .rpt-bar i { display: block; height: 100%; border-radius: 10px; background: var(--tint); width: 0; transition: width .9s cubic-bezier(.2,.8,.2,1); }

    .rpt-foot { margin-top: auto; padding-top: 12px; display: flex; justify-content: flex-end; }

    .rpt-tools { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .btn-ghost {
        display: inline-flex; align-items: center; gap: 7px; padding: 7px 14px; border-radius: 20px; cursor: pointer;
        border: 1px solid var(--border-color); background: transparent; color: var(--text-muted);
        font-size: 12px; font-weight: 500; font-family: inherit; transition: all .2s;
    }
    .btn-ghost:hover { background: var(--bg-hover); color: var(--text-main); }
    .btn-ghost svg { width: 14px; height: 14px; }

    .rpt-section { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin: 22px 0 12px; }
    .rpt-section .section-title { font-size: 15px; font-weight: 600; color: var(--text-main); }

    @media print {
        .rpt-tools, .rpt-foot { display: none !important; }
        .rpt-card, .stat-card { break-inside: avoid; box-shadow: none !important; transform: none !important; }
    }
    @media (prefers-reduced-motion: reduce) { .rpt-card, .rpt-bar i { transition: none !important; } }
</style>

@php
    $n   = fn($v) => number_format((int) $v, 0, ',', '.');
    $g   = fn($path) => (int) data_get($data, $path, 0);
    $pct = fn($a, $b) => $b > 0 ? (int) round($a / $b * 100) : 0;
    $url = fn($name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : null;

    $pendingTotal = $g('inventory.loans_pending') + $g('supply.requests_pending') + $g('vehicles.requests_pending')
                  + $g('facilities.bookings_pending') + $g('travel.pending');
    $lettersMonth = $g('letters.incoming_month') + $g('letters.outgoing_month');

    $modules = [
        [
            'key' => 'letters', 'title' => 'Persuratan', 'sub' => 'Surat masuk & keluar', 'tint' => '#2563eb',
            'icon' => '<path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            'url' => $url('admin.persuratan.incoming-letters.index'), 'attn' => 0, 'bar' => null,
            'rows' => [
                ['Surat Masuk (total)',     $g('letters.incoming_total'), 'strong'],
                ['Surat Masuk (bulan ini)', $g('letters.incoming_month'), null],
                ['Surat Keluar (total)',    $g('letters.outgoing_total'), 'strong'],
                ['Surat Keluar (bulan ini)', $g('letters.outgoing_month'), null],
            ],
        ],
        [
            'key' => 'inventory', 'title' => 'Inventaris Aset', 'sub' => 'Aset & peminjaman', 'tint' => '#f97316',
            'icon' => '<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
            'url' => $url('admin.inventaris.assets.index'), 'attn' => $g('inventory.loans_pending'),
            'bar' => ['Ketersediaan aset', $g('inventory.assets_available'), $g('inventory.assets_total')],
            'rows' => [
                ['Total Aset',            $g('inventory.assets_total'),     'strong'],
                ['Tersedia',              $g('inventory.assets_available'), 'ok'],
                ['Dipinjam',              $g('inventory.assets_loaned'),    null],
                ['Pinjaman aktif',        $g('inventory.loans_active'),     null],
                ['Menunggu persetujuan',  $g('inventory.loans_pending'),    'warn'],
            ],
        ],
        [
            'key' => 'supply', 'title' => 'ATK / Stok', 'sub' => 'Persediaan & permintaan', 'tint' => '#d946ef',
            'icon' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12h6M9 16h4"/>',
            'url' => null, 'attn' => $g('supply.low_stock') + $g('supply.requests_pending'),
            'bar' => ['Item stok aman', max(0, $g('supply.items_total') - $g('supply.low_stock')), $g('supply.items_total')],
            'rows' => [
                ['Total Item',         $g('supply.items_total'),       'strong'],
                ['Stok Rendah',        $g('supply.low_stock'),         'danger'],
                ['Permintaan pending', $g('supply.requests_pending'),  'warn'],
            ],
        ],
        [
            'key' => 'vehicles', 'title' => 'Kendaraan', 'sub' => 'Armada & permohonan', 'tint' => '#10b981',
            'icon' => '<path d="M5 17h14M5 17a2 2 0 11-4 0 2 2 0 014 0zm18 0a2 2 0 11-4 0 2 2 0 014 0zM3 17V9l2-4h10l4 4h2v8M9 9h6"/>',
            'url' => null, 'attn' => $g('vehicles.requests_pending'),
            'bar' => ['Kendaraan tersedia', $g('vehicles.available'), $g('vehicles.total')],
            'rows' => [
                ['Total Kendaraan',    $g('vehicles.total'),             'strong'],
                ['Tersedia',           $g('vehicles.available'),         'ok'],
                ['Permohonan pending', $g('vehicles.requests_pending'),  'warn'],
                ['Disetujui',          $g('vehicles.requests_approved'), null],
            ],
        ],
        [
            'key' => 'facilities', 'title' => 'Fasilitas', 'sub' => 'Booking ruangan', 'tint' => '#8b5cf6',
            'icon' => '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
            'url' => $url('admin.fasilitas.rooms.index'), 'attn' => $g('facilities.bookings_pending'), 'bar' => null,
            'rows' => [
                ['Booking pending',   $g('facilities.bookings_pending'),  'warn'],
                ['Booking disetujui', $g('facilities.bookings_approved'), 'ok'],
                ['Booking bulan ini', $g('facilities.bookings_month'),    null],
            ],
        ],
        [
            'key' => 'events', 'title' => 'Kegiatan', 'sub' => 'Agenda bagian', 'tint' => '#0ea5e9',
            'icon' => '<path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>',
            'url' => null, 'attn' => 0, 'bar' => null,
            'rows' => [
                ['Total Kegiatan', $g('events.total'),      'strong'],
                ['Akan datang',    $g('events.upcoming'),   null],
                ['Bulan ini',      $g('events.this_month'), null],
            ],
        ],
        [
            'key' => 'travel', 'title' => 'Perjalanan Dinas', 'sub' => 'Pengajuan perjalanan', 'tint' => '#ef4444',
            'icon' => '<path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>',
            'url' => null, 'attn' => $g('travel.pending'),
            'bar' => ['Pengajuan disetujui', $g('travel.approved'), $g('travel.total')],
            'rows' => [
                ['Total',     $g('travel.total'),    'strong'],
                ['Pending',   $g('travel.pending'),  'warn'],
                ['Disetujui', $g('travel.approved'), 'ok'],
            ],
        ],
    ];
@endphp

<!-- ===== KEPALA HALAMAN ===== -->
<div class="page-head">
    <div>
        <h2 data-translate>Laporan & Rekap</h2>
        <p><span data-translate>Ringkasan seluruh modul SIM-BU per</span> <span data-convert="digit">{{ now()->translatedFormat('d F Y') }}</span></p>
    </div>
    <div class="rpt-tools">
        <button type="button" class="btn-ghost" onclick="window.print()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V3h12v6M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v7H6z"/></svg>
            <span data-translate>Cetak</span>
        </button>
    </div>
</div>

<!-- ===== SOROTAN ===== -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ $n($pendingTotal) }}</div>
            <div class="stat-lbl" data-translate>Menunggu persetujuan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4m0 4h.01M10.3 3.9L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ $n($g('supply.low_stock')) }}</div>
            <div class="stat-lbl" data-translate>Item ATK stok rendah</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ $n($lettersMonth) }}</div>
            <div class="stat-lbl" data-translate>Surat bulan ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ $n($g('events.upcoming')) }}</div>
            <div class="stat-lbl" data-translate>Kegiatan akan datang</div>
        </div>
    </div>
</div>

<!-- ===== RINGKASAN PER MODUL ===== -->
<div class="rpt-section">
    <div class="section-title" data-translate>Ringkasan per Modul</div>
    <div class="chips" id="rptChips">
        <button type="button" class="chip on" data-f="all"><span data-translate>Semua</span></button>
        <button type="button" class="chip" data-f="attn"><span data-translate>Perlu Tindakan</span></button>
    </div>
</div>

<div class="rpt-grid" id="rptGrid">
    @foreach($modules as $m)
        <section class="rpt-card" style="--tint: {{ $m['tint'] }}" data-attn="{{ $m['attn'] }}">
            <div class="rpt-head">
                <div class="rpt-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $m['icon'] !!}</svg>
                </div>
                <div>
                    <h3 data-translate>{{ $m['title'] }}</h3>
                    <small data-translate>{{ $m['sub'] }}</small>
                </div>
                @if($m['attn'] > 0)
                    <span class="badge badge-warning"><span class="dot"></span><span data-convert="digit">{{ $n($m['attn']) }}</span>&nbsp;<span data-translate>perlu tindakan</span></span>
                @else
                    <span class="badge badge-success"><span class="dot"></span><span data-translate>Aman</span></span>
                @endif
            </div>

            @if($m['bar'])
                @php $p = $pct($m['bar'][1], $m['bar'][2]); @endphp
                <div class="rpt-bar-wrap">
                    <div class="rpt-bar-top">
                        <span data-translate>{{ $m['bar'][0] }}</span>
                        <span data-convert="digit">{{ $p }}%</span>
                    </div>
                    <div class="rpt-bar"><i data-w="{{ $p }}"></i></div>
                </div>
            @endif

            <ul class="rpt-rows">
                @foreach($m['rows'] as $r)
                    <li class="rpt-row">
                        <span data-translate>{{ $r[0] }}</span>
                        <b class="{{ in_array($r[2], ['warn','danger','ok']) ? $r[2] : '' }}" data-convert="digit">{{ $n($r[1]) }}</b>
                    </li>
                @endforeach
            </ul>

            @if($m['url'])
                <div class="rpt-foot">
                    <a href="{{ $m['url'] }}" class="btn-open"><span data-translate>Buka Modul</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
            @endif
        </section>
    @endforeach
</div>

<div class="empty-state" id="rptEmpty" hidden>
    <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
    <h3 data-translate>Semua beres</h3>
    <p data-translate>Tidak ada modul yang memerlukan tindakan saat ini.</p>
</div>

<script>
(function () {
    var applyI18N = function () { if (window.I18N) window.I18N.apply(); };

    /* isi progress bar dengan animasi */
    requestAnimationFrame(function () {
        document.querySelectorAll('.rpt-bar i[data-w]').forEach(function (el) { el.style.width = el.getAttribute('data-w') + '%'; });
    });

    /* filter: Semua / Perlu Tindakan */
    var chips = document.querySelectorAll('#rptChips .chip');
    chips.forEach(function (c) {
        c.addEventListener('click', function () {
            chips.forEach(function (x) { x.classList.toggle('on', x === c); });
            var onlyAttn = c.getAttribute('data-f') === 'attn', shown = 0;
            document.querySelectorAll('#rptGrid .rpt-card').forEach(function (card) {
                var ok = !onlyAttn || +card.getAttribute('data-attn') > 0;
                card.hidden = !ok; card.style.display = ok ? '' : 'none';
                if (ok) shown++;
            });
            document.getElementById('rptEmpty').hidden = shown > 0;
            applyI18N();
        });
    });
})();
</script>
@endsection
