@extends('layouts.app')
@section('title','Surat Masuk')

@section('content')
@php
    $totalLetters = $letters->total();
    $registeredCount = $letters->where('status', 'registered')->count();
    $awaitingCount = $letters->where('status', 'awaiting_direction')->count();
    $archivedCount = $letters->where('status', 'archived')->count();
    $statusList = $letters->map(fn($l) => \Illuminate\Support\Str::headline($l->status))->unique()->values();
@endphp

<div class="page-head">
    <div>
        <h2>Surat Masuk</h2>
        <p>Kelola dan pantau seluruh surat yang masuk ke instansi.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
        <div><div class="stat-val">{{ $totalLetters }}</div><div class="stat-lbl">Total Surat</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
        <div><div class="stat-val">{{ $registeredCount }}</div><div class="stat-lbl">Terkonfirmasi</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg></div>
        <div><div class="stat-val">{{ $awaitingCount }}</div><div class="stat-lbl">Butuh Disposisi</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-ico green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg></div>
        <div><div class="stat-val">{{ $archivedCount }}</div><div class="stat-lbl">Diarsipkan</div></div>
    </div>
</div>

<div class="table-card">
    <div class="table-toolbar" style="justify-content: space-between;">
        <div style="display:flex; align-items:center; gap:12px; flex:1; flex-wrap:wrap;">
            <label class="tb-search" style="max-width: 300px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="suratSearch" placeholder="Cari surat, pengirim..." autocomplete="off">
            </label>
            <div class="chips">
                <button type="button" class="chip on" data-filter="all">Semua</button>
                @foreach($statusList as $st)
                    <button type="button" class="chip" data-filter="{{ $st }}">{{ $st }}</button>
                @endforeach
            </div>
        </div>
        <!-- TOMBOL TAMBAH DI DALAM TOOLBAR -->
        <a href="{{ route('admin.persuratan.incoming-letters.create') }}" class="btn-open" style="padding: 8px 16px; font-size: 13px; font-weight: 600;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:16px; height:16px;"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Surat
        </a>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Agenda</th><th>Surat &amp; Pengirim</th><th>Tanggal Diterima</th><th>Status</th><th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($letters as $l)
                @php $sLabel = \Illuminate\Support\Str::headline($l->status); @endphp
                <tr data-row data-href="{{ route('admin.persuratan.incoming-letters.show', $l) }}" data-status="{{ $sLabel }}" data-search="{{ strtolower($l->subject . ' ' . $l->sender_name . ' ' . $l->register_year . '/' . $l->agenda_sequence) }}">
                    <td style="font-weight:700; color:var(--accent);">{{ $l->register_year }}/{{ str_pad($l->agenda_sequence,4,'0',STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="cell-main">
                            <span class="cell-title">{{ $l->subject }}</span>
                            <span class="cell-sub">Dari: {{ $l->sender_name }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="due"><span class="due-date"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg> {{ $l->received_at->format('d-m-Y') }}</span></div>
                    </td>
                    <td>
                        @php
                            $badge = match($l->status) {
                                'registered' => 'badge-warning',
                                'awaiting_direction' => 'badge-info',
                                'archived' => 'badge-success',
                                default => 'badge-muted'
                            };
                        @endphp
                        <span class="badge {{ $badge }}"><span class="dot"></span>{{ $sLabel }}</span>
                    </td>
                    <td class="col-action">
                        <a href="{{ route('admin.persuratan.incoming-letters.show', $l) }}" class="btn-open">
                            Detail <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state"><h3>Belum ada surat masuk</h3></div></td></tr>
            @endforelse
            <tr id="noResult" hidden><td colspan="5"><div class="empty-state" style="padding:36px 20px"><h3>Tidak ada hasil</h3><p>Coba kata kunci lain.</p></div></td></tr>
            </tbody>
        </table>
    </div>

    @if($letters->total() > 0)
        <x-pagination :paginator="$letters" item-label="surat" />
    @endif
</div>

<script>
    (function () {
        var rows   = [].slice.call(document.querySelectorAll('[data-row]'));
        var search = document.getElementById('suratSearch');
        var chips  = [].slice.call(document.querySelectorAll('.chip'));
        var none   = document.getElementById('noResult');
        var active = 'all';
        if (!search) return;

        function apply() {
            var q = search.value.trim().toLowerCase();
            var shown = 0;
            rows.forEach(function (r) {
                var okQ = !q || r.getAttribute('data-search').indexOf(q) > -1;
                var okS = active === 'all' || r.getAttribute('data-status') === active;
                r.hidden = !(okQ && okS);
                if (okQ && okS) shown++;
            });
            none.hidden = shown !== 0;
        }
        search.addEventListener('input', apply);
        chips.forEach(function (c) {
            c.addEventListener('click', function () {
                active = c.getAttribute('data-filter');
                chips.forEach(function (x) { x.classList.toggle('on', x === c); });
                apply();
            });
        });
        rows.forEach(function (r) {
            r.addEventListener('click', function (e) {
                if (e.target.closest('a,button')) return;
                window.location.href = r.getAttribute('data-href');
            });
        });
    })();
</script>
@endsection
