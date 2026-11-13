<?php
namespace App\Http\Controllers\Pegawai;
use App\Http\Controllers\Controller;
use App\Models\LetterDisposition;
use App\Models\LetterDispositionUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyDispositionController extends Controller
{
    public function index()
    {
        $dispositions = LetterDisposition::with(["incomingLetter","fromUser"])
            ->where("to_user_id", auth()->id())
            ->latest()->paginate(15);
        return view("pegawai.my-dispositions.index", compact("dispositions"));
    }

    public function show(LetterDisposition $disposition)
    {
        if ($disposition->to_user_id !== auth()->id()) abort(403, "Bukan disposisi Anda.");
        $disposition->load(["incomingLetter","fromUser","updates" => fn($q) => $q->latest()]);
        return view("pegawai.my-dispositions.show", compact("disposition"));
    }

    public function updateStatus(Request $r, LetterDisposition $disposition)
    {
        if ($disposition->to_user_id !== auth()->id()) abort(403);
        $d = $r->validate([
            "status" => "required|in:accepted,in_progress,completed,returned",
            "progress_note" => "required|string",
        ]);
        DB::transaction(function () use ($disposition, $d) {
            $disposition->update(["status" => $d["status"]]);
            LetterDispositionUpdate::create([
                "letter_disposition_id" => $disposition->id,
                "updated_by_user_id" => auth()->id(),
                "status" => $d["status"],
                "progress_note" => $d["progress_note"],
            ]);
        });
        return back()->with("success", "Progres diperbarui.");
    }
}
