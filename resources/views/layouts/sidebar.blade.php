@php
$u = auth()->user();
$menu = [
    ['label'=>'Dashboard','route'=>'dashboard','icon'=>'home','show'=>true],
    ['label'=>'Disposisi Saya','route'=>'pegawai.dispositions.index','icon'=>'inbox','show'=>$u?->hasAnyRole(['pegawai','admin_persuratan','kasubbag','kepala_bagian','super_admin'])],
    ['label'=>'Tugas Saya','route'=>'pengemudi.tasks.index','icon'=>'truck','show'=>$u?->hasRole('pengemudi')],
    ['label'=>'Inbox Persetujuan','route'=>'pejabat.approvals.index','icon'=>'check-circle','show'=>$u?->hasAnyRole(['kepala_bagian','kasubbag','super_admin'])],
    ['label'=>'Laporan','route'=>'reports.index','icon'=>'chart-bar','show'=>$u?->hasAnyRole(['super_admin','kepala_bagian','kasubbag','auditor'])],
    ['label'=>'Laporan Auditor','route'=>'auditor.reports.index','icon'=>'search-doc','show'=>$u?->hasRole('auditor')],
];
$groups = [
    'PERSURATAN' => [
        ['label'=>'Surat Masuk','route'=>'admin.persuratan.incoming-letters.index','icon'=>'inbox-in','show'=>$u?->hasAnyRole(['super_admin','admin_persuratan'])],
        ['label'=>'Surat Keluar','route'=>'admin.persuratan.outgoing-letters.index','icon'=>'send','show'=>$u?->hasAnyRole(['super_admin','admin_persuratan'])],
    ],
    'ARSIP' => [
        ['label'=>'Daftar Arsip','route'=>'admin.arsip.records.index','icon'=>'folder','show'=>$u?->hasAnyRole(['super_admin','petugas_arsip'])],
        ['label'=>'Klasifikasi','route'=>'admin.arsip.classifications.index','icon'=>'tag','show'=>$u?->hasAnyRole(['super_admin','petugas_arsip'])],
    ],
    'INVENTARIS' => [
        ['label'=>'Aset','route'=>'admin.inventaris.assets.index','icon'=>'cube','show'=>$u?->hasAnyRole(['super_admin','admin_inventaris'])],
        ['label'=>'Peminjaman','route'=>'admin.inventaris.loans.index','icon'=>'arrow-path','show'=>$u?->hasAnyRole(['super_admin','admin_inventaris'])],
        ['label'=>'Item ATK','route'=>'admin.inventaris.supply-items.index','icon'=>'clipboard','show'=>$u?->hasAnyRole(['super_admin','admin_inventaris'])],
        ['label'=>'Permintaan ATK','route'=>'admin.inventaris.stock-requests.index','icon'=>'list','show'=>$u?->hasAnyRole(['super_admin','admin_inventaris'])],
    ],
    'BMD' => [
        ['label'=>'Data BMD','route'=>'admin.barang-daerah.assets.index','icon'=>'building','show'=>$u?->hasAnyRole(['super_admin','pengurus_bmd'])],
    ],
    'KENDARAAN' => [
        ['label'=>'Kendaraan','route'=>'admin.kendaraan.vehicles.index','icon'=>'truck','show'=>$u?->hasAnyRole(['super_admin','admin_kendaraan'])],
        ['label'=>'Permohonan','route'=>'admin.kendaraan.requests.index','icon'=>'document','show'=>$u?->hasAnyRole(['super_admin','admin_kendaraan'])],
    ],
    'FASILITAS' => [
        ['label'=>'Ruangan','route'=>'admin.fasilitas.rooms.index','icon'=>'building','show'=>$u?->hasAnyRole(['super_admin','admin_fasilitas'])],
        ['label'=>'Booking','route'=>'admin.fasilitas.bookings.index','icon'=>'calendar','show'=>$u?->hasAnyRole(['super_admin','admin_fasilitas'])],
    ],
    'PROTOKOL' => [
        ['label'=>'Kegiatan','route'=>'admin.protokol.events.index','icon'=>'calendar-star','show'=>$u?->hasAnyRole(['super_admin','admin_protokol'])],
    ],
    'TRAVEL' => [
        ['label'=>'Permohonan','route'=>'admin.perjalanan-dinas.requests.index','icon'=>'plane','show'=>$u?->hasAnyRole(['super_admin','admin_perjalanan_dinas'])],
    ],
    'PENGAJUAN SAYA' => [
        ['label'=>'Pinjam Aset','route'=>'pegawai.asset-loans.index','icon'=>'cube','show'=>true],
        ['label'=>'Minta ATK','route'=>'pegawai.stock-requests.index','icon'=>'clipboard','show'=>true],
        ['label'=>'Booking Ruangan','route'=>'pegawai.room-bookings.index','icon'=>'calendar','show'=>true],
        ['label'=>'Kendaraan','route'=>'pegawai.vehicle-requests.index','icon'=>'truck','show'=>true],
        ['label'=>'Perjalanan Dinas','route'=>'pegawai.travel-requests.index','icon'=>'plane','show'=>true],
    ],
    'ADMINISTRASI' => [
        ['label'=>'Kelola User','route'=>'super-admin.users.index','icon'=>'users','show'=>$u?->hasRole('super_admin')],
        ['label'=>'Kelola Role','route'=>'super-admin.roles.index','icon'=>'shield','show'=>$u?->hasRole('super_admin')],
        ['label'=>'Audit Log','route'=>'super-admin.activity-log.index','icon'=>'clipboard','show'=>$u?->hasRole('super_admin')],
    ],
];

