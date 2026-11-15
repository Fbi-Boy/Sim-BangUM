@extends('layouts.app')
@section('title','Detail Disposisi')

@section('content')
<style>
    /* CSS Khusus Halaman Ini biar senada dengan Index lo */
    .detail-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; margin-top: 24px; }
    .card-custom { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,.02); }
    .card-title { font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; }
    .label-sm { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; display: block; }
    .val-md { font-size: 14px; color: var(--text-main); font-weight: 500; margin-bottom: 16px; }
    .inst-box { background: var(--bg-soft); padding: 20px; border-radius: 12px; font-size: 14px; color: var(--text-main); font-style: italic; border-left: 4px solid #3b82f6; line-height: 1.6; }

    /* Timeline Riwayat */
    .timeline-history {
        position: relative;
        padding-left: 24px; /* Jarak teks dari garis */
        margin-top: 16px;
    }

    /* Garis Vertikal */
    .timeline-history::before {
        content: '';
        position: absolute;
        left: 6px; /* Posisi garis */
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: var(--border-color);
    }

    .tl-step {
        position: relative;
        padding-bottom: 24px;
    }

    /* Lingkaran Biru */
    .tl-step::before {
        content: '';
        position: absolute;
        left: -23px; /* Paksa sejajar tengah dengan garis */
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--accent);
        border: 2px solid var(--bg-card);
        z-index: 2; /* Biar lingkaran ada di atas garis */
    }

    .tl-time { font-size: 11px; color: var(--text-muted); margin-bottom: 4px; }
    .tl-note { font-size: 13px; color: var(--text-main); margin-top: 4px; background: var(--bg-soft); padding: 8px 12px; border-radius: 8px; }
</style>

<div class="page-head" style="margin-bottom: 24px;">
    <div>
        <a href="{{ route('pegawai.dispositions.index') }}" style="color: var(--text-muted); font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Kotak Disposisi
        </a>
        <h2>Detail Disposisi</h2>
        <p>Instruksi dari {{ $disposition->fromUser->name ?? 'Atasan' }}</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

<div class="detail-grid">
    <!-- KIRI: Info Surat & Instruksi -->
    <div>
        <div class="card-custom">
            <h3 class="card-title">Informasi Surat Asli</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <span class="label-sm">Perihal Surat</span>
                    <div class="val-md">{{ $disposition->incomingLetter->subject ?? '-' }}</div>
                </div>
                <div>
                    <span class="label-sm">Pengirim Surat</span>
                    <div class="val-md">{{ $disposition->incomingLetter->sender_name ?? '-' }}</div>
                </div>
                <div>
                    <span class="label-sm">Nomor Agenda</span>
                    <div class="val-md">{{ $disposition->incomingLetter->register_year ?? '' }}/{{ str_pad($disposition->incomingLetter->agenda_sequence ?? 0, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <span class="label-sm">Tanggal Surat</span>
                    <div class="val-md">{{ $disposition->incomingLetter->letter_date ? $disposition->incomingLetter->letter_date->format('d F Y') : '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card-custom">
            <h3 class="card-title">Instruksi Dari Atasan</h3>
            <div class="inst-box">
                "{{ $disposition->instruction }}"
            </div>
            <div style="margin-top: 16px; display: flex; gap: 24px;">
                <div>
                    <span class="label-sm">Batas Waktu (Tenggat)</span>
                    <div style="font-size: 14px; font-weight: 600; color: #ef4444;">
                        {{ $disposition->due_at ? $disposition->due_at->format('d F Y') : 'Tidak ada tenggat' }}
                    </div>
                </div>
                <div>
                    <span class="label-sm">Status Saat Ini</span>
                    <div class="val-md">{{ strtoupper(str_replace('_', ' ', $disposition->status)) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- KANAN: Form Update & Riwayat -->
    <div>
        <div class="card-custom">
            <h3 class="card-title">Update Progres Tugas</h3>
            <form method="POST" action="{{ route('pegawai.dispositions.update', $disposition) }}">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label class="label-sm">Ubah Status</label>
                    <select name="status" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-card); color: var(--text-main); font-family: inherit;" required>
                        <option value="accepted" {{ $disposition->status == 'accepted' ? 'selected' : '' }}>Diterima (Belum Mulai)</option>
                        <option value="in_progress" {{ $disposition->status == 'in_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                        <option value="completed" {{ $disposition->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="returned" {{ $disposition->status == 'returned' ? 'selected' : '' }}>Dikembalikan ke Atasan</option>
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label class="label-sm">Catatan Progres <span style="color:#ef4444">*</span></label>
                    <textarea name="progress_note" rows="3" style="width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-card); color: var(--text-main); font-family: inherit;" placeholder="Contoh: Sedang koordinasi dengan bidang terkait..." required></textarea>
                </div>
                <button type="submit" style="width: 100%; padding: 12px; background: #2563eb; color: #fff; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer;">
                    Simpan Progres
                </button>
            </form>
        </div>

        <div class="card-custom">
            <h3 class="card-title">Riwayat Pekerjaan</h3>
            @if($disposition->updates->count() > 0)
                <div class="timeline-history">
                    @foreach($disposition->updates as $upd)
                        <div class="tl-step">
                            <div class="tl-time">{{ $upd->created_at->format('d M Y - H:i') }} WIB</div>
                            <div style="font-size: 13px; font-weight: 600; color: var(--accent);">
                                {{ strtoupper(str_replace('_', ' ', $upd->status)) }}
                            </div>
                            <div class="tl-note">{{ $upd->progress_note }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="font-size: 13px; color: var(--text-muted); text-align: center;">Belum ada riwayat update.</p>
            @endif
        </div>
    </div>
</div>
@endsection
