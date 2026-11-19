@extends('layouts.app')
@section('title', 'Detail Pengajuan Perjalanan Dinas')

@section('content')
<style>
    .travel-detail { max-width:1100px; margin:auto; color:var(--text-main); }
    .td-head { display:flex; justify-content:space-between; align-items:flex-start; gap:15px; margin-bottom:22px; }
    .td-head h2 { margin:0 0 6px; font-size:23px; font-weight:750; }
    .td-head p { margin:0; color:var(--text-muted); font-size:12px; }
    .td-back { display:inline-block; color:var(--text-muted); text-decoration:none; font-size:12px; margin-bottom:12px; }
    .td-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:15px; padding:22px; margin-bottom:18px; }
    .td-card h3 { font-size:14px; margin:0 0 17px; }
    .td-status { display:inline-flex; padding:6px 11px; border-radius:30px; background:var(--accent-soft); color:var(--accent); font-size:10px; font-weight:700; }
    .td-info { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
    .td-item small { display:block; color:var(--text-muted); font-size:10px; margin-bottom:6px; }
    .td-item strong { display:block; font-size:12px; line-height:1.6; overflow-wrap:anywhere; }
    .td-description { background:var(--bg-soft); border:1px solid var(--border-color); padding:14px; border-radius:11px; font-size:12px; line-height:1.7; }
    .td-participants { display:flex; flex-wrap:wrap; gap:8px; }
    .td-person { padding:7px 10px; border-radius:8px; background:var(--bg-soft); border:1px solid var(--border-color); font-size:11px; }
    .td-actions { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .td-action { background:var(--bg-card); border:1px solid var(--border-color); border-radius:14px; padding:18px; }
    .td-action h3 { font-size:13px; margin:0 0 7px; }
    .td-action p { color:var(--text-muted); font-size:11px; line-height:1.6; margin:0 0 15px; }
    .td-input { box-sizing:border-box; width:100%; border:1px solid var(--border-color); background:var(--bg-soft); color:var(--text-main); padding:11px 12px; border-radius:9px; font-size:12px; outline:none; margin-bottom:12px; }
    .td-input:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-soft); }
    .td-btn { width:100%; border:0; border-radius:9px; padding:11px 14px; color:white; font-size:12px; font-weight:700; cursor:pointer; }
    .td-approve { background:#16845c; }
    .td-reject { background:#c94b4b; }
    .td-btn:hover { filter:brightness(.94); }
    @media(max-width:650px) { .td-head { flex-direction:column; } .td-info,.td-actions { grid-template-columns:1fr; } .td-card { padding:17px; } }
</style>

<div class="travel-detail">
    <a href="{{ route('admin.perjalanan-dinas.requests.index') }}" class="td-back">← Kembali ke daftar pengajuan</a>

    <div class="td-head">
        <div>
            <h2>Detail Pengajuan Perjalanan Dinas</h2>
            <p>Periksa kelengkapan informasi sebelum mengambil keputusan.</p>
        </div>
        <span class="td-status">{{ ucfirst(str_replace('_', ' ', $travelRequest->status ?? 'Menunggu')) }}</span>
    </div>

    <div class="td-card">
        <h3>Informasi pemohon</h3>
        <div class="td-info">
            <div class="td-item"><small>Nama pemohon</small><strong>{{ $travelRequest->requester->name ?? '-' }}</strong></div>
            <div class="td-item"><small>Tanggal pengajuan</small><strong>{{ $travelRequest->created_at ? $travelRequest->created_at->format('d M Y, H:i') : '-' }}</strong></div>
        </div>
    </div>

    <div class="td-card">
        <h3>Informasi perjalanan</h3>
        <div class="td-info">
            <div class="td-item"><small>Tujuan</small><strong>{{ $travelRequest->destination ?? '-' }}</strong></div>
            <div class="td-item"><small>Moda transportasi</small><strong>{{ ucfirst($travelRequest->transport_mode ?? '-') }}</strong></div>
            <div class="td-item"><small>Waktu keberangkatan</small><strong>{{ $travelRequest->departure_at ? \Carbon\Carbon::parse($travelRequest->departure_at)->format('d M Y, H:i') : '-' }}</strong></div>
            <div class="td-item"><small>Waktu kembali</small><strong>{{ $travelRequest->return_at ? \Carbon\Carbon::parse($travelRequest->return_at)->format('d M Y, H:i') : '-' }}</strong></div>
        </div>
        <div style="margin-top:20px;">
            <div class="td-item" style="margin-bottom:8px;"><small>Keperluan perjalanan</small></div>
            <div class="td-description">{{ $travelRequest->purpose ?? '-' }}</div>
        </div>
    </div>

    <div class="td-card">
        <h3>Peserta perjalanan</h3>
        @if($travelRequest->participants && $travelRequest->participants->count())
            <div class="td-participants">
                @foreach($travelRequest->participants as $participant)
                    <span class="td-person">{{ $participant->name ?? $participant->user->name ?? '-' }}</span>
                @endforeach
            </div>
        @else
            <p style="font-size:11px;color:var(--text-muted);margin:0;">Tidak ada peserta tambahan.</p>
        @endif
    </div>

    @if(($travelRequest->status ?? '') === 'pending')
        <div class="td-actions">
            <div class="td-action">
                <h3>Setujui pengajuan</h3>
                <p>Pastikan tujuan, waktu, dan keperluan perjalanan telah sesuai.</p>
                <form method="POST" action="{{ route('admin.perjalanan-dinas.requests.approve', $travelRequest) }}" onsubmit="return confirm('Setujui pengajuan perjalanan dinas ini?')">
                    @csrf
                    <button type="submit" class="td-btn td-approve">✓ Setujui Pengajuan</button>
                </form>
            </div>

            <div class="td-action">
                <h3>Tolak pengajuan</h3>
                <p>Berikan alasan yang jelas agar pemohon memahami keputusan.</p>
                <form method="POST" action="{{ route('admin.perjalanan-dinas.requests.reject', $travelRequest) }}" onsubmit="return confirm('Yakin ingin menolak pengajuan ini?')">
                    @csrf
                    <textarea class="td-input" name="rejection_reason" rows="3" required placeholder="Tuliskan alasan penolakan...">{{ old('rejection_reason') }}</textarea>
                    @error('rejection_reason')<small style="color:#dc6464;display:block;margin:-6px 0 10px;">{{ $message }}</small>@enderror
                    <button type="submit" class="td-btn td-reject">Tolak Pengajuan</button>
                </form>
            </div>
        </div>
    @else
        <div class="td-card">
            <h3>Keputusan pengajuan</h3>
            <p style="font-size:12px;color:var(--text-muted);margin:0;">Pengajuan ini sudah diproses dengan status <strong>{{ ucfirst(str_replace('_', ' ', $travelRequest->status)) }}</strong>.</p>
            @if($travelRequest->rejection_reason ?? false)
                <div class="td-description" style="margin-top:12px;">{{ $travelRequest->rejection_reason }}</div>
            @endif
        </div>
    @endif
</div>
@endsection
