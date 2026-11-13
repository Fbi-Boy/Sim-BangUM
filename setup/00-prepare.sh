#!/usr/bin/env bash
# 00-prepare.sh — buat semua folder (tidak bikin file PHP, jadi cepat & anti-antivirus)
echo "00-prepare: creating folders"
for d in \
    app/Http/Controllers/{Auth,Shared,SuperAdmin,Pejabat,Pegawai,Pengemudi,Auditor} \
    app/Http/Controllers/Admin/{Persuratan,Arsip,Inventaris,BarangDaerah,Kendaraan,Fasilitas,Protokol,PerjalananDinas} \
    app/Models/{Identity,Organization,Workflow,Correspondence,Archive,Inventory,Supply,Vehicle,Facility,Event,Travel,Support} \
    app/Policies app/Enums app/Support app/Notifications \
    app/Actions/{Workflow,Correspondence,Inventory,Vehicle,Facility} \
    routes/admin \
    resources/views/shared/{dashboard,notifications,profile,search,work-tasks}; do
    [ -d "$d" ] || mkdir -p "$d"
done
echo "00-prepare: DONE"
