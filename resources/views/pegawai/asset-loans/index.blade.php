@extends('layouts.app')
@section('title', 'Pinjaman Aset Saya')

@section('content')
@include('partials.ui-extras')

@php
    $onPage    = $loans->count();
    $total     = $loans instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $loans->total() : $onPage;
    $submitted = $loans->where('status', 'submitted')->count();
    $issued    = $loans->where('status', 'issued')->count();

    $labels = [
        'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'issued' => 'Dipinjam',
        'returned'  => 'Dikembalikan', 'rejected' => 'Ditolak', 'cancelled' => 'Dibatalkan',
    ];
    $labelOf = fn($s) => $labels[strtolower((string) $s)] ?? $s;
    $badgeOf = fn($s) => match (strtolower((string) $s)) {
        'submitted'             => 'badge-warning',
        'approved', 'issued'    => 'badge-info',
        'returned'              => 'badge-success',
        'rejected', 'cancelled' => 'badge-danger',
        default                 => 'badge-muted',
    };
@endphp

<div class="page-head">
    <div>
        <h2 data-translate>Pinjaman Aset Saya</h2>
        <p data-translate>Riwayat dan status pengajuan peminjaman aset milik Anda.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($total, 0, ',', '.') }}</div>
            <div class="stat-lbl" data-translate>Total pengajuan</div>
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
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h12M12 6l6 6-6 6"/></svg></div>
        <div>
            <div class="stat-val" data-convert="digit">{{ number_format($issued, 0, ',', '.') }}</div>
            <div class="stat-lbl"><span data-translate>Sedang dipinjam</span> <small>(<span data-translate>halaman ini</span>)</small></div>
        </div>
    </div>
</div>

<div class="table-card">
    <x-table-toolbar target="myLoanBody" placeholder="Cari no. pinjaman atau aset..."
        :add-url="route('pegawai.asset-loans.create')" add-label="Ajukan Pinjaman" :report="false" />

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th data-translate>No Pinjaman</th>
                    <th data-translate>Aset</th>
                    <th data-translate>Tgl Mulai</th>
                    <th data-translate>Status</th>
                </tr>
            </thead>
            <tbody id="myLoanBody">
                @foreach($loans as $l)
                    @php
                        $asset = $l->asset?->name ?? '-';
                        $date  = $l->starts_at?->format('d-m-Y') ?? '-';
                        $label = $labelOf($l->status);
                    @endphp
                    <tr data-filter="{{ $l->status }}" data-filter-label="{{ $label }}"
                        data-search="{{ strtolower($l->loan_number . ' ' . $asset . ' ' . $label . ' ' . $l->status) }}">
                        <td><span class="code-tag" data-convert="full">{{ $l->loan_number }}</span></td>
                        <td><div class="cell-main" style="min-width:180px"><span class="cell-title">{{ $asset }}</span></div></td>
                        <td style="white-space:nowrap" data-convert="digit">{{ $date }}</td>
                        <td><span class="badge {{ $badgeOf($l->status) }}"><span class="dot"></span><span data-translate>{{ $label }}</span></span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="empty-state" id="emptyServer" @if($onPage > 0) hidden @endif>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
        <h3 data-translate>Belum ada pengajuan.</h3>
        <p data-translate>Klik "Ajukan Pinjaman" untuk meminjam aset.</p>
    </div>
    <div class="empty-state" id="emptyFilter" hidden>
        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
        <h3 data-translate>Pengajuan tidak ditemukan</h3>
        <p data-translate>Coba kata kunci atau filter yang lain.</p>
    </div>

    <x-table-foot :paginator="$loans" noun="pengajuan" />
</div>
@endsection
