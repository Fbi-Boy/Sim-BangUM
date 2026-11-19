
@extends('layouts.app')
@section('title', 'Draft Surat Keluar')

@section('content')

{{-- FLATPICKR CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* FORM */
    .form-group {
        margin-bottom: 0;
        min-width: 0;
    }

    .form-label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 9px;
    }

    .form-control {
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        padding: 12px 14px;
        background: var(--bg-soft);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-main);
        font-family: inherit;
        font-size: 13.5px;
        outline: none;
        transition: .2s;
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-soft);
        background: var(--bg-card);
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    textarea.form-control {
        min-height: 155px;
        resize: vertical;
        line-height: 1.7;
    }

    /* HEADER */
    .page-head {
        margin-bottom: 24px;
    }

    .page-back {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
    }

    .page-back:hover {
        color: var(--accent);
    }

    .page-head h2 {
        margin: 0 0 6px;
    }

    .page-head p {
        color: var(--text-muted);
        font-size: 13px;
        margin: 0;
    }

    /* CARD */
    .form-card {
        padding: 28px;
        margin-bottom: 0;
    }

    .section-heading {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 22px;
    }

    .form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 22px 24px;
    }

    .form-full {
        grid-column: 1 / -1;
    }

    /* DATE PICKER */
    .date-picker-wrapper {
        position: relative;
        width: 100%;
    }

    .date-picker-wrapper input {
        padding-right: 42px;
        cursor: pointer;
    }

    .date-picker-icon {
        position: absolute;
        width: 18px;
        height: 18px;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
        z-index: 2;
    }

    /* FLATPICKR */
    .flatpickr-calendar {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 12px 35px rgba(0,0,0,.12);
        font-family: inherit;
        overflow: hidden;
    }

    .flatpickr-day.selected,
    .flatpickr-day.selected:hover {
        background: var(--accent);
        border-color: var(--accent);
    }

    .flatpickr-day.today {
        border-color: var(--accent);
    }

    .flatpickr-current-month {
        font-size: 14px;
        font-weight: 600;
    }

    .flatpickr-weekday {
        font-weight: 600;
    }

    /* INFO */
    .date-info {
        min-height: 70px;
        box-sizing: border-box;
        padding: 13px 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        background: var(--accent-soft);
        border-radius: 10px;
    }

    .date-info svg {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
        color: var(--accent);
    }

    .date-info strong {
        display: block;
        font-size: 12px;
        color: var(--text-main);
        margin-bottom: 3px;
    }

    .date-info span {
        display: block;
        color: var(--text-muted);
        font-size: 11.5px;
        line-height: 1.5;
    }

    /* ACTION */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        color: var(--text-main);
        background: var(--bg-soft);
        border: 1px solid var(--border-color);
        transition: .2s;
    }

    .btn-cancel:hover {
        border-color: var(--text-muted);
    }

    /* RESPONSIVE */
    @media(max-width: 768px) {
        .form-card {
            padding: 20px;
        }

        .form-layout {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .form-full {
            grid-column: auto;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions > * {
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
    }
</style>

{{-- HEADER --}}
<div class="page-head">
    <a href="{{ route('admin.persuratan.outgoing-letters.index') }}"
       class="page-back">
        <svg width="14" height="14" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>

    <h2>Draft Surat Keluar</h2>
    <p>Buat draft surat keluar dengan melengkapi informasi berikut.</p>
</div>

{{-- VALIDATION --}}
@if($errors->any())
    <div class="alert alert-error" style="margin-bottom:20px;">
        <ul style="margin-left:16px;">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ route('admin.persuratan.outgoing-letters.store') }}">
    @csrf

    <div class="table-card form-card">

        <h3 class="section-heading">Informasi Surat</h3>

        <div class="form-layout">

            {{-- PERIHAL --}}
            <div class="form-group form-full">
                <label for="subject" class="form-label">
                    Perihal Surat *
                </label>

                <input
                    type="text"
                    id="subject"
                    name="subject"
                    required
                    value="{{ old('subject') }}"
                    class="form-control"
                    placeholder="Contoh: Undangan Rapat Koordinasi"
                >
            </div>

            {{-- TANGGAL --}}
            <div class="form-group">
                <label for="letter_date" class="form-label">
                    Tanggal Surat
                </label>

                <div class="date-picker-wrapper">
                    <input
                        type="text"
                        id="letter_date"
                        name="letter_date"
                        value="{{ old('letter_date') }}"
                        class="form-control js-date-picker"
                        placeholder="Pilih tanggal surat"
                        autocomplete="off"
                    >

                    <svg class="date-picker-icon"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                        <path d="M16 3v4M8 3v4M3 11h18"/>
                    </svg>
                </div>
            </div>

            {{-- INFORMASI --}}
            <div class="date-info">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4M12 8h.01"/>
                </svg>

                <div>
                    <strong>Informasi Tanggal</strong>
                    <span>
                        Gunakan tanggal yang tercantum pada dokumen
                        surat yang akan diterbitkan.
                    </span>
                </div>
            </div>

            {{-- RINGKASAN --}}
            <div class="form-group form-full">
                <label for="body_summary" class="form-label">
                    Ringkasan Isi Surat
                </label>

                <textarea
                    id="body_summary"
                    name="body_summary"
                    rows="5"
                    class="form-control"
                    placeholder="Tuliskan gambaran isi atau tujuan surat keluar..."
                >{{ old('body_summary') }}</textarea>
            </div>

        </div>
    </div>

    {{-- BUTTON --}}
    <div class="form-actions">
        <button type="submit" class="btn"
                style="padding:12px 24px;font-size:14px;">
            Simpan Draft
        </button>

        <a href="{{ route('admin.persuratan.outgoing-letters.index') }}"
           class="btn-cancel">
            Batal
        </a>
    </div>
</form>

{{-- FLATPICKR JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.js-date-picker').forEach(function (element) {

        if (element._flatpickr) return;

        flatpickr(element, {
            locale: 'id',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd F Y',
            allowInput: true,
            disableMobile: true,
            monthSelectorType: 'static',
            animate: true
        });

    });

});
</script>

@endsection
