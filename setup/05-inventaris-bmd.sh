#!/usr/bin/env bash
source "$(dirname "$0")/_lib.sh"
echo "05-inventaris-bmd"
N="App\Http\Controllers\Admin\Inventaris"
D="app/Http/Controllers/Admin/Inventaris"
for c in AssetController AssetCategoryController AssetAssignmentController AssetMovementController AssetLoanController \
         SupplyItemController SupplyCategoryController UnitOfMeasureController \
         StockBalanceController StockRequestController StockTransactionController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
N="App\Http\Controllers\Admin\BarangDaerah"
D="app/Http/Controllers/Admin/BarangDaerah"
for c in BmdAssetController BmdReconciliationController BmdReportController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
echo "05-inventaris-bmd: DONE"
