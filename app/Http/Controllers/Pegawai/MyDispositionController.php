<?php
namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\LetterDisposition;
use App\Models\LetterDispositionUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyDispositionController extends Controller
{
    public function index()
    {
        $dispositions = LetterDisposition::with(['incomingLetter', 'fromUser'])
            ->forUser(Auth::id())
            ->active() // exclude submitted, approved
            ->latest()
            ->paginateSimbu();

        return view('pegawai.my-dispositions.index', compact('dispositions'));
    }

    public function history()
    {
        $dispositions = LetterDisposition::with(['incomingLetter', 'fromUser'])
            ->forUser(Auth::id())
            ->whereIn('status', ['submitted', 'approved', 'cancelled'])
            ->latest()
            ->paginateSimbu();

        return view('pegawai.my-dispositions.index', [
            'dispositions' => $dispositions,
            'isHistory' => true,
        ]);
    }

    public function show(LetterDisposition $disposition)
    {
        if ($disposition->to_user_id !== Auth::id()) abort(403, 'Bukan disposisi Anda.');
        $disposition->load(['incomingLetter.documents', 'fromUser', 'updates' => fn($q) => $q->latest()]);
        return view('pegawai.my-dispositions.show', compact('disposition'));
    }

    public function updateStatus(Request $request, LetterDisposition $disposition)
    {
        if ($disposition->to_user_id !== Auth::id()) abort(403);
        if (!in_array($disposition->status, ['assigned', 'accepted', 'in_progress', 'returned'])) {
            return back()->with('error', 'Status tidak valid untuk update.');
        }

        $data = $request->validate([
            'status' => 'required|in:accepted,in_progress',
            'progress_note' => 'required|string',
        ]);

        DB::transaction(function () use ($disposition, $data) {
            $disposition->update(['status' => $data['status']]);
            LetterDispositionUpdate::create([
                'letter_disposition_id' => $disposition->id,
                'updated_by_user_id' => Auth::id(),
                'status' => $data['status'],
                'progress_note' => $data['progress_note'],
            ]);
        });

        return back()->with('success', 'Progres diperbarui.');
    }

    /** Kirim hasil ke atasan untuk direview */
    public function submitResult(Request $request, LetterDisposition $disposition)
    {
        if ($disposition->to_user_id !== Auth::id()) abort(403);
        if (!in_array($disposition->status, ['in_progress', 'accepted', 'returned'])) {
            return back()->with('error', 'Status tidak valid untuk kirim hasil.');
        }

        $data = $request->validate(['progress_note' => 'required|string|max:2000']);

        DB::transaction(function () use ($disposition, $data) {
            $disposition->update(['status' => 'submitted', 'submitted_at' => now()]);
            LetterDispositionUpdate::create([
                'letter_disposition_id' => $disposition->id,
                'updated_by_user_id' => Auth::id(),
                'status' => 'submitted',
                'progress_note' => $data['progress_note'],
            ]);
        });

        return redirect()->route('pegawai.dispositions.index')
            ->with('success', 'Hasil dikirim ke atasan untuk direview.');
    }
}
