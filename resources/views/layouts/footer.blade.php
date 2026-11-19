{{-- ===================== FOOTER (layouts/footer.blade.php) =====================
     Dipasang di dalam .main (di bawah .content) lewat @include('layouts.footer').
     - Memakai grid + logical properties, jadi otomatis terbalik untuk bahasa Arab (RTL).
     - Tidak memakai position:fixed, jadi tidak menimpa konten, toast, atau menu melayang sidebar.
     - z-index rendah (1) sehingga topbar (50) dan dropdown (70-80) selalu di atasnya. --}}

<style>
    .footer {
        position: relative; z-index: 1; margin-top: auto; overflow: hidden;
        background: var(--bg-card); border-top: 1px solid var(--border-color);
        color: var(--text-muted); font-size: 11.5px; text-align: start;
        transition: background .3s, border-color .3s;
    }
    /* garis gradasi tipis yang mengalir di tepi atas */
    .footer::before {
        content: ''; position: absolute; inset-inline: 0; top: -1px; height: 2px;
        background: linear-gradient(90deg, #2563eb, #38bdf8, #10b981, #f59e0b, #8b5cf6, #2563eb);
        background-size: 200% 100%; animation: ftflow 9s linear infinite;
    }
    body[dir="rtl"] .footer::before { animation-direction: reverse; }
    @keyframes ftflow { to { background-position: 200% 0; } }
    .footer-glow {
        position: absolute; inset-inline-start: -90px; bottom: -110px; width: 280px; height: 220px; border-radius: 50%;
        background: radial-gradient(circle, var(--accent-soft), transparent 70%); pointer-events: none;
    }

    .footer-inner {
        position: relative; display: grid; grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
        align-items: center; gap: 16px; padding: 16px 28px;
    }

    /* kiri: merek */
    .ft-brand { display: flex; align-items: center; gap: 11px; min-width: 0; }
    .ft-logo {
        width: 36px; height: 36px; border-radius: 12px; flex-shrink: 0; display: grid; place-items: center;
        background: linear-gradient(135deg, #2433d6 0%, #4f6bff 60%, #6aa0ff 100%);
        box-shadow: 0 8px 18px rgba(47,63,224,.28), inset 0 1px 0 rgba(255,255,255,.35);
    }
    .ft-logo svg { width: 20px; height: 20px; }
    .ft-brand-text { min-width: 0; line-height: 1.2; }
    .ft-brand-text strong { display: block; font-size: 13.5px; font-weight: 800; letter-spacing: .5px; color: var(--text-main); }
    .ft-brand-text strong b { color: var(--accent); font-weight: 800; }
    .ft-brand-text span { display: block; margin-top: 2px; font-size: 10.5px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ft-ver {
        flex-shrink: 0; padding: 2px 9px; border-radius: 20px; font: 700 10px/1.6 ui-monospace, Menlo, Consolas, monospace;
        color: var(--accent); background: var(--accent-soft); border: 1px solid transparent;
    }

    /* tengah: hak cipta */
    .ft-copy { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; justify-content: center; text-align: center; }
    .ft-copy .dot { width: 3px; height: 3px; border-radius: 50%; background: var(--text-muted); opacity: .6; }
    .ft-copy .yr { font-weight: 600; color: var(--text-main); }

    /* kanan: status koneksi + kembali ke atas */
    .ft-right { display: flex; align-items: center; gap: 12px; justify-self: end; min-width: 0; }
    .ft-status {
        display: inline-flex; align-items: center; gap: 8px; padding: 5px 12px; border-radius: 20px; white-space: nowrap;
        font-size: 11px; font-weight: 600; color: #059669; background: rgba(16,185,129,.12);
    }
    .ft-status i { width: 7px; height: 7px; border-radius: 50%; background: currentColor; animation: ftping 2s infinite; }
    .ft-status.off { color: #dc2626; background: rgba(239,68,68,.12); }
    .ft-status.off i { animation: none; }
    body.dark-mode .ft-status { color: #34d399; }
    body.dark-mode .ft-status.off { color: #f87171; }
    @keyframes ftping { 0% { box-shadow: 0 0 0 0 rgba(16,185,129,.55); } 70%, 100% { box-shadow: 0 0 0 7px rgba(16,185,129,0); } }

    .ft-top {
        position: relative; width: 38px; height: 38px; border: none; border-radius: 50%; padding: 0; flex-shrink: 0; cursor: pointer;
        display: grid; place-items: center; background: var(--bg-soft); color: var(--text-main);
        transition: background .2s, color .2s, transform .2s;
    }
    .ft-top:hover { background: var(--accent-soft); color: var(--accent); transform: translateY(-2px); }
    .ft-top:active { transform: scale(.94); }
    .ft-top > svg.ico { width: 16px; height: 16px; position: relative; z-index: 1; }
    .ft-ring { position: absolute; inset: 0; width: 100%; height: 100%; transform: rotate(-90deg); }
    body[dir="rtl"] .ft-ring { transform: rotate(-90deg) scaleY(-1); }
    .ft-ring circle { fill: none; stroke-width: 2.4; }
    .ft-ring .bg { stroke: var(--border-color); }
    .ft-ring .fg { stroke: var(--accent); stroke-linecap: round; stroke-dasharray: 100.53; stroke-dashoffset: 100.53; transition: stroke-dashoffset .15s linear; }

    .footer a:focus-visible, .footer button:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

    /* tablet / ponsel: susun ke bawah agar tidak saling bertumpuk */
    @media (max-width: 860px) {
        .footer-inner { grid-template-columns: 1fr; justify-items: center; gap: 12px; padding: 16px 18px; text-align: center; }
        .ft-brand { justify-content: center; flex-wrap: wrap; }
        .ft-right { justify-self: center; }
    }
    @media (max-width: 420px) { .ft-brand-text span { white-space: normal; } }
    @media print { .footer { display: none; } }
    @media (prefers-reduced-motion: reduce) { .footer::before, .ft-status i { animation: none; } .ft-ring .fg { transition: none; } }
</style>

<footer class="footer" id="appFooter">
    <span class="footer-glow" aria-hidden="true"></span>
    <div class="footer-inner">

        <div class="ft-brand">
            <span class="ft-logo" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M7 6v7a5 5 0 0 0 10 0V6" stroke="#fff" stroke-width="2.8" stroke-linecap="round"/>
                    <circle cx="18.6" cy="5.4" r="1.8" fill="#ffe58f"/>
                </svg>
            </span>
            <div class="ft-brand-text">
                <strong data-no-i18n>SIM-<b>BU</b></strong>
                <span>Pemda · Bagian Umum</span>
            </div>
            <span class="ft-ver" data-no-i18n>v1.0</span>
        </div>

        <div class="ft-copy">
            <span class="yr" data-no-i18n>&copy; {{ date('Y') }}</span>
            <i class="dot" aria-hidden="true"></i>
            <span>Seluruh hak dilindungi</span>
        </div>

        <div class="ft-right">
            <span class="ft-status" id="ftStatus" title="Status koneksi"><i></i><span id="ftStatusText">Terhubung</span></span>
            <button type="button" class="ft-top" id="ftTop" title="Kembali ke atas" aria-label="Kembali ke atas">
                <svg class="ft-ring" viewBox="0 0 38 38" aria-hidden="true"><circle class="bg" cx="19" cy="19" r="16"/><circle class="fg" id="ftRing" cx="19" cy="19" r="16"/></svg>
                <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
            </button>
        </div>

    </div>
</footer>

<script>
/* Kamus footer (didaftarkan sebelum js/app.js dimuat) */
(function (d) {
    window.I18N_EXTRA = Object.assign(window.I18N_EXTRA || {}, d);
    if (window.I18N && window.I18N.extend) window.I18N.extend(d);
})({
    'Pemda · Bagian Umum':   ['Local Government · General Affairs', 'الحكومة المحلية · الشؤون العامة', '地方政府 · 总务部'],
    'Seluruh hak dilindungi': ['All rights reserved', 'جميع الحقوق محفوظة', '版权所有'],
    'Terhubung':             ['Connected', 'متصل', '已连接'],
    'Tidak terhubung':       ['Offline', 'غير متصل', '未连接'],
    'Status koneksi':        ['Connection status', 'حالة الاتصال', '连接状态'],
    'Kembali ke atas':       ['Back to top', 'العودة إلى الأعلى', '返回顶部']
});

/* Status koneksi (nyata, dari browser) + cincin progres scroll pada tombol "kembali ke atas" */
(function () {
    var st = document.getElementById('ftStatus'), tx = document.getElementById('ftStatusText');
    var ring = document.getElementById('ftRing'), btn = document.getElementById('ftTop');
    if (!st || !tx || !ring || !btn) return;

    function net() {
        var on = navigator.onLine !== false;
        st.classList.toggle('off', !on);
        var t = on ? 'Terhubung' : 'Tidak terhubung';
        if (tx.textContent !== t) tx.textContent = t;      /* teks Indonesia; diterjemahkan otomatis oleh I18N */
    }
    window.addEventListener('online', net);
    window.addEventListener('offline', net);
    net();

    var C = 100.53, queued = false;
    function progress() {
        queued = false;
        var h = document.documentElement;
        var max = h.scrollHeight - h.clientHeight;
        var y = window.pageYOffset || h.scrollTop || 0;
        var p = max > 0 ? Math.min(1, Math.max(0, y / max)) : 0;
        ring.style.strokeDashoffset = (C * (1 - p)).toFixed(2);
    }
    window.addEventListener('scroll', function () { if (!queued) { queued = true; requestAnimationFrame(progress); } }, { passive: true });
    window.addEventListener('resize', progress);
    window.addEventListener('languageChanged', progress);
    progress();

    btn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
})();
</script>
