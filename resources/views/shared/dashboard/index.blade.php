@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h2 style="margin-bottom:16px">Selamat datang, {{ auth()->user()->name }}</h2>
<p style="color:#6b7280;margin-bottom:24px">Role aktif: <strong>{{ auth()->user()->roles->pluck('name')->join(', ') ?: '-' }}</strong></p>

<div class="grid">
@if(auth()->user()?->hasRole('super_admin'))
    <a href="{{ route('admin.persuratan.incoming-letters.index') }}"><strong>Surat Masuk</strong><small>Registrasi & disposisi</small></a>
    <a href="{{ route('admin.persuratan.outgoing-letters.index') }}"><strong>Surat Keluar</strong><small>Draft & kirim</small></a>
    <a href="{{ route('admin.inventaris.assets.index') }}"><strong>Inventaris Aset</strong><small>Data barang tahan lama</small></a>
    <a href="{{ route('admin.inventaris.loans.index') }}"><strong>Peminjaman Aset</strong><small>Setujui & kembali</small></a>
    <a href="{{ route('admin.inventaris.supply-items.index') }}"><strong>Item ATK</strong><small>Master & stok</small></a>
    <a href="{{ route('admin.inventaris.stock-requests.index') }}"><strong>Permintaan ATK</strong><small>Setujui & keluarkan</small></a>
    <a href="{{ route('admin.kendaraan.vehicles.index') }}"><strong>Kendaraan</strong><small>Data kendaraan dinas</small></a>
    <a href="{{ route('admin.kendaraan.requests.index') }}"><strong>Permohonan Kendaraan</strong><small>Setujui & tolak</small></a>
    <a href="{{ route('admin.fasilitas.rooms.index') }}"><strong>Ruangan</strong><small>Data ruang rapat</small></a>
    <a href="{{ route('admin.fasilitas.bookings.index') }}"><strong>Booking Ruangan</strong><small>Setujui & deteksi bentrok</small></a>
@endif
    <a href="{{ route('admin.protokol.events.index') }}"><strong>Kegiatan & Protokol</strong><small>Agenda, rundown, checklist</small></a>

    <a href="{{ route('admin.perjalanan-dinas.requests.index') }}"><strong>Perjalanan Dinas</strong><small>Setujui permohonan</small></a>

    <a href="{{ route('reports.index') }}"><strong>Laporan Sistem</strong><small>Rekap semua modul</small></a>

    <a href="{{ route('pegawai.asset-loans.index') }}"><strong>Pinjam Aset</strong><small>Ajukan peminjaman</small></a>
    <a href="{{ route('pegawai.vehicle-requests.index') }}"><strong>Minta Kendaraan</strong><small>Permohonan kendaraan</small></a>
    <a href="{{ route('pegawai.room-bookings.index') }}"><strong>Booking Ruangan</strong><small>Ajukan ruangan</small></a>
    <a href="{{ route('pegawai.stock-requests.index') }}"><strong>Minta ATK</strong><small>Permintaan alat tulis</small></a>
</div>
@endsection
