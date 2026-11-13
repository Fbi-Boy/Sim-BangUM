<?php
namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view("shared.dashboard.index");
    }
}
