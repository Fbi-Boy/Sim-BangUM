@extends('layouts.app')
@section('title', 'Detail Surat')

@section('content')
<style>
    .info-grid { display: grid; grid-template-columns: 120px 1fr; gap: 12px; font-size: 13.5px; margin-bottom: 12px; }
    .info-label { color: var(--text-muted); font-weight: 500; }
    .info-val { color: var(--text-main); font-weight: 600; }

    .timeline-disp { border-left: 2px solid var(--border-color); margin-left: 8px; padding-left: 20px; position: relative; margin-bottom: 24px; }
    .timeline-disp::before { content: ''; position: absolute; left: -6px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: var(--accent); border: 2px solid var(--bg-card); }

    .form-control { width: 100%; padding: 11px 14px; background: var(--bg-soft); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-main); font-size: 13px; outline: none; }
    .form-control:focus { border-color: var(--accent); background: var(--bg-card); }
    .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-main); margin-bottom: 6px; }
    .sticky-side { position: sticky; top: 84px; }
</style>

<div class="page-head" style="margin-bottom: 20px;">
    <div>
        <a href="{{ route('admin.persuratan.incoming-letters.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:12.5px; font-weight:500; display:inline-flex; align-items:center; gap:6px; margin-bottom:8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h2>Agenda {{ $incomingLetter->register_year }}/{{ str_pad($incomingLetter->agenda_sequence,4,'0',STR_PAD_LEFT) }}</h2>
    </div>
</div>

@if(session('success'))<div class="alert alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>@endif
@if($errors->any())
    <div class="alert alert-error" style="margin-bottom:20px;">
        <ul style="margin-left:16px;">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start;">

    <!-- KOLOM KIRI -->
    <div>
        <!-- Informasi Surat -->
        <div class="table-card" style="padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size:16px; font-weight:700; margin-bottom:16px; border-bottom:1px solid var(--border-color); padding-bottom:12px; color:var(--text-main);">Informasi Surat</h3>
            <div class="info-grid"><div class="info-label">Pengirim</div><div class="info-val">{{ $incomingLetter->sender_name }}</div></div>
            <div class="info-grid"><div class="info-label">Nomor Surat</div><div class="info-val">{{ $incomingLetter->external_letter_number ?? '-' }}</div></div>
            <div class="info-grid"><div class="info-label">Perihal</div><div class="info-val">{{ $incomingLetter->subject }}</div></div>
            <div class="info-grid"><div class="info-label">Tanggal</div><div class="info-val">{{ $incomingLetter->letter_date?->format('d F Y') ?? '-' }}</div></div>
            <div class="info-grid"><div class="info-label">Status</div>
                <div>
                    @php
                        $color = match($incomingLetter->status) {
                            'registered' => 'badge-warning',
                            'awaiting_direction' => 'badge-info',
                            'archived' => 'badge-success',
                            default => 'badge-muted'
                        };
                    @endphp
                    <span class="badge {{ $color }}"><span class="dot"></span>{{ Str::headline($incomingLetter->status) }}</span>
                </div>
            </div>
            <div class="info-grid" style="margin-bottom:0;"><div class="info-label">Ringkasan</div><div class="info-val" style="font-weight:400; color:var(--text-muted);">{{ $incomingLetter->summary ?? '-' }}</div></div>
        </div>

        <!-- Lampiran -->
        <div class="table-card" style="padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size:16px; font-weight:700; margin-bottom:16px; color:var(--text-main);">Lampiran ({{ $incomingLetter->documents->count() }})</h3>
            @forelse($incomingLetter->documents as $doc)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:12px; border:1px solid var(--border-color); border-radius:10px; margin-bottom:8px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <div>
                            <div style="font-size:13px; font-weight:600; color:var(--text-main);">{{ $doc->original_filename }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">{{ $doc->humanSize() }}</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.persuratan.incoming-letters.documents.download', [$incomingLetter, $doc]) }}" class="btn-open" style="padding:6px 12px;">Unduh</a>
                </div>
            @empty
                <p style="color:var(--text-muted); font-size:13px;">Tidak ada file lampiran.</p>
            @endforelse
        </div>

        <!-- Riwayat Disposisi -->
        <div class="table-card" style="padding: 24px;">
            <h3 style="font-size:16px; font-weight:700; margin-bottom:16px; color:var(--text-main);">Riwayat Disposisi ({{ $dispositions->count() }})</h3>
            @forelse($dispositions as $d)
                <div class="timeline-disp">
                    <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:4px;">
                        <strong style="color:var(--text-main);">{{ $d->fromUser->name }} &rarr; {{ $d->toUser->name ?? '-' }}</strong>
                        <span style="color:var(--text-muted);">{{ $d->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div style="background:var(--bg-soft); padding:12px; border-radius:8px; font-size:13px; color:var(--text-main); margin-top:8px; border:1px solid var(--border-color);">
                        "{{ $d->instruction }}"
                    </div>
                    <div style="margin-top:8px; font-size:11px; display:flex; gap:12px; color:var(--text-muted);">
                        <span>Prioritas: <strong style="color:{{ $d->priority == 'urgent' ? '#ef4444' : 'inherit' }}">{{ ucfirst($d->priority) }}</strong></span>
                        <span>Status: <strong>{{ strtoupper($d->status) }}</strong></span>
                    </div>
                </div>
            @empty
                <p style="color:var(--text-muted); font-size:13px;">Belum ada riwayat disposisi.</p>
            @endforelse
        </div>
    </div>

    <!-- KOLOM KANAN (FORM DISPOSISI STICKY) -->
    <div class="sticky-side">
        <div class="table-card" style="padding: 24px; border-top: 4px solid var(--accent);">
            <h3 style="font-size:16px; font-weight:700; margin-bottom:16px; color:var(--text-main);">Beri Disposisi</h3>
            <form method="POST" action="{{ route('admin.persuratan.incoming-letters.dispositions.store', $incomingLetter) }}">
                @csrf
                <div style="margin-bottom:16px;">
                    <label class="form-label">Penerima *</label>
                    <select name="to_user_id" required class="form-control">
                        <option value="">-- Pilih Kaban/Kasubbag --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('to_user_id')==$u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom:16px;">
                    <label class="form-label">Instruksi *</label>
                    <textarea name="instruction" rows="4" required class="form-control" placeholder="Tulis instruksi...">{{ old('instruction') }}</textarea>
                </div>

                <!-- Tumpuk ke bawah -->
                <div style="margin-bottom:16px;">
                    <label class="form-label">Prioritas</label>
                    <select name="priority" class="form-control">
                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent (Mendesak)</option>
                    </select>
                </div>
                <div style="margin-bottom:24px;">
                    <label class="form-label">Batas Waktu (Tenggat)</label>
                    <input type="date" name="due_at" value="{{ old('due_at') }}" class="form-control">
                </div>

                <button type="submit" class="btn" style="width:100%; padding:12px; font-size:13.5px;">Kirim Disposisi</button>
            </form>
        </div>
    </div>

</div>
@endsection
