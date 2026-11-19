@extends('layouts.app')
@section('title','Perjalanan Dinas')
@section('content')
@php
    /* Pemetaan status -> [kelas warna, label]. Nilai tak dikenal tampil apa adanya (warna netral). */
    $statusMap = [
        'draft'       => ['off',  'Draf'],
        'draf'        => ['off',  'Draf'],
        'submitted'   => ['info', 'Diajukan'],
        'diajukan'    => ['info', 'Diajukan'],
        'pending'     => ['warn', 'Menunggu'],
        'menunggu'    => ['warn', 'Menunggu'],
        'approved'    => ['ok',   'Disetujui'],
        'disetujui'   => ['ok',   'Disetujui'],
        'ongoing'     => ['sky',  'Dalam Perjalanan'],
        'in_progress' => ['sky',  'Dalam Perjalanan'],
        'berlangsung' => ['sky',  'Dalam Perjalanan'],
        'completed'   => ['ok',   'Selesai'],
        'done'        => ['ok',   'Selesai'],
        'selesai'     => ['ok',   'Selesai'],
        'rejected'    => ['bad',  'Ditolak'],
        'ditolak'     => ['bad',  'Ditolak'],
        'cancelled'   => ['bad',  'Dibatalkan'],
        'canceled'    => ['bad',  'Dibatalkan'],
        'dibatalkan'  => ['bad',  'Dibatalkan'],
    ];
    $trip = fn($ico) => $ico;
@endphp

