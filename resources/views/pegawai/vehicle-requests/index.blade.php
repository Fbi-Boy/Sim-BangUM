@extends('layouts.app')
@section('title', 'Permohonan Kendaraan Saya')

@section('content')
@include('partials.ui-extras')
<style>
    /* ===== Banner (gaya dashboard) ===== */
    .hero {
        position: relative; overflow: hidden; border-radius: 24px; padding: 28px 34px; color: #fff; margin-bottom: 18px;
        background:
            radial-gradient(circle at 85% 15%, rgba(167,139,250,.55) 0, transparent 45%),
            radial-gradient(circle at 10% 110%, rgba(56,189,248,.45) 0, transparent 50%),
            linear-gradient(135deg, #2563eb 0%, #4338ca 100%);
        box-shadow: 0 18px 36px rgba(37,99,235,.28);
    }
    .hero::before {
        content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.16) 1.2px, transparent 1.2px);
        background-size: 20px 20px; opacity: .55;
        -webkit-mask-image: linear-gradient(90deg, transparent 35%, #000 100%); mask-image: linear-gradient(90deg, transparent 35%, #000 100%);
    }
    .hero-body { position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; gap: 20px; }
    .hero-text { max-width: 58%; }
    .hero-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; background: rgba(255,255,255,.18); backdrop-filter: blur(6px); font-size: 11px; font-weight: 500; margin-bottom: 12px; }
    .hero-tag i { width: 6px; height: 6px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 3px rgba(52,211,153,.35); }
    .hero h1 { font-size: 26px; font-weight: 700; line-height: 1.2; margin: 0 0 8px; letter-spacing: -.3px; color: #fff; }
    .hero p { font-size: 12.5px; opacity: .92; line-height: 1.65; margin: 0 0 18px; color: #fff; }
    .hero-btn {
        display: inline-flex; align-items: center; gap: 8px; background: #fff; color: #2563eb; padding: 9px 18px; border-radius: 10px;
        font-size: 12px; font-weight: 600; text-decoration: none; box-shadow: 0 8px 18px rgba(0,0,0,.15); transition: transform .2s;
    }
    .hero-btn:hover { transform: translateY(-2px); }
    .hero-art { flex: 0 0 290px; height: 140px; }
    .hero-art svg { width: 100%; height: 100%; overflow: visible; }

    /* ===== Animasi bertema kendaraan: mobil melaju di jalan ===== */
    .hero .road-dash { animation: roadMove 1s linear infinite; }          /* marka jalan bergerak ke kiri */
    .hero .car { animation: carBounce .6s ease-in-out infinite; }         /* mobil naik-turun halus */
    .hero .wheel { transform-box: fill-box; transform-origin: center; animation: wheelSpin .7s linear infinite; }
    .hero .cloud { animation: cloudDrift 9s linear infinite; }
    .hero .cloud.c2 { animation-duration: 13s; animation-delay: -5s; }
    .hero .speed { animation: speedLine 1.1s ease-in-out infinite; }
    .hero .speed.s2 { animation-delay: -.4s; }
    .hero .speed.s3 { animation-delay: -.8s; }
    @keyframes roadMove  { to { stroke-dashoffset: -28; } }
    @keyframes carBounce { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-2.5px); } }
    @keyframes wheelSpin { to { transform: rotate(360deg); } }
    @keyframes cloudDrift { from { transform: translateX(40px); opacity: 0; } 15% { opacity: .8; } 85% { opacity: .8; } to { transform: translateX(-60px); opacity: 0; } }
    @keyframes speedLine { 0% { transform: translateX(10px); opacity: 0; } 40% { opacity: .85; } 100% { transform: translateX(-12px); opacity: 0; } }
    @media (prefers-reduced-motion: reduce) { .hero .road-dash, .hero .car, .hero .wheel, .hero .cloud, .hero .speed { animation: none; } }
    @media (max-width: 760px) { .hero-art { display: none; } .hero-text { max-width: 100%; } .hero { padding: 24px 22px; } }

    /* ===== Plat nomor ===== */
    .plate {
        display: inline-block; padding: 3px 10px; border-radius: 7px; border: 1.5px solid var(--text-main); font-size: 12px; font-weight: 700;
        letter-spacing: 1px; font-variant-numeric: tabular-nums; color: var(--text-main); background: var(--bg-card);
    }
    .time-range { display: flex; flex-direction: column; gap: 2px; white-space: nowrap; }
    .time-range .t1 { font-weight: 500; }
    .time-range .t2 { font-size: 11.5px; color: var(--text-muted); }
</style>

@php
    $onPage    = $requests->count();
    $total     = $requests instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $requests->total() : $onPage;
    $submitted = $requests->where('status', 'submitted')->count();
    $assigned  = $requests->whereIn('status', ['approved', 'assigned', 'completed'])->count();

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
@endphp

