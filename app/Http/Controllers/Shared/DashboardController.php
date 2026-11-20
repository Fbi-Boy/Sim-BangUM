<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $dashboards = [
            'super_admin' => 'dashboard.super-admin',
            'kepala_bagian' => 'dashboard.pejabat',
            'kasubbag' => 'dashboard.pejabat',
            'pengemudi' => 'dashboard.pengemudi',
            'auditor' => 'dashboard.auditor',
            'admin_inventaris' => 'dashboard.admin-inventaris',
            'petugas_arsip' => 'dashboard.admin-arsip',
            'pengurus_bmd' => 'dashboard.admin-bmd',
            'admin_kendaraan' => 'dashboard.admin-kendaraan',
            'admin_fasilitas' => 'dashboard.admin-fasilitas',
            'admin_protokol' => 'dashboard.admin-protokol',
            'admin_perjalanan_dinas' => 'dashboard.admin-travel',
            'admin_persuratan' => 'dashboard.admin-persuratan',
            'pegawai' => 'dashboard.pegawai',
        ];

        foreach ($dashboards as $role => $view) {
            if ($user->hasRole($role)) {
                return view($view);
            }
        }

        abort(403, 'Role Anda belum memiliki dashboard.');
    }
}
