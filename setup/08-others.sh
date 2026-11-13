#!/usr/bin/env bash
source "$(dirname "$0")/_lib.sh"
echo "08-others: Pegawai + Pengemudi + Auditor"
N="App\Http\Controllers\Pegawai"
D="app/Http/Controllers/Pegawai"
for c in MyRequestController AssetLoanRequestController StockRequestController VehicleRequestController \
         RoomBookingRequestController MaintenanceReportController TravelRequestController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
N="App\Http\Controllers\Pengemudi"
D="app/Http/Controllers/Pengemudi"
for c in TaskController AssignmentConfirmationController UsageLogController IncidentReportController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
N="App\Http\Controllers\Auditor"
D="app/Http/Controllers/Auditor"
for c in ReportViewerController AuditTrailController ExportController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
echo "08-others: DONE"
