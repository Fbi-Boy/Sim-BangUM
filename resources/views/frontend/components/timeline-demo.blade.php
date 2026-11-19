@extends('layouts.app')
@section('title','Contoh Timeline')
@section('content')
@php
    $jadwal = [
        ['time' => '08:00', 'title' => 'Apel Pagi & Briefing',  'place' => 'Halaman Kantor',     'unit' => 'Seluruh Bagian'],
        ['time' => '10:00', 'title' => 'Verifikasi Surat Masuk', 'place' => 'Ruang Persuratan',   'unit' => 'Persuratan'],
        ['time' => '13:00', 'title' => 'Cek Stok ATK',           'place' => 'Gudang',             'unit' => 'Inventaris', 'desc' => 'Pencocokan stok fisik dengan catatan sistem.'],
        ['time' => '15:30', 'title' => 'Rapat Koordinasi',       'place' => 'Ruang Rapat Utama',  'unit' => 'Bagian Umum', 'duration' => 60],
    ];

    $langkah = [
        ['title' => 'Pengajuan dibuat',     'desc' => 'Pegawai mengisi formulir peminjaman aset.', 'state' => 'done'],
        ['title' => 'Verifikasi admin',     'desc' => 'Admin inventaris memeriksa ketersediaan aset.', 'state' => 'now'],
        ['title' => 'Persetujuan pejabat',  'desc' => 'Menunggu persetujuan Kasubbag.', 'state' => 'next'],
        ['title' => 'Aset diserahkan',      'desc' => 'Serah terima aset kepada pemohon.', 'state' => 'next'],
    ];
@endphp

<style>
    .demo-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; }
    .demo-note { font-size: 12px; color: var(--text-muted); margin-top: 14px; }
</style>

<div class="demo-grid">
    <div class="card">
        <x-timeline :items="$jadwal" title="Jadwal Hari Ini" />
        <p class="demo-note">Status otomatis dari jam sekarang.</p>
    </div>

    <div class="card">
        <x-timeline :items="$jadwal" :date="now()->subDay()" title="Jadwal Kemarin" />
        <p class="demo-note">Tanggal lampau: semua selesai.</p>
    </div>

    <div class="card">
        <x-timeline :items="$jadwal" :date="now()->addDay()" title="Jadwal Besok" />
        <p class="demo-note">Tanggal depan: semua berikutnya.</p>
    </div>

    <div class="card">
        <x-timeline :items="$langkah" numbered :show-time="false" :auto="false" title="Alur Peminjaman Aset" unit="langkah" />
        <p class="demo-note">Mode langkah demi langkah, status manual.</p>
    </div>

    <div class="card">
        <x-timeline :items="[]" title="Jadwal Kosong" empty-text="Belum ada jadwal untuk hari ini." />
    </div>
</div>
@endsection
