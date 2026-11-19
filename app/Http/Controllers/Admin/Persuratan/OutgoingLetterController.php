<?php

namespace App\Http\Controllers\Admin\Persuratan;

use App\Http\Controllers\Concerns\HandlesPeriodReports;
use App\Http\Controllers\Controller;
use App\Models\OutgoingLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutgoingLetterController extends Controller
{
    use HandlesPeriodReports;

    public function index(Request $request)
    {
        $query = OutgoingLetter::latest();

        // ?report=1 -> halaman laporan | ?export=xlsx -> spreadsheet, untuk ?period=weekly|monthly|yearly
        // Periode disaring dari tanggal pendaftaran (created_at) karena letter_date boleh kosong.
        if ($out = $this->periodOutput($request, $query, 'created_at', 'Laporan Surat Keluar', [
            'No. Agenda' => fn ($l) => $l->register_year . '/' . str_pad($l->agenda_sequence, 4, '0', STR_PAD_LEFT),
            'Perihal'    => fn ($l) => $l->subject,
            'Tgl Surat'  => fn ($l) => $l->letter_date ? \Carbon\Carbon::parse($l->letter_date)->format('d-m-Y') : '-',
            'Status'     => fn ($l) => $l->status,
            'Dibuat'     => fn ($l) => $l->created_at?->format('d-m-Y H:i'),
        ], 'surat-keluar')) {
            return $out;
        }

        $letters = $query->paginate(15);
        return view('admin.persuratan.outgoing-letters.index', compact('letters'));
    }

    public function create()
    {
        return view('admin.persuratan.outgoing-letters.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:250',
            'body_summary' => 'nullable|string',
            'letter_date' => 'nullable|date',
        ]);

        $year = now()->year;
        $seq = (OutgoingLetter::where('register_year', $year)->max('agenda_sequence') ?? 0) + 1;
        $data['register_year'] = $year;
        $data['agenda_sequence'] = $seq;
        $data['created_by_user_id'] = Auth::id();
        $data['status'] = 'draft';

        OutgoingLetter::create($data);
        return redirect()->route('admin.persuratan.outgoing-letters.index')
            ->with('success', 'Surat keluar tersimpan sebagai draft.');
    }

    public function show(OutgoingLetter $outgoingLetter)
    {
        return redirect()->route('admin.persuratan.outgoing-letters.index');
    }
}
