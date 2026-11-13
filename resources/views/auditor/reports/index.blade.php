@extends('layouts.app')
@section('title','Laporan Auditor')
@section('content')
<h2 style="margin-bottom:16px">Pilih Laporan</h2>
<div class="grid">
<a href="{{ route('auditor.reports.correspondence') }}"><strong>í³¬ Persuratan</strong><small>Surat masuk & keluar</small></a>
<a href="{{ route('auditor.reports.loans') }}"><strong>í³¦ Peminjaman Aset</strong><small>Riwayat pinjam</small></a>
<a href="{{ route('auditor.reports.stock') }}"><strong>í³ Permintaan ATK</strong><small>Riwayat ATK</small></a>
<a href="{{ route('auditor.reports.vehicles') }}"><strong>íº— Kendaraan</strong><small>Permohonan kendaraan</small></a>
<a href="{{ route('auditor.reports.bookings') }}"><strong>í¿¢ Booking Ruangan</strong><small>Riwayat booking</small></a>
<a href="{{ route('auditor.reports.travel') }}"><strong>âœˆï¸ Perjalanan Dinas</strong><small>Riwayat travel</small></a>
</div>
@endsection
