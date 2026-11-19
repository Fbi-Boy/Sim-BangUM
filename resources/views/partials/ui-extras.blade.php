<style>
    .stat-card {
        position: relative; overflow: hidden; border-radius: 18px;
        transition: transform .22s, box-shadow .22s, border-color .22s;
    }
    .stat-card::after {
        content: ''; position: absolute; right: -24px; top: -24px; width: 88px; height: 88px; border-radius: 50%;
        background: var(--tint, #2563eb); opacity: .10; transition: transform .3s; pointer-events: none;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 14px 28px rgba(37,99,235,.14); border-color: var(--tint, #2563eb); }
    .stat-card:hover::after { transform: scale(1.6); }

    .stat-card:has(.stat-ico.blue)  { --tint: #2563eb; }
    .stat-card:has(.stat-ico.red)   { --tint: #ef4444; }
    .stat-card:has(.stat-ico.amber) { --tint: #f59e0b; }
    .stat-card:has(.stat-ico.green) { --tint: #10b981; }

    .stat-card .stat-ico { width: 44px; height: 44px; border-radius: 13px; }
    .stat-card .stat-ico.blue,  body.dark-mode .stat-card .stat-ico.blue,
    .stat-card .stat-ico.red,   body.dark-mode .stat-card .stat-ico.red,
    .stat-card .stat-ico.amber, body.dark-mode .stat-card .stat-ico.amber,
    .stat-card .stat-ico.green, body.dark-mode .stat-card .stat-ico.green {
        color: #fff; background: var(--tint);
        box-shadow: 0 8px 16px color-mix(in srgb, var(--tint) 40%, transparent);
    }
    .stat-card .stat-val { position: relative; z-index: 1; }
    .stat-card .stat-lbl { position: relative; z-index: 1; }

    @media (prefers-reduced-motion: reduce) { .stat-card, .stat-card::after { transition: none !important; } }
</style>
