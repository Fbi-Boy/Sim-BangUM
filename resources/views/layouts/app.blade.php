<!DOCTYPE html>
<html lang="id">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.ui')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;500;600;700&family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg-body: #f6f7fb; --bg-card: #ffffff; --bg-topbar: #ffffff; --bg-hover: #f3f5fb;
    --bg-soft: #f3f6fd; --text-main: #111827; --text-muted: #6b7280; --border-color: #e6e9f2;
    --table-header: #f9fafb; --accent: #2563eb; --accent-hover: #1d4ed8; --accent-soft: #eef3ff;
    --shadow-pop: 0 18px 50px rgba(20, 30, 80, .16);
    --font-main: 'Poppins', sans-serif;
}
body.dark-mode {
    --bg-body: #0f172a; --bg-card: #1e293b; --bg-topbar: #1e293b; --bg-hover: #334155;
    --bg-soft: rgba(255, 255, 255, .06); --text-main: #f8fafc; --text-muted: #94a3b8;
    --border-color: #334155; --table-header: #0f172a; --accent: #60a5fa; --accent-hover: #93c5fd;
    --accent-soft: rgba(59, 130, 246, .16); --shadow-pop: 0 18px 50px rgba(0, 0, 0, .45);
}
/* Font menyesuaikan bahasa */
body[data-lang="ar"] { --font-main: 'Noto Sans Arabic', 'Poppins', sans-serif; }
body[data-lang="zh"] { --font-main: 'Noto Sans SC', 'Poppins', sans-serif; }

* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: var(--font-main); background: var(--bg-body); color: var(--text-main); font-size: 14px; transition: background 0.3s, color 0.3s; }
button, input, select, textarea { font-family: inherit; }
.wrap { display: flex; min-height: 100vh; }

.main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
.topbar {
    position: sticky; top: 0; z-index: 50; height: 68px; padding: 0 28px; gap: 16px;
    display: flex; justify-content: space-between; align-items: center;
    background: var(--bg-topbar); border-bottom: 1px solid var(--border-color);
    transition: background .3s, border-color .3s;
}
.topbar-left { min-width: 0; }
.topbar-title { font-size: 18px; font-weight: 700; letter-spacing: -.2px; line-height: 1.2; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.topbar-date { display: flex; align-items: center; gap: 7px; margin-top: 3px; font-size: 11.5px; font-weight: 500; color: var(--text-muted); }
.topbar-date::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.18); }

.topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

.btn-icon {
    width: 42px; height: 42px; border-radius: 12px; border: 1px solid transparent; background: var(--bg-soft);
    color: var(--text-main); display: grid; place-items: center; cursor: pointer; transition: background .2s, color .2s, transform .2s;
    position: relative;
}
.btn-icon:hover { background: var(--accent-soft); color: var(--accent); }
.btn-icon:active { transform: scale(.94); }
.btn-icon svg { width: 19px; height: 19px; }
.btn-icon .i-sun { display: none; }
body.dark-mode .btn-icon .i-sun { display: block; }
body.dark-mode .btn-icon .i-moon { display: none; }

/* ===== TOMBOL BAHASA ===== */
.lang-box { position: relative; }
.lang-btn {
    height: 42px; padding: 0 12px 0 10px; border-radius: 12px; border: 1px solid transparent; background: var(--bg-soft);
    color: var(--text-main); display: flex; align-items: center; gap: 8px; cursor: pointer;
    transition: background .2s, color .2s, border-color .2s, transform .2s;
}
.lang-btn:hover, .lang-box.open .lang-btn { background: var(--accent-soft); color: var(--accent); }
.lang-box.open .lang-btn { border-color: var(--accent); }
.lang-btn:active { transform: scale(.96); }
.lang-btn svg { width: 18px; height: 18px; }
.lang-btn .lang-cur { font-size: 12.5px; font-weight: 700; letter-spacing: .6px; }

