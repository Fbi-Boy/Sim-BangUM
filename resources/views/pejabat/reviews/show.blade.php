@extends('layouts.app')
@section('title', 'Detail Review')

@section('content')
<div class="page-head" style="margin-bottom:20px;">
    <div>
        <a href="{{ route('pejabat.reviews.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:13px;">← Kembali ke Review</a>
        <h2 style="margin-top:8px;">Review Hasil: {{ $disposition->toUser->name ?? '-' }}</h2>
    </div>
</div>

<div style="display:grid; grid-template-columns:1.5fr 1fr; gap:24px;">
    <div>
        <div class="card" style="padding:24px;">
            <h3 style="font-size:16px; margin-bottom:16px; border-bottom:1px solid var(--border-color); padding-bottom:12px;">Informasi Surat</h3>
            <p><strong>Perihal:</strong> {{ $disposition->incomingLetter->subject ?? '-' }}</p>
            <p><strong>Pengirim:</strong> {{ $disposition->incomingLetter->sender_name ?? '-' }}</p>
            <p><strong>Nomor:</strong> {{ $disposition->incomingLetter->register_year ?? '' }}/{{ str_pad($disposition->incomingLetter->agenda_sequence ?? 0, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="card" style="padding:24px; margin-top:16px;">
            <h3 style="font-size:16px; margin-bottom:16px; border-bottom:1px solid var(--border-color); padding-bottom:12px;">Instruksi Anda</h3>
            <p style="background:var(--bg-soft); padding:14px; border-radius:8px; font-style:italic;">"{{ $disposition->instruction }}"</p>
        </div>

        <div class="card" style="padding:24px; margin-top:16px;">
            <h3 style="font-size:16px; margin-bottom:16px; border-bottom:1px solid var(--border-color); padding-bottom:12px;">Hasil Pekerjaan Pegawai</h3>
            @forelse($disposition->updates as $u)
                <div style="padding:10px 0; border-bottom:1px solid var(--border-color);">
                    <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--text-muted);">
                        <span>{{ $u->user->name ?? '-' }}</span>
                        <span>{{ $u->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div style="font-weight:600; color:var(--accent); font-size:12px; margin-top:4px;">{{ strtoupper(str_replace('_', ' ', $u->status)) }}</div>
                    <div style="font-size:13px; margin-top:4px;">{{ $u->progress_note }}</div>
                </div>
            @empty
                <p style="color:var(--text-muted); font-size:13px;">Belum ada riwayat.</p>
            @endforelse
        </div>
    </div>

    <div>
        <div class="card" style="padding:24px; border-top:4px solid #10b981;">
            <h3 style="font-size:16px; margin-bottom:16px;">Keputusan</h3>
            <form method="POST" action="{{ route('pejabat.reviews.approve', $disposition) }}" style="margin-bottom:12px;">
                @csrf
                <button type="submit" style="width:100%; padding:12px; background:#10b981; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer;" onclick="return confirm('Setujui hasil & tutup tugas?')">
                    ✓ Setujui & Tutup Tugas
                </button>
            </form>
            <form method="POST" action="{{ route('pejabat.reviews.reject', $disposition) }}">
                @csrf
                <p style="margin-bottom:8px;"><label style="font-size:12px; font-weight:600;">Alasan Penolakan *</label></p>
                <textarea name="reason" rows="3" required style="width:100%; padding:10px; border:1px solid var(--border-color); border-radius:8px; margin-bottom:12px;" placeholder="Jelaskan apa yang perlu diperbaiki..."></textarea>
                <button type="submit" style="width:100%; padding:12px; background:#dc2626; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer;">
                    ✗ Kembalikan ke Pegawai
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
