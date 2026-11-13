#!/usr/bin/env bash
# 99-check.sh — verifikasi semua file sudah ada
echo "======================================"
echo "VERIFIKASI SIM-BU"
echo "======================================"
chk() {
    local dir="$1" expect="$2"
    local count=$(ls "$dir"/*.php 2>/dev/null | wc -l)
    if [ "$count" -eq "$expect" ]; then echo "OK   $dir  $count/$expect"
    elif [ "$count" -lt "$expect" ]; then echo "KURANG $dir  $count/$expect  <-- RETRY"
    else echo "LEBIH  $dir  $count/$expect"; fi
}
chk app/Http/Controllers/Auth 5
chk app/Http/Controllers/Shared 5
chk app/Http/Controllers/SuperAdmin 10
chk app/Http/Controllers/Pejabat 6
chk app/Http/Controllers/Admin/Persuratan 4
chk app/Http/Controllers/Admin/Arsip 5
chk app/Http/Controllers/Admin/Inventaris 11
chk app/Http/Controllers/Admin/BarangDaerah 3
chk app/Http/Controllers/Admin/Kendaraan 5
chk app/Http/Controllers/Admin/Fasilitas 5
chk app/Http/Controllers/Admin/Protokol 4
chk app/Http/Controllers/Admin/PerjalananDinas 2
chk app/Http/Controllers/Pegawai 7
chk app/Http/Controllers/Pengemudi 4
chk app/Http/Controllers/Auditor 3
echo "---"
[ -f app/Http/Middleware/EnsureRole.php ] && echo "OK   EnsureRole.php" || echo "KURANG EnsureRole.php"
[ -f app/Http/Middleware/EnsurePermission.php ] && echo "OK   EnsurePermission.php" || echo "KURANG EnsurePermission.php"
echo "---"
echo "Total controller: $(find app/Http/Controllers -name '*Controller.php' 2>/dev/null | wc -l)  (target 74+)"
