@extends('layouts.app')
@section('title','Contoh Tombol')
@section('content')
@php
    $variants = ['primary','secondary','soft','outline','ghost','danger','danger-soft','success','success-soft','warning','dark','link'];
    $sizes    = ['xs','sm','md','lg','xl'];
@endphp

<style>
    .demo-title { font-size: 13px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: var(--text-muted); margin: 0 0 14px; }
    .demo-row   { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
    .demo-sub   { font-size: 12px; color: var(--text-muted); width: 100%; margin: 6px 0 -4px; }
</style>

<div class="card">
    <h3 class="demo-title">Varian</h3>
    <div class="demo-row">
        @foreach($variants as $v)
            <x-button :variant="$v">{{ ucfirst($v) }}</x-button>
        @endforeach
    </div>
</div>

<div class="card">
    <h3 class="demo-title">Ukuran</h3>
    <div class="demo-row">
        @foreach($sizes as $s)
            <x-button :size="$s">{{ strtoupper($s) }}</x-button>
        @endforeach
    </div>
    <div class="demo-sub">Soft</div>
    <div class="demo-row">
        @foreach($sizes as $s)
            <x-button variant="soft" :size="$s">{{ strtoupper($s) }}</x-button>
        @endforeach
    </div>
    <div class="demo-sub">Outline</div>
    <div class="demo-row">
        @foreach($sizes as $s)
            <x-button variant="outline" :size="$s">{{ strtoupper($s) }}</x-button>
        @endforeach
    </div>
</div>

<div class="card">
    <h3 class="demo-title">Dengan ikon</h3>
    <div class="demo-row">
        <x-button>
            <x-slot name="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg></x-slot>
            Tambah Data
        </x-button>

        <x-button variant="secondary">
            <x-slot name="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></svg></x-slot>
            Unduh
        </x-button>

        <x-button variant="soft">
            Selanjutnya
            <x-slot name="trailing"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></x-slot>
        </x-button>

        <x-button variant="danger-soft">
            <x-slot name="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="m19 6-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></x-slot>
            Hapus
        </x-button>
    </div>

    <div class="demo-sub">Hanya ikon (semua ukuran)</div>
    <div class="demo-row">
        @foreach($sizes as $s)
            <x-button icon-only variant="soft" :size="$s" aria-label="Cari">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </x-button>
        @endforeach
        <x-button icon-only pill aria-label="Tambah">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        </x-button>
    </div>
</div>

<div class="card">
    <h3 class="demo-title">Status &amp; bentuk</h3>
    <div class="demo-row">
        <x-button :loading="true">Menyimpan</x-button>
        <x-button variant="secondary" :loading="true">Memuat</x-button>
        <x-button :disabled="true">Dinonaktifkan</x-button>
        <x-button variant="outline" :disabled="true">Dinonaktifkan</x-button>
        <x-button pill>Pill</x-button>
        <x-button pill variant="soft" size="sm">Pill kecil</x-button>
        <x-button href="#" variant="link">Tautan teks</x-button>
    </div>

    <div class="demo-sub">Lebar penuh</div>
    <div style="max-width:360px;display:grid;gap:10px">
        <x-button block size="lg">Masuk</x-button>
        <x-button block variant="secondary">Batal</x-button>
    </div>

    <div class="demo-sub">Grup tombol</div>
    <div class="sbtn-group">
        <x-button variant="secondary" size="sm">Hari</x-button>
        <x-button variant="secondary" size="sm">Minggu</x-button>
        <x-button variant="secondary" size="sm">Bulan</x-button>
    </div>

    <div class="demo-sub">Submit form dengan loading otomatis (klik untuk mencoba)</div>
    <form onsubmit="event.preventDefault(); return false;" class="demo-row">
        <x-button type="submit" submit-loading>Kirim Formulir</x-button>
    </form>
</div>
@endsection