$icons = [
    'home'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/>',
    'inbox'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z"/>',
    'truck'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-1.036-.84-1.875-1.875-1.875h-1.5v-3.375a1.125 1.125 0 00-1.125-1.125H9.75M8.25 21h8.25"/>',
    'check-circle'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'chart-bar'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>',
    'search-doc'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>',
    'inbox-in'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859M12 3v8.25m0 0l-3-3m3 3l3-3"/>',
    'send'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>',
    'folder'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>',
    'tag'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>',
    'cube'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>',
    'arrow-path'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>',
    'clipboard'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>',
    'list'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>',
    'building'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>',
    'document'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
    'calendar'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>',
    'calendar-star'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>',
    'plane'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>',
    'users'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>',
    'shield'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>',
];
@endphp

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">SB</div>
        <div>
            <div class="sidebar-brand-title">SIM-BU</div>
            <div class="sidebar-brand-sub">Bagian Umum</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @foreach($menu as $m)
            @if($m['show'])
                @php $active = request()->routeIs($m['route']) || request()->routeIs(preg_replace('/\.index$/', '.*', $m['route'])); @endphp
                <a href="{{ route($m['route']) }}" class="sidebar-item {{ $active ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="sidebar-icon">
                        {!! $icons[$m['icon']] ?? '' !!}
                    </svg>
                    <span>{{ $m['label'] }}</span>
                </a>
            @endif
        @endforeach

        @foreach($groups as $groupLabel => $items)
            @php
                $visible = collect($items)->filter(fn($i) => $i['show'])->values();
            @endphp
            @if($visible->count() > 0)
                <div class="sidebar-section">{{ $groupLabel }}</div>
                @foreach($visible as $it)
                    @php $active = request()->routeIs($it['route']) || request()->routeIs(preg_replace('/\.index$/', '.*', $it['route'])); @endphp
                    <a href="{{ route($it['route']) }}" class="sidebar-item {{ $active ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="sidebar-icon">
                            {!! $icons[$it['icon']] ?? '' !!}
                        </svg>
                        <span>{{ $it['label'] }}</span>
                    </a>
                @endforeach
            @endif
        @endforeach
    </nav>
</aside>
