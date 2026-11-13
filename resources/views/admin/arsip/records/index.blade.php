@extends('layouts.app')
@section('title','Arsip')
@section('content')
<a href="{{ route('admin.arsip.records.create') }}" class="btn">+ Daftarkan Arsip</a>
<a href="{{ route('admin.arsip.classifications.index') }}" class="btn" style="background:#6b7280;margin-left:8px">Klasifikasi</a>
<table style="margin-top:16px">
<tr><th>Kode</th><th>Judul</th><th>Klasifikasi</th><th>Tanggal</th><th>Status</th><th></th></tr>
@forelse($records as $r)
<tr><td>{{ $r->archive_code }}</td><td>{{ $r->title }}</td><td>{{ $r->classification->code }} — {{ $r->classification->name }}</td>
<td>{{ $r->record_date?->format('d-m-Y') ?? '-' }}</td><td>{{ $r->archive_status }}</td>
<td><a href="{{ route('admin.arsip.records.show',$r) }}" class="btn btn-sm">Detail</a></td></tr>
@empty<tr><td colspan="6">Belum ada arsip.</td></tr>@endforelse
</table>
<div style="margin-top:16px">{{ $records->links() }}</div>
@endsection
