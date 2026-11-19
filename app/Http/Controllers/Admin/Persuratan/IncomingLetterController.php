<?php

namespace App\Http\Controllers\Admin\Persuratan;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\IncomingLetter;
use App\Models\LetterDisposition;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncomingLetterController extends Controller
{
    public function index()
    {
        $letters = IncomingLetter::latest()->paginateSimbu();
        return view('admin.persuratan.incoming-letters.index', compact('letters'));
    }

    public function create()
    {
        return view('admin.persuratan.incoming-letters.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sender_name' => 'required|string|max:180',
            'subject' => 'required|string|max:250',
            'letter_date' => 'nullable|date',
            'external_letter_number' => 'nullable|string|max:120',
            'summary' => 'nullable|string',
            'document' => 'nullable|array',
            'document.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        unset($data['document']);

        $year = now()->year;
        $seq = (IncomingLetter::where('register_year', $year)->max('agenda_sequence') ?? 0) + 1;
        $data['register_year'] = $year;
        $data['agenda_sequence'] = $seq;
        $data['received_at'] = now();
        $data['registered_by_user_id'] = Auth::id();
        $data['status'] = 'registered';

        $letter = IncomingLetter::create($data);

        $uploadedCount = 0;
        if ($request->hasFile('document')) {
            foreach ($request->file('document') as $file) {
                if (! $file->isValid()) continue;
                $path = $file->store('letters/' . $letter->id, 'local');
                Document::create([
                    'title' => $file->getClientOriginalName(),
                    'documentable_type' => IncomingLetter::class,
                    'documentable_id' => $letter->id,
                    'disk' => 'local',
                    'path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size_bytes' => $file->getSize(),
                    'uploaded_by_user_id' => Auth::id(),
                ]);
                $uploadedCount++;
            }
        }

        ActivityLogger::log('letter.incoming.created', $letter, 'Surat masuk: ' . $letter->subject . ' · ' . $uploadedCount . ' lampiran');

        return redirect()->route('admin.persuratan.incoming-letters.show', $letter)
            ->with('success', 'Surat terdaftar dengan ' . $uploadedCount . ' lampiran.');
    }

    public function show(IncomingLetter $incomingLetter)
    {
        $incomingLetter->load(['recipientUnit', 'registeredBy', 'documents', 'dispositions.fromUser']);

        $currentUser = \Illuminate\Support\Facades\Auth::user();

        // LOGIKA FILTER DROPDOWN DISPOSISI
        if ($currentUser->hasAnyRole(['super_admin', 'admin_persuratan'])) {
            // Admin hanya bisa kirim ke Pejabat
            $users = \App\Models\User::whereHas('roles', function($q) {
                $q->whereIn('code', ['kepala_bagian', 'kasubbag']);
            })->orderBy('name')->get();

        } elseif ($currentUser->hasAnyRole(['kepala_bagian', 'kasubbag'])) {
            // Pejabat bisa kirim ke Staf/Pegawai/Admin lain
            $users = \App\Models\User::whereHas('roles', function($q) {
                $q->whereIn('code', ['pegawai', 'pengemudi', 'admin_inventaris', 'petugas_arsip']);
            })->orderBy('name')->get();

        } else {
            // Role lain (misal Pegawai) tidak boleh mendisposisikan dari halaman ini
            $users = collect(); // Kosongkan
        }

        return view('admin.persuratan.incoming-letters.show', [
            'incomingLetter' => $incomingLetter,
            'dispositions' => $incomingLetter->dispositions,
            'users' => $users
        ]);
    }

    public function storeDisposition(Request $request, IncomingLetter $incomingLetter)
    {
        $data = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'instruction' => 'required|string',
            'priority' => 'required|in:normal,urgent,immediate',
            'due_at' => 'nullable|date',
        ]);
        $data['incoming_letter_id'] = $incomingLetter->id;
        $data['from_user_id'] = Auth::id();
        $data['status'] = 'assigned';

        $disp = LetterDisposition::create($data);
        ActivityLogger::log('letter.disposition.created', $disp, 'Disposisi ke user #' . $data['to_user_id']);

        $incomingLetter->update(['status' => 'awaiting_direction']);

        return redirect()->route('admin.persuratan.incoming-letters.show', $incomingLetter)
            ->with('success', 'Disposisi berhasil dikirim.');
    }

    public function downloadDocument(IncomingLetter $incomingLetter, Document $document)
    {
        if ($document->documentable_type !== IncomingLetter::class || $document->documentable_id !== $incomingLetter->id) {
            abort(404);
        }
        return Storage::disk($document->disk)->download($document->path, $document->original_filename);
    }
}
