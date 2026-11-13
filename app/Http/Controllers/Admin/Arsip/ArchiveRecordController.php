<?php
namespace App\Http\Controllers\Admin\Arsip;
use App\Http\Controllers\Controller;
use App\Models\ArchiveClassification;
use App\Models\ArchiveRecord;
use App\Models\Document;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchiveRecordController extends Controller
{
    public function classifications()
    {
        $items = ArchiveClassification::withCount("records")->orderBy("code")->paginate(20);
        return view("admin.arsip.classifications.index", compact("items"));
    }
    public function storeClassification(Request $r)
    {
        $d = $r->validate([
            "code"=>"required|string|max:60|unique:archive_classifications,code",
            "name"=>"required|string|max:180",
            "retention_active_months"=>"nullable|integer|min:1",
            "final_disposition"=>"nullable|string|max:30",
        ]);
        $d["is_active"] = true;
        ArchiveClassification::create($d);
        return back()->with("success","Klasifikasi ditambahkan.");
    }

    public function index()
    {
        $records = ArchiveRecord::with(["classification","registeredBy"])->latest()->paginate(15);
        return view("admin.arsip.records.index", compact("records"));
    }
    public function create()
    {
        $classifications = ArchiveClassification::where("is_active",true)->orderBy("code")->get();
        return view("admin.arsip.records.create", compact("classifications"));
    }
    public function store(Request $r)
    {
        $d = $r->validate([
            "archive_classification_id"=>"required|exists:archive_classifications,id",
            "title"=>"required|string|max:200",
            "description"=>"nullable|string",
            "record_date"=>"nullable|date",
            "physical_location"=>"nullable|string|max:180",
        ]);
        $year = now()->year;
        $seq = (ArchiveRecord::whereYear("created_at",$year)->count() ?? 0) + 1;
        $d["archive_code"] = "ARS-{$year}-".str_pad($seq,4,"0",STR_PAD_LEFT);
        $d["registered_by_user_id"] = auth()->id();
        $d["archive_status"] = "active";
        $rec = ArchiveRecord::create($d);
        ActivityLogger::log("archive.record.created", $rec, "Arsip: ".$rec->title);
        return redirect()->route("admin.arsip.records.show", $rec)->with("success","Arsip terdaftar.");
    }
    public function show(ArchiveRecord $record)
    {
        $record->load(["classification","registeredBy","documents"]);
        return view("admin.arsip.records.show", compact("record"));
    }
    public function upload(Request $r, ArchiveRecord $record)
    {
        $r->validate(["file"=>"required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx"]);
        $file = $r->file("file");
        $path = $file->store("archives/".$record->id, "local");
        Document::create([
            "title"=>$file->getClientOriginalName(),
            "documentable_type"=>ArchiveRecord::class,
            "documentable_id"=>$record->id,
            "disk"=>"local","path"=>$path,
            "original_filename"=>$file->getClientOriginalName(),
            "mime_type"=>$file->getMimeType(),
            "file_size_bytes"=>$file->getSize(),
            "uploaded_by_user_id"=>auth()->id(),
        ]);
        ActivityLogger::log("archive.document.uploaded", $record, $file->getClientOriginalName());
        return back()->with("success","Lampiran diupload.");
    }
    public function download(ArchiveRecord $record, Document $document)
    {
        if ($document->documentable_type !== ArchiveRecord::class || $document->documentable_id !== $record->id) abort(404);
        return Storage::disk($document->disk)->download($document->path, $document->original_filename);
    }
}
