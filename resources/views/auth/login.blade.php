<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIM-BU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', 'Segoe UI', Tahoma, sans-serif; }
        html[lang="ar"] * { font-family: 'Tajawal', 'Poppins', sans-serif; }

        :root {
            --primary: #4f4df0;
            --left-1: #8f9cff;
            --left-2: #6f7dff;
            --text: #111322;
            --muted: #8a8fa3;
        }

        body {
            min-height: 100vh;
            display: flex; justify-content: center; align-items: center;
            padding: 24px; position: relative; overflow-x: hidden;
            background:
                radial-gradient(1200px 700px at 15% 10%, #1d4ed8 0%, transparent 60%),
                radial-gradient(1000px 700px at 90% 95%, #6d28d9 0%, transparent 55%),
                linear-gradient(160deg, #060b27 0%, #0b1a56 50%, #0a2a7a 100%);
        }

        /* ===== BACKGROUND ===== */
        #bg { position: fixed; inset: 0; z-index: 0; width: 100%; height: 100%; }
        .aurora { position: fixed; border-radius: 50%; filter: blur(90px); z-index: 0; pointer-events: none; animation: drift 16s ease-in-out infinite alternate; }
        .aurora.a1 { width: 460px; height: 460px; background: #3b82f6; top: -140px; left: -120px; opacity: .45; }
        .aurora.a2 { width: 520px; height: 520px; background: #8b5cf6; bottom: -200px; right: -140px; opacity: .40; animation-delay: -6s; }
        .aurora.a3 { width: 300px; height: 300px; background: #06b6d4; top: 52%; left: 6%; opacity: .25; animation-delay: -10s; }
        .aurora.a4 { width: 260px; height: 260px; background: #f472b6; top: 8%; right: 8%; opacity: .18; animation-delay: -3s; }
        @keyframes drift { to { transform: translate(50px, 36px) scale(1.12); } }

        .ring { position: fixed; z-index: 0; border-radius: 50%; border: 1px solid rgba(255,255,255,.10); pointer-events: none; }
        .ring.r1 { width: 720px; height: 720px; top: 50%; left: 50%; margin: -360px 0 0 -360px; }
        .ring.r2 { width: 980px; height: 980px; top: 50%; left: 50%; margin: -490px 0 0 -490px; border-color: rgba(255,255,255,.06); }
        .vignette { position: fixed; inset: 0; z-index: 0; pointer-events: none; background: radial-gradient(ellipse at center, transparent 45%, rgba(2,6,30,.55) 100%); }

        /* ===== CARD ===== */
        .auth-container {
            position: relative; z-index: 2;
            display: flex; width: 100%; max-width: 860px; min-height: 520px;
            background: linear-gradient(160deg, var(--left-1), var(--left-2));
            border-radius: 30px; overflow: hidden;
            box-shadow: 0 40px 90px rgba(3, 8, 50, .55), 0 0 0 1px rgba(255,255,255,.18), 0 0 80px rgba(99,102,241,.25);
            animation: rise .7s cubic-bezier(.2,.8,.2,1) both;
        }
        @keyframes rise { from { opacity: 0; transform: translateY(24px) scale(.98); } }

        /* PANEL KIRI (dipersempit) */
        .auth-left {
            flex: 0 0 310px; padding: 30px 46px 0 28px; color: #fff;
            display: flex; flex-direction: column; position: relative; overflow: hidden;
        }
        .auth-left::before { /* pola titik halus */
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background-image: radial-gradient(rgba(255,255,255,.18) 1.2px, transparent 1.2px);
            background-size: 22px 22px;
            -webkit-mask-image: linear-gradient(180deg, #000, transparent 55%);
                    mask-image: linear-gradient(180deg, #000, transparent 55%);
        }
        .brand { display: flex; align-items: center; gap: 10px; position: relative; }
        .brand-logo {
            width: 38px; height: 38px; border-radius: 11px; background: #fff; color: #2f3fe0;
            display: grid; place-items: center; font-size: 24px; font-weight: 800; line-height: 1;
            box-shadow: 0 8px 18px rgba(20, 30, 140, .35);
        }
        .brand-name { font-size: 17px; font-weight: 700; letter-spacing: .3px; }
        .brand-sub { font-size: 10.5px; opacity: .9; margin-top: 8px; letter-spacing: .3px; position: relative; }
        .left-text { position: relative; }
        .left-text h1 { font-size: 22px; line-height: 1.3; font-weight: 600; margin-top: 26px; letter-spacing: -.2px; }
        .left-text h1 span { color: #fff7b0; }

        .illustration { margin-top: auto; width: 100%; position: relative; }
        .illustration svg { width: 100%; height: auto; display: block; overflow: visible; }

        /* animasi ilustrasi */
        .float { animation: float 4.5s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }
        .float.f2 { animation-delay: -1.5s; } .float.f3 { animation-delay: -3s; }
        @keyframes float { 50% { transform: translateY(-9px) rotate(2deg); } }
        .spin { animation: spin 9s linear infinite; transform-box: fill-box; transform-origin: center; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .orbit { animation: spin 60s linear infinite; transform-box: fill-box; transform-origin: center; }
        .pulse { animation: pulse 2.2s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }
        @keyframes pulse { 50% { transform: scale(1.15); } }
        .twinkle { animation: twinkle 2.4s ease-in-out infinite; transform-box: fill-box; transform-origin: center; }
        .twinkle.t2 { animation-delay: -.8s; } .twinkle.t3 { animation-delay: -1.6s; }
        @keyframes twinkle { 0%,100% { opacity: .2; transform: scale(.6); } 50% { opacity: 1; transform: scale(1.1); } }
        .bar { transform-box: fill-box; transform-origin: bottom; animation: grow 2.6s ease-in-out infinite alternate; }
        .bar.b2 { animation-delay: -.8s; } .bar.b3 { animation-delay: -1.6s; } .bar.b4 { animation-delay: -2.2s; }
        @keyframes grow { from { transform: scaleY(.35); } to { transform: scaleY(1); } }
        .line { transform-box: fill-box; transform-origin: left; animation: type 3s ease-in-out infinite; }
        .line.l2 { animation-delay: -1s; } .line.l3 { animation-delay: -2s; }
        @keyframes type { 0% { transform: scaleX(.1); } 60%,100% { transform: scaleX(1); } }
        .wave { transform-origin: 238px 152px; animation: wave 1.6s ease-in-out infinite; }
        @keyframes wave { 0%,100% { transform: rotate(-8deg); } 50% { transform: rotate(14deg); } }
        .typing { animation: typing .5s ease-in-out infinite alternate; }
        @keyframes typing { to { transform: translateY(2px); } }
        .blink { transform-box: fill-box; transform-origin: center; animation: blink 4.5s infinite; }
        @keyframes blink { 0%,94%,100% { transform: scaleY(1); } 97% { transform: scaleY(.1); } }
        .steam { stroke-dasharray: 14; animation: steam 2.4s ease-in-out infinite; }
        .steam.s2 { animation-delay: -1.2s; }
        @keyframes steam { 0% { stroke-dashoffset: 14; opacity: 0; } 50% { opacity: .9; } 100% { stroke-dashoffset: -14; opacity: 0; } }
        .bob { animation: bob 3.2s ease-in-out infinite; }
        @keyframes bob { 50% { transform: translateY(-2px); } }

        /* PANEL KANAN */
        .auth-right {
            flex: 1; background: #fff; margin-inline-start: -34px;
            border-start-start-radius: 30px; border-end-start-radius: 30px;
            padding: 24px 54px 32px; display: flex; flex-direction: column; position: relative; z-index: 1;
        }

        /* dropdown bahasa */
        .lang { align-self: flex-end; position: relative; }
        .lang-btn {
            display: flex; align-items: center; gap: 7px; background: #f4f5fb; border: 1px solid #ecedf6;
            border-radius: 20px; padding: 6px 12px; font-size: 11.5px; color: #555a72; cursor: pointer; transition: .2s;
        }
        .lang-btn:hover { background: #ebedfb; }
        .lang-btn .code { background: var(--primary); color: #fff; border-radius: 6px; font-size: 9.5px; font-weight: 600; padding: 2px 5px; }
        .lang-btn svg { transition: transform .2s; }
        .lang.open .lang-btn svg { transform: rotate(180deg); }
        .lang-menu {
            position: absolute; top: calc(100% + 8px); inset-inline-end: 0; min-width: 160px; background: #fff;
            border-radius: 14px; box-shadow: 0 14px 40px rgba(30,30,90,.18); border: 1px solid #eef0f8;
            padding: 6px; opacity: 0; transform: translateY(-6px); pointer-events: none; transition: .18s; z-index: 10;
        }
        .lang.open .lang-menu { opacity: 1; transform: none; pointer-events: auto; }
        .lang-item {
            width: 100%; display: flex; align-items: center; gap: 10px; background: none; border: none; cursor: pointer;
            padding: 9px 10px; border-radius: 10px; font-size: 12.5px; color: #333; text-align: start;
        }
        .lang-item:hover { background: #f3f4ff; }
        .lang-item.active { background: #eceeff; color: var(--primary); font-weight: 600; }
        .lang-item .code { font-size: 9.5px; font-weight: 600; background: #e4e7fb; color: var(--primary); border-radius: 6px; padding: 2px 5px; }

        .auth-right h2 { font-size: 21px; font-weight: 600; color: var(--text); margin: 20px 0 22px; }

        .form-group { position: relative; margin-bottom: 20px; }
        .form-group input {
            width: 100%; padding: 10px 28px 10px 0; padding-inline: 0 28px; border: none; border-bottom: 1px solid #e6e8ef;
            font-size: 13px; outline: none; background: transparent; color: #222; transition: border-color .25s;
        }
        .form-group input::placeholder { color: #9a9fb2; }
        .form-group input:focus { border-bottom-color: var(--primary); }
        .toggle-pass { position: absolute; inset-inline-end: 0; top: 9px; background: none; border: none; cursor: pointer; color: #6b6f80; display: flex; }

        .row { display: flex; justify-content: space-between; align-items: center; margin: 6px 0 22px; font-size: 11.5px; color: #777; }
        .row label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .row input[type=checkbox] { appearance: none; -webkit-appearance: none; width: 15px; height: 15px; border-radius: 4px; background: #fff; border: 1.5px solid #c9ccda; display: grid; place-items: center; cursor: pointer; }
        .row input[type=checkbox]:checked { background: var(--primary); border-color: var(--primary); }
        .row input[type=checkbox]:checked::after { content: ''; width: 4px; height: 8px; border: solid #fff; border-width: 0 2px 2px 0; transform: rotate(45deg) translate(-1px,-1px); }
        .row a { color: var(--primary); font-weight: 500; text-decoration: underline; }

        .btn-submit {
            width: 100%; padding: 13px; border: none; border-radius: 30px;
            background: linear-gradient(135deg, #5b59ff, #4340e0);
            color: #fff; font-size: 14px; font-weight: 600; cursor: pointer;
            box-shadow: 0 10px 22px rgba(79,77,240,.38); transition: transform .2s, box-shadow .2s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(79,77,240,.48); }

        .divider { display: flex; align-items: center; gap: 12px; margin: 22px 0 16px; color: #8a8fa3; font-size: 12px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #eceef5; }

        .social-login { display: flex; justify-content: center; gap: 14px; }
        .social-btn { width: 36px; height: 36px; border-radius: 50%; background: #fff; display: grid; place-items: center; box-shadow: 0 3px 10px rgba(30,30,80,.13); transition: transform .2s; text-decoration: none; }
        .social-btn:hover { transform: translateY(-3px); }
        .social-btn svg { width: 16px; height: 16px; }

        .bottom-link { text-align: center; font-size: 11px; color: #8a8fa3; margin-top: 22px; }
        .bottom-link a { color: var(--primary); text-decoration: underline; }

        .alert { padding: 10px 12px; border-radius: 10px; font-size: 12px; margin-bottom: 16px; background: #fee2e2; color: #dc2626; }
        .alert.ok { background: #dcfce7; color: #166534; }

        @media (max-width: 820px) {
            .auth-container { flex-direction: column; max-width: 480px; }
            .auth-left { flex: none; padding: 26px 28px 0; }
            .left-text h1 { font-size: 20px; margin-top: 16px; }
            .illustration { max-width: 240px; align-self: center; }
            .auth-right { margin: -28px 0 0; border-radius: 30px 30px 0 0; padding: 22px 28px 30px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>

<canvas id="bg"></canvas>
<div class="aurora a1"></div><div class="aurora a2"></div><div class="aurora a3"></div><div class="aurora a4"></div>
<div class="ring r1"></div><div class="ring r2"></div>
<div class="vignette"></div>

<div class="auth-container">
    <!-- KIRI -->
    <div class="auth-left">
        <div class="brand">
            <div class="brand-logo">U</div>
            <div class="brand-name">SIM-BU</div>
        </div>
        <div class="brand-sub" data-i18n="brandSub">Pemda · Bagian Umum</div>

        <div class="left-text">
            <h1 data-i18n-html="title">Kelola Administrasi <span>Bagian Umum</span> Lebih Mudah &amp; Cepat.</h1>
        </div>

        <div class="illustration">
            <svg viewBox="0 0 300 262" fill="none" aria-hidden="true">
                <!-- orbit & glow -->
                <circle cx="150" cy="150" r="104" fill="#fff" opacity=".10"/>
                <circle class="orbit" cx="150" cy="150" r="122" stroke="#fff" stroke-opacity=".35" stroke-dasharray="2 7" stroke-linecap="round"/>

                <!-- sparkles -->
                <path class="twinkle" d="M268 118v10M263 123h10" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                <path class="twinkle t2" d="M24 150v8M20 154h8" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                <path class="twinkle t3" d="M120 14v8M116 18h8" stroke="#fff" stroke-width="2" stroke-linecap="round"/>

                <!-- floating: dokumen -->
                <g class="float">
                    <g transform="rotate(-9 44 62)">
                        <rect x="20" y="36" width="42" height="52" rx="6" fill="#fff"/>
                        <rect x="20" y="36" width="42" height="12" rx="6" fill="#4f4df0"/>
                        <rect x="27" y="56" width="28" height="3.5" rx="1.7" fill="#c9cdf5"/>
                        <rect x="27" y="64" width="22" height="3.5" rx="1.7" fill="#c9cdf5"/>
                        <rect x="27" y="72" width="26" height="3.5" rx="1.7" fill="#c9cdf5"/>
                    </g>
                </g>
                <!-- floating: centang -->
                <g class="float f2">
                    <circle cx="150" cy="30" r="15" fill="#34d399"/>
                    <path d="M143 30l5 5 9-10" stroke="#fff" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <!-- floating: grafik -->
                <g class="float f3">
                    <g transform="rotate(8 250 62)">
                        <rect x="226" y="38" width="52" height="42" rx="8" fill="#fff"/>
                        <rect x="234" y="58" width="7" height="14" rx="2" fill="#a5b4ff"/>
                        <rect x="245" y="50" width="7" height="22" rx="2" fill="#6f7dff"/>
                        <rect x="256" y="44" width="7" height="28" rx="2" fill="#4f4df0"/>
                        <path d="M233 52l10-8 9 4 14-10" stroke="#34d399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </g>
                </g>
                <!-- gear -->
                <g class="spin">
                    <circle cx="34" cy="112" r="11" stroke="#fff" stroke-width="6" stroke-dasharray="4.3 4.3" opacity=".95"/>
                    <circle cx="34" cy="112" r="7.5" stroke="#fff" stroke-width="3"/>
                </g>
                <!-- notifikasi -->
                <g class="pulse">
                    <circle cx="270" cy="168" r="9" fill="#fb7185"/>
                    <path d="M270 163v6" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>
                    <circle cx="270" cy="172" r="1.2" fill="#fff"/>
                </g>

                <!-- bayangan lantai -->
                <ellipse cx="150" cy="248" rx="118" ry="8" fill="#1b1f7a" opacity=".22"/>

                <!-- tanaman -->
                <g class="bob" style="transform-origin: 28px 236px">
                    <path d="M28 226c-12-6-14-20-6-28 8 6 10 18 6 28zM28 226c10-8 18-6 22-16-10-2-22 4-22 16z" fill="#34d399"/>
                </g>
                <path d="M21 224h14l-2 22H23z" fill="#1e2fd0"/>

                <!-- kursi -->
                <rect x="143" y="140" width="84" height="68" rx="20" fill="#ff6b81"/>
                <rect x="183" y="205" width="5" height="32" fill="#2a2d78"/>
                <path d="M158 240h56" stroke="#2a2d78" stroke-width="5" stroke-linecap="round"/>
                <circle cx="160" cy="244" r="3.5" fill="#2a2d78"/><circle cx="212" cy="244" r="3.5" fill="#2a2d78"/>

                <!-- pegawai: badan (seragam khaki) -->
                <path d="M149 205c0-38 10-56 36-56s36 18 36 56z" fill="#d6b270"/>
                <!-- kerah -->
                <path d="M172 149l13 17 13-17-6-4h-14z" fill="#c29d58"/>
                <path d="M177 148l8 11 8-11z" fill="#f6cfaa"/>
                <!-- tali ID card -->
                <path d="M176 149l9 28 9-28" stroke="#2f5bff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <g class="bob">
                    <rect x="177" y="176" width="16" height="20" rx="3" fill="#fff"/>
                    <circle cx="185" cy="183" r="3.2" fill="#8f9cff"/>
                    <rect x="180" y="188" width="10" height="2.4" rx="1.2" fill="#c9cdf5"/>
                    <rect x="181.5" y="192" width="7" height="2" rx="1" fill="#c9cdf5"/>
                </g>
                <!-- lencana bahu & saku -->
                <rect x="156" y="158" width="9" height="9" rx="2" fill="#f2c94c"/>
                <rect x="205" y="158" width="9" height="9" rx="2" fill="#f2c94c"/>
                <rect x="199" y="176" width="14" height="11" rx="2" stroke="#b08a45" stroke-width="1.5"/>

                <!-- leher & kepala -->
                <rect x="179" y="136" width="12" height="16" rx="5" fill="#f2bf98"/>
                <circle cx="185" cy="122" r="18" fill="#f6cfaa"/>
                <circle cx="167" cy="124" r="3.5" fill="#f2bf98"/><circle cx="203" cy="124" r="3.5" fill="#f2bf98"/>
                <path d="M166 120c-3-22 38-24 38 0-5-9-12-12-19-12s-14 3-19 12z" fill="#1d1d33"/>
                <path d="M170 110c4-6 12-8 18-6" stroke="#3b3b5c" stroke-width="2" stroke-linecap="round"/>
                <!-- wajah -->
                <path d="M174 117q4-3 8 0M188 117q4-3 8 0" stroke="#2a2118" stroke-width="1.8" stroke-linecap="round"/>
                <ellipse class="blink" cx="178" cy="123" rx="2" ry="2.3" fill="#2a2118"/>
                <ellipse class="blink" cx="192" cy="123" rx="2" ry="2.3" fill="#2a2118"/>
                <circle cx="173" cy="129" r="3" fill="#ff9aa8" opacity=".5"/><circle cx="197" cy="129" r="3" fill="#ff9aa8" opacity=".5"/>
                <path d="M178 130q7 7 14 0" stroke="#b5533a" stroke-width="2" stroke-linecap="round" fill="none"/>

                <!-- meja -->
                <rect x="34" y="195" width="232" height="10" rx="4" fill="#2230e0"/>
                <rect x="44" y="205" width="8" height="40" fill="#1a25b8"/>
                <rect x="248" y="205" width="8" height="40" fill="#1a25b8"/>

                <!-- monitor (menghadap penonton) -->
                <rect x="73" y="184" width="10" height="11" fill="#c5c9e6"/>
                <rect x="62" y="192" width="32" height="4" rx="2" fill="#aeb3d9"/>
                <rect x="40" y="124" width="96" height="62" rx="6" fill="#e9ebf8"/>
                <rect x="45" y="129" width="86" height="52" rx="3.5" fill="#0d1650"/>
                <circle cx="53" cy="137" r="3" fill="#34d399"/>
                <rect x="60" y="135" width="30" height="4" rx="2" fill="#4a5bd8"/>
                <!-- bar chart di layar -->
                <rect class="bar" x="52" y="150" width="9" height="26" rx="2" fill="#6f7dff"/>
                <rect class="bar b2" x="64" y="150" width="9" height="26" rx="2" fill="#34d399"/>
                <rect class="bar b3" x="76" y="150" width="9" height="26" rx="2" fill="#fbbf24"/>
                <rect class="bar b4" x="88" y="150" width="9" height="26" rx="2" fill="#fb7185"/>
                <!-- teks mengetik di layar -->
                <rect class="line" x="103" y="148" width="24" height="3.5" rx="1.7" fill="#8f9cff"/>
                <rect class="line l2" x="103" y="157" width="18" height="3.5" rx="1.7" fill="#8f9cff"/>
                <rect class="line l3" x="103" y="166" width="22" height="3.5" rx="1.7" fill="#8f9cff"/>

                <!-- keyboard -->
                <rect x="112" y="189" width="46" height="6" rx="2.5" fill="#cfd3ee"/>
                <path d="M117 191.500h36" stroke="#9aa1d4" stroke-width="1.2" stroke-dasharray="3 2.500"/>

                <!-- tangan kiri: mengetik -->
                <g class="typing">
                    <path d="M157 164l-9 24" stroke="#d6b270" stroke-width="13" stroke-linecap="round"/>
                    <path d="M148 188l-20 3" stroke="#f6cfaa" stroke-width="9" stroke-linecap="round"/>
                    <circle cx="125" cy="191" r="5.5" fill="#f6cfaa"/>
                </g>

                <!-- tangan kanan: melambai -->
                <path d="M214 164l24-12" stroke="#d6b270" stroke-width="13" stroke-linecap="round"/>
                <g class="wave">
                    <path d="M238 152l3-28" stroke="#d6b270" stroke-width="12" stroke-linecap="round"/>
                    <path d="M240 138l1.500-14" stroke="#f6cfaa" stroke-width="9" stroke-linecap="round"/>
                    <circle cx="241.500" cy="119" r="7" fill="#f6cfaa"/>
                    <path d="M236 113l-2-6M241 111v-7M246 113l2-6" stroke="#f6cfaa" stroke-width="3" stroke-linecap="round"/>
                </g>

                <!-- cangkir kopi -->
                <rect x="226" y="177" width="16" height="18" rx="4" fill="#fff"/>
                <path d="M242 182h3.500a4 4 0 010 9H242" stroke="#fff" stroke-width="2.500"/>
                <rect x="228" y="179" width="12" height="3" rx="1.500" fill="#c9cdf5"/>
                <path class="steam" d="M230 172c-3-4 3-6 0-10" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                <path class="steam s2" d="M237 172c-3-4 3-6 0-10" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <!-- KANAN -->
    <div class="auth-right">
        <div class="lang" id="lang">
            <button type="button" class="lang-btn" id="langBtn" aria-haspopup="listbox" aria-expanded="false">
                <span class="code" id="langCode">ID</span>
                <span id="langLabel">Indonesia</span>
                <svg width="9" height="9" viewBox="0 0 10 10"><path d="M1 3l4 4 4-4z" fill="#8a8fa3"/></svg>
            </button>
            <div class="lang-menu" role="listbox">
                <button type="button" class="lang-item" data-lang="id"><span class="code">ID</span> Indonesia</button>
                <button type="button" class="lang-item" data-lang="en"><span class="code">EN</span> English</button>
                <button type="button" class="lang-item" data-lang="ar"><span class="code">AR</span> العربية</button>
            </div>
        </div>

        <h2 data-i18n="heading">Masuk ke Akun</h2>

        @if($errors->any()) <div class="alert">{{ $errors->first() }}</div> @endif
        @if(session('status')) <div class="alert ok">{{ session('status') }}</div> @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Alamat Email" data-i18n-ph="email">
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" required placeholder="Password" data-i18n-ph="password">
                <button type="button" class="toggle-pass" id="togglePass" aria-label="Toggle password">
                    <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M14.12 14.12a3 3 0 11-4.24-4.24M1 1l22 22"/>
                    </svg>
                </button>
            </div>

            <div class="row">
                <label><input type="checkbox" name="remember" id="remember" checked> <span data-i18n="remember">Ingat saya</span></label>
                <a href="#" data-i18n="forgot">Lupa password?</a>
            </div>

            <button type="submit" class="btn-submit" data-i18n="submit">Masuk</button>
        </form>

        <div class="divider" data-i18n="or">Atau Masuk Dengan</div>

        <div class="social-login">
            <a href="#" class="social-btn" aria-label="Google">
                <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M22.5 12.2c0-.8-.1-1.5-.2-2.2H12v4.2h5.9a5 5 0 01-2.2 3.3v2.7h3.5c2-1.9 3.3-4.7 3.3-8z"/><path fill="#34A853" d="M12 23c3 0 5.5-1 7.3-2.7l-3.5-2.7c-1 .7-2.2 1-3.8 1-2.9 0-5.4-2-6.2-4.6H2.2v2.8A11 11 0 0012 23z"/><path fill="#FBBC05" d="M5.8 14c-.2-.7-.4-1.3-.4-2s.1-1.4.4-2V7.2H2.2a11 11 0 000 9.6z"/><path fill="#EA4335" d="M12 5.4c1.6 0 3.1.6 4.2 1.7l3.1-3.1A11 11 0 002.2 7.2L5.8 10c.8-2.6 3.3-4.6 6.2-4.6z"/></svg>
            </a>
            <a href="#" class="social-btn" aria-label="Facebook">
                <svg viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12a12 12 0 10-13.9 11.9v-8.4H7.1V12h3V9.4c0-3 1.8-4.7 4.5-4.7 1.3 0 2.7.2 2.7.2v3h-1.5c-1.5 0-2 .9-2 1.9V12h3.4l-.5 3.5h-2.9v8.4A12 12 0 0024 12z"/></svg>
            </a>
            <a href="#" class="social-btn" aria-label="Instagram">
                <svg viewBox="0 0 24 24" fill="none" stroke="#E1306C" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="#E1306C"/></svg>
            </a>
            <a href="#" class="social-btn" aria-label="X / Twitter">
                <svg viewBox="0 0 24 24"><path fill="#1DA1F2" d="M23 4.6a9 9 0 0 1-2.6.7 4.5 4.5 0 0 0 2-2.5 9 9 0 0 1-2.9 1.1 4.5 4.5 0 0 0-7.7 4.1A12.8 12.8 0 0 1 3 3.3a4.5 4.5 0 0 0 1.4 6 4.5 4.5 0 0 1-2-.6v.1a4.5 4.5 0 0 0 3.6 4.4 4.5 4.5 0 0 1-2 .1 4.5 4.5 0 0 0 4.2 3.1A9 9 0 0 1 2 18.3a12.8 12.8 0 0 0 6.9 2c8.3 0 12.8-6.9 12.8-12.8v-.6A9 9 0 0 0 23 4.6z"/></svg>
            </a>
            <a href="#" class="social-btn" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24"><path fill="#0A66C2" d="M20.4 20.5h-3.6v-5.6c0-1.3 0-3-1.8-3s-2.1 1.4-2.1 2.9v5.7H9.3V9h3.4v1.6c.5-.9 1.6-1.8 3.4-1.8 3.6 0 4.3 2.4 4.3 5.5v6.2zM5.3 7.4a2.1 2.1 0 110-4.2 2.1 2.1 0 010 4.2zM7.1 20.5H3.6V9h3.5v11.5z"/></svg>
            </a>
        </div>

        <div class="bottom-link"><span data-i18n="help">Butuh bantuan akses?</span> <a href="#" data-i18n="contact">Hubungi Admin</a></div>
    </div>
</div>

@verbatim
<script>
/* ===== BAHASA (ID / EN / AR) ===== */
const I18N = {
    id: {
        label: 'Indonesia', code: 'ID', dir: 'ltr',
        brandSub: 'Pemda · Bagian Umum',
        title: 'Kelola Administrasi <span>Bagian Umum</span> Lebih Mudah &amp; Cepat.',
        heading: 'Masuk ke Akun', email: 'Alamat Email', password: 'Password',
        remember: 'Ingat saya', forgot: 'Lupa password?', submit: 'Masuk',
        or: 'Atau Masuk Dengan', help: 'Butuh bantuan akses?', contact: 'Hubungi Admin'
    },
    en: {
        label: 'English', code: 'EN', dir: 'ltr',
        brandSub: 'Local Government · General Affairs',
        title: 'Manage <span>General Affairs</span> Administration Easier &amp; Faster.',
        heading: 'Sign In to Your Account', email: 'Email Address', password: 'Password',
        remember: 'Remember me', forgot: 'Forgot password?', submit: 'Sign In',
        or: 'Or Sign In With', help: 'Need access help?', contact: 'Contact Admin'
    },
    ar: {
        label: 'العربية', code: 'AR', dir: 'rtl',
        brandSub: 'الحكومة المحلية · الشؤون العامة',
        title: 'أدِر إدارة <span>الشؤون العامة</span> بسهولة وسرعة.',
        heading: 'تسجيل الدخول إلى حسابك', email: 'البريد الإلكتروني', password: 'كلمة المرور',
        remember: 'تذكرني', forgot: 'نسيت كلمة المرور؟', submit: 'تسجيل الدخول',
        or: 'أو سجّل الدخول عبر', help: 'تحتاج مساعدة في الوصول؟', contact: 'تواصل مع المسؤول'
    }
};

const langBox = document.getElementById('lang');
const langBtn = document.getElementById('langBtn');

function setLang(key) {
    const t = I18N[key] || I18N.id;
    document.documentElement.lang = key;
    document.documentElement.dir = t.dir;
    document.querySelectorAll('[data-i18n]').forEach(el => { el.textContent = t[el.dataset.i18n]; });
    document.querySelectorAll('[data-i18n-html]').forEach(el => { el.innerHTML = t[el.dataset.i18nHtml]; });
    document.querySelectorAll('[data-i18n-ph]').forEach(el => { el.placeholder = t[el.dataset.i18nPh]; });
    document.getElementById('langLabel').textContent = t.label;
    document.getElementById('langCode').textContent = t.code;
    document.querySelectorAll('.lang-item').forEach(b => b.classList.toggle('active', b.dataset.lang === key));
    try { localStorage.setItem('simbu_lang', key); } catch (e) {}
}

langBtn.addEventListener('click', e => {
    e.stopPropagation();
    const open = langBox.classList.toggle('open');
    langBtn.setAttribute('aria-expanded', open);
});
document.querySelectorAll('.lang-item').forEach(b => b.addEventListener('click', () => {
    setLang(b.dataset.lang);
    langBox.classList.remove('open');
    langBtn.setAttribute('aria-expanded', 'false');
}));
document.addEventListener('click', () => langBox.classList.remove('open'));
document.addEventListener('keydown', e => { if (e.key === 'Escape') langBox.classList.remove('open'); });

let saved = 'id';
try { saved = localStorage.getItem('simbu_lang') || 'id'; } catch (e) {}
setLang(saved);

/* ===== LIHAT / SEMBUNYIKAN PASSWORD ===== */
const pass = document.getElementById('password');
const eye  = document.getElementById('eyeIcon');
const hiddenIcon = eye.innerHTML;
const shownIcon  = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>';
document.getElementById('togglePass').addEventListener('click', () => {
    const show = pass.type === 'password';
    pass.type = show ? 'text' : 'password';
    eye.innerHTML = show ? shownIcon : hiddenIcon;
});

/* ===== BACKGROUND: JARINGAN PARTIKEL INTERAKTIF ===== */
(function () {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const c = document.getElementById('bg');
    const ctx = c.getContext('2d');
    const mouse = { x: null, y: null };
    let w = 0, h = 0, pts = [];

    function resize() {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        w = window.innerWidth; h = window.innerHeight;
        c.width = w * dpr; c.height = h * dpr;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        const n = Math.min(95, Math.floor((w * h) / 15000));
        pts = Array.from({ length: n }, () => ({
            x: Math.random() * w, y: Math.random() * h,
            vx: (Math.random() - .5) * .35, vy: (Math.random() - .5) * .35,
            r: Math.random() * 1.6 + .8
        }));
    }
    window.addEventListener('resize', resize);
    window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
    window.addEventListener('mouseleave', () => { mouse.x = mouse.y = null; });
    resize();

    function frame() {
        ctx.clearRect(0, 0, w, h);
        for (let i = 0; i < pts.length; i++) {
            const p = pts[i];
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > w) p.vx *= -1;
            if (p.y < 0 || p.y > h) p.vy *= -1;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(190,210,255,.75)';
            ctx.fill();

            for (let j = i + 1; j < pts.length; j++) {
                const q = pts[j];
                const d = Math.hypot(p.x - q.x, p.y - q.y);
                if (d < 140) {
                    ctx.strokeStyle = 'rgba(150,180,255,' + (0.28 * (1 - d / 140)) + ')';
                    ctx.lineWidth = 1;
                    ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y); ctx.stroke();
                }
            }
            if (mouse.x !== null) {
                const dm = Math.hypot(p.x - mouse.x, p.y - mouse.y);
                if (dm < 170) {
                    ctx.strokeStyle = 'rgba(255,255,255,' + (0.45 * (1 - dm / 170)) + ')';
                    ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke();
                }
            }
        }
        requestAnimationFrame(frame);
    }
    frame();
})();
</script>
@endverbatim

</body>
</html>
