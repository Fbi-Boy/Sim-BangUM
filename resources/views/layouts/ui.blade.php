<style>
/* ================= UI KIT SIM-BU (dipakai semua halaman menu) ================= */
[hidden] { display: none !important; }

/* ---------- kepala halaman ---------- */
.page-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
.page-head h2 { font-size: 20px; font-weight: 700; letter-spacing: -.2px; color: var(--text-main); }
.page-head p { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

/* ---------- kartu statistik ---------- */
.stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-bottom: 20px; }
.stat-card {
    display: flex; align-items: center; gap: 14px; padding: 16px 18px;
    background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px;
    transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(20,30,80,.08); }
.stat-ico { width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center; flex-shrink: 0; }
.stat-ico svg { width: 22px; height: 22px; }
.stat-ico.blue  { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
.stat-ico.red   { background: rgba(239,68,68,.12);  color: #dc2626; }
.stat-ico.amber { background: rgba(245,158,11,.15); color: #d97706; }
.stat-ico.green { background: rgba(16,185,129,.14); color: #059669; }
body.dark-mode .stat-ico.red   { color: #f87171; }
body.dark-mode .stat-ico.amber { color: #fbbf24; }
body.dark-mode .stat-ico.green { color: #34d399; }
.stat-val { font-size: 23px; font-weight: 700; line-height: 1.1; color: var(--text-main); }
.stat-lbl { font-size: 12px; color: var(--text-muted); margin-top: 3px; }
.stat-lbl small { opacity: .75; font-size: 10.5px; }

/* ---------- kartu tabel ---------- */
.table-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; overflow: hidden; margin-bottom: 16px; }
.table-toolbar { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; padding: 16px 18px; border-bottom: 1px solid var(--border-color); }
.tb-search {
    flex: 1; min-width: 230px; max-width: 340px; height: 40px; display: flex; align-items: center; gap: 9px; padding: 0 12px;
    background: var(--bg-soft, #f3f6fd); border: 1px solid transparent; border-radius: 12px; color: var(--text-muted);
    transition: border-color .2s, box-shadow .2s;
}
.tb-search:focus-within { border-color: var(--accent, #2563eb); box-shadow: 0 0 0 3px var(--accent-soft, #eef3ff); }
.tb-search svg { width: 17px; height: 17px; flex-shrink: 0; }
.tb-search input, .tb-search input:focus {
    border: none; outline: none; box-shadow: none; background: transparent; padding: 0; max-width: none; width: 100%;
    font-size: 13px; color: var(--text-main); border-radius: 0;
}
.chips { display: flex; gap: 8px; flex-wrap: wrap; }
.chip {
    padding: 7px 14px; border-radius: 20px; border: 1px solid var(--border-color); background: transparent;
    color: var(--text-muted); font-size: 12px; font-weight: 500; cursor: pointer; transition: all .2s;
}
.chip:hover { background: var(--bg-hover); color: var(--text-main); }
.chip.on { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); border-color: var(--accent, #2563eb); font-weight: 600; }

.table-wrap { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13.5px; background: transparent; }
.data-table th {
    padding: 12px 18px; text-align: left; white-space: nowrap; font-size: 11px; font-weight: 600; letter-spacing: .8px;
    text-transform: uppercase; color: var(--text-muted); background: var(--table-header); border-bottom: 1px solid var(--border-color);
}
.data-table td { padding: 14px 18px; vertical-align: middle; color: var(--text-main); background: transparent; border-bottom: 1px solid var(--border-color); }
.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: var(--bg-hover); }
.data-table tr[data-href] { cursor: pointer; }
.data-table .col-action { text-align: right; white-space: nowrap; }

.cell-main { display: flex; flex-direction: column; gap: 3px; min-width: 220px; max-width: 380px; }
.cell-title { font-weight: 600; line-height: 1.35; color: var(--text-main); }
.cell-sub { font-size: 12px; line-height: 1.4; color: var(--text-muted); }

.person { display: flex; align-items: center; gap: 10px; white-space: nowrap; }
.avatar-sm {
    width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0; display: grid; place-items: center;
    font-size: 11.5px; font-weight: 700; letter-spacing: .4px; color: #fff; background: linear-gradient(135deg, #2433d6, #6aa0ff);
}

/* ---------- badge ---------- */
.badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 20px; font-size: 11.5px; font-weight: 600; white-space: nowrap; }
.badge svg { width: 12px; height: 12px; }
.badge .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.badge-success { background: rgba(16,185,129,.13); color: #059669; }
.badge-info    { background: rgba(59,130,246,.13); color: #2563eb; }
.badge-warning { background: rgba(245,158,11,.16); color: #b45309; }
.badge-danger  { background: rgba(239,68,68,.13);  color: #dc2626; }
.badge-muted   { background: var(--bg-soft, #f3f6fd); color: var(--text-muted); }
body.dark-mode .badge-success { color: #34d399; }
body.dark-mode .badge-info    { color: #60a5fa; }
body.dark-mode .badge-warning { color: #fbbf24; }
body.dark-mode .badge-danger  { color: #f87171; }

/* ---------- tenggat ---------- */
.due { display: inline-flex; flex-direction: column; gap: 2px; white-space: nowrap; }
.due-date { display: inline-flex; align-items: center; gap: 6px; font-weight: 500; }
.due-date svg { width: 14px; height: 14px; opacity: .7; }
.due-note { font-size: 11px; color: var(--text-muted); padding-left: 20px; }
.due.soon    .due-date, .due.soon    .due-note { color: #d97706; }
.due.overdue .due-date, .due.overdue .due-note { color: #dc2626; font-weight: 600; }
body.dark-mode .due.soon    .due-date, body.dark-mode .due.soon    .due-note { color: #fbbf24; }
body.dark-mode .due.overdue .due-date, body.dark-mode .due.overdue .due-note { color: #f87171; }

/* ---------- tombol aksi ---------- */
.btn-open {
    display: inline-flex; align-items: center; gap: 6px; padding: 7px 13px; border-radius: 10px; text-decoration: none;
    background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); font-size: 12.5px; font-weight: 600;
    transition: background .2s, color .2s;
}
.btn-open svg { width: 14px; height: 14px; transition: transform .2s; }
.btn-open:hover { background: #2563eb; color: #fff; }
.btn-open:hover svg { transform: translateX(3px); }

/* ---------- keadaan kosong ---------- */
.empty-state { text-align: center; padding: 56px 20px; }
.empty-ico { width: 70px; height: 70px; border-radius: 22px; margin: 0 auto 16px; display: grid; place-items: center; background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); }
.empty-ico svg { width: 30px; height: 30px; }
.empty-state h3 { font-size: 15px; font-weight: 600; color: var(--text-main); }
.empty-state p { font-size: 13px; color: var(--text-muted); margin-top: 6px; }

/* ---------- footer tabel + paginasi ---------- */
.table-foot { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 14px 18px; border-top: 1px solid var(--border-color); }
.foot-info { font-size: 12.5px; color: var(--text-muted); }
.pager-list { display: flex; gap: 6px; list-style: none; }
.pager-list a, .pager-list span {
    min-width: 34px; height: 34px; padding: 0 10px; display: grid; place-items: center; border-radius: 10px;
    font-size: 12.5px; font-weight: 500; text-decoration: none; color: var(--text-muted); border: 1px solid var(--border-color); transition: all .2s;
}
.pager-list a:hover { background: var(--accent-soft, #eef3ff); color: var(--accent, #2563eb); border-color: transparent; }
.pager-list .active span { background: #2563eb; color: #fff; border-color: #2563eb; }
body.dark-mode .pager-list .active span { background: #60a5fa; border-color: #60a5fa; color: #0f172a; }
.pager-list .disabled span { opacity: .4; }
.pager-list .dots span { border: none; }
.pager-list svg { width: 14px; height: 14px; }

@media (max-width: 720px) {
    .table-toolbar { padding: 14px; }
    .tb-search { max-width: none; }
    .data-table th, .data-table td { padding: 12px 14px; }
}
</style>
