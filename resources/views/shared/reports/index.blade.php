@extends('layouts.app')
@section('title','Laporan & Rekap')
@section('content')
<h2 style="margin-bottom:16px">Ringkasan Sistem ‚Äî {{ now()->format('d F Y') }}</h2>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">

<div class="card">
<h3 style="color:#1e40af;margin-bottom:12px">Ì≥¨ Persuratan</h3>
<table style="font-size:13px">
<tr><td>Surat Masuk (total)</td><td align="right"><strong>{{ $data['letters']['incoming_total'] }}</strong></td></tr>
<tr><td>Surat Masuk (bulan ini)</td><td align="right">{{ $data['letters']['incoming_month'] }}</td></tr>
<tr><td>Surat Keluar (total)</td><td align="right"><strong>{{ $data['letters']['outgoing_total'] }}</strong></td></tr>
<tr><td>Surat Keluar (bulan ini)</td><td align="right">{{ $data['letters']['outgoing_month'] }}</td></tr>
</table>
</div>

<div class="card">
<h3 style="color:#1e40af;margin-bottom:12px">Ì≥¶ Inventaris Aset</h3>
<table style="font-size:13px">
<tr><td>Total Aset</td><td align="right"><strong>{{ $data['inventory']['assets_total'] }}</strong></td></tr>
<tr><td>Tersedia</td><td align="right">{{ $data['inventory']['assets_available'] }}</td></tr>
<tr><td>Dipinjam</td><td align="right">{{ $data['inventory']['assets_loaned'] }}</td></tr>
<tr><td>Pinjaman aktif</td><td align="right">{{ $data['inventory']['loans_active'] }}</td></tr>
<tr><td>Menunggu persetujuan</td><td align="right"><strong style="color:#ea580c">{{ $data['inventory']['loans_pending'] }}</strong></td></tr>
</table>
</div>

<div class="card">
<h3 style="color:#1e40af;margin-bottom:12px">Ì≥é ATK / Stok</h3>
<table style="font-size:13px">
<tr><td>Total Item</td><td align="right"><strong>{{ $data['supply']['items_total'] }}</strong></td></tr>
<tr><td>Stok Rendah</td><td align="right"><strong style="color:#dc2626">{{ $data['supply']['low_stock'] }}</strong></td></tr>
<tr><td>Permintaan pending</td><td align="right">{{ $data['supply']['requests_pending'] }}</td></tr>
</table>
</div>

<div class="card">
<h3 style="color:#1e40af;margin-bottom:12px">Ì∫ó Kendaraan</h3>
<table style="font-size:13px">
<tr><td>Total Kendaraan</td><td align="right"><strong>{{ $data['vehicles']['total'] }}</strong></td></tr>
<tr><td>Tersedia</td><td align="right">{{ $data['vehicles']['available'] }}</td></tr>
<tr><td>Permohonan pending</td><td align="right"><strong style="color:#ea580c">{{ $data['vehicles']['requests_pending'] }}</strong></td></tr>
<tr><td>Disetujui</td><td align="right">{{ $data['vehicles']['requests_approved'] }}</td></tr>
</table>
</div>

<div class="card">
<h3 style="color:#1e40af;margin-bottom:12px">ÔøΩÔøΩ Fasilitas</h3>
<table style="font-size:13px">
<tr><td>Booking pending</td><td align="right"><strong style="color:#ea580c">{{ $data['facilities']['bookings_pending'] }}</strong></td></tr>
<tr><td>Booking disetujui</td><td align="right">{{ $data['facilities']['bookings_approved'] }}</td></tr>
<tr><td>Booking bulan ini</td><td align="right">{{ $data['facilities']['bookings_month'] }}</td></tr>
</table>
</div>

<div class="card">
<h3 style="color:#1e40af;margin-bottom:12px">Ìæ≠ Kegiatan</h3>
<table style="font-size:13px">
<tr><td>Total Kegiatan</td><td align="right"><strong>{{ $data['events']['total'] }}</strong></td></tr>
<tr><td>Akan datang</td><td align="right">{{ $data['events']['upcoming'] }}</td></tr>
<tr><td>Bulan ini</td><td align="right">{{ $data['events']['this_month'] }}</td></tr>
</table>
</div>

<div class="card">
<h3 style="color:#1e40af;margin-bottom:12px">‚úàÔ∏è Perjalanan Dinas</h3>
<table style="font-size:13px">
<tr><td>Total</td><td align="right"><strong>{{ $data['travel']['total'] }}</strong></td></tr>
<tr><td>Pending</td><td align="right"><strong style="color:#ea580c">{{ $data['travel']['pending'] }}</strong></td></tr>
<tr><td>Disetujui</td><td align="right">{{ $data['travel']['approved'] }}</td></tr>
</table>
</div>

</div>
@endsection
