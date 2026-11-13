#!/bin/bash
restyle() {
    local f="$1"
    if grep -q "@extends" "$f" 2>/dev/null; then
        echo "SKIP (sudah pakai layout): $f"
        return
    fi
    local h1=$(grep -oP '(?<=<h1>)[^<]+' "$f" 2>/dev/null | head -1)
    [ -z "$h1" ] && h1="Halaman"
    local tmp=$(mktemp)
    sed -n '/<body>/,/<\/body>/p' "$f" 2>/dev/null | sed '1d;$d' > "$tmp"
    if [ ! -s "$tmp" ]; then
        echo "SKIP (tidak ada body): $f"
        rm "$tmp"
        return
    fi
    perl -0pi -e "s/\@if\(session\('success'\)\).*?\@endif//gs" "$tmp" 2>/dev/null
    perl -0pi -e "s/\@if\(session\('error'\)\).*?\@endif//gs" "$tmp" 2>/dev/null
    {
        echo "@extends('layouts.app')"
        echo "@section('title', '$h1')"
        echo "@section('content')"
        cat "$tmp"
        echo "@endsection"
    } > "$f"
    rm "$tmp"
    echo "OK: $f"
}

for f in \
  resources/views/admin/persuratan/incoming-letters/*.blade.php \
  resources/views/admin/persuratan/outgoing-letters/*.blade.php \
  resources/views/admin/inventaris/assets/*.blade.php \
  resources/views/admin/inventaris/loans/*.blade.php \
  resources/views/admin/inventaris/supply-items/*.blade.php \
  resources/views/admin/inventaris/stock-requests/*.blade.php \
  resources/views/admin/kendaraan/vehicles/*.blade.php \
  resources/views/admin/kendaraan/requests/*.blade.php \
  resources/views/admin/fasilitas/rooms/*.blade.php \
  resources/views/admin/fasilitas/bookings/*.blade.php \
  resources/views/pegawai/asset-loans/*.blade.php \
  resources/views/pegawai/vehicle-requests/*.blade.php \
  resources/views/pegawai/room-bookings/*.blade.php \
  resources/views/pegawai/stock-requests/*.blade.php \
; do
  [ -f "$f" ] && restyle "$f"
done

echo "=== SELESAI ==="
