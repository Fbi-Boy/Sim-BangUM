@extends('layouts.app')
@section('title', 'Detail Kegiatan')

@section('content')
<style>
    .workspace { max-width:1200px; margin:auto; color:var(--text-main); }
    .ws-head { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; margin-bottom:22px; }
    .ws-head h2 { margin:0 0 6px; font-size:23px; font-weight:750; }
    .ws-head p { margin:0; font-size:12px; color:var(--text-muted); }
    .ws-back { display:inline-block; color:var(--text-muted); text-decoration:none; font-size:12px; margin-bottom:12px; }
    .ws-back:hover { color:var(--accent); }
    .ws-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:15px; padding:21px; margin-bottom:18px; }
    .ws-overview { display:grid; grid-template-columns:1.5fr 1fr 1fr; gap:12px; }
    .ws-stat { background:var(--bg-soft); border:1px solid var(--border-color); padding:15px; border-radius:12px; }
    .ws-stat small { display:block; font-size:10px; color:var(--text-muted); margin-bottom:7px; }
    .ws-stat strong { font-size:13px; line-height:1.5; }
    .ws-section-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; }
    .ws-section-head h3 { font-size:15px; margin:0 0 4px; }
    .ws-section-head p { font-size:11px; color:var(--text-muted); margin:0; }
    .ws-badge { display:inline-flex; padding:5px 9px; border-radius:20px; background:var(--accent-soft); color:var(--accent); font-size:10px; font-weight:700; }
    .ws-timeline { position:relative; padding-left:25px; }
    .ws-timeline:before { content:""; position:absolute; left:7px; top:7px; bottom:8px; width:1px; background:var(--border-color); }
    .ws-agenda { position:relative; padding:0 0 20px; }
    .ws-agenda:before { content:""; position:absolute; left:-22px; top:4px; width:9px; height:9px; border-radius:50%; background:var(--accent); box-shadow:0 0 0 4px var(--accent-soft); }
    .ws-agenda:last-child { padding-bottom:0; }
    .ws-agenda h4 { margin:0 0 6px; font-size:13px; }
    .ws-agenda p { margin:0; color:var(--text-muted); font-size:11px; }
    .ws-agenda-time { font-size:10px; color:var(--accent); font-weight:700; margin-bottom:5px; }
    .ws-form-grid { display:grid; grid-template-columns:1fr 1fr; gap:13px; }
    .ws-field { margin-bottom:13px; min-width:0; }
    .ws-field.full { grid-column:1/-1; }
    .ws-label { display:block; font-size:11px; font-weight:650; margin-bottom:7px; }
    .ws-control { box-sizing:border-box; width:100%; padding:10px 12px; border:1px solid var(--border-color); background:var(--bg-soft); color:var(--text-main); border-radius:9px; outline:none; font-size:12px; }
    .ws-control:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-soft); }
    .ws-btn { display:inline-flex; align-items:center; justify-content:center; padding:9px 13px; border:0; border-radius:9px; background:var(--accent); color:#fff; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
    .ws-btn:hover { filter:brightness(.94); }
    .ws-btn-muted { background:var(--bg-soft); color:var(--text-main); border:1px solid var(--border-color); }
    .ws-task { display:flex; justify-content:space-between; align-items:center; gap:15px; padding:14px 0; border-bottom:1px solid var(--border-color); }
    .ws-task:last-child { border-bottom:0; padding-bottom:0; }
    .ws-task h4 { font-size:12px; margin:0 0 5px; }
    .ws-task p { font-size:10px; color:var(--text-muted); margin:0; }
    .ws-task-actions { display:flex; align-items:center; gap:9px; }
    .ws-priority { font-size:9px; padding:4px 7px; border-radius:20px; background:var(--bg-soft); color:var(--text-muted); }
    .ws-empty { padding:24px; text-align:center; border:1px dashed var(--border-color); border-radius:12px; color:var(--text-muted); font-size:11px; }
    @media(max-width:800px) { .ws-overview { grid-template-columns:1fr 1fr; } }
    @media(max-width:560px) { .ws-head { flex-direction:column; } .ws-overview,.ws-form-grid { grid-template-columns:1fr; } .ws-field.full { grid-column:auto; } .ws-card { padding:16px; } .ws-section-head { align-items:flex-start; flex-direction:column; } .ws-task { align-items:flex-start; flex-direction:column; } }
</style>

<div class="workspace">
    <a href="{{ route('admin.protokol.events.index') }}" class="ws-back">← Kembali ke daftar kegiatan</a>

    <div class="ws-head">
        <div>
            <h2>{{ $event->title }}</h2>
            <p>Kelola rundown, agenda, dan pembagian tugas kegiatan.</p>
        </div>
        <span class="ws-badge">{{ ucfirst(str_replace('_', ' ', $event->status ?? 'Terjadwal')) }}</span>
    </div>

    <div class="ws-card">
        <div class="ws-section-head">
            <div><h3>Ringkasan kegiatan</h3><p>Informasi utama kegiatan yang sedang dikelola.</p></div>
        </div>
        <div class="ws-overview">
            <div class="ws-stat"><small>NAMA KEGIATAN</small><strong>{{ $event->title }}</strong></div>
            <div class="ws-stat"><small>NOMOR KEGIATAN</small><strong>{{ $event->event_number ?? '-' }}</strong></div>
            <div class="ws-stat"><small>JENIS KEGIATAN</small><strong>{{ ucfirst($event->event_type ?? '-') }}</strong></div>
            <div class="ws-stat"><small>MULAI</small><strong>{{ $event->starts_at ? \Carbon\Carbon::parse($event->starts_at)->format('d M Y, H:i') : '-' }}</strong></div>
            <div class="ws-stat"><small>SELESAI</small><strong>{{ $event->ends_at ? \Carbon\Carbon::parse($event->ends_at)->format('d M Y, H:i') : '-' }}</strong></div>
            <div class="ws-stat"><small>LOKASI</small><strong>{{ $event->location ?? '-' }}</strong></div>
        </div>
        @if($event->description)
            <p style="font-size:12px; color:var(--text-muted); line-height:1.7; margin:16px 0 0;">{{ $event->description }}</p>
        @endif
    </div>

    <div class="ws-card">
        <div class="ws-section-head">
            <div><h3>Susun agenda kegiatan</h3><p>Tambahkan urutan acara beserta waktu dan penanggung jawab.</p></div>
        </div>

        <form method="POST" action="{{ route('admin.protokol.events.agendas.store', $event) }}">
            @csrf
            <div class="ws-form-grid">
                <div class="ws-field full">
                    <label class="ws-label">Nama agenda</label>
                    <input class="ws-control" name="agenda_title" value="{{ old('agenda_title') }}" required placeholder="Contoh: Pembukaan dan sambutan">
                    @error('agenda_title')<small style="color:#dc6464">{{ $message }}</small>@enderror
                </div>
                <div class="ws-field">
                    <label class="ws-label">Waktu mulai</label>
                    <input class="ws-control" type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required>
                    @error('starts_at')<small style="color:#dc6464">{{ $message }}</small>@enderror
                </div>
                <div class="ws-field">
                    <label class="ws-label">Waktu selesai</label>
                    <input class="ws-control" type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" required>
                    @error('ends_at')<small style="color:#dc6464">{{ $message }}</small>@enderror
                </div>
                <div class="ws-field full">
                    <label class="ws-label">Penanggung jawab</label>
                    <input class="ws-control" name="person_in_charge" value="{{ old('person_in_charge') }}" placeholder="Nama penanggung jawab">
                    @error('person_in_charge')<small style="color:#dc6464">{{ $message }}</small>@enderror
                </div>
            </div>
            <button class="ws-btn" type="submit">+ Tambahkan agenda</button>
        </form>
    </div>

    <div class="ws-card">
        <div class="ws-section-head">
            <div><h3>Timeline agenda</h3><p>Urutan pelaksanaan kegiatan.</p></div>
            <span class="ws-badge">{{ $event->agendas->count() }} agenda</span>
        </div>

        @if($event->agendas->count())
            <div class="ws-timeline">
                @foreach($event->agendas->sortBy('starts_at') as $agenda)
                    <div class="ws-agenda">
                        <div class="ws-agenda-time">
                            {{ $agenda->starts_at ? \Carbon\Carbon::parse($agenda->starts_at)->format('H:i') : '--:--' }}
                            –
                            {{ $agenda->ends_at ? \Carbon\Carbon::parse($agenda->ends_at)->format('H:i') : '--:--' }}
                        </div>
                        <h4>{{ $agenda->agenda_title }}</h4>
                        <p>Penanggung jawab: {{ $agenda->person_in_charge ?? '-' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="ws-empty">Belum ada agenda. Tambahkan agenda pertama melalui formulir di atas.</div>
        @endif
    </div>

    <div class="ws-card">
        <div class="ws-section-head">
            <div><h3>Pembagian tugas</h3><p>Tentukan pekerjaan dan pegawai yang bertanggung jawab.</p></div>
            <span class="ws-badge">{{ $event->tasks->count() }} tugas</span>
        </div>

        <form method="POST" action="{{ route('admin.protokol.events.tasks.store', $event) }}">
            @csrf
            <div class="ws-form-grid">
                <div class="ws-field full">
                    <label class="ws-label">Nama tugas</label>
                    <input class="ws-control" name="task_title" value="{{ old('task_title') }}" required placeholder="Contoh: Menyiapkan perlengkapan rapat">
                    @error('task_title')<small style="color:#dc6464">{{ $message }}</small>@enderror
                </div>
                <div class="ws-field">
                    <label class="ws-label">Ditugaskan kepada</label>
                    <select class="ws-control" name="assigned_to_user_id" required>
                        <option value="">Pilih pegawai</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_to_user_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to_user_id')<small style="color:#dc6464">{{ $message }}</small>@enderror
                </div>
                <div class="ws-field">
                    <label class="ws-label">Batas waktu</label>
                    <input class="ws-control" type="datetime-local" name="due_at" value="{{ old('due_at') }}">
                    @error('due_at')<small style="color:#dc6464">{{ $message }}</small>@enderror
                </div>
                <div class="ws-field full">
                    <label class="ws-label">Prioritas</label>
                    <select class="ws-control" name="priority">
                        @foreach(['low'=>'Rendah','normal'=>'Normal','high'=>'Tinggi','urgent'=>'Mendesak'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority', 'normal') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('priority')<small style="color:#dc6464">{{ $message }}</small>@enderror
                </div>
            </div>
            <button class="ws-btn" type="submit">+ Tambahkan tugas</button>
        </form>

        <div style="height:1px;background:var(--border-color);margin:22px 0;"></div>

        @forelse($event->tasks as $t)
            <div class="ws-task">
                <div>
                    <h4>{{ $t->task_title }}</h4>
                    <p>PIC: {{ $t->assignee?->name ?? '-' }} · Tenggat: {{ $t->due_at ? \Carbon\Carbon::parse($t->due_at)->format('d M Y H:i') : '-' }}</p>
                </div>
                <div class="ws-task-actions">
                    <span class="ws-priority">{{ ucfirst($t->priority ?? 'normal') }}</span>
                    @if(($t->status ?? '') !== 'completed')
                        <form method="POST" action="{{ route('admin.protokol.tasks.complete', $t) }}" onsubmit="return confirm('Tandai tugas ini sebagai selesai?')">
                            @csrf
                            <button type="submit" class="ws-btn">Selesaikan</button>
                        </form>
                    @else
                        <span class="ws-badge">Selesai</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="ws-empty">Belum ada tugas untuk kegiatan ini.</div>
        @endforelse
    </div>
</div>
@endsection