<style>
    /* ===== Modul Travel · Permohonan (tema langit / penerbangan) ===== */
    .tv { --mod: #0284c7; --mod-2: #38bdf8; --mod-soft: rgba(14,165,233,.12); --sun: #f97316; }
    body.dark-mode .tv { --mod: #38bdf8; --mod-soft: rgba(56,189,248,.15); }

    /* ---------- HERO ---------- */
    .tv-hero {
        position: relative; overflow: hidden; border-radius: 20px; padding: 24px 26px 34px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap; color: #fff;
        background: linear-gradient(160deg, #0369a1 0%, #0ea5e9 55%, #7dd3fc 100%);
        box-shadow: 0 16px 38px rgba(2,132,199,.28);
    }
    .tv-sun {                                   /* matahari terbenam */
        position: absolute; inset-inline-end: 28px; top: -34px; width: 130px; height: 130px; border-radius: 50%;
        background: radial-gradient(circle, #fde68a 0%, #fbbf24 38%, rgba(249,115,22,.0) 72%); opacity: .75;
    }
    .tv-cloud { position: absolute; background: rgba(255,255,255,.22); border-radius: 50px; filter: blur(1px); }
    .tv-cloud::before, .tv-cloud::after { content: ''; position: absolute; background: inherit; border-radius: 50%; }
    .tv-cloud.c1 { width: 120px; height: 28px; inset-inline-start: 8%; top: 18px; }
    .tv-cloud.c1::before { width: 46px; height: 46px; top: -22px; inset-inline-start: 18px; }
    .tv-cloud.c1::after  { width: 34px; height: 34px; top: -14px; inset-inline-start: 60px; }
    .tv-cloud.c2 { width: 90px; height: 22px; inset-inline-start: 46%; bottom: 52px; opacity: .8; }
    .tv-cloud.c2::before { width: 36px; height: 36px; top: -17px; inset-inline-start: 14px; }
    .tv-cloud.c2::after  { width: 26px; height: 26px; top: -10px; inset-inline-start: 46px; }

    .tv-track {                                  /* jalur pesawat + garis putus-putus */
        position: absolute; inset-inline: 0; bottom: 14px; height: 0; border-top: 2px dashed rgba(255,255,255,.55);
    }
    .tv-plane {
        position: absolute; top: -16px; width: 30px; height: 30px; color: #fff; filter: drop-shadow(0 4px 6px rgba(0,0,0,.25));
        inset-inline-start: -8%; transform: rotate(45deg); animation: tvfly 16s linear infinite;
    }
    body[dir="rtl"] .tv-plane { transform: scaleX(-1) rotate(45deg); }
    @keyframes tvfly { from { inset-inline-start: -8%; } to { inset-inline-start: 106%; } }

    .tv-hero-ico {
        width: 56px; height: 56px; border-radius: 17px; flex-shrink: 0; display: grid; place-items: center; position: relative; z-index: 1;
        background: rgba(255,255,255,.20); border: 1px solid rgba(255,255,255,.38); backdrop-filter: blur(4px);
    }
    .tv-hero-ico svg { width: 29px; height: 29px; }
    .tv-hero-text { flex: 1; min-width: 210px; position: relative; z-index: 1; }
    .tv-crumb { display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 500; opacity: .88; margin-bottom: 4px; }
    .tv-crumb i { font-style: normal; opacity: .6; }
    .tv-title { font-size: 22px; font-weight: 700; letter-spacing: -.2px; line-height: 1.2; }
    .tv-sub { font-size: 12.5px; opacity: .92; margin-top: 4px; }
    .tv-hero-side { display: flex; align-items: center; gap: 14px; position: relative; z-index: 1; }
    .tv-modes { display: flex; gap: 6px; }
    .tv-modes span { width: 34px; height: 34px; border-radius: 11px; display: grid; place-items: center; background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.26); }
    .tv-modes svg { width: 18px; height: 18px; }
    .tv-total {
        display: flex; flex-direction: column; align-items: center; padding: 8px 18px; border-radius: 14px;
        background: rgba(255,255,255,.20); border: 1px solid rgba(255,255,255,.34);
    }
    .tv-total b { font-size: 22px; font-weight: 700; line-height: 1.1; }
    .tv-total span { font-size: 10.5px; font-weight: 500; opacity: .94; }

    /* ---------- KARTU TABEL ---------- */
    .tv-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; }
    .tv-card-head { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px dashed var(--border-color); }
    .tv-card-head svg { width: 18px; height: 18px; color: var(--sun); transform: rotate(45deg); }
    body[dir="rtl"] .tv-card-head svg { transform: scaleX(-1) rotate(45deg); }
    .tv-card-head h3 { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .tv-scroll { overflow-x: auto; }
    .tv table { margin: 0; }
    .tv th { white-space: nowrap; }
    .tv td { vertical-align: middle; }
    .tv tbody tr:hover td { background: var(--mod-soft); }

    .tv-no {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; white-space: nowrap;
        font-family: ui-monospace, Menlo, Consolas, monospace; font-size: 12px; font-weight: 700; letter-spacing: .3px;
        color: var(--mod); background: var(--mod-soft); border: 1px dashed var(--mod-2);
    }
    .tv-no svg { width: 13px; height: 13px; }

    .tv-person { display: flex; align-items: center; gap: 10px; font-weight: 600; min-width: 150px; }
    .tv-avatar {
        width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0; display: grid; place-items: center; color: #fff;
        font-size: 11.5px; font-weight: 700; letter-spacing: .4px; background: linear-gradient(135deg, #0369a1, #38bdf8);
    }

    /* tujuan: titik asal --- pesawat --- pin */
    .tv-dest { display: flex; align-items: center; gap: 10px; min-width: 190px; }
    .tv-route { display: flex; align-items: center; gap: 0; flex-shrink: 0; color: var(--mod); }
    .tv-route .dot { width: 8px; height: 8px; border-radius: 50%; border: 2px solid var(--mod); background: var(--bg-card); }
    .tv-route .ln { position: relative; width: 34px; height: 0; border-top: 2px dashed var(--mod-2); }
    .tv-route .ln svg { position: absolute; top: -8px; inset-inline-start: 9px; width: 15px; height: 15px; background: var(--bg-card); color: var(--sun); transform: rotate(45deg); }
    body[dir="rtl"] .tv-route .ln svg { transform: scaleX(-1) rotate(45deg); }
    .tv-route .pin { width: 17px; height: 17px; color: #ef4444; }
    .tv-dest-name { font-weight: 600; color: var(--text-main); }

    .tv-when { display: flex; flex-direction: column; gap: 2px; white-space: nowrap; }
    .tv-when-d, .tv-when-t { display: flex; align-items: center; gap: 7px; }
    .tv-when-d { font-weight: 600; color: var(--text-main); }
    .tv-when-t { font-size: 12px; color: var(--text-muted); }
    .tv-when svg { width: 14px; height: 14px; color: var(--mod); flex-shrink: 0; }
    .tv-cd { display: inline-flex; align-items: center; gap: 4px; margin-top: 5px; width: fit-content; padding: 2px 9px; border-radius: 20px; font-size: 10.5px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #f97316, #fbbf24); white-space: nowrap; }

    .tv-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .tv-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .tv-badge.ok   { background: rgba(16,185,129,.14); color: #059669; }
    .tv-badge.info { background: rgba(59,130,246,.14); color: #2563eb; }
    .tv-badge.sky  { background: rgba(14,165,233,.16); color: #0284c7; }
    .tv-badge.warn { background: rgba(245,158,11,.16); color: #b45309; }
    .tv-badge.bad  { background: rgba(239,68,68,.14);  color: #dc2626; }
    .tv-badge.off  { background: rgba(107,114,128,.16); color: #6b7280; }
    body.dark-mode .tv-badge.ok   { color: #34d399; }
    body.dark-mode .tv-badge.info { color: #60a5fa; }
    body.dark-mode .tv-badge.sky  { color: #7dd3fc; }
    body.dark-mode .tv-badge.warn { color: #fbbf24; }
    body.dark-mode .tv-badge.bad  { color: #f87171; }
    body.dark-mode .tv-badge.off  { color: #cbd5e1; }

    .tv-detail {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 10px; text-decoration: none; white-space: nowrap;
        font-size: 12.5px; font-weight: 600; color: var(--mod); background: var(--mod-soft); border: 1px solid transparent;
        transition: background .2s, color .2s, transform .2s;
    }
    .tv-detail:hover { background: var(--mod); color: #fff; transform: translateY(-1px); }
    .tv-detail svg { width: 15px; height: 15px; }

    .tv-empty { text-align: center; padding: 46px 16px; }
    .tv-empty-ico { width: 68px; height: 68px; margin: 0 auto 14px; border-radius: 22px; display: grid; place-items: center; background: var(--mod-soft); color: var(--mod); }
    .tv-empty-ico svg { width: 32px; height: 32px; }
    .tv-empty strong { display: block; font-size: 14px; font-weight: 600; color: var(--text-main); }
    .tv-empty p { font-size: 12.5px; color: var(--text-muted); margin-top: 4px; }

    .tv-pager { padding: 14px 18px; border-top: 1px dashed var(--border-color); }
    .tv-pager:empty { display: none; }

    @media (prefers-reduced-motion: reduce) { .tv-plane { animation: none; inset-inline-start: 50%; } }
</style>

<div class="tv">
    <!-- HERO -->
    <div class="tv-hero">
        <span class="tv-sun" aria-hidden="true"></span>
        <span class="tv-cloud c1" aria-hidden="true"></span>
        <span class="tv-cloud c2" aria-hidden="true"></span>
        <div class="tv-track" aria-hidden="true">
            <svg class="tv-plane" viewBox="0 0 24 24" fill="currentColor"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
        </div>

        <div class="tv-hero-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
        </div>
        <div class="tv-hero-text">
            <div class="tv-crumb"><span>Travel</span><i>/</i><span>Permohonan</span></div>
            <div class="tv-title">Permohonan Perjalanan Dinas</div>
            <div class="tv-sub">Pantau pengajuan, tujuan, dan jadwal keberangkatan perjalanan dinas pegawai</div>
        </div>
        <div class="tv-hero-side">
            <div class="tv-modes" aria-hidden="true">
                <span><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="16" rx="2"/><path d="M4 11h16M12 3v8"/><path d="m8 19-2 3M18 22l-2-3"/><circle cx="8" cy="15" r="1"/><circle cx="16" cy="15" r="1"/></svg></span>
                <span><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6v6M16 6v6M2 12h19.6"/><path d="M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/></svg></span>
                <span><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"/><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/><path d="M12 10v4M12 2v3"/></svg></span>
            </div>
            <div class="tv-total"><b data-no-i18n>{{ method_exists($requests, 'total') ? $requests->total() : count($requests) }}</b><span>Total Permohonan</span></div>
        </div>
    </div>

    <!-- TABEL (id container dipakai oleh AJAX pagination di layout) -->
    <div class="tv-card table-card" id="ajax-table-container">
        <div class="tv-card-head">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
            <h3>Daftar Permohonan</h3>
        </div>
        <div class="tv-scroll">
            <table>
                <thead>
                    <tr><th>No</th><th>Pemohon</th><th>Tujuan</th><th>Berangkat</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                @forelse($requests as $r)
                    @php
                        $key = strtolower(trim((string) $r->status));
                        [$cls, $statusLabel] = $statusMap[$key] ?? ['off', \Illuminate\Support\Str::headline((string) $r->status)];

                        $reqName  = optional($r->requester)->name ?? '-';
                        $initials = mb_strtoupper(collect(preg_split('/\s+/', trim($reqName)))->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));

                        $dep  = $r->departure_at;
                        $days = $dep ? (int) now()->startOfDay()->diffInDays($dep->copy()->startOfDay(), false) : null;
                    @endphp
                    <tr>
                        <td>
                            <span class="tv-no" data-no-i18n>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 17v2M13 11v2"/></svg>
                                {{ $r->request_number }}
                            </span>
                        </td>
                        <td>
                            <div class="tv-person" data-no-i18n>
                                <span class="tv-avatar">{{ $initials ?: '?' }}</span>
                                <span>{{ $reqName }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="tv-dest">
                                <span class="tv-route" aria-hidden="true">
                                    <i class="dot"></i>
                                    <span class="ln"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg></span>
                                    <svg class="pin" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a8 8 0 0 0-8 8c0 6 8 12 8 12s8-6 8-12a8 8 0 0 0-8-8zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
                                </span>
                                <span class="tv-dest-name" data-no-i18n>{{ $r->destination }}</span>
                            </div>
                        </td>
                        <td>
                            @if($dep)
                                <div class="tv-when" data-no-i18n>
                                    <span class="tv-when-d">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"/><path d="M6.36 17.4 4 17l-2-4 1.1-.55a2 2 0 0 1 1.8 0l.17.1a2 2 0 0 0 1.8 0L8 12 5 6l.9-.45a2 2 0 0 1 2.09.2l4.02 3a2 2 0 0 0 2.1.2l4.19-2.06a2.41 2.41 0 0 1 1.73-.17L21 7a1.4 1.4 0 0 1 .87 1.99l-.38.76c-.23.46-.6.84-1.07 1.08L7.58 17.2a2 2 0 0 1-1.22.18Z"/></svg>
                                        {{ $dep->format('d-m-Y') }}
                                    </span>
                                    <span class="tv-when-t">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                                        {{ $dep->format('H:i') }}
                                    </span>
                                </div>
                                @if($days === 0)
                                    <span class="tv-cd">Hari ini</span>
                                @elseif($days === 1)
                                    <span class="tv-cd">Besok</span>
                                @elseif($days !== null && $days > 1)
                                    <span class="tv-cd"><b data-no-i18n>{{ $days }}</b><span>hari lagi</span></span>
                                @endif
                            @else
                                <span data-no-i18n>-</span>
                            @endif
                        </td>
                        <td><span class="tv-badge {{ $cls }}">{{ $statusLabel }}</span></td>
                        <td>
                            <a href="{{ route('admin.perjalanan-dinas.requests.show',$r) }}" class="tv-detail">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:0">
                            <div class="tv-empty">
                                <div class="tv-empty-ico" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
                                </div>
                                <strong>Belum ada pengajuan.</strong>
                                <p>Pengajuan perjalanan dinas dari pegawai akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="tv-pager">{{ method_exists($requests, 'links') ? $requests->links() : '' }}</div>
    </div>
</div>

<script>
/* Kamus khusus halaman ini (aman dijalankan sebelum maupun sesudah I18N siap) */
(function (d) {
    window.I18N_EXTRA = Object.assign(window.I18N_EXTRA || {}, d);
    if (window.I18N && window.I18N.extend) window.I18N.extend(d);
})({
    'Permohonan Perjalanan Dinas': ['Official Travel Requests', 'طلبات السفر الرسمي', '公务出差申请'],
    'Pantau pengajuan, tujuan, dan jadwal keberangkatan perjalanan dinas pegawai': ['Track employee travel requests, destinations, and departure schedules', 'تابع طلبات سفر الموظفين ووجهاتهم ومواعيد المغادرة', '跟踪员工出差申请、目的地和出发日程'],
    'Total Permohonan': ['Total Requests', 'إجمالي الطلبات', '申请总数'],
    'Daftar Permohonan': ['Request List', 'قائمة الطلبات', '申请列表'],
    'Pemohon':   ['Applicant', 'مقدم الطلب', '申请人'],
    'Tujuan':    ['Destination', 'الوجهة', '目的地'],
    'Berangkat': ['Departure', 'المغادرة', '出发'],
    'Hari ini':  ['Today', 'اليوم', '今天'],
    'Besok':     ['Tomorrow', 'غداً', '明天'],
    'hari lagi': ['days to go', 'أيام متبقية', '天后出发'],
    'Belum ada pengajuan.': ['No requests yet.', 'لا توجد طلبات بعد.', '暂无申请。'],
    'Pengajuan perjalanan dinas dari pegawai akan muncul di sini.': ['Employee travel requests will appear here.', 'ستظهر طلبات سفر الموظفين هنا.', '员工的出差申请将显示在这里。'],

    /* nilai status */
    'Draf':             ['Draft', 'مسودة', '草稿'],
    'Diajukan':         ['Submitted', 'مُقدَّم', '已提交'],
    'Menunggu':         ['Pending', 'قيد الانتظار', '待处理'],
    'Disetujui':        ['Approved', 'تمت الموافقة', '已批准'],
    'Dalam Perjalanan': ['On the Way', 'في الطريق', '出差途中'],
    'Selesai':          ['Completed', 'مكتمل', '已完成'],
    'Ditolak':          ['Rejected', 'مرفوض', '已拒绝'],
    'Dibatalkan':       ['Cancelled', 'ملغى', '已取消']
});
</script>
@endsection
