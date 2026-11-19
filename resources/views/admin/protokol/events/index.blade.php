@extends('layouts.app')
@section('title','Kegiatan')
@section('content')
@php
    /* Pemetaan status -> [kelas warna, label]. Nilai tak dikenal tampil apa adanya (warna netral). */
    $statusMap = [
        'draft'        => ['off',  'Draf'],
        'draf'         => ['off',  'Draf'],
        'scheduled'    => ['info', 'Dijadwalkan'],
        'dijadwalkan'  => ['info', 'Dijadwalkan'],
        'ongoing'      => ['warn', 'Berlangsung'],
        'berlangsung'  => ['warn', 'Berlangsung'],
        'completed'    => ['ok',   'Selesai'],
        'done'         => ['ok',   'Selesai'],
        'selesai'      => ['ok',   'Selesai'],
        'postponed'    => ['warn', 'Ditunda'],
        'ditunda'      => ['warn', 'Ditunda'],
        'cancelled'    => ['bad',  'Dibatalkan'],
        'canceled'     => ['bad',  'Dibatalkan'],
        'dibatalkan'   => ['bad',  'Dibatalkan'],
    ];
@endphp

<style>
    /* ===== Modul Protokol · Kegiatan (tema ungu) ===== */
    .ev { --mod: #7c3aed; --mod-2: #8b5cf6; --mod-soft: rgba(139,92,246,.12); }
    body.dark-mode .ev { --mod: #a78bfa; --mod-soft: rgba(167,139,250,.15); }

    .ev-hero {
        position: relative; overflow: hidden; border-radius: 18px; padding: 22px 24px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap; color: #fff;
        background: linear-gradient(135deg, #5b21b6 0%, #8b5cf6 62%, #c4b5fd 100%);
        box-shadow: 0 14px 34px rgba(124,58,237,.26);
    }
    .ev-hero::before {
        content: ''; position: absolute; inset-inline-end: -50px; top: -60px; width: 220px; height: 220px; border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.28), rgba(255,255,255,0) 70%);
    }
    .ev-hero::after {
        content: ''; position: absolute; inset-inline-end: 90px; bottom: -70px; width: 150px; height: 150px; border-radius: 50%;
        background: rgba(255,255,255,.10);
    }
    .ev-hero-ico {
        width: 54px; height: 54px; border-radius: 16px; flex-shrink: 0; display: grid; place-items: center; position: relative; z-index: 1;
        background: rgba(255,255,255,.20); border: 1px solid rgba(255,255,255,.35); backdrop-filter: blur(4px);
    }
    .ev-hero-ico svg { width: 28px; height: 28px; }
    .ev-hero-text { flex: 1; min-width: 200px; position: relative; z-index: 1; }
    .ev-crumb { display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 500; opacity: .85; margin-bottom: 4px; }
    .ev-crumb i { font-style: normal; opacity: .6; }
    .ev-title { font-size: 22px; font-weight: 700; letter-spacing: -.2px; line-height: 1.2; }
    .ev-sub { font-size: 12.5px; opacity: .9; margin-top: 4px; }
    .ev-hero-side { display: flex; align-items: center; gap: 12px; position: relative; z-index: 1; }
    .ev-total {
        display: flex; flex-direction: column; align-items: center; padding: 8px 18px; border-radius: 14px;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.30);
    }
    .ev-total b { font-size: 22px; font-weight: 700; line-height: 1.1; }
    .ev-total span { font-size: 10.5px; font-weight: 500; opacity: .92; }
    .ev-add {
        display: inline-flex; align-items: center; gap: 8px; padding: 11px 18px; border-radius: 12px; text-decoration: none;
        background: #fff; color: #5b21b6; font-size: 13px; font-weight: 600; box-shadow: 0 8px 18px rgba(0,0,0,.14);
        transition: transform .2s, box-shadow .2s;
    }
    .ev-add:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,.18); }
    .ev-add svg { width: 16px; height: 16px; }

    .ev-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; }
    .ev-card-head { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid var(--border-color); }
    .ev-card-head::before { content: ''; width: 4px; height: 18px; border-radius: 3px; background: linear-gradient(var(--mod-2), var(--mod)); }
    .ev-card-head h3 { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .ev-scroll { overflow-x: auto; }
    .ev table { margin: 0; }
    .ev th { white-space: nowrap; }
    .ev td { vertical-align: middle; }
    .ev tbody tr:hover td { background: var(--mod-soft); }

    .ev-no {
        display: inline-block; padding: 4px 10px; border-radius: 8px; font-family: ui-monospace, Menlo, Consolas, monospace;
        font-size: 12px; font-weight: 700; letter-spacing: .3px; color: var(--mod); background: var(--mod-soft); white-space: nowrap;
    }
    .ev-name { display: flex; align-items: center; gap: 10px; font-weight: 600; min-width: 180px; }
    .ev-name-ico { width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0; display: grid; place-items: center; background: var(--mod-soft); color: var(--mod); }
    .ev-name-ico svg { width: 17px; height: 17px; }
    .ev-type { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; background: var(--bg-soft); color: var(--text-main); border: 1px solid var(--border-color); white-space: nowrap; }

    .ev-when { display: flex; flex-direction: column; gap: 2px; white-space: nowrap; }
    .ev-when-d { display: flex; align-items: center; gap: 7px; font-weight: 600; color: var(--text-main); }
    .ev-when-d svg, .ev-when-t svg, .ev-loc svg { width: 14px; height: 14px; color: var(--mod); flex-shrink: 0; }
    .ev-when-t { display: flex; align-items: center; gap: 7px; font-size: 12px; color: var(--text-muted); }
    .ev-today { margin-inline-start: 4px; padding: 1px 8px; border-radius: 20px; font-size: 10.5px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #7c3aed, #a78bfa); }
    .ev-loc { display: inline-flex; align-items: center; gap: 6px; color: var(--text-main); }

    .ev-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .ev-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .ev-badge.ok   { background: rgba(16,185,129,.14); color: #059669; }
    .ev-badge.info { background: rgba(59,130,246,.14); color: #2563eb; }
    .ev-badge.warn { background: rgba(245,158,11,.16); color: #b45309; }
    .ev-badge.bad  { background: rgba(239,68,68,.14);  color: #dc2626; }
    .ev-badge.off  { background: rgba(107,114,128,.16); color: #6b7280; }
    body.dark-mode .ev-badge.ok   { color: #34d399; }
    body.dark-mode .ev-badge.info { color: #60a5fa; }
    body.dark-mode .ev-badge.warn { color: #fbbf24; }
    body.dark-mode .ev-badge.bad  { color: #f87171; }
    body.dark-mode .ev-badge.off  { color: #cbd5e1; }

    .ev-detail {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 10px; text-decoration: none; white-space: nowrap;
        font-size: 12.5px; font-weight: 600; color: var(--mod); background: var(--mod-soft); border: 1px solid transparent;
        transition: background .2s, color .2s, transform .2s;
    }
    .ev-detail:hover { background: var(--mod); color: #fff; transform: translateY(-1px); }
    .ev-detail svg { width: 15px; height: 15px; }

    .ev-empty { text-align: center; padding: 44px 16px; }
    .ev-empty-ico { width: 64px; height: 64px; margin: 0 auto 14px; border-radius: 20px; display: grid; place-items: center; background: var(--mod-soft); color: var(--mod); }
    .ev-empty-ico svg { width: 30px; height: 30px; }
    .ev-empty strong { display: block; font-size: 14px; font-weight: 600; color: var(--text-main); }
    .ev-empty p { font-size: 12.5px; color: var(--text-muted); margin: 4px 0 14px; }
    .ev-empty .ev-add { background: linear-gradient(135deg, #5b21b6, #8b5cf6); color: #fff; box-shadow: 0 8px 18px rgba(124,58,237,.28); }

    .ev-pager { padding: 14px 18px; border-top: 1px solid var(--border-color); }
    .ev-pager:empty { display: none; }
</style>

<div class="ev">
    <!-- HERO -->
    <div class="ev-hero">
        <div class="ev-hero-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2.5"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
        </div>
        <div class="ev-hero-text">
            <div class="ev-crumb"><span>Protokol</span><i>/</i><span>Kegiatan</span></div>
            <div class="ev-title">Kegiatan Protokol</div>
            <div class="ev-sub">Kelola jadwal, lokasi, dan status seluruh kegiatan kedinasan</div>
        </div>
        <div class="ev-hero-side">
            <div class="ev-total"><b data-no-i18n>{{ method_exists($events, 'total') ? $events->total() : count($events) }}</b><span>Total Kegiatan</span></div>
            <a href="{{ route('admin.protokol.events.create') }}" class="ev-add">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                <span>Buat Kegiatan</span>
            </a>
        </div>
    </div>

    <!-- TABEL (id container dipakai oleh AJAX pagination di layout) -->
    <div class="ev-card table-card" id="ajax-table-container">
        <div class="ev-card-head"><h3>Daftar Kegiatan</h3></div>
        <div class="ev-scroll">
            <table>
                <thead>
                    <tr><th>No</th><th>Judul</th><th>Jenis</th><th>Jadwal</th><th>Lokasi</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                @forelse($events as $e)
                    @php
                        $key = strtolower(trim((string) $e->status));
                        [$cls, $statusLabel] = $statusMap[$key] ?? ['off', \Illuminate\Support\Str::headline((string) $e->status)];
                    @endphp
                    <tr>
                        <td><span class="ev-no" data-no-i18n>{{ $e->event_number }}</span></td>
                        <td>
                            <div class="ev-name">
                                <span class="ev-name-ico" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.9 6.3 6.9.8-5.1 4.7 1.4 6.8L12 17.3 5.9 20.6l1.4-6.8L2.2 9.1l6.9-.8z"/></svg>
                                </span>
                                <span data-no-i18n>{{ $e->title }}</span>
                            </div>
                        </td>
                        <td><span class="ev-type">{{ \Illuminate\Support\Str::headline((string) $e->event_type) }}</span></td>
                        <td>
                            <div class="ev-when" data-no-i18n>
                                <span class="ev-when-d">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2.5"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    {{ $e->starts_at->format('d-m-Y') }}
                                </span>
                                <span class="ev-when-t">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                                    {{ $e->starts_at->format('H:i') }}
                                </span>
                            </div>
                            @if($e->starts_at->isToday())<span class="ev-today">Hari ini</span>@endif
                        </td>
                        <td>
                            <span class="ev-loc">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span data-no-i18n>{{ $e->location ?? '-' }}</span>
                            </span>
                        </td>
                        <td><span class="ev-badge {{ $cls }}">{{ $statusLabel }}</span></td>
                        <td>
                            <a href="{{ route('admin.protokol.events.show',$e) }}" class="ev-detail">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:0">
                            <div class="ev-empty">
                                <div class="ev-empty-ico" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2.5"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                                </div>
                                <strong>Belum ada kegiatan.</strong>
                                <p>Mulai dengan membuat kegiatan protokol pertama Anda.</p>
                                <a href="{{ route('admin.protokol.events.create') }}" class="ev-add">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                    <span>Buat Kegiatan</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="ev-pager">{{ method_exists($events, 'links') ? $events->links() : '' }}</div>
    </div>
</div>

<script>
/* Kamus khusus halaman ini (aman dijalankan sebelum maupun sesudah I18N siap) */
(function (d) {
    window.I18N_EXTRA = Object.assign(window.I18N_EXTRA || {}, d);
    if (window.I18N && window.I18N.extend) window.I18N.extend(d);
})({
    'Kegiatan Protokol': ['Protocol Events', 'فعاليات المراسم', '礼宾活动'],
    'Kelola jadwal, lokasi, dan status seluruh kegiatan kedinasan': ['Manage the schedule, location, and status of all official events', 'إدارة جدول وموقع وحالة جميع الفعاليات الرسمية', '管理所有公务活动的日程、地点和状态'],
    'Total Kegiatan': ['Total Events', 'إجمالي الفعاليات', '活动总数'],
    'Buat Kegiatan':  ['Create Event', 'إنشاء فعالية', '创建活动'],
    'Daftar Kegiatan': ['Event List', 'قائمة الفعاليات', '活动列表'],
    'Judul':    ['Title', 'العنوان', '标题'],
    'Jenis':    ['Type', 'النوع', '类型'],
    'Jadwal':   ['Schedule', 'الموعد', '日程'],
    'Lokasi':   ['Location', 'الموقع', '地点'],
    'Hari ini': ['Today', 'اليوم', '今天'],
    'Belum ada kegiatan.': ['No events yet.', 'لا توجد فعاليات بعد.', '暂无活动。'],
    'Mulai dengan membuat kegiatan protokol pertama Anda.': ['Start by creating your first protocol event.', 'ابدأ بإنشاء أول فعالية مراسم لديك.', '请先创建您的第一个礼宾活动。'],

    /* nilai status */
    'Draf':        ['Draft', 'مسودة', '草稿'],
    'Dijadwalkan': ['Scheduled', 'مجدول', '已安排'],
    'Berlangsung': ['Ongoing', 'جارٍ', '进行中'],
    'Ditunda':     ['Postponed', 'مؤجل', '已延期'],
    'Dibatalkan':  ['Cancelled', 'ملغى', '已取消'],

    /* nilai jenis kegiatan yang umum */
    'Upacara':   ['Ceremony', 'مراسم رسمية', '仪式'],
    'Rapat':     ['Meeting', 'اجتماع', '会议'],
    'Kunjungan': ['Visit', 'زيارة', '访问'],
    'Audiensi':  ['Audience', 'استقبال', '接见'],
    'Resepsi':   ['Reception', 'حفل استقبال', '招待会'],
    'Seminar':   ['Seminar', 'ندوة', '研讨会'],
    'Acara':     ['Event', 'فعالية', '活动'],
    'Lainnya':   ['Other', 'أخرى', '其他']
});
</script>
@endsection
