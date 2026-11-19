
@extends('layouts.app')
@section('title','Disposisi Saya')
@section('content')

@php
    $items = $dispositions->getCollection();

    /* ---- Pemetaan status ke warna badge ---- */
    $toneStatus = function ($s) {
        $s = strtolower((string) $s);

        $map = [
            'assigned'   => 'info',
            'in_progress'=> 'warning',
            'returned'   => 'danger',
            'submitted'  => 'info',
            'approved'   => 'success',

            // Kompatibilitas status lama
            'accepted'   => 'info',
            'completed'  => 'success',
        ];

        if (isset($map[$s])) {
            return $map[$s];
        }

        $fallback = [
            'danger'  => ['tolak','reject','batal','cancel','gagal'],
            'warning' => ['belum','baru','new','pending','menunggu','dikirim','open'],
            'info'    => ['proses','progress','dibaca','read','diterima','berjalan'],
            'success' => ['selesai','done','complete','tuntas','disetujui'],
        ];

        foreach ($fallback as $tone => $keys) {
            foreach ($keys as $k) {
                if (str_contains($s, $k)) {
                    return $tone;
                }
            }
        }

        return 'muted';
    };

    /* ---- Label status Bahasa Indonesia ---- */
    $statusLabel = function ($s) {
        return match (strtolower((string) $s)) {
            'assigned'   => 'Baru',
            'in_progress'=> 'Dikerjakan',
            'returned'   => 'Dikembalikan',
            'submitted'  => 'Menunggu Review',
            'approved'   => 'Selesai',

            // Kompatibilitas status lama
            'accepted'   => 'Diterima',
            'completed'  => 'Selesai',

            default => \Illuminate\Support\Str::headline((string) $s),
        };
    };

    /* ---- Pemetaan prioritas ---- */
    $tonePriority = function ($p) {
        $p = strtolower((string) $p);

        $map = [
            'danger'  => ['tinggi','high','urgent','segera','penting','kilat'],
            'warning' => ['sedang','medium','normal','biasa'],
            'success' => ['rendah','low'],
        ];

        foreach ($map as $tone => $keys) {
            foreach ($keys as $k) {
                if (str_contains($p, $k)) {
                    return $tone;
                }
            }
        }

        return 'muted';
    };

    $initials = fn($name) => mb_strtoupper(
        collect(preg_split('/\s+/', trim((string) $name)))
            ->filter()
            ->take(2)
            ->map(fn($w) => mb_substr($w, 0, 1))
            ->join('')
    );

    /* ---- Ringkasan ---- */
    $cntUrgent = $items->filter(
        fn($d) => $tonePriority($d->priority) === 'danger'
    )->count();

    $cntOverdue = $items->filter(
        fn($d) =>
            $d->due_at &&
            $d->due_at->copy()->endOfDay()->isPast() &&
            $toneStatus($d->status) !== 'success'
    )->count();

    $cntDone = $items->filter(
        fn($d) => $toneStatus($d->status) === 'success'
    )->count();

    $statusList = $items
        ->map(fn($d) => $statusLabel($d->status))
        ->unique()
        ->values();
@endphp

<div class="page-head">
    <div>
        <h2>Kotak Disposisi</h2>
        <p>Surat yang didisposisikan kepada Anda. Buka untuk membaca instruksi dan menindaklanjuti.</p>
    </div>
</div>

<!-- RINGKASAN -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-ico blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-6l-2 3h-4l-2-3H2"/>
                <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
            </svg>
        </div>
        <div>
            <div class="stat-val">{{ $dispositions->total() }}</div>
            <div class="stat-lbl">Total disposisi</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-ico red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                <path d="M4 22v-7"/>
            </svg>
        </div>
        <div>
            <div class="stat-val">{{ $cntUrgent }}</div>
            <div class="stat-lbl">Prioritas tinggi <small>· halaman ini</small></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-ico amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
            </svg>
        </div>
        <div>
            <div class="stat-val">{{ $cntOverdue }}</div>
            <div class="stat-lbl">Lewat tenggat <small>· halaman ini</small></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-ico green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>
        </div>
        <div>
            <div class="stat-val">{{ $cntDone }}</div>
            <div class="stat-lbl">Selesai <small>· halaman ini</small></div>
        </div>
    </div>
</div>

