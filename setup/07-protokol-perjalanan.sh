#!/usr/bin/env bash
source "$(dirname "$0")/_lib.sh"
echo "07-protokol-perjalanan"
N="App\Http\Controllers\Admin\Protokol"
D="app/Http/Controllers/Admin/Protokol"
for c in EventController EventAgendaController EventTaskController EventGuestController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
N="App\Http\Controllers\Admin\PerjalananDinas"
D="app/Http/Controllers/Admin/PerjalananDinas"
for c in TravelRequestController TravelParticipantController; do
    mk_controller "$N" "$c" "$D/$c.php"
done
echo "07-protokol-perjalanan: DONE"
