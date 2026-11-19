@extends('layouts.app')
@section('title', 'Review Hasil Pegawai')

@section('content')
<div class="page-head">
    <div>
        <h2>Review Hasil Pegawai</h2>
        <p>Tugas yang sudah dikirim pegawai untuk diperiksa.</p>
    </div>
</div>

@if(session('success'))<div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-error" style="margin-bottom:16px;">{{ session('error') }}</div>@endif

<div class="table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Pegawai</th>
                    <th>Surat</th>
                    <th>Instruksi</th>
                    <th>Dikirim</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($dispositions as $d)
                <tr data-row data-href="{{ route('pejabat.reviews.show', $d) }}">
                    <td>
                        <div class="person">
                            <span class="avatar-sm">{{ mb_strtoupper(mb_substr($d->toUser->name ?? '?', 0, 2)) }}</span>
                            <span>{{ $d->toUser->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="cell-main">
                            <span class="cell-title">{{ $d->incomingLetter->subject ?? '-' }}</span>
                            <span class="cell-sub">{{ Str::limit($d->instruction, 60) }}</span>
                        </div>
                    </td>
                    <td>{{ Str::limit($d->instruction, 40) }}</td>
                    <td>{{ $d->submitted_at?->format('d M Y H:i') ?? '-' }}</td>
                    <td class="col-action">
                        <a href="{{ route('pejabat.reviews.show', $d) }}" class="btn-open">
                            Review <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                            <h3>Belum ada hasil yang perlu direview</h3>
                            <p>Tugas yang dikirim pegawai akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($dispositions->hasPages())
    <div class="table-foot">
        <span class="foot-info">Menampilkan {{ $dispositions->firstItem() }}–{{ $dispositions->lastItem() }} dari {{ $dispositions->total() }}</span>
        {{ $dispositions->onEachSide(1)->links('components.pagination') }}
    </div>
    @endif
</div>
@endsection
