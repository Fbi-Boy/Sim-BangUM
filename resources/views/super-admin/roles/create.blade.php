
@extends('layouts.app')

@section('title', 'Tambah Role')

@section('content')
@php
    $oldCode = old('code');
    $oldName = old('name');
    $oldDescription = old('description');
@endphp

<div class="retro-role">

    {{-- HEADER --}}
    <div class="retro-heading">
        <div>
            <div class="retro-eyebrow">
                <span class="retro-dot"></span>
                ACCESS CONTROL SYSTEM / 01
            </div>

            <h1>New Role<span class="retro-cursor">_</span></h1>

            <p>Daftarkan role baru untuk mengatur hak akses pengguna sistem.</p>
        </div>

        <a href="{{ route('super-admin.roles.index') }}" class="retro-back">
            <span>←</span> Kembali
        </a>
    </div>

    {{-- ERROR --}}
    @if($errors->any())
        <div class="retro-error">
            <div class="retro-error-icon">!</div>
            <div>
                <strong>INPUT ERROR DETECTED</strong>
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="retro-layout">

        {{-- FORM --}}
        <div class="retro-panel">

            <div class="retro-panel-head">
                <div>
                    <span class="retro-section-code">FORM / 001</span>
                    <h2>Role Configuration</h2>
                </div>
                <span class="retro-status">● NEW ENTRY</span>
            </div>

            <form method="POST" action="{{ route('super-admin.roles.store') }}">
                @csrf

                <div class="retro-form-body">

                    {{-- CODE --}}
                    <div class="retro-field">
                        <label for="code">
                            <span class="retro-number">01</span>
                            ROLE CODE <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ $oldCode }}"
                            placeholder="admin_gudang"
                            maxlength="50"
                            required
                            autocomplete="off"
                            spellcheck="false"
                        >

                        <div class="retro-hint">
                            <span>↳</span>
                            Gunakan huruf kecil, angka, dan underscore tanpa spasi.
                        </div>
                        @error('code')
                            <small class="retro-validation">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- NAME --}}
                    <div class="retro-field">
                        <label for="name">
                            <span class="retro-number">02</span>
                            ROLE NAME <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ $oldName }}"
                            placeholder="Admin Gudang"
                            maxlength="100"
                            required
                        >

                        <div class="retro-hint">
                            <span>↳</span>
                            Nama role yang akan ditampilkan pada sistem.
                        </div>
                        @error('name')
                            <small class="retro-validation">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="retro-field">
                        <label for="description">
                            <span class="retro-number">03</span>
                            DESCRIPTION
                            <span class="optional">OPTIONAL</span>
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            maxlength="500"
                            placeholder="Jelaskan tanggung jawab dan fungsi role ini..."
                        >{{ $oldDescription }}</textarea>

                        <div class="retro-textarea-footer">
                            <span class="retro-hint">
                                <span>↳</span>
                                Deskripsi membantu administrator mengenali fungsi role.
                            </span>
                            <span class="retro-counter" id="descriptionCount">0 / 500</span>
                        </div>
                        @error('description')
                            <small class="retro-validation">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="retro-form-footer">
                    <span class="retro-footer-note">
                        <span class="retro-live-dot"></span>
                        READY TO REGISTER
                    </span>

                    <div class="retro-actions">
                        <a href="{{ route('super-admin.roles.index') }}" class="retro-cancel">
                            Batal
                        </a>
                        <button type="submit" class="retro-submit">
                            <span>＋</span> Create Role
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- PREVIEW --}}
        <aside class="retro-preview">

            <div class="retro-preview-top">
                <span>LIVE PREVIEW</span>
                <span class="retro-preview-indicator">●</span>
            </div>

            <div class="retro-preview-content">
                <div class="retro-role-icon">R</div>

                <div class="retro-preview-label">ROLE IDENTIFICATION</div>
                <h3 id="previewName">{{ $oldName ?: 'New Role' }}</h3>

                <div class="retro-code-box">
                    <span>CODE //</span>
                    <strong id="previewCode">{{ $oldCode ?: 'role_code' }}</strong>
                </div>

                <div class="retro-preview-description" id="previewDescription">
                    {{ $oldDescription ?: 'Deskripsi role akan ditampilkan di sini.' }}
                </div>
            </div>

            <div class="retro-preview-bottom">
                <div>
                    <span>ACCESS STATUS</span>
                    <strong>NOT CONFIGURED</strong>
                </div>
                <div class="retro-preview-lock">⌑</div>
            </div>

            <div class="retro-tip">
                <span class="retro-tip-icon">i</span>
                <p>
                    Setelah role dibuat, lanjutkan pengaturan permission melalui
                    halaman manajemen role.
                </p>
            </div>
        </aside>

    </div>

    <div class="retro-bottom-line">
        <span>SIM-BANGUM / ROLE MANAGEMENT</span>
        <span>SECURE ADMINISTRATION PANEL © {{ date('Y') }}</span>
    </div>

