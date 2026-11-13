#!/usr/bin/env bash
source "$(dirname "$0")/_lib.sh"
echo "06-kendaraan-fasilitas"
N="App\Http\Controllers\Admin\Kendaraan"
D="app/Http/Controllers/Admin/Kendaraan"
for c in VehicleController DriverController VehicleRequestController VehicleAssignmentController VehicleUsageLogController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
N="App\Http\Controllers\Admin\Fasilitas"
D="app/Http/Controllers/Admin/Fasilitas"
for c in LocationController RoomController RoomBookingController MaintenanceTicketController MaintenanceScheduleController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
echo "06-kendaraan-fasilitas: DONE"
