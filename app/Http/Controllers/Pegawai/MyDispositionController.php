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
            ->where('to_user_id', Auth::id())
            ->latest()
            ->paginateSimbu(); // <--- HANYA INI YANG BERUBAH

        return view('pegawai.my-dispositions.index', compact('dispositions'));
    }

    public function show(LetterDisposition $disposition)
    {
        if ($disposition->to_user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak melihat disposisi ini.');
        }

        $disposition->load([
            'incomingLetter',
            'fromUser',
            'updates' => fn($q) => $q->latest()
        ]);

        return view('pegawai.my-dispositions.show', compact('disposition'));
    }

    public function updateStatus(Request $request, LetterDisposition $disposition)
    {
        if ($disposition->to_user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|in:accepted,in_progress,completed,returned',
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

        return back()->with('success', 'Progres tugas berhasil diperbarui!');
    }
}