.lang-menu {
    position: absolute; right: 0; top: calc(100% + 10px); width: 190px; padding: 6px; z-index: 70;
    background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 14px; box-shadow: var(--shadow-pop);
    opacity: 0; transform: translateY(-6px) scale(.98); transform-origin: top right; pointer-events: none; transition: opacity .18s, transform .18s;
}
.lang-box.open .lang-menu { opacity: 1; transform: none; pointer-events: auto; }
.lang-item {
    display: flex; align-items: center; gap: 10px; width: 100%; padding: 8px 10px; border: none; border-radius: 8px;
    background: none; cursor: pointer; font-size: 13px; font-weight: 500; color: var(--text-main); text-align: left;
}
.lang-item:hover { background: var(--bg-hover); color: var(--accent); }
.lang-item.active { background: var(--accent-soft); color: var(--accent); font-weight: 600; }
.lang-item .lang-name { flex: 1; }
.lang-item .lang-check { width: 15px; height: 15px; opacity: 0; color: var(--accent); }
.lang-item.active .lang-check { opacity: 1; }
.lang-code {
    min-width: 34px; padding: 3px 0; border-radius: 7px; text-align: center; color: #fff;
    font-size: 10.5px; font-weight: 700; letter-spacing: .6px; flex-shrink: 0;
}
.lang-code.c-id { background: #dc2626; }
.lang-code.c-en { background: #1d4ed8; }
.lang-code.c-ar { background: #059669; }
.lang-code.c-zh { background: #d97706; }

.user-box { position: relative; }
.user-chip {
    display: flex; align-items: center; gap: 10px; padding: 4px 12px 4px 4px; border-radius: 14px;
    background: var(--bg-soft); border: 1px solid transparent; cursor: pointer; color: var(--text-main); text-align: left;
    transition: background .2s, border-color .2s, box-shadow .2s;
}
.user-chip:hover, .user-box.open .user-chip { background: var(--accent-soft); }
.user-box.open .user-chip { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }
.avatar {
    width: 36px; height: 36px; border-radius: 11px; flex-shrink: 0; display: grid; place-items: center;
    background: linear-gradient(135deg, #2433d6 0%, #4f6bff 60%, #6aa0ff 100%); color: #fff;
    font-size: 12.5px; font-weight: 700; letter-spacing: .5px; box-shadow: 0 6px 14px rgba(47,63,224,.28);
}
.avatar.lg { width: 44px; height: 44px; border-radius: 13px; font-size: 14px; }
.user-text { min-width: 0; line-height: 1.25; }
.user-name { font-size: 13px; font-weight: 600; color: var(--text-main); max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-role { font-size: 10.5px; font-weight: 500; color: var(--text-muted); max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-chev { width: 14px; height: 14px; color: var(--text-muted); transition: transform .25s; flex-shrink: 0; }
.user-box.open .user-chev { transform: rotate(180deg); color: var(--accent); }

.user-menu {
    position: absolute; right: 0; top: calc(100% + 10px); width: 270px; padding: 8px; z-index: 70;
    background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 18px; box-shadow: var(--shadow-pop);
    opacity: 0; transform: translateY(-6px) scale(.98); transform-origin: top right; pointer-events: none; transition: opacity .18s, transform .18s;
}
.user-box.open .user-menu { opacity: 1; transform: none; pointer-events: auto; }
.menu-head { display: flex; align-items: center; gap: 12px; padding: 10px 10px 12px; }
.menu-head .who { min-width: 0; }
.menu-head .who strong { display: block; font-size: 13.5px; font-weight: 600; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.menu-head .who span { display: block; font-size: 11.5px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.role-chips { display: flex; flex-wrap: wrap; gap: 6px; padding: 0 10px 12px; }
.role-chip { font-size: 10.5px; font-weight: 600; padding: 3px 9px; border-radius: 20px; background: var(--accent-soft); color: var(--accent); }
.menu-sep { height: 1px; background: var(--border-color); margin: 2px 4px 6px; }
.menu-logout {
    width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: none; border-radius: 11px;
    background: none; cursor: pointer; font-size: 13px; font-weight: 500; color: var(--text-muted); text-align: left; transition: background .2s, color .2s;
}
.menu-logout:hover { background: #fee2e2; color: #b91c1c; }
body.dark-mode .menu-logout:hover { background: rgba(239,68,68,.16); color: #fca5a5; }
.menu-logout svg { width: 18px; height: 18px; }

.content { padding: 24px 28px; flex: 1; transition: opacity .2s; }
.footer { text-align: center; padding: 16px; color: var(--text-muted); font-size: 11.5px; border-top: 1px solid var(--border-color); }

.card { background: var(--bg-card); border-radius: 14px; padding: 20px; border: 1px solid var(--border-color); margin-bottom: 16px; }
table { width: 100%; border-collapse: collapse; background: var(--bg-card); font-size: 13.5px; }
th { padding: 10px 12px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 12px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); background: var(--table-header); }
td { padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-main); }
tr:hover td { background: var(--bg-hover); }
.btn { display: inline-block; padding: 8px 14px; background: #2563eb; color: #fff; border: none; border-radius: 10px; cursor: pointer; text-decoration: none; font-size: 13px; font-weight: 500; }

/* Loading bar di atas */
#nprogress-bar {
    position: fixed; top: 0; left: 0; height: 3px; width: 0%;
    background: var(--accent); z-index: 9999; transition: width 0.3s ease;
}

/* === RTL SUPPORT === */
body[dir="rtl"] { direction: rtl; text-align: right; }
body[dir="rtl"] #nprogress-bar { left: auto; right: 0; }
body[dir="rtl"] .topbar-left { text-align: right; }
body[dir="rtl"] .lang-menu, body[dir="rtl"] .user-menu { right: auto; left: 0; transform-origin: top left; }
body[dir="rtl"] .lang-item, body[dir="rtl"] .menu-logout, body[dir="rtl"] .user-chip { text-align: right; }
body[dir="rtl"] .user-chip { padding: 4px 4px 4px 12px; }
body[dir="rtl"] th, body[dir="rtl"] td { text-align: right; }
body[dir="rtl"] input, body[dir="rtl"] select, body[dir="rtl"] textarea { text-align: right; }
body[dir="rtl"] input[type="email"], body[dir="rtl"] input[type="url"], body[dir="rtl"] input[type="number"] { direction: ltr; text-align: right; }
body[dir="rtl"] .sidebar { border-right: none; border-left: 1px solid var(--border-color); }
</style>
</head>
<body>
<div id="nprogress-bar"></div>

<script>
    // Terapkan tema, bahasa & arah tulisan sedini mungkin (mencegah kedipan)
    try { if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode'); } catch (e) {}
    try {
        var _l = localStorage.getItem('lang') || 'id';
        document.body.setAttribute('dir', _l === 'ar' ? 'rtl' : 'ltr');
        document.body.setAttribute('data-lang', _l);
        document.documentElement.setAttribute('lang', _l === 'zh' ? 'zh-CN' : _l);
        document.documentElement.setAttribute('dir', _l === 'ar' ? 'rtl' : 'ltr');
    } catch (e) {}
</script>

@php
    $authUser = auth()->user();
    $initials = mb_strtoupper(collect(preg_split('/\s+/', trim($authUser->name)))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join(''));
    $roleNames = $authUser->roles->pluck('name')->map(fn($r) => \Illuminate\Support\Str::headline($r));
@endphp

<div class="wrap">
    @include('layouts.sidebar')
    <div class="main">
        <div class="topbar">
            <div class="topbar-left">
                <div class="topbar-title" id="topbar-title">@yield('title', 'Dashboard')</div>
                <div class="topbar-date" id="tb-date" data-no-i18n>SIM-BU</div>
            </div>

            <div class="topbar-right">
                <!-- BAHASA -->
                <div class="lang-box" id="langBox">
                    <button type="button" class="lang-btn" id="langBtn" title="Ubah Bahasa">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
                        <span class="lang-cur" id="langCur" data-no-i18n>ID</span>
                    </button>
                    <div class="lang-menu" role="menu" data-no-i18n>
                        <button type="button" class="lang-item" data-lang="id" onclick="setLanguage('id')">
                            <span class="lang-code c-id">ID</span><span class="lang-name">Indonesia</span>
                            <svg class="lang-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                        <button type="button" class="lang-item" data-lang="en" onclick="setLanguage('en')">
                            <span class="lang-code c-en">EN</span><span class="lang-name">English</span>
                            <svg class="lang-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                        <button type="button" class="lang-item" data-lang="ar" onclick="setLanguage('ar')">
                            <span class="lang-code c-ar">AR</span><span class="lang-name">العربية</span>
                            <svg class="lang-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                        <button type="button" class="lang-item" data-lang="zh" onclick="setLanguage('zh')">
                            <span class="lang-code c-zh">ZH</span><span class="lang-name">中文 (China)</span>
                            <svg class="lang-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M20 6 9 17l-5-5"/></svg>
                        </button>
                    </div>
                </div>

                <!-- TEMA -->
                <button type="button" class="btn-icon" onclick="toggleTheme()" id="theme-btn" title="Ubah tema">
                    <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
                    <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                </button>

                <!-- USER -->
                <div class="user-box" id="userBox">
                    <button type="button" class="user-chip" id="userBtn">
                        <span class="avatar" data-no-i18n>{{ $initials }}</span>
                        <span class="user-text">
                            <div class="user-name" data-no-i18n>{{ $authUser->name }}</div>
                            <div class="user-role">{{ $roleNames->join(', ') ?: 'Tanpa role' }}</div>
                        </span>
                        <svg class="user-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="user-menu" role="menu">
                        <div class="menu-head">
                            <span class="avatar lg" data-no-i18n>{{ $initials }}</span>
                            <div class="who" data-no-i18n><strong>{{ $authUser->name }}</strong><span>{{ $authUser->email }}</span></div>
                        </div>
                        <div class="role-chips">
                            @forelse($roleNames as $rn) <span class="role-chip">{{ $rn }}</span> @empty <span class="role-chip">Tanpa role</span> @endforelse
                        </div>
                        <div class="menu-sep"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="menu-logout" role="menuitem">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- KONTEN UTAMA (ID: app-content untuk SPA Router) -->
        <div class="content" id="app-content">
            @yield('content')
        </div>

        <div class="footer">SIM-BU v1.0 &copy; {{ date('Y') }} <span>Pemda · Bagian Umum</span></div>
    </div>
</div>

<script>
/* =====================================================================
   SISTEM MULTI-BAHASA (i18n) + AUTO-TRANSLATE SERVER
   ===================================================================== */
window.I18N = (function () {
    var DICT = {
        'Dashboard': ['Dashboard', 'لوحة التحكم', '仪表板'],
        'Menu': ['Menu', 'القائمة', '菜单'],
        'Keluar': ['Logout', 'تسجيل الخروج', '退出'],
        'Simpan': ['Save', 'حفظ', '保存'],
        'Batal': ['Cancel', 'إلغاء', '取消'],
        'Tambah Data': ['Add Data', 'إضافة بيانات', '添加数据'],
        'Cari': ['Search', 'بحث', '搜索'],
        'Menunggu': ['Pending', 'قيد الانتظار', '待处理'],
        'Disetujui': ['Approved', 'تمت الموافقة', '已批准'],
        'Ditolak': ['Rejected', 'مرفوض', '已拒绝'],
        'Tanpa role': ['No role', 'بدون دور', '无角色'],
        'Pemda · Bagian Umum': ['Local Government · General Affairs', 'الحكومة المحلية · الشؤون العامة', '地方政府 · 总务部'],
        'Surat Masuk': ['Incoming Mail', 'البريد الوارد', '收文'],
        'Surat Keluar': ['Outgoing Mail', 'البريد الصادر', '发文'],
        'Disposisi': ['Disposition', 'التوجيه', '批示'],
        'Arsip': ['Archive', 'الأرشيف', '档案'],
        'Inventaris': ['Inventory', 'المخزون', '库存'],
        'Aset': ['Assets', 'الأصول', '资产'],
        'Laporan': ['Reports', 'التقارير', '报告'],
        'Pengaturan': ['Settings', 'الإعدادات', '设置'],
        'Profil': ['Profile', 'الملف الشخصي', '个人资料'],
        'Pengguna': ['Users', 'المستخدمون', '用户'],
        'Peran': ['Roles', 'الأدوار', '角色'],
        'Hak Akses': ['Permissions', 'الصلاحيات', '权限'],
        'Data Master': ['Master Data', 'البيانات الرئيسية', '主数据'],
        'Pegawai': ['Employees', 'الموظفون', '员工'],
        'Jabatan': ['Position', 'المنصب', '职位'],
        'Unit Kerja': ['Work Unit', 'وحدة العمل', '工作单位'],
        'Bidang': ['Division', 'القسم', '部门'],
        'Kategori': ['Category', 'الفئة', '类别'],
        'Jenis': ['Type', 'النوع', '类型'],
        'Riwayat': ['History', 'السجل', '历史'],
        'Notifikasi': ['Notifications', 'الإشعارات', '通知'],
        'Bantuan': ['Help', 'مساعدة', '帮助'],
        'No': ['No', 'م', '序号'],
        'No.': ['No.', 'رقم', '编号'],
        'Nama': ['Name', 'الاسم', '姓名'],
        'Nama Lengkap': ['Full Name', 'الاسم الكامل', '全名'],
        'Email': ['Email', 'البريد الإلكتروني', '电子邮件'],
        'Kata Sandi': ['Password', 'كلمة المرور', '密码'],
        'Password': ['Password', 'كلمة المرور', '密码'],
        'Konfirmasi Kata Sandi': ['Confirm Password', 'تأكيد كلمة المرور', '确认密码'],
        'Telepon': ['Phone', 'الهاتف', '电话'],
        'Alamat': ['Address', 'العنوان', '地址'],
        'Tanggal': ['Date', 'التاريخ', '日期'],
        'Waktu': ['Time', 'الوقت', '时间'],
        'Status': ['Status', 'الحالة', '状态'],
        'Aksi': ['Actions', 'الإجراءات', '操作'],
        'Keterangan': ['Notes', 'ملاحظات', '备注'],
        'Deskripsi': ['Description', 'الوصف', '描述'],
        'Perihal': ['Subject', 'الموضوع', '事由'],
        'Nomor Surat': ['Letter Number', 'رقم الخطاب', '文号'],
        'Tanggal Surat': ['Letter Date', 'تاريخ الخطاب', '文件日期'],
        'Pengirim': ['Sender', 'المرسل', '发件人'],
        'Penerima': ['Recipient', 'المستلم', '收件人'],
        'Lampiran': ['Attachment', 'المرفق', '附件'],
        'Jumlah': ['Quantity', 'الكمية', '数量'],
        'Total': ['Total', 'المجموع', '总计'],
        'Harga': ['Price', 'السعر', '价格'],
        'Dibuat': ['Created', 'تاريخ الإنشاء', '创建时间'],
        'Diperbarui': ['Updated', 'تاريخ التحديث', '更新时间'],
        'Tambah': ['Add', 'إضافة', '添加'],
        'Tambah Baru': ['Add New', 'إضافة جديد', '新增'],
        'Simpan Perubahan': ['Save Changes', 'حفظ التغييرات', '保存更改'],
        'Hapus': ['Delete', 'حذف', '删除'],
        'Edit': ['Edit', 'تعديل', '编辑'],
        'Ubah': ['Edit', 'تعديل', '修改'],
        'Detail': ['Details', 'التفاصيل', '详情'],
        'Lihat': ['View', 'عرض', '查看'],
        'Kembali': ['Back', 'رجوع', '返回'],
        'Tutup': ['Close', 'إغلاق', '关闭'],
        'Kirim': ['Send', 'إرسال', '发送'],
        'Cari...': ['Search...', 'بحث...', '搜索...'],
        'Filter': ['Filter', 'تصفية', '筛选'],
        'Reset': ['Reset', 'إعادة تعيين', '重置'],
        'Ekspor': ['Export', 'تصدير', '导出'],
        'Impor': ['Import', 'استيراد', '导入'],
        'Cetak': ['Print', 'طباعة', '打印'],
        'Unduh': ['Download', 'تنزيل', '下载'],
        'Unggah': ['Upload', 'رفع', '上传'],
        'Pilih': ['Select', 'اختيار', '选择'],
        '-- Pilih --': ['-- Select --', '-- اختر --', '-- 请选择 --'],
        'Ya': ['Yes', 'نعم', '是'],
        'Tidak': ['No', 'لا', '否'],
        'Setuju': ['Approve', 'موافقة', '批准'],
        'Tolak': ['Reject', 'رفض', '拒绝'],
        'Selesai': ['Done', 'منتهي', '完成'],
        'Lanjut': ['Next', 'التالي', '继续'],
        'Aktif': ['Active', 'نشط', '启用'],
        'Nonaktif': ['Inactive', 'غير نشط', '停用'],
        'Diproses': ['Processing', 'قيد المعالجة', '处理中'],
        'Baru': ['New', 'جديد', '新'],
        'Tidak ada data': ['No data available', 'لا توجد بيانات', '暂无数据'],
        'Data tidak ditemukan': ['No data found', 'لم يتم العثور على بيانات', '未找到数据'],
        'Memuat...': ['Loading...', 'جارٍ التحميل...', '加载中...'],
        'Berhasil': ['Success', 'تم بنجاح', '成功'],
        'Gagal': ['Failed', 'فشل', '失败'],
        'Sebelumnya': ['Previous', 'السابق', '上一页'],
        'Selanjutnya': ['Next', 'التالي', '下一页'],
        'Berikutnya': ['Next', 'التالي', '下一页'],
        'Tampilkan': ['Show', 'عرض', '显示'],
        'per halaman': ['per page', 'لكل صفحة', '每页'],
        'Halaman': ['Page', 'صفحة', '页'],
        'Apakah Anda yakin?': ['Are you sure?', 'هل أنت متأكد؟', '您确定吗?'],
        'Apakah Anda yakin ingin menghapus data ini?': ['Are you sure you want to delete this data?', 'هل أنت متأكد أنك تريد حذف هذه البيانات؟', '您确定要删除此数据吗?'],
        'Selamat Datang': ['Welcome', 'مرحباً', '欢迎']
    };

    var IDX = { en: 0, ar: 1, zh: 2 };
    var LOWER = {};
    var cur = 'id';
    var textStore = new WeakMap();
    var attrStore = new WeakMap();
    var ATTRS = ['placeholder', 'title', 'aria-label', 'alt', 'data-short'];
    var SKIP = { SCRIPT: 1, STYLE: 1, TEXTAREA: 1, NOSCRIPT: 1, CODE: 1, PRE: 1 };
    var observer = null, pending = false, applying = false;

    var MISSING = {}, TRIED = {}, fetching = false;

    function rebuildLower() { LOWER = {}; Object.keys(DICT).forEach(function (k) { LOWER[k.toLowerCase()] = DICT[k]; }); }
    if (window.I18N_EXTRA) Object.keys(window.I18N_EXTRA).forEach(function (k) { DICT[k] = window.I18N_EXTRA[k]; });
    rebuildLower();

    function norm(s) { return s.replace(/\s+/g, ' ').trim(); }
    function lookup(s) { var k = norm(s); if (!k) return null; return DICT[k] || LOWER[k.toLowerCase()] || null; }

    function translate(orig, lang) {
        if (lang === 'id') return orig;
        var k = norm(orig);
        var hit = lookup(orig);
        var t = hit ? hit[IDX[lang]] : null;

        if (!t) {
            // Jika tidak ada di kamus, catat untuk diterjemahkan ke server (hanya jika ada huruf)
            if (k && /\p{L}/u.test(k) && k.length <= 1000 && k.length > 2 && !(TRIED[lang] && TRIED[lang][k])) {
                MISSING[lang] = MISSING[lang] || {};
                MISSING[lang][k] = 1;
            }
            return orig;
        }
        var m = orig.match(/^(\s*)[\s\S]*?(\s*)$/);
        return (m ? m[1] : '') + t + (m ? m[2] : '');
    }

    function skipNode(el) {
        while (el && el.nodeType === 1) {
            if (SKIP[el.tagName] || el.hasAttribute('data-no-i18n')) return true;
            el = el.parentNode;
        }
        return false;
    }

    function applyTo(root, lang) {
        if (!root) return;
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
        var n, nodes = [];
        while ((n = walker.nextNode())) nodes.push(n);

        nodes.forEach(function (node) {
            if (!node.data.trim() || skipNode(node.parentNode)) return;
            var rec = textStore.get(node);
            if (!rec || node.data !== rec.set) rec = { orig: node.data, set: node.data };
            var out = translate(rec.orig, lang);
            rec.set = out;
            textStore.set(node, rec);
            if (node.data !== out) node.data = out;
        });

        var els = root.nodeType === 1 ? [root] : [];
        var q = root.querySelectorAll ? root.querySelectorAll('[placeholder],[title],[aria-label],[alt],[data-short],input[type=submit],input[type=button]') : [];
        els = els.concat(Array.prototype.slice.call(q));
        els.forEach(function (el) {
            if (skipNode(el)) return;
            var store = attrStore.get(el) || {};
            var list = ATTRS.slice();
            if (el.tagName === 'INPUT' && (el.type === 'submit' || el.type === 'button')) list.push('value');
            list.forEach(function (a) {
                if (!el.hasAttribute(a)) return;
                var val = el.getAttribute(a);
                var rec = store[a];
                if (!rec || val !== rec.set) rec = { orig: val, set: val };
                var out = translate(rec.orig, lang);
                rec.set = out; store[a] = rec;
                if (val !== out) el.setAttribute(a, out);
            });
            attrStore.set(el, store);
        });
    }

    function run(root) {
        applying = true;
        if (observer) observer.disconnect();
        try { applyTo(root || document.body, cur); } catch (e) { console.error(e); }
        startObserver();
        applying = false;
        fetchMissing();
    }

    function startObserver() {
        if (!observer) {
            observer = new MutationObserver(function () {
                if (applying || pending) return;
                pending = true;
                requestAnimationFrame(function () { pending = false; run(document.body); });
            });
        }
        observer.observe(document.body, { childList: true, subtree: true, characterData: true });
    }

    // ===== FUNGSI AJAX KE SERVER =====
    function fetchMissing() {
        var lang = cur;
        if (lang === 'id' || fetching) return;

        var list = Object.keys(MISSING[lang] || {});
        if (list.length === 0) return;

        var chunk = list.slice(0, 50); // Maksimal 50 teks per request
        fetching = true;

        var tk = document.querySelector('meta[name="csrf-token"]');
        var token = tk ? tk.content : '';

        fetch('/translate-batch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ lang: lang, texts: chunk })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            TRIED[lang] = TRIED[lang] || {};
            chunk.forEach(function(k){
                delete MISSING[lang][k];
                TRIED[lang][k] = 1;
            });

            var newDict = {};
            var tr = res.translations || {};
            Object.keys(tr).forEach(function (orig) {
                var arr = ['', '', ''];
                arr[IDX[lang]] = tr[orig];
                newDict[orig] = arr;
            });
            I18N.extend(newDict);

            fetching = false;
            // Jika masih ada sisa teks, lanjut translate setelah 1 detik
            if (Object.keys(MISSING[lang] || {}).length > 0) {
                setTimeout(fetchMissing, 1000);
            }
        })
        .catch(function () {
            chunk.forEach(function(k){ TRIED[lang][k] = 1; });
            fetching = false;
        });
    }

    var LOCALES = { id: 'id-ID', en: 'en-US', ar: 'ar-EG', zh: 'zh-CN' };
    function updateDate() {
        var el = document.getElementById('tb-date');
        if (!el) return;
        try { el.textContent = new Date().toLocaleDateString(LOCALES[cur] || 'id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }); } catch (e) {}
    }

    function set(lang) {
        if (!LOCALES[lang]) lang = 'id';
        cur = lang;
        try { localStorage.setItem('lang', lang); } catch (e) {}
        var rtl = lang === 'ar';
        document.body.setAttribute('dir', rtl ? 'rtl' : 'ltr');
        document.body.setAttribute('data-lang', lang);
        document.documentElement.setAttribute('dir', rtl ? 'rtl' : 'ltr');
        document.documentElement.setAttribute('lang', lang === 'zh' ? 'zh-CN' : lang);

        var code = document.getElementById('langCur');
        if (code) code.textContent = lang.toUpperCase();
        document.querySelectorAll('.lang-item').forEach(function (b) { b.classList.toggle('active', b.getAttribute('data-lang') === lang); });

        updateDate();
        TRIED = {}; // Reset agar bisa fetch ulang jika ada update
        run(document.body);
        window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang: lang, rtl: rtl } }));
    }

    function init() {
        try { cur = localStorage.getItem('lang') || 'id'; } catch (e) { cur = 'id'; }
        if (!LOCALES[cur]) cur = 'id';
        set(cur);
    }

    return {
        set: set, init: init, apply: run, get: function () { return cur; },
        extend: function (obj) { Object.keys(obj).forEach(function (k) { DICT[k] = obj[k]; }); rebuildLower(); run(document.body); },
        t: function (idText) { return translate(idText, cur); }
    };
})();

// ========== THEME & LANGUAGE ==========
function toggleTheme() { var dark = document.body.classList.toggle('dark-mode'); try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {} }
function setLanguage(lang) {
    var langBox = document.getElementById('langBox');
    if(langBox) langBox.classList.remove('open');
    I18N.set(lang);
}

var langBox = document.getElementById('langBox');
if(langBox) {
    document.getElementById('langBtn').addEventListener('click', function (e) { e.stopPropagation(); langBox.classList.toggle('open'); });
}
document.addEventListener('click', function (e) { if (langBox && !langBox.contains(e.target)) langBox.classList.remove('open'); });
(function () { var box = document.getElementById('userBox'); if(!box) return; var btn = document.getElementById('userBtn'); btn.addEventListener('click', function (e) { e.stopPropagation(); box.classList.toggle('open'); }); document.addEventListener('click', function (e) { if (!box.contains(e.target)) box.classList.remove('open'); }); })();

I18N.init();

// ========== PROGRESS BAR ==========
window.showProgress = function() {
    var bar = document.getElementById('nprogress-bar');
    if(bar) { bar.style.width = '30%'; setTimeout(function() { bar.style.width = '70%'; }, 200); }
};
window.hideProgress = function() {
    var bar = document.getElementById('nprogress-bar');
    if(bar) { bar.style.width = '100%'; setTimeout(function() { bar.style.width = '0%'; }, 300); }
};

// ========== RE-EXECUTE SCRIPTS AFTER SPA NAVIGATION ==========
window.runScripts = function(container) {
    var scripts = container.querySelectorAll('script');
    scripts.forEach(function(oldScript) {
        var newScript = document.createElement('script');
        Array.from(oldScript.attributes).forEach(function(attr) {
            newScript.setAttribute(attr.name, attr.value);
        });
        newScript.textContent = oldScript.textContent;
        oldScript.parentNode.replaceChild(newScript, oldScript);
    });
};

// ========== SPA ROUTER (Navigasi Tanpa Refresh) ==========
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        var link = e.target.closest('a');
        if (!link || !link.getAttribute('href')) return;
        var href = link.getAttribute('href');
        if (href === '#' || href.startsWith('#') || link.getAttribute('target') === '_blank' || href.indexOf('logout') > -1 || link.hasAttribute('download')) return;

        var url = new URL(link.href);
        if (url.origin !== window.location.origin) return;

        if (link.closest('.sidebar') || link.closest('.topbar') || link.classList.contains('quick-card')) {
            e.preventDefault();
            navigateTo(url.href);
        }
    });

    window.addEventListener('popstate', function() {
        navigateTo(window.location.href, false);
    });

    window.navigateTo = function(url, pushState) {
        if (pushState === undefined) pushState = true;
        var content = document.getElementById('app-content');
        if (!content) { window.location.href = url; return; }

        showProgress();
        content.style.opacity = '0.4';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.text(); })
        .then(function(html) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');

            var newContent = doc.getElementById('app-content');
            var newTitle = doc.querySelector('title');
            var newTopbarTitle = doc.querySelector('.topbar-title');

            if (newContent) {
                content.innerHTML = newContent.innerHTML;
                content.style.opacity = '1';

                if (newTitle) document.title = newTitle.innerText;
                if (newTopbarTitle) document.getElementById('topbar-title').innerText = newTopbarTitle.innerText;

                if (pushState) window.history.pushState({}, '', url);
                window.scrollTo({ top: 0, behavior: 'smooth' });

                runScripts(content);
                I18N.apply(document.body);
            } else {
                window.location.href = url;
            }
            hideProgress();
        })
        .catch(function(err) {
            console.error(err);
            window.location.href = url;
        });
    };
});

// ========== AJAX PAGINATION ==========
document.addEventListener("DOMContentLoaded", function() {
    document.body.addEventListener('click', function(e) {
        var link = e.target.closest('.sbp-list a');
        if (link && link.getAttribute('href') && link.getAttribute('href') !== '#') {
            e.preventDefault();
            loadAjaxPage(link.getAttribute('href'));
        }
    });

    document.body.addEventListener('change', function(e) {
        if (e.target.classList.contains('ajax-per-page')) {
            loadAjaxPage(e.target.value);
        }
    });

    window.loadAjaxPage = function(url) {
        var container = document.getElementById('ajax-table-container') || document.querySelector('.table-card');
        if (!container) return;

        showProgress();
        container.style.opacity = '0.4';
        container.style.pointerEvents = 'none';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.text(); })
        .then(function(html) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');
            var newContent = doc.getElementById('ajax-table-container') || doc.querySelector('.table-card');

            if (newContent && container) {
                container.innerHTML = newContent.innerHTML;
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
                window.history.pushState({}, '', url);

                runScripts(container);
                I18N.apply(container);
            }
            hideProgress();
        })
        .catch(function(error) {
            console.error('Error:', error);
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
            hideProgress();
        });
    };
});
</script>
</body>
</html>
