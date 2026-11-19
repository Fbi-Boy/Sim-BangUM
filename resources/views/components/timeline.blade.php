@props([
    'items'     => [],          // daftar langkah / kegiatan (lihat kolom di bawah)
    'date'      => null,        // tanggal jadwal (default: hari ini) -> menentukan status otomatis
    'auto'      => true,        // true: status done/now/next dihitung dari tanggal & jam sekarang
    'numbered'  => false,       // true: titik berisi nomor (mode langkah demi langkah)
    'showTime'  => true,        // false: sembunyikan kolom jam
    'duration'  => 90,          // menit sebuah kegiatan dianggap "berlangsung" (bisa per item)
    'title'     => null,        // judul opsional di atas timeline
    'summary'   => true,        // tampilkan "3 kegiatan · 1 selesai"
    'unit'      => 'kegiatan',  // kata benda untuk ringkasan
    'live'      => 'Berlangsung',
    'emptyText' => 'Tidak ada kegiatan.',
])

{{--
    SIM-BU Timeline / Step by Step (komponen mandiri: gaya + logika status semua di sini)
    Lokasi: resources/views/components/timeline.blade.php

    Kolom tiap item (semua opsional kecuali title):
        time      "08:00"                      jam mulai (dipakai untuk status otomatis)
        title     "Apel Pagi & Briefing"
        place     "Halaman Kantor"
        unit      "Seluruh Bagian"             label kecil (badge)
        desc      "Keterangan tambahan"
        state     done | now | next            paksa status (menimpa status otomatis)
        color     "#2563eb"                    warna titik
        href      url                          judul jadi tautan
        duration  60                           lama berlangsung (menit)

    Jadwal harian (status otomatis dari jam sekarang):
        <x-timeline :items="$jadwal" title="Jadwal Hari Ini" />
        <x-timeline :items="$jadwal" :date="$tanggal" />          (tanggal lain: lampau = selesai, depan = berikutnya)

    Langkah demi langkah tanpa jam (status manual):
        <x-timeline :items="$langkah" numbered :show-time="false" :auto="false" />
--}}
@php
    $rows = collect($items)->map(function ($i) {
        if (is_array($i)) return $i;
        if (is_object($i) && method_exists($i, 'toArray')) return $i->toArray();
        return (array) $i;
    })->values();

    $day     = $date ? \Illuminate\Support\Carbon::parse($date) : now();
    $today   = now()->startOfDay();
    $dayStart = $day->copy()->startOfDay();
    $isPast  = $dayStart->lt($today);
    $isToday = $dayStart->eq($today);

    $mins = function ($t) {
        return preg_match('/^(\d{1,2})[:.](\d{2})/', (string) $t, $m) ? ((int) $m[1]) * 60 + (int) $m[2] : null;
    };
    $nowMin = now()->hour * 60 + now()->minute;

    /* kegiatan terakhir yang sudah dimulai (khusus hari ini) */
    $cur = -1;
    if ($auto && $isToday) {
        foreach ($rows as $i => $r) {
            $t = $mins($r['time'] ?? null);
            if ($t !== null && $t <= $nowMin) $cur = $i;
        }
    }

    $states = [];
    foreach ($rows as $i => $r) {
        if (!empty($r['state'])) {
            $s = $r['state'];
        } elseif (!$auto) {
            $s = 'next';
        } elseif ($isPast) {
            $s = 'done';
        } elseif ($isToday) {
            if ($i < $cur)       { $s = 'done'; }
            elseif ($i === $cur) {
                $dur = (int) ($r['duration'] ?? $duration);
                $s = ($nowMin - $mins($r['time'])) < $dur ? 'now' : 'done';
            } else               { $s = 'next'; }
        } else {
            $s = 'next';
        }
        $states[$i] = $s;
    }

    $doneCount = count(array_filter($states, fn($s) => $s === 'done'));
    $total     = $rows->count();
    $sumText   = $total . ' ' . $unit;
    if ($total && ($auto ? ($isPast || $isToday) : true)) {
        $sumText .= $doneCount === $total ? ' · semua selesai' : ' · ' . $doneCount . ' selesai';
    }

    $palette = ['#2563eb', '#8b5cf6', '#ec4899', '#10b981', '#f59e0b', '#06b6d4'];
