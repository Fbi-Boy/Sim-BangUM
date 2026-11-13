@extends('layouts.app')
@section('title','Klasifikasi Arsip')
@section('content')
<div class="card">
<h3>Tambah Klasifikasi</h3>
<form method="POST" action="{{ route('admin.arsip.classifications.store') }}" style="margin-top:8px">@csrf
<input name="code" placeholder="Kode (mis: 005.2)" required style="width:150px;display:inline-block">
<input name="name" placeholder="Nama klasifikasi" required style="width:250px;display:inline-block">
<input name="retention_active_months" type="number" placeholder="Retensi (bulan)" style="width:130px;display:inline-block">
<button class="btn btn-sm">Simpan</button>
</form>
</div>
<div class="card">
<h3>Daftar Klasifikasi</h3>
<table style="margin-top:12px">
<tr><th>Kode</th><th>Nama</th><th>Retensi (bulan)</th><th>Jumlah Arsip</th></tr>
@forelse($items as $i)
<tr><td>{{ $i->code }}</td><td>{{ $i->name }}</td><td>{{ $i->retention_active_months ?? '-' }}</td><td>{{ $i->records_count }}</td></tr>
@empty<tr><td colspan="4">Belum ada klasifikasi.</td></tr>@endforelse
</table>
<div style="margin-top:12px">{{ $items->links() }}</div>
</div>
@endsection