<!-- TABEL -->
<div class="table-card">

    @if($items->count() > 0)
        <div class="table-toolbar">

            <label class="tb-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                <input
                    type="text"
                    id="dispSearch"
                    placeholder="Cari surat, pengirim, instruksi..."
                    autocomplete="off"
                >
            </label>

            <div class="chips">
                <button type="button" class="chip on" data-filter="all">
                    Semua
                </button>

                @foreach($statusList as $st)
                    <button type="button" class="chip" data-filter="{{ $st }}">
                        {{ $st }}
                    </button>
                @endforeach
            </div>

        </div>
    @endif

    <div class="table-wrap">
        <table class="data-table">

            <thead>
                <tr>
                    <th>Surat &amp; Instruksi</th>
                    <th>Pengirim</th>
                    <th>Prioritas</th>
                    <th>Tenggat</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

                @forelse($dispositions as $d)

                    @php
                        $subject = $d->incomingLetter?->subject ?? '-';
                        $sender = $d->fromUser?->name ?? '-';

                        $sTone = $toneStatus($d->status);
                        $pTone = $tonePriority($d->priority);

                        $sLabel = $statusLabel($d->status);

                        $isDone = $sTone === 'success';

                        $daysLeft = $d->due_at
                            ? (int) now()->startOfDay()->diffInDays(
                                $d->due_at->copy()->startOfDay(),
                                false
                            )
                            : null;

                        $dueClass = '';
                        $dueNote = null;

                        if ($d->due_at && !$isDone) {
                            if ($daysLeft < 0) {
                                $dueClass = 'overdue';
                                $dueNote = 'Lewat ' . abs($daysLeft) . ' hari';
                            } elseif ($daysLeft === 0) {
                                $dueClass = 'soon';
                                $dueNote = 'Hari ini';
                            } elseif ($daysLeft === 1) {
                                $dueClass = 'soon';
                                $dueNote = 'Besok';
                            } elseif ($daysLeft <= 3) {
                                $dueClass = 'soon';
                                $dueNote = $daysLeft . ' hari lagi';
                            }
                        }
                    @endphp

                    <tr
                        data-row
                        data-href="{{ route('pegawai.dispositions.show', $d) }}"
                        data-status="{{ $sLabel }}"
                        data-search="{{ \Illuminate\Support\Str::lower($subject . ' ' . $sender . ' ' . $d->instruction) }}"
                    >

                        <!-- SURAT & INSTRUKSI -->
                        <td>
                            <div class="cell-main">
                                <span class="cell-title">{{ $subject }}</span>
                                <span class="cell-sub">
                                    {{ \Illuminate\Support\Str::limit($d->instruction, 60) }}
                                </span>
                            </div>
                        </td>

                        <!-- PENGIRIM -->
                        <td>
                            <div class="person">
                                <span class="avatar-sm">{{ $initials($sender) }}</span>
                                <span>{{ $sender }}</span>
                            </div>
                        </td>

                        <!-- PRIORITAS -->
                        <td>
                            @if($d->priority)
                                <span class="badge badge-{{ $pTone }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                        <path d="M4 22v-7"/>
                                    </svg>
                                    {{ \Illuminate\Support\Str::headline((string) $d->priority) }}
                                </span>
                            @else
                                <span style="color:var(--text-muted)">-</span>
                            @endif
                        </td>

                        <!-- TENGGAT -->
                        <td>
                            @if($d->due_at)
                                <div class="due {{ $dueClass }}">
                                    <span class="due-date">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                                            <path d="M16 2v4M8 2v4M3 10h18"/>
                                        </svg>
                                        {{ $d->due_at->format('d-m-Y') }}
                                    </span>

                                    @if($dueNote)
                                        <span class="due-note">{{ $dueNote }}</span>
                                    @endif
                                </div>
                            @else
                                <span style="color:var(--text-muted)">-</span>
                            @endif
                        </td>

                        <!-- STATUS BADGE -->
                        <td>
                            <span class="badge badge-{{ $sTone }}">
                                <span class="dot"></span>

                                @switch($d->status)

                                    @case('assigned')
                                        Baru
                                        @break

                                    @case('in_progress')
                                        Dikerjakan
                                        @break

                                    @case('returned')
                                        Dikembalikan
                                        @break

                                    @case('submitted')
                                        Menunggu Review
                                        @break

                                    @case('approved')
                                        Selesai
                                        @break

                                    @default
                                        {{ $sLabel }}

                                @endswitch
                            </span>
                        </td>

                        <!-- AKSI -->
                        <td class="col-action">
                            <x-button
                                href="{{ route('pegawai.dispositions.show', $d) }}"
                                variant="soft"
                                size="sm"
                            >
                                Buka

                                <x-slot name="trailing">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/>
                                        <path d="m12 5 7 7-7 7"/>
                                    </svg>
                                </x-slot>
                            </x-button>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-ico">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 12h-6l-2 3h-4l-2-3H2"/>
                                        <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                                    </svg>
                                </div>
                                <h3>Belum ada disposisi</h3>
                                <p>Disposisi surat yang ditujukan kepada Anda akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>

                @endforelse

                <!-- HASIL PENCARIAN KOSONG -->
                <tr id="noResult" hidden>
                    <td colspan="6">
                        <div class="empty-state" style="padding:36px 20px">
                            <div class="empty-ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.3-4.3"/>
                                </svg>
                            </div>
                            <h3>Tidak ada hasil</h3>
                            <p>Coba kata kunci atau filter status yang lain.</p>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    @if($items->count() > 0)
        <x-pagination :paginator="$dispositions" item-label="disposisi" />
    @endif

</div>

<script>
    (function () {
        var rows = [].slice.call(document.querySelectorAll('[data-row]'));
        var search = document.getElementById('dispSearch');
        var chips = [].slice.call(document.querySelectorAll('.chip'));
        var none = document.getElementById('noResult');

        var active = 'all';

        if (!search) return;

        function apply() {
            var q = search.value.trim().toLowerCase();
            var shown = 0;

            rows.forEach(function (r) {
                var okQ = !q ||
                    r.getAttribute('data-search').indexOf(q) > -1;

                var okS = active === 'all' ||
                    r.getAttribute('data-status') === active;

                r.hidden = !(okQ && okS);

                if (okQ && okS) shown++;
            });

            none.hidden = shown !== 0;
        }

        search.addEventListener('input', apply);

        chips.forEach(function (c) {
            c.addEventListener('click', function () {
                active = c.getAttribute('data-filter');

                chips.forEach(function (x) {
                    x.classList.toggle('on', x === c);
                });

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