</div>

<style>
.retro-role {
    --retro-ink: #26352c;
    --retro-muted: #788278;
    --retro-paper: #f4f2e9;
    --retro-card: #fffef8;
    --retro-line: #d9d9c9;
    --retro-green: #496d50;
    --retro-green-dark: #304b36;
    --retro-orange: #d18a4c;
    --retro-red: #b94f42;

    color: var(--text-main, #26352c);
    padding-bottom: 24px;
    font-family: inherit;
}

.retro-role * {
    box-sizing: border-box;
}

.retro-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 25px;
}

.retro-eyebrow,
.retro-section-code,
.retro-status,
.retro-preview-top,
.retro-preview-label,
.retro-code-box,
.retro-preview-bottom span,
.retro-preview-bottom strong,
.retro-bottom-line,
.retro-footer-note {
    font-family: "Courier New", monospace;
    letter-spacing: .7px;
}

.retro-eyebrow {
    display: flex;
    align-items: center;
    gap: 9px;
    color: var(--retro-green);
    font-size: 10px;
    font-weight: 800;
    margin-bottom: 9px;
}

.retro-dot,
.retro-live-dot {
    width: 7px;
    height: 7px;
    display: inline-block;
    border-radius: 50%;
    background: #78a16e;
    box-shadow: 0 0 0 3px rgba(120,161,110,.15);
}

.retro-heading h1 {
    font-size: clamp(25px, 3vw, 33px);
    font-weight: 850;
    letter-spacing: -1.2px;
    margin: 0;
    color: var(--text-main, var(--retro-ink));
}

.retro-cursor {
    color: var(--retro-orange);
    animation: retroBlink 1s steps(2, start) infinite;
}

@keyframes retroBlink {
    to { visibility: hidden; }
}

.retro-heading p {
    color: var(--retro-muted);
    font-size: 13px;
    margin: 7px 0 0;
}

.retro-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 13px;
    border: 1px solid var(--retro-line);
    border-radius: 5px;
    color: var(--text-main);
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    background: var(--bg-card, var(--retro-card));
    transition: .2s;
}

.retro-back:hover,
.retro-cancel:hover {
    border-color: var(--retro-green);
    color: var(--retro-green);
}

.retro-back span {
    font-size: 17px;
}

.retro-error {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
    border: 1px solid #e7b9ae;
    border-left: 4px solid var(--retro-red);
    background: rgba(185,79,66,.07);
    border-radius: 5px;
    margin-bottom: 18px;
    color: var(--text-main);
}

.retro-error-icon {
    width: 23px;
    height: 23px;
    display: grid;
    place-items: center;
    background: var(--retro-red);
    color: white;
    border-radius: 3px;
    font-weight: 900;
}

.retro-error strong {
    font-family: "Courier New", monospace;
    font-size: 11px;
    letter-spacing: .5px;
}

.retro-error ul {
    margin: 6px 0 0;
    padding-left: 17px;
    font-size: 12px;
    line-height: 1.7;
}

.retro-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(240px, .8fr);
    gap: 18px;
    align-items: start;
}

.retro-panel,
.retro-preview {
    border: 1px solid var(--retro-line);
    border-radius: 7px;
    background: var(--bg-card, var(--retro-card));
    overflow: hidden;
}