<!-- ===== KOTAK PANJANG (BANNER) ===== -->
<div class="hero">
    <div class="hero-body">
        <div class="hero-text">
            <div class="hero-tag"><i></i> <span data-translate>SIM-BU · Kendaraan Dinas</span></div>
            <h1 data-translate>Permohonan Kendaraan Saya</h1>
            <p data-translate>Ajukan kendaraan dinas untuk perjalanan Anda dan pantau penugasan mobil serta statusnya di sini.</p>
            <a href="{{ route('pegawai.vehicle-requests.create') }}" class="hero-btn">
                <span data-translate>Ajukan Permohonan</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>

        <div class="hero-art" aria-hidden="true">
            <svg viewBox="0 0 290 140" fill="none">
                <circle cx="150" cy="66" r="58" fill="rgba(255,255,255,.10)"/>
                <circle cx="150" cy="66" r="38" fill="rgba(255,255,255,.10)"/>

                <!-- awan -->
                <g class="cloud"><ellipse cx="230" cy="26" rx="20" ry="7" fill="#fff" opacity=".7"/><ellipse cx="220" cy="22" rx="11" ry="6" fill="#fff" opacity=".7"/></g>
                <g class="cloud c2"><ellipse cx="90" cy="36" rx="15" ry="5.5" fill="#fff" opacity=".6"/><ellipse cx="82" cy="32" rx="8" ry="4.5" fill="#fff" opacity=".6"/></g>

                <!-- jalan -->
                <rect x="0" y="108" width="290" height="20" rx="10" fill="rgba(15,23,42,.28)"/>
                <line class="road-dash" x1="0" y1="118" x2="290" y2="118" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-dasharray="14 14" opacity=".75"/>

                <!-- garis kecepatan di belakang mobil -->
                <g stroke="#fff" stroke-width="2.4" stroke-linecap="round">
                    <line class="speed"    x1="40" y1="74" x2="68" y2="74"/>
                    <line class="speed s2" x1="28" y1="86" x2="62" y2="86"/>
                    <line class="speed s3" x1="46" y1="98" x2="70" y2="98"/>
                </g>

                <!-- mobil -->
                <g class="car">
                    <path d="M92 100 L98 80 Q101 72 110 72 L152 72 Q161 72 167 79 L182 92 L196 95 Q204 97 204 104 L204 108 L92 108 Z" fill="#fff"/>
                    <path d="M108 76 L118 76 L118 90 L100 90 Z" fill="#bfdbfe"/>
                    <path d="M124 76 L152 76 Q157 76 161 81 L170 90 L124 90 Z" fill="#bfdbfe"/>
                    <rect x="196" y="97" width="8" height="5" rx="2.5" fill="#fbbf24"/>
                    <rect x="92" y="97" width="6" height="5" rx="2.5" fill="#f87171"/>
                    <rect x="130" y="94" width="14" height="3" rx="1.5" fill="#cbd5e1"/>
                    <g class="wheel"><circle cx="118" cy="108" r="11" fill="#1e293b"/><circle cx="118" cy="108" r="5" fill="#cbd5e1"/><path d="M118 103v10M113 108h10" stroke="#64748b" stroke-width="1.6" stroke-linecap="round"/></g>
                    <g class="wheel"><circle cx="178" cy="108" r="11" fill="#1e293b"/><circle cx="178" cy="108" r="5" fill="#cbd5e1"/><path d="M178 103v10M173 108h10" stroke="#64748b" stroke-width="1.6" stroke-linecap="round"/></g>
                </g>

                <circle cx="254" cy="52" r="4" fill="#fff" opacity=".8"/>
                <circle cx="30" cy="40" r="3" fill="#fff" opacity=".7"/>
            </svg>
        </div>
    </div>
</div>

<!-- ===== KARTU STATISTIK ===== -->
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
            <div class="stat-val" data-convert="digit">{{ number_format($assigned, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Disetujui / selesai</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<!-- ===== TABEL ===== -->
<div class="table-card">
    <x-table-toolbar target="myVehBody" placeholder="Cari no., tujuan, plat nomor..."
        :add-url="route('pegawai.vehicle-requests.create')" add-label="Ajukan Permohonan" :report="false" />

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>No</th>
                    <th data-translate>Tujuan</th>
                    <th data-translate>Jadwal</th>
                    <th data-translate>Kendaraan</th>
                    <th data-translate>Status</th>
                </tr>
            </thead>
            <tbody id="myVehBody">
                @foreach($requests as $r)
                    @php
                        $plate = $r->vehicle?->license_plate;
                        $day   = $r->starts_at?->format('d-m-Y') ?? '-';
                        $hour  = $r->starts_at?->format('H:i') ?? '-';
                        $label = $labelOf($r->status);
                    @endphp
                    <tr data-filter="{{ $r->status }}" data-filter-label="{{ $label }}"
                        data-search="{{ strtolower($r->request_number . ' ' . $r->destination . ' ' . ($plate ?? '') . ' ' . $day . ' ' . $label . ' ' . $r->status) }}">
                        <td><span class="code-tag" data-convert="full">{{ $r->request_number }}</span></td>
                        <td><div class="cell-main" style="min-width:170px"><span class="cell-title">{{ $r->destination }}</span></div></td>
                        <td>
                            <div class="time-range" data-convert="digit">
                                <span class="t1">{{ $day }}</span>
                                <span class="t2">{{ $hour }}</span>
                            </div>
                        </td>
                        <td>
                            @if($plate)
                                <span class="plate" data-convert="full">{{ $plate }}</span>
                            @else
                                <span class="badge badge-muted" data-translate>Belum ditugaskan</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $badgeOf($r->status) }}"><span class="dot"></span><span data-translate>{{ $label }}</span></span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="emptyServer" @if($onPage > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14M5 17a2 2 0 11-4 0 2 2 0 014 0zm18 0a2 2 0 11-4 0 2 2 0 014 0zM3 17V9l2-4h10l4 4h2v8M9 9h6"/></svg></div>
        <h3 data-translate>Belum ada permohonan.</h3>
        <p data-translate>Klik "Ajukan Permohonan" untuk meminta kendaraan dinas.</p>
    </div>
    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Permohonan tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter yang lain.</p>
    </div>

    <x-table-foot :paginator="$requests" noun="permohonan" />
</div>
@endsection
