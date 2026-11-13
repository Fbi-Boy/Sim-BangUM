#!/usr/bin/env bash
source "$(dirname "$0")/_lib.sh"
echo "02-superadmin: 10 controllers"
N="App\Http\Controllers\SuperAdmin"
D="app/Http/Controllers/SuperAdmin"
for c in UserController RoleController PermissionController RoleAssignmentController \
         DelegationController OrganizationalUnitController PositionController \
         MasterDataController SystemSettingController ActivityLogController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
echo "02-superadmin: DONE"
