@extends('layouts.app')
@section('title', 'Booking Ruangan')

@section('content')
@include('partials.ui-extras')
<style>
    .btn-act.reject { background: rgba(239,68,68,.13); color: #dc2626; }
    .btn-act.reject:hover { background: #dc2626; color: #fff; }
    body.dark-mode .btn-act.reject { color: #f87171; }
    body.dark-mode .btn-act.reject:hover { background: #ef4444; color: #fff; }
    .act-group { display: inline-flex; gap: 6px; flex-wrap: wrap; justify-content: flex-end; }
    .time-range { display: flex; flex-direction: column; gap: 2px; white-space: nowrap; }
    .time-range .t1 { font-weight: 500; }
    .time-range .t2 { font-size: 11.5px; color: var(--text-muted); }
</style>

@php
    $onPage    = $bookings->count();
    $total     = $bookings instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $bookings->total() : $onPage;
    $submitted = $bookings->where('status', 'submitted')->count();
    $approved  = $bookings->where('status', 'approved')->count();

    $labels = [
        'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak',
        'cancelled' => 'Dibatalkan', 'completed' => 'Selesai',
    ];
    $labelOf = fn($s) => $labels[strtolower((string) $s)] ?? $s;
    $badgeOf = fn($s) => match (strtolower((string) $s)) {
        'submitted'             => 'badge-warning',
        'approved'              => 'badge-info',
        'completed'             => 'badge-success',
        'rejected', 'cancelled' => 'badge-danger',
        default                 => 'badge-muted',
    };
    $initialsOf = fn($name) => mb_strtoupper(collect(preg_split('/\s+/', trim((string) $name)))->filter()->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));
@endphp

<div class="page-head">
    <div>
        <h2 data-translate>Booking Ruangan</h2>
        <p data-translate>Tinjau pengajuan booking ruangan, lalu setujui atau tolak.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total booking</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($submitted, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Menunggu persetujuan</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($approved, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Disetujui</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<div class="table-card">
    <x-table-toolbar target="bookingBody" placeholder="Cari no., ruangan, pemohon..." />

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>No</th>
                    <th data-translate>Ruangan</th>
                    <th data-translate>Pemohon</th>
                    <th data-translate>Jadwal</th>
                    <th data-translate>Status</th>
                    <th class="col-action" data-translate>Aksi</th>
                </tr>
            </thead>
            <tbody id="bookingBody">
                @foreach($bookings as $b)
                    @php
                        $room  = $b->room?->name ?? '-';
                        $who   = $b->requester?->name ?? '-';
                        $day   = $b->starts_at?->format('d-m-Y') ?? '-';
                        $range = ($b->starts_at?->format('H:i') ?? '-') . ' s/d ' . ($b->ends_at?->format('H:i') ?? '-');
                        $label = $labelOf($b->status);
                    @endphp
                    <tr data-filter="{{ $b->status }}" data-filter-label="{{ $label }}"
                        data-search="{{ strtolower($b->booking_number . ' ' . $room . ' ' . $who . ' ' . $day . ' ' . $label . ' ' . $b->status) }}">
                        <td><span class="code-tag" data-convert="full">{{ $b->booking_number }}</span></td>
                        <td><div class="cell-main" style="min-width:150px"><span class="cell-title">{{ $room }}</span></div></td>
                        <td>
                            <div class="person">
                                <div class="avatar-sm">{{ $initialsOf($who) ?: '?' }}</div>
                                <span data-convert="full">{{ $who }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="time-range" data-convert="digit">
                                <span class="t1">{{ $day }}</span>
                                <span class="t2">{{ $range }}</span>
                            </div>
                        </td>
                        <td><span class="badge {{ $badgeOf($b->status) }}"><span class="dot"></span><span data-translate>{{ $label }}</span></span></td>
                        <td class="col-action">
                            @if($b->status === 'submitted')
                                <div class="act-group">
                                    <form method="POST" action="{{ route('admin.fasilitas.bookings.approve', $b) }}" class="act-form">
                                        @csrf
                                        <button type="submit" class="btn-act approve">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>
                                            <span data-translate>Setujui</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.fasilitas.bookings.reject', $b) }}" class="act-form">
                                        @csrf
                                        <button type="submit" class="btn-act reject">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
                                            <span data-translate>Tolak</span>
                                        </button>
                                    </form>
                                </div>
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
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
        <h3 data-translate>Belum ada booking.</h3>
        <p data-translate>Pengajuan booking ruangan akan muncul di sini.</p>
    </div>
    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Booking tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter yang lain.</p>
    </div>

    <x-table-foot :paginator="$bookings" noun="booking" />
</div>
@endsection
