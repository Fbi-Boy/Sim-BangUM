<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ $period['name'] }} {{ $period['label'] }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 32px; font-family: 'Segoe UI', system-ui, -apple-system, Arial, sans-serif; color: #0f172a; background: #f1f5f9; }
        .sheet { max-width: 1000px; margin: 0 auto; background: #fff; border-radius: 16px; padding: 32px 36px; box-shadow: 0 10px 30px rgba(15,23,42,.08); }
        .top { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; border-bottom: 2px solid #2563eb; padding-bottom: 16px; }
        .top h1 { font-size: 20px; margin: 0 0 4px; letter-spacing: -.2px; }
        .top p { margin: 0; font-size: 13px; color: #475569; }
        .tag { display: inline-block; padding: 4px 12px; border-radius: 20px; background: #eef3ff; color: #2563eb; font-size: 12px; font-weight: 600; }
        .meta { display: flex; gap: 24px; flex-wrap: wrap; margin: 16px 0; font-size: 12.5px; color: #475569; }
        .meta b { color: #0f172a; }
        table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        th { text-align: left; padding: 10px 12px; background: #f1f5f9; color: #475569; font-size: 11px; text-transform: uppercase; letter-spacing: .6px; border-bottom: 1px solid #cbd5e1; }
        td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        tr:nth-child(even) td { background: #fafbfd; }
        td.no, th.no { width: 44px; text-align: center; }
        .empty { text-align: center; padding: 40px 0; color: #64748b; }
        .foot { margin-top: 20px; font-size: 11.5px; color: #64748b; display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .actions { max-width: 1000px; margin: 0 auto 16px; display: flex; gap: 8px; justify-content: flex-end; }
        .btn { padding: 9px 18px; border: none; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; background: #2563eb; color: #fff; }
        .btn.ghost { background: #fff; color: #334155; border: 1px solid #cbd5e1; }
        @media print {
            body { padding: 0; background: #fff; }
            .sheet { box-shadow: none; border-radius: 0; padding: 0; max-width: none; }
            .actions { display: none; }
            tr { break-inside: avoid; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button type="button" class="btn ghost" onclick="window.close()">Tutup</button>
        <button type="button" class="btn" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="sheet">
        <div class="top">
            <div>
                <h1>{{ $title }}</h1>
                <p>SIM-BU · Bagian Umum</p>
            </div>
            <span class="tag">{{ $period['name'] }} · {{ $period['label'] }}</span>
        </div>

        <div class="meta">
            <div>Periode: <b>{{ $period['start']->translatedFormat('d F Y') }} – {{ $period['end']->translatedFormat('d F Y') }}</b></div>
            <div>Jumlah data: <b>{{ number_format(count($rows), 0, ',', '.') }}</b></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="no">No</th>
                    @foreach($headers as $h)
                        <th>{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $i => $row)
                    <tr>
                        <td class="no">{{ $i + 1 }}</td>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ count($headers) + 1 }}" class="empty">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="foot">
            <span>Dicetak oleh {{ auth()->user()->name ?? '-' }}</span>
            <span>{{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
        </div>
    </div>
</body>
</html>
