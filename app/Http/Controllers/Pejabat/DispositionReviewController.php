<?php
namespace App\Http\Controllers\Pejabat;

use App\Http\Controllers\Controller;
use App\Models\LetterDisposition;
use App\Models\LetterDispositionUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DispositionReviewController extends Controller
{
    public function index()
    {
        $dispositions = LetterDisposition::with(['incomingLetter', 'toUser', 'fromUser'])
            ->submitted()
            ->where('from_user_id', Auth::id())
            ->latest('submitted_at')
            ->paginateSimbu();

        return view('pejabat.reviews.index', compact('dispositions'));
    }

    public function show(LetterDisposition $disposition)
    {
        if ($disposition->from_user_id !== Auth::id()) abort(403);
        $disposition->load(['incomingLetter.documents', 'toUser', 'updates' => fn($q) => $q->latest()]);
        return view('pejabat.reviews.show', compact('disposition'));
    }

    public function approve(LetterDisposition $disposition)
    {
        if ($disposition->from_user_id !== Auth::id()) abort(403);
        if ($disposition->status !== 'submitted') return back()->with('error', 'Status tidak valid.');

        DB::transaction(function () use ($disposition) {
            $disposition->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id(),
            ]);
            // Update parent jika ada
            if ($disposition->parent_disposition_id) {
                LetterDisposition::where('id', $disposition->parent_disposition_id)
                    ->update(['status' => 'approved']);
            }
            LetterDispositionUpdate::create([
                'letter_disposition_id' => $disposition->id,
                'updated_by_user_id' => Auth::id(),
                'status' => 'approved',
                'progress_note' => 'Hasil disetujui. Tugas ditutup.',
            ]);
        });

        return redirect()->route('pejabat.reviews.index')->with('success', 'Tugas disetujui & ditutup.');
    }

    public function reject(Request $request, LetterDisposition $disposition)
    {
        if ($disposition->from_user_id !== Auth::id()) abort(403);
        if ($disposition->status !== 'submitted') return back()->with('error', 'Status tidak valid.');

        $data = $request->validate(['reason' => 'required|string|max:1000']);

        DB::transaction(function () use ($disposition, $data) {
            $disposition->update(['status' => 'returned']);
            LetterDispositionUpdate::create([
                'letter_disposition_id' => $disposition->id,
                'updated_by_user_id' => Auth::id(),
                'status' => 'returned',
                'progress_note' => 'Ditolak: ' . $data['reason'],
            ]);
        });

        return redirect()->route('pejabat.reviews.index')->with('success', 'Hasil dikembalikan ke pegawai.');
    }
}
