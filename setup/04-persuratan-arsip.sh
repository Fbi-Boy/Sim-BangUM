#!/usr/bin/env bash
source "$(dirname "$0")/_lib.sh"
echo "04-persuratan-arsip"
N="App\Http\Controllers\Admin\Persuratan"
D="app/Http/Controllers/Admin/Persuratan"
for c in IncomingLetterController OutgoingLetterController DispositionTrackingController LetterRegisterController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
N="App\Http\Controllers\Admin\Arsip"
D="app/Http/Controllers/Admin/Arsip"
for c in DocumentController DocumentVersionController ArchiveClassificationController ArchiveRecordController ArchiveLoanController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
echo "04-persuratan-arsip: DONE"
