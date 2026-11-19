@extends('layouts.app')
@section('title', 'Permohonan Kendaraan')

@section('content')
@include('partials.ui-extras')
<style>
    /* ================= HERO ================= */
    .rq-hero {
        position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-between; gap: 20px;
        margin-bottom: 20px; padding: 26px 32px; border-radius: 24px; color: #fff;
        background: linear-gradient(120deg, #172a6e 0%, #2150d8 55%, #3aa0f5 118%);
        box-shadow: 0 20px 42px rgba(30, 64, 175, .26);
    }
    .rq-hero::before { /* titik halus */
        content: ''; position: absolute; inset: 0; pointer-events: none; opacity: .5;
        background-image: radial-gradient(rgba(255,255,255,.22) 1.2px, transparent 1.2px); background-size: 22px 22px;
        -webkit-mask-image: linear-gradient(100deg, #000 0, transparent 55%); mask-image: linear-gradient(100deg, #000 0, transparent 55%);
    }
    .rq-hero::after {
        content: ''; position: absolute; width: 300px; height: 300px; right: -80px; top: -140px; border-radius: 50%; pointer-events: none;
        background: radial-gradient(circle, rgba(255,255,255,.2), transparent 68%);
    }
    .rq-text { position: relative; z-index: 2; display: flex; flex-direction: column; gap: 10px; min-width: 0; }
    .rq-text h2 { font-size: 26px; line-height: 1.15; font-weight: 800; letter-spacing: -.4px; }
    .rq-text p { max-width: 460px; font-size: 13.5px; line-height: 1.6; color: rgba(255,255,255,.84); }
    .rq-pill {
        align-self: flex-start; display: inline-flex; align-items: center; gap: 8px; padding: 6px 13px; border-radius: 20px;
        background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.24); font-size: 12px; font-weight: 600; backdrop-filter: blur(6px);
    }
    .rq-pill i { width: 8px; height: 8px; border-radius: 50%; background: #fbbf24; box-shadow: 0 0 0 0 rgba(251,191,36,.6); animation: rq-ping 1.6s infinite; }
    .rq-pill b { font-weight: 800; }

    /* peta rute: titik asal -> tujuan, mobil berjalan di sepanjang rute */
    .rq-scene { position: relative; z-index: 1; flex-shrink: 0; width: 360px; height: 130px; }
    .rq-map { position: absolute; inset: 0; width: 360px; height: 130px; overflow: visible; }
    .rq-route-bg { fill: none; stroke: rgba(255,255,255,.16); stroke-width: 14; stroke-linecap: round; }
    .rq-route { fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-dasharray: 2 9; animation: rq-dash 1.2s linear infinite; }
    .rq-origin { fill: #fff; }
    .rq-ring { fill: none; stroke: rgba(255,255,255,.6); stroke-width: 2; transform-box: fill-box; transform-origin: center; animation: rq-pulse 2s ease-out infinite; }
    .rq-pin { animation: rq-bob 2.2s ease-in-out infinite; }
    .rq-car {
        position: absolute; left: 0; top: 0; width: 40px; height: 28px;
        offset-path: path('M20 96 C 90 96, 90 36, 160 52 S 280 100, 330 34'); offset-rotate: auto; offset-distance: 0%;
        animation: rq-go 8s ease-in-out infinite; filter: drop-shadow(0 6px 6px rgba(5,10,40,.35));
    }
    .rq-car svg { display: block; width: 100%; height: 100%; }

    /* ================= TABEL ================= */
    .data-table tbody tr.is-pending td:first-child { box-shadow: inset 3px 0 0 #f59e0b; }
    .dest { display: flex; align-items: center; gap: 10px; min-width: 160px; }
    .dest-ico { width: 30px; height: 30px; flex-shrink: 0; border-radius: 10px; display: grid; place-items: center; background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
    .dest-ico svg { width: 15px; height: 15px; }
    .dest-name { font-weight: 600; color: var(--text-main); line-height: 1.35; }

    .when { display: flex; flex-direction: column; gap: 2px; white-space: nowrap; }
    .when-date { display: inline-flex; align-items: center; gap: 6px; font-weight: 600; color: var(--text-main); }
    .when-date svg { width: 13px; height: 13px; color: var(--text-muted); }
    .when-time { font-size: 11.5px; color: var(--text-muted); padding-left: 19px; }

    /* form penugasan */
    .vr-assign { display: flex; align-items: center; justify-content: flex-end; flex-wrap: wrap; gap: 8px; }
    .vr-assign .dd { width: 158px; }
    .vr-assign-btn {
        height: 36px; display: inline-flex; align-items: center; gap: 7px; padding: 0 15px; border: none; border-radius: 11px; cursor: pointer;
        background: linear-gradient(135deg, #2bc796 0%, #059669 100%); color: #fff; font: inherit; font-size: 12.5px; font-weight: 700;
        box-shadow: 0 8px 16px rgba(5,150,105,.26); transition: transform .2s, box-shadow .2s, filter .2s;
    }
    .vr-assign-btn svg { width: 14px; height: 14px; }
    .vr-assign-btn:hover { transform: translateY(-1px); box-shadow: 0 12px 20px rgba(5,150,105,.34); filter: brightness(1.05); }
    .vr-assign-btn:active { transform: scale(.97); }
    .no-act { color: var(--text-muted); }

    .vr-empty-ico { position: relative; }
    @keyframes rq-dash  { to { stroke-dashoffset: -22; } }
    @keyframes rq-pulse { from { transform: scale(.6); opacity: .8; } to { transform: scale(2.2); opacity: 0; } }
    @keyframes rq-bob   { 50% { transform: translateY(-3px); } }
    @keyframes rq-go    { 0% { offset-distance: 0%; } 100% { offset-distance: 100%; } }
    @keyframes rq-ping  { 70% { box-shadow: 0 0 0 9px rgba(251,191,36,0); } 100% { box-shadow: 0 0 0 0 rgba(251,191,36,0); } }

    @media (max-width: 900px) { .rq-scene { display: none; } .rq-hero { padding: 24px; } .rq-text h2 { font-size: 22px; } }
    @media (prefers-reduced-motion: reduce) { .rq-route, .rq-ring, .rq-pin, .rq-car, .rq-pill i { animation: none !important; } .rq-car { offset-distance: 55%; } }
</style>

@php
    $onPage    = $requests->count();
    $total     = $requests instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $requests->total() : $onPage;
    $submitted = $requests->where('status', 'submitted')->count();
    $vehicles  = \App\Models\Vehicle::where('status', 'available')->get();   // sekali saja, bukan per baris

    /* opsi untuk dropdown penugasan */
    $vehicleOptions = $vehicles->map(function ($v) {
        $model = trim(($v->make ?? '') . ' ' . ($v->model ?? ''));
        $cap   = $v->passenger_capacity ? $v->passenger_capacity . ' orang' : '';
        return ['value' => $v->id, 'label' => $v->license_plate, 'sub' => trim($model . ($model && $cap ? ' · ' : '') . $cap)];
    })->all();
    $driverOptions = collect($drivers)->map(fn($d) => ['value' => $d->id, 'label' => $d->name])->all();

    $labels = [
        'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'assigned' => 'Ditugaskan',
        'completed' => 'Selesai', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan',
    ];
    $labelOf = fn($s) => $labels[strtolower((string) $s)] ?? $s;
    $badgeOf = fn($s) => match (strtolower((string) $s)) {
        'submitted'              => 'badge-warning',
        'approved', 'assigned'   => 'badge-info',
        'completed'              => 'badge-success',
        'rejected', 'cancelled'  => 'badge-danger',
        default                  => 'badge-muted',
    };
    $initialsOf = fn($name) => mb_strtoupper(collect(preg_split('/\s+/', trim((string) $name)))->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));
@endphp

<!-- HERO -->
<section class="rq-hero">
    <div class="rq-text">
        @if($submitted > 0)
            <span class="rq-pill"><i></i><span><b data-convert="digit">{{ number_format($submitted, 0, ',', '.') }}</b> <span data-translate>Menunggu penugasan</span></span></span>
        @endif
        <h2 data-translate>Permohonan Kendaraan</h2>
        <p data-translate>Tinjau permohonan kendaraan dinas dan tugaskan mobil serta pengemudi.</p>
    </div>

    <div class="rq-scene" aria-hidden="true">
        <svg class="rq-map" viewBox="0 0 360 130" fill="none">
            <defs>
                <pattern id="rqGrid" width="24" height="24" patternUnits="userSpaceOnUse"><path d="M24 0H0V24" stroke="rgba(255,255,255,.1)"/></pattern>
            </defs>
            <rect width="360" height="130" rx="16" fill="url(#rqGrid)"/>
            <path class="rq-route-bg" d="M20 96 C 90 96, 90 36, 160 52 S 280 100, 330 34"/>
            <path class="rq-route" d="M20 96 C 90 96, 90 36, 160 52 S 280 100, 330 34"/>
            <circle class="rq-ring" cx="20" cy="96" r="7"/>
            <circle class="rq-origin" cx="20" cy="96" r="6"/>
            <g class="rq-pin">
                <path d="M330 34s-12-11-12-20a12 12 0 0 1 24 0c0 9-12 20-12 20z" fill="#fb7185"/>
                <circle cx="330" cy="14" r="4.5" fill="#fff"/>
            </g>
        </svg>
        <span class="rq-car">
            <svg viewBox="0 0 60 28" fill="none">
                <path d="M3 20v-4q0-3 3-4l10-2 6-6q1-1 3-1h13q2 0 3 1l5 6 7 1q3 1 3 4v5q0 1-1 1H4q-1 0-1-1z" fill="#fff"/>
                <path d="M21 10l4-4h8v6zM36 6h4l4 6h-8z" fill="#93c5fd"/>
                <circle cx="16" cy="21" r="4.5" fill="#1e293b"/><circle cx="16" cy="21" r="2" fill="#cbd5e1"/>
                <circle cx="46" cy="21" r="4.5" fill="#1e293b"/><circle cx="46" cy="21" r="2" fill="#cbd5e1"/>
            </svg>
        </span>
    </div>
</section>

<!-- STATISTIK -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14M5 17a2 2 0 11-4 0 2 2 0 014 0zm18 0a2 2 0 11-4 0 2 2 0 014 0zM3 17V9l2-4h10l4 4h2v8M9 9h6"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total permohonan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($submitted, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Menunggu penugasan</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($vehicles->count(), 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Kendaraan tersedia</div>
        </div>
    </div>
</div>

<!-- TABEL -->
<div class="table-card">
    <x-table-toolbar target="reqBody" placeholder="Cari no., pemohon, tujuan..." />

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>No</th>
                    <th data-translate>Pemohon</th>
                    <th data-translate>Tujuan</th>
                    <th data-translate>Jadwal</th>
                    <th data-translate>Status</th>
                    <th class="col-action" data-translate>Aksi</th>
                </tr>
            </thead>
            <tbody id="reqBody">
                @foreach($requests as $r)
                    @php
                        $who   = $r->requester?->name ?? '-';
                        $date  = $r->starts_at?->format('d-m-Y') ?? '-';
                        $time  = $r->starts_at?->format('H:i') ?? '';
                        $label = $labelOf($r->status);
                    @endphp
                    <tr class="{{ $r->status === 'submitted' ? 'is-pending' : '' }}" data-filter="{{ $r->status }}" data-filter-label="{{ $label }}"
                        data-search="{{ strtolower($r->request_number . ' ' . $who . ' ' . $r->destination . ' ' . $label . ' ' . $r->status) }}">
                        <td><span class="code-tag" data-convert="full">{{ $r->request_number }}</span></td>
                        <td>
                            <div class="person">
                                <div class="avatar-sm">{{ $initialsOf($who) ?: '?' }}</div>
                                <span data-convert="full">{{ $who }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="dest">
                                <span class="dest-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 0 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
                                <span class="dest-name">{{ $r->destination }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="when">
                                <span class="when-date">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    <span data-convert="digit">{{ $date }}</span>
                                </span>
                                @if($time)<span class="when-time" data-convert="digit">{{ $time }}</span>@endif
                            </div>
                        </td>
                        <td><span class="badge {{ $badgeOf($r->status) }}"><span class="dot"></span><span data-translate>{{ $label }}</span></span></td>
                        <td class="col-action">
                            @if($r->status === 'submitted')
                                <form method="POST" action="{{ route('admin.kendaraan.requests.approve', $r) }}" class="act-form assign-form vr-assign">
                                    @csrf
                                    <x-dropdown name="vehicle_id" :options="$vehicleOptions" placeholder="Pilih mobil" icon="car" size="sm" required />
                                    <x-dropdown name="driver_user_id" :options="$driverOptions" placeholder="Pilih driver" icon="user" size="sm" required />
                                    <button type="submit" class="vr-assign-btn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>
                                        <span data-translate>Tugaskan</span>
                                    </button>
                                </form>
                            @else
                                <span class="no-act">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="emptyServer" @if($onPage > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14M5 17a2 2 0 11-4 0 2 2 0 014 0zm18 0a2 2 0 11-4 0 2 2 0 014 0zM3 17V9l2-4h10l4 4h2v8M9 9h6"/></svg></div>
        <h3 data-translate>Belum ada permohonan.</h3>
        <p data-translate>Permohonan kendaraan dinas akan muncul di sini.</p>
    </div>
    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Permohonan tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter yang lain.</p>
    </div>

    <x-table-foot :paginator="$requests" noun="permohonan" />
</div>
@endsection
