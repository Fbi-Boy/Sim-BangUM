
@extends('layouts.app')
@section('title','Atur Role User')
@section('content')

@php
    $selectedRoles = old('roles', $user->roles->pluck('id')->toArray());

    $roleTone = function ($name) {
        $name = strtolower($name);

        return match (true) {
            $name === 'super_admin' => 'rose',
            str_starts_with($name, 'admin') => 'blue',
            in_array($name, ['kepala_bagian', 'kasubbag']) => 'violet',
            $name === 'pegawai' => 'green',
            $name === 'pengemudi' => 'orange',
            $name === 'auditor' => 'teal',
            default => 'slate',
        };
    };

    $avatarInitials = collect(
        preg_split('/\s+/', trim((string) $user->name))
    )->filter()->take(2)->map(
        fn($word) => mb_substr($word, 0, 1)
    )->join('');

    $avatarTones = ['a1','a2','a3','a4','a5','a6'];
    $avatarTone = $avatarTones[abs(crc32((string) $user->email)) % count($avatarTones)];
@endphp

<style>
    /* =========================================
       USER ROLE EDIT · SECURITY MANAGEMENT
    ========================================= */

    .ure {
        --mod: #4f46e5;
        --mod-2: #818cf8;
        --mod-soft: rgba(99,102,241,.12);
        --mod-border: rgba(99,102,241,.25);
        color: var(--text-main);
    }

    body.dark-mode .ure {
        --mod: #818cf8;
        --mod-soft: rgba(129,140,248,.16);
        --mod-border: rgba(129,140,248,.28);
    }

    .ure * { box-sizing: border-box; }

    /* HERO */

    .ure-hero {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 17px;
        padding: 25px 27px;
        min-height: 150px;
        margin-bottom: 18px;
        border-radius: 19px;
        color: #fff;
        background: linear-gradient(145deg,#1e1b4b 0%,#4338ca 58%,#818cf8 100%);
        box-shadow: 0 14px 34px rgba(67,56,202,.24);
    }

    .ure-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        opacity: .5;
        background-image: radial-gradient(rgba(255,255,255,.16) 1px,transparent 1px);
        background-size: 18px 18px;
        pointer-events: none;
    }

    .ure-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        right: 7%;
        top: -115px;
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 50%;
        box-shadow:
            0 0 0 22px rgba(255,255,255,.035),
            0 0 0 48px rgba(255,255,255,.025);
        pointer-events: none;
    }

    .ure-hero-icon {
        position: relative;
        z-index: 1;
        width: 57px;
        height: 57px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 17px;
        background: rgba(255,255,255,.16);
        backdrop-filter: blur(5px);
    }

    .ure-hero-icon svg {
        width: 28px;
        height: 28px;
    }

    .ure-hero-content {
        position: relative;
        z-index: 1;
        min-width: 0;
    }

    .ure-breadcrumb {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 6px;
        font-size: 11px;
        opacity: .8;
    }

    .ure-breadcrumb a {
        color: #fff;
        text-decoration: none;
    }

    .ure-breadcrumb a:hover { text-decoration: underline; }

    .ure-hero h2 {
        margin: 0;
        color: #fff;
        font-size: 22px;
        font-weight: 750;
        letter-spacing: -.3px;
    }

    .ure-hero p {
        margin: 5px 0 0;
        color: rgba(255,255,255,.82);
        font-size: 12px;
    }

    /* USER PROFILE */

    .ure-profile {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 17px 19px;
        margin-bottom: 18px;
        border: 1px solid var(--border-color);
        border-radius: 15px;
        background: var(--bg-card);
    }

    .ure-avatar {
        width: 49px;
        height: 49px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 15px;
        color: white;
        font-size: 15px;
        font-weight: 750;
        box-shadow: 0 6px 15px rgba(0,0,0,.12);
    }

    .ure-avatar.a1 { background: linear-gradient(135deg,#4338ca,#818cf8); }
    .ure-avatar.a2 { background: linear-gradient(135deg,#0f766e,#2dd4bf); }
    .ure-avatar.a3 { background: linear-gradient(135deg,#be123c,#fb7185); }
    .ure-avatar.a4 { background: linear-gradient(135deg,#c2410c,#fb923c); }
    .ure-avatar.a5 { background: linear-gradient(135deg,#0369a1,#38bdf8); }
    .ure-avatar.a6 { background: linear-gradient(135deg,#6d28d9,#c084fc); }

    .ure-profile-info {
        flex: 1;
        min-width: 0;
    }

    .ure-profile-name {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
    }

    .ure-profile-email {
        margin-top: 4px;
        color: var(--text-muted);
        font-size: 11.5px;
        overflow-wrap: anywhere;
    }

    .ure-user-tag {
        padding: 3px 8px;
        border-radius: 20px;
        background: var(--mod-soft);
        color: var(--mod);
        font-size: 9px;
        font-weight: 750;
    }

    .ure-profile-meta {
        text-align: right;
        flex-shrink: 0;
    }

    .ure-profile-meta small {
        display: block;
        color: var(--text-muted);
        font-size: 10px;
        margin-bottom: 4px;
    }

    .ure-profile-meta strong {
        color: var(--mod);
        font-size: 20px;
    }

    /* MAIN CARD */

    .ure-card {
        overflow: hidden;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        background: var(--bg-card);
        margin-bottom: 17px;
    }

    .ure-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 17px 19px;
        border-bottom: 1px solid var(--border-color);
    }

    .ure-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ure-card-title-icon {
        width: 35px;
        height: 35px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 10px;
        background: var(--mod-soft);
        color: var(--mod);
    }

    .ure-card-title-icon svg {
        width: 18px;
        height: 18px;
    }

    .ure-card-title h3 {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-main);
    }

    .ure-card-title p {
        margin: 3px 0 0;
        color: var(--text-muted);
        font-size: 10.5px;
    }

    .ure-counter {
        padding: 6px 10px;
        border: 1px solid var(--mod-border);
        border-radius: 20px;
        color: var(--mod);
        background: var(--mod-soft);
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* TOOLBAR */

    .ure-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        padding: 13px 19px;
        border-bottom: 1px dashed var(--border-color);
    }

    .ure-search {
        flex: 1;
        min-width: 200px;
        height: 38px;
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 0 11px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background: var(--bg-soft);
        color: var(--text-muted);
    }

    .ure-search:focus-within {
        border-color: var(--mod);
        box-shadow: 0 0 0 3px var(--mod-soft);
    }

    .ure-search svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .ure-search input {
        width: 100%;
        min-width: 0;
        border: 0;
        outline: 0;
        background: transparent;
        color: var(--text-main);
        font-size: 11.5px;
    }

    .ure-tool-btn {
        height: 38px;
        padding: 0 12px;
        border: 1px solid var(--border-color);
        border-radius: 9px;
        background: var(--bg-card);
        color: var(--text-main);
        font-size: 10.5px;
        font-weight: 650;
        cursor: pointer;
        transition: .2s;
    }

    .ure-tool-btn:hover {
        border-color: var(--mod);
        color: var(--mod);
        background: var(--mod-soft);
    }

    /* ROLE GRID */

    .ure-role-list {
        display: grid;
        grid-template-columns: repeat(2,minmax(0,1fr));
        gap: 11px;
        padding: 18px;
    }

    .ure-role-option {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-width: 0;
        padding: 14px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: var(--bg-card);
        cursor: pointer;
        transition: border-color .2s, background .2s, box-shadow .2s, transform .2s;
    }

    .ure-role-option:hover {
        border-color: var(--mod-2);
        background: var(--mod-soft);
        transform: translateY(-2px);
    }

    .ure-role-option:has(input:checked) {
        border-color: var(--mod);
        background: var(--mod-soft);
        box-shadow: 0 0 0 1px var(--mod-border);
    }

    .ure-checkbox {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
        margin: 2px 0 0;
        accent-color: var(--mod);
        cursor: pointer;
    }

    .ure-role-content {
        flex: 1;
        min-width: 0;
    }

    .ure-role-name {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 6px;
        color: var(--text-main);
        font-size: 12px;
        font-weight: 750;
    }

    .ure-role-code {
        display: inline-flex;
        padding: 3px 7px;
        border-radius: 5px;
        background: var(--bg-soft);
        color: var(--text-muted);
        font-size: 9px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .ure-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        width: fit-content;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 750;
    }

    .ure-role-badge::before {
        content: "";
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }

    .ure-role-badge.rose   { background:rgba(244,63,94,.13); color:#e11d48; }
    .ure-role-badge.blue   { background:rgba(59,130,246,.13); color:#2563eb; }
    .ure-role-badge.violet { background:rgba(139,92,246,.14); color:#7c3aed; }
    .ure-role-badge.green  { background:rgba(16,185,129,.13); color:#059669; }
    .ure-role-badge.orange { background:rgba(249,115,22,.14); color:#c2410c; }
    .ure-role-badge.teal   { background:rgba(20,184,166,.14); color:#0f766e; }
    .ure-role-badge.slate  { background:rgba(100,116,139,.14); color:#475569; }

    body.dark-mode .ure-role-badge.rose   { color:#fb7185; }
    body.dark-mode .ure-role-badge.blue   { color:#60a5fa; }
    body.dark-mode .ure-role-badge.violet { color:#c4b5fd; }
    body.dark-mode .ure-role-badge.green  { color:#34d399; }
    body.dark-mode .ure-role-badge.orange { color:#fb923c; }
    body.dark-mode .ure-role-badge.teal   { color:#2dd4bf; }
    body.dark-mode .ure-role-badge.slate  { color:#cbd5e1; }

    .ure-role-option[hidden] { display: none !important; }

    .ure-no-result {
        display: none;
        grid-column: 1/-1;
        padding: 30px 15px;
        text-align: center;
        color: var(--text-muted);
        font-size: 11px;
    }

    .ure-no-result.show { display: block; }

    /* SELECTED SUMMARY */

    .ure-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
        padding: 15px 19px;
        border-top: 1px solid var(--border-color);
        background: var(--bg-soft);
    }

    .ure-summary-label {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .ure-summary-icon {
        width: 33px;
        height: 33px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        background: var(--mod-soft);
        color: var(--mod);
    }

    .ure-summary-icon svg {
        width: 17px;
        height: 17px;
    }

    .ure-summary-label strong {
        display: block;
        font-size: 11px;
    }

    .ure-summary-label small {
        display: block;
        margin-top: 3px;
        color: var(--text-muted);
        font-size: 9.5px;
    }

    .ure-summary-count {
        color: var(--mod);
        font-size: 20px;
        font-weight: 800;
    }

    /* FOOTER ACTIONS */

    .ure-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 19px;
        border-top: 1px solid var(--border-color);
    }

    .ure-footer-note {
        color: var(--text-muted);
        font-size: 10px;
        line-height: 1.6;
    }

    .ure-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .ure-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 39px;
        padding: 0 15px;
        border: 1px solid var(--border-color);
        border-radius: 9px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .ure-btn svg {
        width: 15px;
        height: 15px;
    }

    .ure-btn-secondary {
        background: var(--bg-soft);
        color: var(--text-main);
    }

    .ure-btn-secondary:hover {
        border-color: var(--mod);
        color: var(--mod);
    }

    .ure-btn-primary {
        border-color: transparent;
        background: linear-gradient(135deg,#4338ca,#6366f1);
        color: #fff;
        box-shadow: 0 5px 13px rgba(79,70,229,.2);
    }

    .ure-btn-primary:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 17px rgba(79,70,229,.25);
    }

    .ure-btn-primary:disabled {
        opacity: .55;
        cursor: not-allowed;
        transform: none;
    }

    /* WARNING */

    .ure-warning {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 15px;
        margin-bottom: 17px;
        border: 1px solid rgba(245,158,11,.25);
        border-radius: 11px;
        background: rgba(245,158,11,.08);
        color: var(--text-main);
    }

    .ure-warning svg {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
        color: #d97706;
    }

    .ure-warning strong {
        display: block;
        font-size: 10.5px;
        margin-bottom: 3px;
    }

    .ure-warning p {
        margin: 0;
        color: var(--text-muted);
        font-size: 10px;
        line-height: 1.6;
    }

    @media(max-width:650px) {
        .ure-role-list { grid-template-columns: 1fr; }
        .ure-hero { padding: 20px; }
        .ure-profile { align-items: flex-start; }
        .ure-profile-meta { margin-left: auto; }
    }

    @media(max-width:480px) {
        .ure-hero-icon { width: 45px; height: 45px; }
        .ure-hero h2 { font-size: 18px; }
        .ure-toolbar { align-items: stretch; }
        .ure-search { min-width: 100%; }
        .ure-tool-btn { flex: 1; }
        .ure-footer { align-items: stretch; }
        .ure-actions { width: 100%; }
        .ure-actions .ure-btn { flex: 1; }
        .ure-profile-meta { display: none; }
    }

    @media(prefers-reduced-motion:reduce) {
        .ure *, .ure *::before, .ure *::after {
            transition: none !important;
            animation: none !important;
        }
    }
</style>

<div class="ure">

    {{-- HERO --}}
    <div class="ure-hero">
        <div class="ure-hero-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>
        </div>

        <div class="ure-hero-content">
            <div class="ure-breadcrumb">
                <a href="{{ route('super-admin.users.index') }}">Kelola User</a>
                <span>/</span>
                <span>Atur Role</span>
            </div>

            <h2>Pengaturan Hak Akses</h2>
            <p>Kelola role dan kewenangan pengguna dalam sistem SIM-BangUM.</p>
        </div>
    </div>

    {{-- VALIDATION ERROR --}}
    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:17px;">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul style="margin:7px 0 0 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- USER PROFILE --}}
    <div class="ure-profile">
        <div class="ure-avatar {{ $avatarTone }}">
            {{ $avatarInitials ?: '?' }}
        </div>

        <div class="ure-profile-info">
            <div class="ure-profile-name">
                {{ $user->name }}

                @if($user->id === auth()->id())
                    <span class="ure-user-tag">Akun Anda</span>
                @endif
            </div>

            <div class="ure-profile-email">{{ $user->email }}</div>
        </div>

        <div class="ure-profile-meta">
            <small>Role saat ini</small>
            <strong id="ureSelectedCount">{{ count($selectedRoles) }}</strong>
        </div>
    </div>

    {{-- WARNING --}}
    <div class="ure-warning">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m10.3 3.9-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3.1l-8-14a2 2 0 0 0-3.4 0z"/>
            <path d="M12 9v4M12 17h.01"/>
        </svg>
        <div>
            <strong>Perhatian: perubahan hak akses</strong>
            <p>Role yang dipilih akan menentukan menu dan kewenangan yang dapat diakses pengguna. Pastikan setiap role diberikan sesuai tanggung jawabnya.</p>
        </div>
    </div>

    {{-- ROLE FORM --}}
    <form method="POST"
        action="{{ route('super-admin.users.roles.update', $user) }}"
        id="ureForm">

        @csrf
        @method('PUT')

        <div class="ure-card">

            {{-- CARD HEADER --}}
            <div class="ure-card-head">
                <div class="ure-card-title">
                    <div class="ure-card-title-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="7.5" cy="15.5" r="4.5"/>
                            <path d="m10.7 12.3 9.8-9.8M16 7l3 3M14 9l2 2"/>
                        </svg>
                    </div>

                    <div>
                        <h3>Daftar Role Pengguna</h3>
                        <p>Pilih satu atau beberapa role yang akan diberikan.</p>
                    </div>
                </div>

                <span class="ure-counter">
                    {{ $roles->count() }} Role tersedia
                </span>
            </div>

            {{-- TOOLBAR --}}
            <div class="ure-toolbar">
                <label class="ure-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="text" id="ureSearch"
                        placeholder="Cari nama atau kode role..."
                        autocomplete="off">
                </label>

                <button type="button" class="ure-tool-btn" id="ureSelectAll">
                    Pilih semua
                </button>

                <button type="button" class="ure-tool-btn" id="ureClearAll">
                    Hapus pilihan
                </button>
            </div>

            {{-- ROLE OPTIONS --}}
            <div class="ure-role-list" id="ureRoleList">

                @forelse($roles as $r)
                    @php
                        $tone = $roleTone($r->name);
                        $isChecked = in_array($r->id, $selectedRoles);
                    @endphp

                    <label class="ure-role-option"
                        data-role-option
                        data-search="{{ strtolower($r->name.' '.$r->code) }}">

                        <input
                            type="checkbox"
                            class="ure-checkbox"
                            name="roles[]"
                            value="{{ $r->id }}"
                            {{ $isChecked ? 'checked' : '' }}
                        >

                        <div class="ure-role-content">
                            <div class="ure-role-name">
                                {{ \Illuminate\Support\Str::headline($r->name) }}
                            </div>

                            <div class="ure-role-code">
                                {{ $r->code }}
                            </div>

                            <div style="margin-top:9px;">
                                <span class="ure-role-badge {{ $tone }}">
                                    {{ \Illuminate\Support\Str::headline($r->name) }}
                                </span>
                            </div>
                        </div>

                        <svg class="ure-check-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="var(--mod)"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="width:16px;height:16px;flex-shrink:0;opacity:{{ $isChecked ? '1' : '0' }};">
                            <path d="m5 12 4 4L19 6"/>
                        </svg>
                    </label>

                @empty
                    <div class="ure-no-result show">
                        Belum ada role yang tersedia.
                    </div>
                @endforelse

                <div class="ure-no-result" id="ureNoResult">
                    Tidak ada role yang cocok dengan pencarian.
                </div>
            </div>

            {{-- SELECTED SUMMARY --}}
            <div class="ure-summary">
                <div class="ure-summary-label">
                    <div class="ure-summary-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 7h-9M14 17H5"/>
                            <circle cx="17" cy="17" r="3"/>
                            <circle cx="7" cy="7" r="3"/>
                        </svg>
                    </div>

                    <div>
                        <strong>Role yang dipilih</strong>
                        <small>Jumlah hak akses yang akan disimpan</small>
                    </div>
                </div>

                <div class="ure-summary-count">
                    <span id="ureSummaryCount">0</span>
                    <small style="font-size:10px;color:var(--text-muted);font-weight:500;">role</small>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="ure-footer">
                <div class="ure-footer-note">
                    Perubahan akan diterapkan setelah tombol simpan ditekan.
                </div>

                <div class="ure-actions">
                    <a href="{{ route('super-admin.users.index') }}"
                        class="ure-btn ure-btn-secondary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                        Kembali
                    </a>

                    <button type="submit" class="ure-btn ure-btn-primary" id="ureSubmit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <path d="M17 21v-8H7v8M7 3v5h8"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    const root = document.querySelector('.ure');
    if (!root) return;

    const search = root.querySelector('#ureSearch');
    const options = Array.from(root.querySelectorAll('[data-role-option]'));
    const checkboxes = options.map(option => option.querySelector('input[type="checkbox"]'));
    const noResult = root.querySelector('#ureNoResult');

    const summaryCount = root.querySelector('#ureSummaryCount');
    const profileCount = root.querySelector('#ureSelectedCount');

    const selectAll = root.querySelector('#ureSelectAll');
    const clearAll = root.querySelector('#ureClearAll');
    const form = root.querySelector('#ureForm');

    function updateSummary() {
        const selected = checkboxes.filter(input => input.checked).length;

        summaryCount.textContent = selected;
        profileCount.textContent = selected;

        options.forEach(option => {
            const checkbox = option.querySelector('input[type="checkbox"]');
            const icon = option.querySelector('.ure-check-icon');

            if (icon) {
                icon.style.opacity = checkbox.checked ? '1' : '0';
            }
        });
    }

    function filterRoles() {
        const keyword = search.value.trim().toLowerCase();
        let visible = 0;

        options.forEach(option => {
            const text = option.dataset.search || '';
            const matched = text.includes(keyword);

            option.hidden = !matched;

            if (matched) visible++;
        });

        noResult.classList.toggle('show', visible === 0);
    }

    checkboxes.forEach(input => {
        input.addEventListener('change', updateSummary);
    });

    search.addEventListener('input', filterRoles);

    selectAll.addEventListener('click', function () {
        options.forEach(option => {
            if (!option.hidden) {
                option.querySelector('input[type="checkbox"]').checked = true;
            }
        });

        updateSummary();
    });

    clearAll.addEventListener('click', function () {
        checkboxes.forEach(input => {
            input.checked = false;
        });

        updateSummary();
    });

    form.addEventListener('submit', function (event) {
        const selected = checkboxes.filter(input => input.checked);

        if (selected.length === 0) {
            event.preventDefault();
            alert('Pilih minimal satu role sebelum menyimpan perubahan.');
            return;
        }

        const confirmed = confirm(
            'Simpan perubahan role untuk ' +
            @json($user->name) +
            '?\n\nJumlah role yang dipilih: ' + selected.length
        );

        if (!confirmed) {
            event.preventDefault();
        }
    });

    updateSummary();
})();
</script>
@endsection
