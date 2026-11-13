<?php

namespace App\Http\Controllers\Admin\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetLoan;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with('category')->latest()->paginate(15);
        return view('admin.inventaris.assets.index', compact('assets'));
    }

    public function create()
    {
        $categories = AssetCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventaris.assets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_code' => 'required|string|max:60|unique:assets,asset_code',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'name' => 'required|string|max:180',
            'brand' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        $data['created_by_user_id'] = auth()->id();
        $data['status'] = 'available';
        $data['condition'] = 'good';
        Asset::create($data);
        return redirect()->route('admin.inventaris.assets.index')->with('success', 'Aset ditambahkan.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'loans.requester']);
        return view('admin.inventaris.assets.show', compact('asset'));
    }

    public function loans()
    {
        $loans = AssetLoan::with(['asset', 'requester'])->latest()->paginate(15);
        return view('admin.inventaris.loans.index', compact('loans'));
    }

    public function approveLoan(AssetLoan $loan)
    {
        if ($loan->status !== 'submitted') { return back()->with('error', 'Status tidak valid.'); }
        $loan->update(['status' => 'issued']);
        $loan->asset->update(['status' => 'loaned']);
        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function returnLoan(AssetLoan $loan)
    {
        if ($loan->status !== 'issued') { return back()->with('error', 'Status tidak valid.'); }
        $loan->update(['status' => 'returned', 'returned_at' => now()]);
        $loan->asset->update(['status' => 'available']);
        return back()->with('success', 'Pengembalian dicatat.');
    }
}
