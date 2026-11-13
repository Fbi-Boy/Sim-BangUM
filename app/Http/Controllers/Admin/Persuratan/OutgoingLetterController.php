<?php

namespace App\Http\Controllers\Admin\Persuratan;

use App\Http\Controllers\Controller;
use App\Models\OutgoingLetter;
use Illuminate\Http\Request;

class OutgoingLetterController extends Controller
{
    public function index()
    {
        $letters = OutgoingLetter::latest()->paginate(15);
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
        $data['created_by_user_id'] = auth()->id();
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
