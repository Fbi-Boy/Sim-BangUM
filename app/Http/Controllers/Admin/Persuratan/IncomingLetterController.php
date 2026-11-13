<?php
namespace App\Http\Controllers\Admin\Persuratan;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\IncomingLetter;
use App\Models\LetterDisposition;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncomingLetterController extends Controller
{
    public function index()
    {
        $letters = IncomingLetter::latest()->paginate(15);
        return view("admin.persuratan.incoming-letters.index", compact("letters"));
    }

    public function create()
    {
        return view("admin.persuratan.incoming-letters.create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "sender_name" => "required|string|max:180",
            "subject" => "required|string|max:250",
            "letter_date" => "nullable|date",
            "external_letter_number" => "nullable|string|max:120",
            "summary" => "nullable|string",
        ]);
        $year = now()->year;
        $seq = (IncomingLetter::where("register_year", $year)->max("agenda_sequence") ?? 0) + 1;
        $data["register_year"] = $year;
        $data["agenda_sequence"] = $seq;
        $data["received_at"] = now();
        $data["registered_by_user_id"] = auth()->id();
        $data["status"] = "registered";
        $letter = IncomingLetter::create($data);
        ActivityLogger::log("letter.incoming.created", $letter, "Surat masuk: ".$letter->subject);
        return redirect()->route("admin.persuratan.incoming-letters.show", $letter)
            ->with("success", "Surat terdaftar. Silakan upload lampiran.");
    }

    public function show(IncomingLetter $incomingLetter)
    {
        $incomingLetter->load(["recipientUnit","registeredBy","documents","dispositions.fromUser"]);
        $users = User::orderBy("name")->get();
        return view("admin.persuratan.incoming-letters.show", ["incomingLetter" => $incomingLetter, "dispositions" => $incomingLetter->dispositions, "users" => $users]);
    }

    public function storeDisposition(Request $request, IncomingLetter $incomingLetter)
    {
        $data = $request->validate([
            "to_user_id" => "required|exists:users,id",
            "instruction" => "required|string",
            "priority" => "required|in:normal,urgent,immediate",
            "due_at" => "nullable|date",
        ]);
        $data["incoming_letter_id"] = $incomingLetter->id;
        $data["from_user_id"] = auth()->id();
        $data["status"] = "assigned";
        $disp = LetterDisposition::create($data);
        ActivityLogger::log("letter.disposition.created", $disp, "Disposisi ke user #".$data["to_user_id"]);
        $incomingLetter->update(["status" => "awaiting_direction"]);
        return redirect()->route("admin.persuratan.incoming-letters.show", $incomingLetter)->with("success", "Disposisi terkirim.");
    }

    public function uploadDocument(Request $request, IncomingLetter $incomingLetter)
    {
        $request->validate([
            "file" => "required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx",
            "title" => "nullable|string|max:200",
        ]);
        $file = $request->file("file");
        $path = $file->store("letters/".$incomingLetter->id, "local");
        Document::create([
            "title" => $request->input("title") ?: $file->getClientOriginalName(),
            "documentable_type" => IncomingLetter::class,
            "documentable_id" => $incomingLetter->id,
            "disk" => "local",
            "path" => $path,
            "original_filename" => $file->getClientOriginalName(),
            "mime_type" => $file->getMimeType(),
            "file_size_bytes" => $file->getSize(),
            "uploaded_by_user_id" => auth()->id(),
        ]);
        ActivityLogger::log("letter.incoming.attachment.uploaded", $incomingLetter, $file->getClientOriginalName());
        return back()->with("success", "Lampiran diupload.");
    }

    public function downloadDocument(IncomingLetter $incomingLetter, Document $document)
    {
        if ($document->documentable_type !== IncomingLetter::class || $document->documentable_id !== $incomingLetter->id) {
            abort(404);
        }
        return Storage::disk($document->disk)->download($document->path, $document->original_filename);
    }

    public function deleteDocument(IncomingLetter $incomingLetter, Document $document)
    {
        if ($document->documentable_type !== IncomingLetter::class || $document->documentable_id !== $incomingLetter->id) abort(404);
        Storage::disk($document->disk)->delete($document->path);
        ActivityLogger::log("letter.incoming.attachment.deleted", $incomingLetter, $document->original_filename);
        $document->delete();
        return back()->with("success", "Lampiran dihapus.");
    }
}
