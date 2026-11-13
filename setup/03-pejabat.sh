#!/usr/bin/env bash
source "$(dirname "$0")/_lib.sh"
echo "03-pejabat: 6 controllers"
N="App\Http\Controllers\Pejabat"
D="app/Http/Controllers/Pejabat"
for c in ApprovalInboxController ApprovalActionController VerificationController \
         DispositionController WorkloadController SummaryReportController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
echo "03-pejabat: DONE"
