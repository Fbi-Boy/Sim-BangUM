<?php
use Illuminate\Support\Facades\Route;
Route::get('/', fn () => auth()->check() ? redirect()->route('dashboard') : redirect()->route('login'));
require __DIR__.'/auth.php';
Route::middleware(['auth'])->group(function () {
    require __DIR__.'/shared.php';
    require __DIR__.'/pegawai.php';
    require __DIR__.'/pejabat.php';
    require __DIR__.'/pengemudi.php';
    require __DIR__.'/auditor.php';
});
require __DIR__.'/super-admin.php';
foreach ([
    ['admin.persuratan','admin/persuratan','super_admin,admin_persuratan'],
    ['admin.barang-daerah','admin/barang-daerah','super_admin,pengurus_bmd'],
    ['admin.arsip','admin/arsip','super_admin,petugas_arsip'],
    ['admin.inventaris','admin/inventaris','super_admin,admin_inventaris'],
    ['admin.kendaraan','admin/kendaraan','super_admin,admin_kendaraan'],
    ['admin.fasilitas','admin/fasilitas','super_admin,admin_fasilitas'],
    ['admin.protokol','admin/protokol','super_admin,admin_protokol'],
    ['admin.perjalanan-dinas','admin/perjalanan-dinas','super_admin,admin_perjalanan_dinas'],
] as [$name,$prefix,$roles]) {
    Route::middleware(['auth',"role:{$roles}"])
        ->prefix($prefix)->name("{$name}.")
        ->group(fn () => require __DIR__."/{$prefix}.php");
}