@endphp

@once
<style>
    /* ===== SIM-BU TIMELINE ===== */
    .stl { position: relative; --stl-line: var(--border-color, #e6e9f2); }
    .stl-head { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; }
    .stl-head h4 { margin: 0; font-size: 15px; font-weight: 700; color: var(--text-main, #111827); }
    .stl-sum { font-size: 12px; color: var(--text-muted, #6b7280); }

    .stl-item {
        position: relative; display: grid; grid-template-columns: 62px 22px minmax(0, 1fr); column-gap: 14px;
        animation: stl-in .4s ease both; animation-delay: calc(var(--i, 0) * 60ms);
    }
    .stl-notime .stl-item { grid-template-columns: 22px minmax(0, 1fr); }
    .stl-numbered .stl-item { grid-template-columns: 62px 30px minmax(0, 1fr); }
    .stl-numbered.stl-notime .stl-item { grid-template-columns: 30px minmax(0, 1fr); }

    .stl-time { text-align: right; padding-top: 1px; line-height: 1.25; }
    .stl-time b { display: block; font-size: 13px; font-weight: 700; color: var(--text-main, #111827); }
    .stl-time small { font-size: 10px; font-weight: 500; color: var(--text-muted, #6b7280); }

    /* rel + titik */
    .stl-rail { position: relative; display: flex; justify-content: center; }
    .stl-rail::after { content: ''; position: absolute; top: 24px; bottom: -4px; width: 2px; border-radius: 2px; background: var(--stl-line); }
    .stl-numbered .stl-rail::after { top: 34px; }
    .stl-item:last-child .stl-rail::after { display: none; }
    .stl-done .stl-rail::after { background: var(--dot); opacity: .45; }

    .stl-dot {
        position: relative; z-index: 1; width: 18px; height: 18px; margin-top: 1px; border-radius: 50%;
        display: grid; place-items: center; border: 2px solid var(--dot); background: var(--bg-card, #fff); color: var(--dot);
        font-size: 12px; font-weight: 700; transition: background .2s, box-shadow .2s;
    }
    .stl-numbered .stl-dot { width: 28px; height: 28px; margin-top: 0; }
    .stl-dot svg { width: 11px; height: 11px; }
    .stl-done .stl-dot, .stl-now .stl-dot { background: var(--dot); color: #fff; }
    .stl-now .stl-dot::before {
        content: ''; position: absolute; inset: -6px; border-radius: 50%; border: 2px solid var(--dot);
        opacity: .5; animation: stl-pulse 1.8s ease-out infinite;
    }
    .stl:not(.stl-numbered) .stl-now .stl-dot::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; }

    /* isi */
    .stl-body { min-width: 0; padding-bottom: 22px; }
    .stl-item:last-child .stl-body { padding-bottom: 0; }
    .stl-body h5 { margin: 0; font-size: 14px; font-weight: 600; line-height: 1.35; color: var(--text-main, #111827); }
    .stl-body h5 a { color: inherit; text-decoration: none; }
    .stl-body h5 a:hover { color: var(--accent, #2563eb); }
    .stl-done .stl-body h5 { color: var(--text-muted, #6b7280); }
    .stl-desc { margin: 4px 0 0; font-size: 12.5px; line-height: 1.5; color: var(--text-muted, #6b7280); }
    .stl-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 6px; }
    .stl-place { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; color: var(--text-muted, #6b7280); }
    .stl-place svg { width: 13px; height: 13px; }
    .stl-tag { font-size: 10.5px; font-weight: 600; padding: 3px 9px; border-radius: 20px; background: var(--bg-soft, #f3f6fd); color: var(--dot); }
    .stl-live {
        display: inline-flex; align-items: center; gap: 5px; font-size: 10.5px; font-weight: 700; padding: 3px 9px;
        border-radius: 20px; background: #10b981; color: #fff;
    }
    .stl-live::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: #fff; animation: stl-blink 1s ease-in-out infinite; }

    /* kegiatan yang sedang berlangsung disorot */
    .stl-now .stl-body { margin-top: -6px; margin-bottom: 14px; padding: 8px 14px 10px; border-radius: 12px; background: var(--accent-soft, #eef3ff); }
    .stl-item.stl-now:last-child .stl-body { margin-bottom: 0; }

    .stl-empty { text-align: center; padding: 28px 16px; border-radius: 14px; font-size: 13px; color: var(--text-muted, #6b7280); background: var(--bg-soft, #f3f6fd); }

    @keyframes stl-in    { from { opacity: 0; transform: translateY(6px); } }
    @keyframes stl-pulse { from { transform: scale(.7); opacity: .6; } to { transform: scale(1.35); opacity: 0; } }
    @keyframes stl-blink { 50% { opacity: .3; } }

    @media (max-width: 480px) {
        .stl-item { grid-template-columns: 50px 22px minmax(0, 1fr); column-gap: 10px; }
        .stl-numbered .stl-item { grid-template-columns: 50px 30px minmax(0, 1fr); }
        .stl-notime .stl-item { grid-template-columns: 22px minmax(0, 1fr); }
        .stl-numbered.stl-notime .stl-item { grid-template-columns: 30px minmax(0, 1fr); }
    }
    @media (prefers-reduced-motion: reduce) {
        .stl-item, .stl-now .stl-dot::before, .stl-live::before { animation: none; }
    }
</style>
@endonce

<div {{ $attributes->class(['stl', 'stl-numbered' => $numbered, 'stl-notime' => !$showTime]) }}>
    @if ($title || ($summary && $total))
        <div class="stl-head">
            @if ($title)<h4>{{ $title }}</h4>@endif
            @if ($summary && $total)<span class="stl-sum">{{ $sumText }}</span>@endif
        </div>
    @endif

    @forelse ($rows as $i => $r)
        @php
            $s     = $states[$i];
            $color = $r['color'] ?? $palette[$i % count($palette)];
            $href  = $r['href'] ?? null;
        @endphp
        <div class="stl-item stl-{{ $s }}" style="--dot: {{ $color }}; --i: {{ $i }}">
            @if ($showTime)
                <div class="stl-time">
                    <b>{{ $r['time'] ?? '' }}</b>
                    @if (!empty($r['time']))<small>{{ $r['tz'] ?? 'WIB' }}</small>@endif
                </div>
            @endif

            <div class="stl-rail">
                <span class="stl-dot">
                    @if ($s === 'done')
                        <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 6.3l2.4 2.4 4.6-5"/></svg>
                    @elseif ($numbered)
                        {{ $i + 1 }}
                    @endif
                </span>
            </div>

            <div class="stl-body">
                <h5>@if ($href)<a href="{{ $href }}">{{ $r['title'] ?? '' }}</a>@else{{ $r['title'] ?? '' }}@endif</h5>
                @if (!empty($r['desc']))<p class="stl-desc">{{ $r['desc'] }}</p>@endif
                @if ($s === 'now' || !empty($r['place']) || !empty($r['unit']))
                    <div class="stl-meta">
                        @if ($s === 'now')<span class="stl-live">{{ $live }}</span>@endif
                        @if (!empty($r['place']))
                            <span class="stl-place">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 0 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                {{ $r['place'] }}
                            </span>
                        @endif
                        @if (!empty($r['unit']))<span class="stl-tag">{{ $r['unit'] }}</span>@endif
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="stl-empty">{{ $emptyText }}</div>
    @endforelse
</div>
