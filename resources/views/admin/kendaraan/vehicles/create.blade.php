
@extends('layouts.app')

@section('title', 'Tambah Kendaraan')

@section('content')
<div class="vehicle-page">
    <div class="vehicle-heading">
        <div>
            <a href="{{ route('admin.kendaraan.vehicles.index') }}" class="vehicle-back">
                ← Kembali ke daftar kendaraan
            </a>
            <h1>Tambah Kendaraan</h1>
            <p>Daftarkan kendaraan dinas ke dalam sistem inventaris.</p>
        </div>
        <div class="vehicle-icon">▰</div>
    </div>

    @if($errors->any())
        <div class="vehicle-alert">
            <strong>Data belum dapat disimpan.</strong>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.kendaraan.vehicles.store') }}">
        @csrf

        <div class="vehicle-card">
            <div class="vehicle-card-heading">
                <h3>Identitas Kendaraan</h3>
                <span>* Wajib diisi</span>
            </div>

            <div class="vehicle-fields">
                <div class="vehicle-field vehicle-full">
                    <label>Nomor Polisi <span>*</span></label>
                    <input type="text" name="license_plate" required
                        value="{{ old('license_plate') }}" placeholder="Contoh: P 1234 AB">
                </div>

                <div class="vehicle-field">
                    <label>Merek</label>
                    <input type="text" name="make"
                        value="{{ old('make') }}" placeholder="Contoh: Toyota">
                </div>

                <div class="vehicle-field">
                    <label>Model</label>
                    <input type="text" name="model"
                        value="{{ old('model') }}" placeholder="Contoh: Avanza">
                </div>

                <div class="vehicle-field">
                    <label>Tahun Pembuatan</label>
                    <input type="number" name="manufacture_year"
                        value="{{ old('manufacture_year') }}" min="1900" max="2100"
                        placeholder="Contoh: 2022">
                </div>

                <div class="vehicle-field">
                    <label>Kapasitas Penumpang</label>
                    <input type="number" name="passenger_capacity"
                        value="{{ old('passenger_capacity') }}" min="1"
                        placeholder="Contoh: 7">
                    <small>Jumlah maksimal penumpang kendaraan.</small>
                </div>
            </div>
        </div>

        <div class="vehicle-actions">
            <a href="{{ route('admin.kendaraan.vehicles.index') }}" class="vehicle-btn vehicle-secondary">
                Batal
            </a>
            <button type="submit" class="vehicle-btn vehicle-primary">
                Simpan Kendaraan
            </button>
        </div>
    </form>
</div>

<style>
.vehicle-page {
    --vehicle-accent:#b88738;
    --vehicle-soft:rgba(184,135,56,.11);
    max-width:850px; margin:0 auto; color:var(--text-main);
}
.vehicle-heading { display:flex; justify-content:space-between; align-items:center; gap:15px; margin-bottom:22px; }
.vehicle-back { display:block; text-decoration:none; font-size:13px; color:var(--text-muted,#84909d); margin-bottom:12px; }
.vehicle-back:hover { color:var(--vehicle-accent); }
.vehicle-heading h1 { font-size:24px; margin:0 0 6px; }
.vehicle-heading p { margin:0; font-size:13px; color:var(--text-muted,#84909d); }
.vehicle-icon { width:52px; height:52px; display:grid; place-items:center; border-radius:14px; background:var(--vehicle-soft); color:var(--vehicle-accent); font-size:24px; }
.vehicle-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:14px; padding:23px; }
.vehicle-card-heading { display:flex; justify-content:space-between; align-items:center; margin-bottom:22px; }
.vehicle-card-heading h3 { margin:0; font-size:16px; }
.vehicle-card-heading span { color:var(--text-muted,#84909d); font-size:11px; }
.vehicle-fields { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:19px; }
.vehicle-full { grid-column:1/-1; }
.vehicle-field { display:flex; flex-direction:column; gap:8px; min-width:0; }
.vehicle-field label { font-size:13px; font-weight:600; }
.vehicle-field label span { color:#dc6262; }
.vehicle-field input { width:100%; box-sizing:border-box; min-height:43px; padding:10px 12px; border:1px solid var(--border-color); border-radius:8px; background:var(--bg-soft); color:var(--text-main); font:inherit; font-size:13px; }
.vehicle-field input:focus { outline:none; border-color:var(--vehicle-accent); box-shadow:0 0 0 3px var(--vehicle-soft); }
.vehicle-field small { color:var(--text-muted,#84909d); font-size:11px; }
.vehicle-alert { padding:14px 17px; margin-bottom:18px; border-radius:10px; background:rgba(220,38,38,.08); border:1px solid rgba(220,38,38,.25); color:var(--text-main); font-size:13px; }
.vehicle-alert ul { margin:8px 0 0 18px; color:#dc6262; }
.vehicle-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; }
.vehicle-btn { display:inline-flex; justify-content:center; align-items:center; min-height:41px; padding:0 17px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600; cursor:pointer; border:1px solid transparent; }
.vehicle-secondary { background:var(--bg-card); border-color:var(--border-color); color:var(--text-main); }
.vehicle-primary { background:var(--vehicle-accent); color:white; }
@media(max-width:600px) { .vehicle-fields { grid-template-columns:1fr; } .vehicle-full { grid-column:auto; } .vehicle-card { padding:17px; } .vehicle-heading h1 { font-size:21px; } }
</style>
@endsection
