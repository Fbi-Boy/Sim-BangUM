#!/bin/bash
# Deteksi view jadul, wrap dengan layout baru, strip inline style aneh

restyle() {
    local f="$1"
    # Skip kalau sudah extends
    if grep -q "@extends" "$f" 2>/dev/null; then
        echo "SKIP (extends): $f"
        return 0
    fi
    # Ambil title dari <title> tag
    local title=$(grep -oP '(?<=<title>)[^<]+' "$f" 2>/dev/null | head -1)
    [ -z "$title" ] && title=$(grep -oP '(?<=<h1>)[^<]+' "$f" 2>/dev/null | head -1)
    [ -z "$title" ] && title="Halaman"

    # Extract body content (antara <body> dan </body>)
    local body=$(perl -0777 -ne 'print $1 if /<body[^>]*>(.*?)<\/body>/s' "$f" 2>/dev/null)

    # Kalau tidak ada body, ambil seluruh file (fallback)
    if [ -z "$body" ]; then
        body=$(cat "$f")
    fi

    # Cleanup
    # 1. Hapus border="1" cellpadding="6" cellspacing="0" dari table
    body=$(echo "$body" | sed 's/border="[0-9]*"//g; s/cellpadding="[0-9]*"//g; s/cellspacing="[0-9]*"//g')
    # 2. Hapus style="..." dari <table>, <td>, <th>
    body=$(echo "$body" | sed -E 's/<(table|thead|tbody|tr|td|th|h1|h2|h3)[^>]*style="[^"]*"//g')
    # 3. Convert <h1> ke <h2> (konsisten dengan layout title)
    body=$(echo "$body" | sed 's/<h1>/<h2>/g; s/<\/h1>/<\/h2>/g')
    # 4. Hapus <style>...</style> di body
    body=$(echo "$body" | perl -0777 -pe 's/<style[^>]*>.*?<\/style>//gs')
    # 5. Hapus <h1> di awal kalau judul sudah di topbar
    body=$(echo "$body" | sed 's/^[[:space:]]*<h2>[^<]*<\/h2>//')

    # Write wrapped
    {
        echo "@extends('layouts.app')"
        echo "@section('title', '$title')"
        echo "@section('content')"
        echo "$body"
        echo "@endsection"
    } > "$f"
    echo "OK: $f"
}

# Loop semua view lama yang berpotensi jadul
for pattern in \
  "resources/views/admin/persuratan/*/*.blade.php" \
  "resources/views/admin/inventaris/*/*.blade.php" \
  "resources/views/admin/kendaraan/*/*.blade.php" \
  "resources/views/admin/fasilitas/*/*.blade.php" \
  "resources/views/pegawai/*/*.blade.php" \
  "resources/views/admin/arsip/*/*.blade.php" \
; do
  for f in $pattern; do
    [ -f "$f" ] && restyle "$f"
  done
done

echo ""
echo "=== SELESAI ==="
