@extends('layouts.app')
@section('title','Ruangan Rapat')
@section('content')
@php
    /* Pemetaan status -> [kelas warna, label]. Nilai tak dikenal tampil apa adanya (warna netral). */
    $statusMap = [
        'tersedia'    => ['ok',   'Tersedia'],
        'available'   => ['ok',   'Tersedia'],
        'aktif'       => ['ok',   'Aktif'],
        'active'      => ['ok',   'Aktif'],
        'dipakai'     => ['warn', 'Dipakai'],
        'terpakai'    => ['warn', 'Dipakai'],
        'maintenance' => ['warn', 'Perawatan'],
        'perawatan'   => ['warn', 'Perawatan'],
        'rusak'       => ['bad',  'Rusak'],
        'nonaktif'    => ['off',  'Nonaktif'],
        'inactive'    => ['off',  'Nonaktif'],
    ];
@endphp

<style>
    /* ===== Modul Fasilitas · Ruangan (tema teal) ===== */
    .rm { --mod: #0d9488; --mod-2: #14b8a6; --mod-soft: rgba(20,184,166,.12); }
    body.dark-mode .rm { --mod: #2dd4bf; --mod-soft: rgba(45,212,191,.14); }

    .rm-hero {
        position: relative; overflow: hidden; border-radius: 18px; padding: 22px 24px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 16px; flex-wrap: wrap; color: #fff;
        background: linear-gradient(135deg, #0f766e 0%, #14b8a6 62%, #5eead4 100%);
        box-shadow: 0 14px 34px rgba(13,148,136,.26);
    }
    .rm-hero::before {            /* hiasan lingkaran */
        content: ''; position: absolute; inset-inline-end: -50px; top: -60px; width: 220px; height: 220px; border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.28), rgba(255,255,255,0) 70%);
    }
    .rm-hero::after {
        content: ''; position: absolute; inset-inline-end: 90px; bottom: -70px; width: 150px; height: 150px; border-radius: 50%;
        background: rgba(255,255,255,.10);
    }
    .rm-hero-ico {
        width: 54px; height: 54px; border-radius: 16px; flex-shrink: 0; display: grid; place-items: center; position: relative; z-index: 1;
        background: rgba(255,255,255,.20); border: 1px solid rgba(255,255,255,.35); backdrop-filter: blur(4px);
    }
    .rm-hero-ico svg { width: 28px; height: 28px; }
    .rm-hero-text { flex: 1; min-width: 200px; position: relative; z-index: 1; }
    .rm-crumb { display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 500; opacity: .85; margin-bottom: 4px; }
    .rm-crumb i { font-style: normal; opacity: .6; }
    .rm-title { font-size: 22px; font-weight: 700; letter-spacing: -.2px; line-height: 1.2; }
    .rm-sub { font-size: 12.5px; opacity: .9; margin-top: 4px; }
    .rm-hero-side { display: flex; align-items: center; gap: 12px; position: relative; z-index: 1; }
    .rm-total {
        display: flex; flex-direction: column; align-items: center; padding: 8px 18px; border-radius: 14px;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.30);
    }
    .rm-total b { font-size: 22px; font-weight: 700; line-height: 1.1; }
    .rm-total span { font-size: 10.5px; font-weight: 500; opacity: .92; }
    .rm-add {
        display: inline-flex; align-items: center; gap: 8px; padding: 11px 18px; border-radius: 12px; text-decoration: none;
        background: #fff; color: #0f766e; font-size: 13px; font-weight: 600; box-shadow: 0 8px 18px rgba(0,0,0,.14);
        transition: transform .2s, box-shadow .2s;
    }
    .rm-add:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,.18); }
    .rm-add svg { width: 16px; height: 16px; }

    /* kartu tabel */
    .rm-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; }
    .rm-card-head { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid var(--border-color); }
    .rm-card-head::before { content: ''; width: 4px; height: 18px; border-radius: 3px; background: linear-gradient(var(--mod-2), var(--mod)); }
    .rm-card-head h3 { font-size: 14px; font-weight: 600; color: var(--text-main); }
    .rm-scroll { overflow-x: auto; }
    .rm table { margin: 0; }
    .rm th { white-space: nowrap; }
    .rm td { vertical-align: middle; }
    .rm tbody tr:hover td { background: var(--mod-soft); }

    .rm-code {
        display: inline-block; padding: 4px 10px; border-radius: 8px; font-family: ui-monospace, Menlo, Consolas, monospace;
        font-size: 12px; font-weight: 700; letter-spacing: .4px; color: var(--mod); background: var(--mod-soft); border: 1px solid var(--mod-soft);
    }
    .rm-name { display: flex; align-items: center; gap: 10px; font-weight: 600; }
    .rm-name-ico { width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0; display: grid; place-items: center; background: var(--mod-soft); color: var(--mod); }
    .rm-name-ico svg { width: 17px; height: 17px; }
    .rm-cap { display: inline-flex; align-items: center; gap: 6px; color: var(--text-main); }
    .rm-cap svg { width: 16px; height: 16px; color: var(--mod); }
    .rm-cap b { font-weight: 700; }
    .rm-cap span { color: var(--text-muted); font-size: 12.5px; }
    .rm-type { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; background: var(--bg-soft); color: var(--text-main); border: 1px solid var(--border-color); }

    .rm-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .rm-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .rm-badge.ok   { background: rgba(16,185,129,.14); color: #059669; }
    .rm-badge.warn { background: rgba(245,158,11,.16); color: #b45309; }
    .rm-badge.bad  { background: rgba(239,68,68,.14);  color: #dc2626; }
    .rm-badge.off  { background: rgba(107,114,128,.16); color: #6b7280; }
    body.dark-mode .rm-badge.ok   { color: #34d399; }
    body.dark-mode .rm-badge.warn { color: #fbbf24; }
    body.dark-mode .rm-badge.bad  { color: #f87171; }
    body.dark-mode .rm-badge.off  { color: #cbd5e1; }

    /* keadaan kosong */
    .rm-empty { text-align: center; padding: 44px 16px; }
    .rm-empty-ico { width: 64px; height: 64px; margin: 0 auto 14px; border-radius: 20px; display: grid; place-items: center; background: var(--mod-soft); color: var(--mod); }
    .rm-empty-ico svg { width: 30px; height: 30px; }
    .rm-empty strong { display: block; font-size: 14px; font-weight: 600; color: var(--text-main); }
    .rm-empty p { font-size: 12.5px; color: var(--text-muted); margin: 4px 0 14px; }
    .rm-empty .rm-add { background: linear-gradient(135deg, #0f766e, #14b8a6); color: #fff; box-shadow: 0 8px 18px rgba(13,148,136,.28); }

    .rm-pager { padding: 14px 18px; border-top: 1px solid var(--border-color); }
    .rm-pager:empty { display: none; }
</style>

<div class="rm">
    <!-- HERO -->
    <div class="rm-hero">
        <div class="rm-hero-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4M10 10h4M10 14h4M10 18h4"/></svg>
        </div>
        <div class="rm-hero-text">
            <div class="rm-crumb"><span>Fasilitas</span><i>/</i><span>Ruangan</span></div>
            <div class="rm-title">Ruangan Rapat</div>
            <div class="rm-sub">Kelola data ruangan rapat beserta kapasitas dan statusnya</div>
        </div>
        <div class="rm-hero-side">
            <div class="rm-total"><b data-no-i18n>{{ method_exists($rooms, 'total') ? $rooms->total() : count($rooms) }}</b><span>Total Ruangan</span></div>
            <a href="{{ route('admin.fasilitas.rooms.create') }}" class="rm-add">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                <span>Tambah Ruangan</span>
            </a>
        </div>
    </div>

    <!-- TABEL (id container dipakai oleh AJAX pagination di layout) -->
    <div class="rm-card table-card" id="ajax-table-container">
        <div class="rm-card-head"><h3>Daftar Ruangan</h3></div>
        <div class="rm-scroll">
            <table>
                <thead>
                    <tr><th>Kode</th><th>Nama Ruangan</th><th>Kapasitas</th><th>Tipe</th><th>Status</th></tr>
                </thead>
                <tbody>
                @forelse($rooms as $r)
                    @php
                        $key = strtolower(trim((string) $r->status));
                        [$cls, $statusLabel] = $statusMap[$key] ?? ['off', \Illuminate\Support\Str::headline((string) $r->status)];
                    @endphp
                    <tr>
                        <td><span class="rm-code" data-no-i18n>{{ $r->room_code }}</span></td>
                        <td>
                            <div class="rm-name">
                                <span class="rm-name-ico" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2.5"/><path d="M3 10h18"/><path d="M9 15h6"/></svg>
                                </span>
                                <span data-no-i18n>{{ $r->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="rm-cap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <b data-no-i18n>{{ $r->capacity }}</b><span>orang</span>
                            </span>
                        </td>
                        <td><span class="rm-type">{{ \Illuminate\Support\Str::headline((string) $r->room_type) }}</span></td>
                        <td><span class="rm-badge {{ $cls }}">{{ $statusLabel }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:0">
                            <div class="rm-empty">
                                <div class="rm-empty-ico" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4M10 10h4M10 14h4M10 18h4"/></svg>
                                </div>
                                <strong>Belum ada ruangan.</strong>
                                <p>Mulai dengan menambahkan ruangan rapat pertama Anda.</p>
                                <a href="{{ route('admin.fasilitas.rooms.create') }}" class="rm-add">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                    <span>Tambah Ruangan</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="rm-pager">{{ method_exists($rooms, 'links') ? $rooms->links() : '' }}</div>
    </div>
</div>

<script>
/* Kamus khusus halaman ini (aman dijalankan sebelum maupun sesudah I18N siap) */
(function (d) {
    window.I18N_EXTRA = Object.assign(window.I18N_EXTRA || {}, d);
    if (window.I18N && window.I18N.extend) window.I18N.extend(d);
})({
    'Ruangan Rapat':   ['Meeting Rooms', 'قاعات الاجتماعات', '会议室'],
    'Kelola data ruangan rapat beserta kapasitas dan statusnya': ['Manage meeting rooms along with their capacity and status', 'إدارة قاعات الاجتماعات مع سعتها وحالتها', '管理会议室及其容量和状态'],
    'Total Ruangan':   ['Total Rooms', 'إجمالي القاعات', '会议室总数'],
    'Tambah Ruangan':  ['Add Room', 'إضافة قاعة', '添加会议室'],
    'Daftar Ruangan':  ['Room List', 'قائمة القاعات', '会议室列表'],
    'Kode':            ['Code', 'الرمز', '编码'],
    'Nama Ruangan':    ['Room Name', 'اسم القاعة', '会议室名称'],
    'Kapasitas':       ['Capacity', 'السعة', '容量'],
    'Tipe':            ['Type', 'النوع', '类型'],
    'orang':           ['people', 'شخص', '人'],
    'Belum ada ruangan.': ['No rooms yet.', 'لا توجد قاعات بعد.', '暂无会议室。'],
    'Mulai dengan menambahkan ruangan rapat pertama Anda.': ['Start by adding your first meeting room.', 'ابدأ بإضافة أول قاعة اجتماعات لديك.', '请先添加您的第一个会议室。'],

    /* nilai status */
    'Tersedia':  ['Available', 'متاح', '可用'],
    'Dipakai':   ['In Use', 'قيد الاستخدام', '使用中'],
    'Perawatan': ['Maintenance', 'قيد الصيانة', '维护中'],
    'Rusak':     ['Damaged', 'تالف', '损坏'],

    /* nilai tipe ruangan yang umum */
    'Rapat':            ['Meeting', 'اجتماع', '会议'],
    'Ruang Rapat':      ['Meeting Room', 'قاعة اجتماعات', '会议室'],
    'Ruang Pertemuan':  ['Conference Room', 'قاعة لقاءات', '会议厅'],
    'Ruang Diskusi':    ['Discussion Room', 'قاعة نقاش', '讨论室'],
    'Ruang Pelatihan':  ['Training Room', 'قاعة تدريب', '培训室'],
    'Aula':             ['Hall', 'قاعة كبرى', '礼堂'],
    'Auditorium':       ['Auditorium', 'قاعة المحاضرات', '大礼堂']
});
</script>
@endsection
