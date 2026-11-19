@extends('layouts.app')
@section('title','Detail Disposisi')

@section('content')
<style>
    /* === LAYOUT === */
    .detail-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; margin-top: 24px; }
    .card-custom { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,.02); }
    .card-title { font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; }
    .label-sm { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; display: block; }
    .val-md { font-size: 14px; color: var(--text-main); font-weight: 500; margin-bottom: 16px; }
    .inst-box { background: var(--bg-soft); padding: 20px; border-radius: 12px; font-size: 14px; color: var(--text-main); font-style: italic; border-left: 4px solid #3b82f6; line-height: 1.6; }

    /* === LAMPIRAN === */
    .attach-list { display: flex; flex-direction: column; gap: 8px; }
    .attach-item {
        display: flex; align-items: center; gap: 12px; padding: 10px 12px;
        background: var(--bg-soft); border: 1px solid var(--border-color);
        border-radius: 10px; transition: .2s;
    }
    .attach-item:hover { border-color: var(--accent); background: var(--bg-card); }

    .attach-thumb {
        width: 40px; height: 40px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; background: var(--bg-card); color: var(--text-muted);
    }
    .attach-thumb.pdf { background: #fee2e2; color: #dc2626; }
    .attach-thumb.img { background: #dbeafe; color: #2563eb; }
    .attach-thumb.doc { background: #dbeafe; color: #2563eb; }
    .attach-thumb svg { width: 20px; height: 20px; }

    .attach-info { flex: 1; min-width: 0; }
    .attach-name { font-size: 13px; font-weight: 600; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .attach-meta { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

    .attach-btn {
        padding: 6px 12px; font-size: 12px; font-weight: 600;
        background: var(--accent); color: #fff; border: none; border-radius: 6px;
        text-decoration: none; white-space: nowrap; transition: .15s;
    }
    .attach-btn:hover { background: #1d4ed8; }

    /* === TIMELINE KONTINYU (dot-to-dot, scalable untuk N item) === */
    .timeline-history { position: relative; padding-left: 32px; margin-top: 16px; }

    .tl-step { position: relative; padding-bottom: 28px; }
    .tl-step:last-child { padding-bottom: 0; }

    /* Dot setiap step */
    .tl-step::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--accent);
        border: 2px solid var(--bg-card);
        box-sizing: content-box;
        z-index: 2;
    }

    /* Garis penghubung ke dot berikutnya (otomatis N-1 segmen) */
    .tl-step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: -19px;
        top: 20px;
        bottom: -12px;
        width: 2px;
        background: var(--border-color);
        z-index: 1;
    }

    /* Konten step */
    .tl-time { font-size: 11px; color: var(--text-muted); margin-bottom: 4px; }
    .tl-status { font-size: 13px; font-weight: 700; color: var(--accent); margin-bottom: 4px; letter-spacing: .3px; }
    .tl-note { font-size: 13px; color: var(--text-main); background: var(--bg-soft); padding: 8px 12px; border-radius: 8px; margin-top: 4px; line-height: 1.5; }

    /* Responsive: stack 1 kolom di HP */
    @media (max-width: 900px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
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

@php
    $letter = $disposition->incomingLetter;
    $documents = $letter ? $letter->documents : collect();
@endphp

<div class="detail-grid">
    <!-- ============ KIRI ============ -->
    <div>
        <div class="card-custom">
            <h3 class="card-title">Informasi Surat Asli</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <span class="label-sm">Perihal Surat</span>
                    <div class="val-md">{{ $letter->subject ?? '-' }}</div>
                </div>
                <div>
                    <span class="label-sm">Pengirim Surat</span>
                    <div class="val-md">{{ $letter->sender_name ?? '-' }}</div>
                </div>
                <div>
                    <span class="label-sm">Nomor Agenda</span>
                    <div class="val-md">{{ $letter->register_year ?? '' }}/{{ str_pad($letter->agenda_sequence ?? 0, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <span class="label-sm">Tanggal Surat</span>
                    <div class="val-md">{{ $letter && $letter->letter_date ? $letter->letter_date->format('d F Y') : '-' }}</div>
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

        {{-- ============ LAMPIRAN ============ --}}
        <div class="card-custom">
            <h3 class="card-title">Lampiran Surat ({{ $documents->count() }})</h3>

            @if($documents->count() > 0)
                <div class="attach-list">
                    @foreach($documents as $doc)
                        @php
                            $ext = strtolower(pathinfo($doc->original_filename, PATHINFO_EXTENSION));
                            $thumbClass = 'doc';
                            if ($ext === 'pdf') $thumbClass = 'pdf';
                            elseif (in_array($ext, ['jpg','jpeg','png','gif','webp','bmp'])) $thumbClass = 'img';
                        @endphp
                        <div class="attach-item">
                            <div class="attach-thumb {{ $thumbClass }}">
                                @if($thumbClass === 'pdf')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                @elseif($thumbClass === 'img')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="attach-info">
                                <div class="attach-name">{{ $doc->original_filename }}</div>
                                <div class="attach-meta">
                                    {{ $doc->humanSize() }} · {{ $doc->created_at->format('d M Y') }}
                                </div>
                            </div>
                            <a href="{{ route('admin.persuratan.incoming-letters.documents.download', [$letter, $doc]) }}"
                               class="attach-btn" target="_blank" rel="noopener">
                                Unduh
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 24px 12px; color: var(--text-muted); font-size: 13px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 36px; height: 36px; margin: 0 auto 8px; display: block; opacity: .5;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Tidak ada lampiran pada surat ini.
                </div>
            @endif
        </div>
    </div>

    <!-- ============ KANAN ============ -->
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
            <h3 class="card-title">Riwayat Pekerjaan ({{ $disposition->updates->count() }})</h3>

            @if($disposition->updates->count() > 0)
                <div class="timeline-history">
                    @foreach($disposition->updates as $upd)
                        <div class="tl-step">
                            <div class="tl-time">{{ $upd->created_at->format('d M Y - H:i') }} WIB</div>
                            <div class="tl-status">{{ strtoupper(str_replace('_', ' ', $upd->status)) }}</div>
                            <div class="tl-note">{{ $upd->progress_note }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="font-size: 13px; color: var(--text-muted); text-align: center; padding: 20px 0;">
                    Belum ada riwayat update.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