.retro-panel {
    box-shadow: 3px 3px 0 rgba(38,53,44,.06);
}

.retro-panel-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 19px 21px;
    background: var(--bg-soft, var(--retro-paper));
    border-bottom: 1px solid var(--retro-line);
}

.retro-section-code {
    display: block;
    color: var(--retro-muted);
    font-size: 9px;
    font-weight: 700;
    margin-bottom: 5px;
}

.retro-panel-head h2 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    color: var(--text-main);
}

.retro-status {
    font-size: 9px;
    font-weight: 800;
    color: var(--retro-green);
    background: rgba(73,109,80,.1);
    padding: 7px 9px;
    border: 1px solid rgba(73,109,80,.2);
    border-radius: 3px;
    white-space: nowrap;
}

.retro-form-body {
    padding: 23px 21px 12px;
}

.retro-field {
    margin-bottom: 23px;
}

.retro-field label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: "Courier New", monospace;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .5px;
    margin-bottom: 9px;
    color: var(--text-main);
}

.retro-number {
    display: inline-grid;
    place-items: center;
    width: 21px;
    height: 21px;
    background: var(--retro-green);
    color: #fff;
    border-radius: 3px;
    font-size: 10px;
}

.required {
    color: var(--retro-red);
}

.optional {
    font-size: 9px;
    font-weight: 700;
    color: var(--retro-muted);
    border: 1px solid var(--retro-line);
    border-radius: 3px;
    padding: 3px 5px;
    margin-left: auto;
}

.retro-field input,
.retro-field textarea {
    display: block;
    width: 100%;
    border: 1px solid var(--retro-line);
    border-radius: 4px;
    background: var(--bg-soft, var(--retro-paper));
    color: var(--text-main);
    padding: 12px 13px;
    font-family: inherit;
    font-size: 13px;
    outline: none;
    transition: .2s;
}

.retro-field input {
    height: 44px;
}

.retro-field textarea {
    resize: vertical;
    min-height: 105px;
    line-height: 1.6;
}

.retro-field input:focus,
.retro-field textarea:focus {
    border-color: var(--retro-green);
    box-shadow: 0 0 0 3px rgba(73,109,80,.12);
    background: var(--bg-card, #fff);
}

.retro-field input::placeholder,
.retro-field textarea::placeholder {
    color: #a1a69b;
}

.retro-hint {
    display: flex;
    gap: 6px;
    align-items: center;
    color: var(--retro-muted);
    font-size: 10px;
    margin-top: 8px;
    line-height: 1.5;
}

.retro-hint span {
    color: var(--retro-orange);
    font-weight: 900;
}

.retro-textarea-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
}

.retro-textarea-footer .retro-hint {
    flex: 1;
}

.retro-counter {
    font-family: "Courier New", monospace;
    font-size: 10px;
    color: var(--retro-muted);
}

.retro-validation {
    display: block;
    color: var(--retro-red);
    font-size: 11px;
    margin-top: 6px;
}

.retro-form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 15px 21px;
    background: var(--bg-soft, var(--retro-paper));
    border-top: 1px solid var(--retro-line);
}

.retro-footer-note {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 9px;
    font-weight: 800;
    color: var(--retro-muted);
}

.retro-live-dot {
    width: 6px;
    height: 6px;
}

.retro-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.retro-cancel,
.retro-submit {
    min-height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 15px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
}

