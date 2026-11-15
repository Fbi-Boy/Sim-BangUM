<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // <-- Ganti auth() jadi Auth::

        if ($user->hasRole('super_admin')) {
            return view('dashboard.super-admin');
        }

        if ($user->hasAnyRole(['kepala_bagian', 'kasubbag'])) {
            return view('dashboard.pejabat');
        }

        if ($user->hasRole('pengemudi')) {
            return view('dashboard.pengemudi');
        }

        return view('dashboard.pegawai');
    }
}
