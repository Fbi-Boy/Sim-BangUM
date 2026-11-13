#!/usr/bin/env bash
# _lib.sh — helper bersama. Jangan dijalankan langsung, di-source oleh script lain.

mk_controller() {
    local ns="$1" cls="$2" path="$3"
    [ -f "$path" ] && { echo "  skip: $path"; return 0; }
    mkdir -p "$(dirname "$path")"
    cat > "$path" <<EOF
<?php

namespace ${ns};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ${cls} extends Controller
{
    // TODO: implement methods
}
EOF
    [ -f "$path" ] && echo "  OK: $path" || echo "  FAIL: $path"
}