.retro-cancel {
    color: var(--text-main);
    border: 1px solid var(--retro-line);
    background: var(--bg-card, #fff);
}

.retro-submit {
    color: #fff;
    background: var(--retro-green);
    border: 1px solid var(--retro-green);
    box-shadow: 2px 2px 0 var(--retro-green-dark);
}

.retro-submit:hover {
    background: var(--retro-green-dark);
    transform: translateY(-1px);
}

.retro-submit span {
    font-size: 17px;
    line-height: 1;
}

/* PREVIEW */
.retro-preview {
    background: var(--bg-soft, var(--retro-paper));
}

.retro-preview-top {
    display: flex;
    justify-content: space-between;
    padding: 13px 15px;
    font-size: 9px;
    font-weight: 800;
    color: var(--retro-muted);
    border-bottom: 1px dashed var(--retro-line);
}

.retro-preview-indicator {
    color: #78a16e;
}

.retro-preview-content {
    padding: 23px 18px 20px;
    text-align: center;
}

.retro-role-icon {
    width: 57px;
    height: 57px;
    display: grid;
    place-items: center;
    margin: 0 auto 16px;
    border: 1px solid var(--retro-green);
    background: rgba(73,109,80,.1);
    color: var(--retro-green);
    font-family: "Courier New", monospace;
    font-size: 27px;
    font-weight: 900;
    border-radius: 5px;
    box-shadow: 3px 3px 0 rgba(73,109,80,.2);
}

.retro-preview-label {
    font-size: 9px;
    color: var(--retro-muted);
    font-weight: 800;
}

.retro-preview-content h3 {
    font-size: 19px;
    font-weight: 850;
    margin: 8px 0 14px;
    overflow-wrap: anywhere;
    color: var(--text-main);
}

.retro-code-box {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 10px;
    background: var(--bg-card, #fff);
    border: 1px dashed var(--retro-line);
    border-radius: 4px;
    text-align: left;
}

.retro-code-box span {
    color: var(--retro-muted);
    font-size: 9px;
}

.retro-code-box strong {
    color: var(--retro-green);
    font-size: 12px;
    overflow-wrap: anywhere;
}

.retro-preview-description {
    margin-top: 14px;
    color: var(--retro-muted);
    font-size: 11px;
    line-height: 1.7;
    text-align: left;
    overflow-wrap: anywhere;
}

.retro-preview-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 13px 16px;
    border-top: 1px dashed var(--retro-line);
}

.retro-preview-bottom div:first-child {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.retro-preview-bottom span {
    font-size: 8px;
    color: var(--retro-muted);
}

.retro-preview-bottom strong {
    font-size: 10px;
    color: var(--retro-orange);
}

.retro-preview-lock {
    font-size: 22px;
    color: var(--retro-muted);
}

.retro-tip {
    display: flex;
    gap: 10px;
    padding: 13px 15px;
    background: rgba(209,138,76,.1);
    border-top: 1px solid rgba(209,138,76,.2);
}

.retro-tip-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: var(--retro-orange);
    color: #fff;
    font-family: Georgia, serif;
    font-weight: 900;
    font-size: 12px;
}

.retro-tip p {
    margin: 0;
    color: var(--text-main);
    font-size: 10px;
    line-height: 1.6;
}

.retro-bottom-line {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 17px;
    padding-top: 12px;
    border-top: 1px solid var(--retro-line);
    color: var(--retro-muted);
    font-size: 8px;
    font-weight: 700;
}

@media (max-width: 850px) {
    .retro-layout {
        grid-template-columns: 1fr;
    }

    .retro-preview {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .retro-preview-top {
        grid-column: 1 / -1;
    }

    .retro-preview-content {
        grid-row: span 2;
    }

    .retro-preview-bottom {
        border-top: 0;
        align-self: end;
    }

    .retro-tip {
        align-self: start;
    }
}

@media (max-width: 560px) {
    .retro-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .retro-panel-head,
    .retro-form-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .retro-actions {
        width: 100%;
    }

    .retro-cancel,
    .retro-submit {
        flex: 1;
    }

    .retro-preview {
        display: block;
    }

    .retro-preview-content {
        padding: 20px 16px;
    }

    .retro-bottom-line {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const codeInput = document.getElementById('code');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');

    const previewCode = document.getElementById('previewCode');
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    const descriptionCount = document.getElementById('descriptionCount');

    function updatePreview() {
        previewCode.textContent = codeInput.value.trim() || 'role_code';
        previewName.textContent = nameInput.value.trim() || 'New Role';
        previewDescription.textContent =
            descriptionInput.value.trim() || 'Deskripsi role akan ditampilkan di sini.';

        descriptionCount.textContent = descriptionInput.value.length + ' / 500';
    }

    codeInput.addEventListener('input', updatePreview);
    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);

    updatePreview();
});
</script>
@endsection
