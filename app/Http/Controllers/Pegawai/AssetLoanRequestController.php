<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetLoan;
use Illuminate\Http\Request;

class AssetLoanRequestController extends Controller
{
    public function index()
    {
        $loans = AssetLoan::with('asset')->where('requester_user_id', auth()->id())->latest()->paginate(15);
        return view('pegawai.asset-loans.index', compact('loans'));
    }

    public function create()
    {
        $assets = Asset::where('status', 'available')->orderBy('name')->get();
        return view('pegawai.asset-loans.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'starts_at' => 'required|date',
            'expected_return_at' => 'required|date|after:starts_at',
            'purpose' => 'required|string',
        ]);
        $asset = Asset::findOrFail($data['asset_id']);
        if ($asset->status !== 'available') {
            return back()->withErrors(['asset_id' => 'Aset tidak tersedia.'])->withInput();
        }
        $year = now()->year;
        $seq = (AssetLoan::whereYear('created_at', $year)->count() ?? 0) + 1;
        $data['loan_number'] = "LOAN-{$year}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
        $data['requester_user_id'] = auth()->id();
        $data['status'] = 'submitted';
        AssetLoan::create($data);
        return redirect()->route('pegawai.asset-loans.index')->with('success', 'Pengajuan terkirim.');
    }

    public function show(AssetLoan $assetLoan) { return redirect()->route('pegawai.asset-loans.index'); }
}
