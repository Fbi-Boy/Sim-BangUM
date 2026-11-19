/* =====================================================================
   SIM-BU CORE JS (Tema, Bahasa, SPA, Pagination)
   ===================================================================== */
window.I18N = (function () {
    var DICT_STATIC = {
        // --- UI & Navigasi ---
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
        'Selamat Datang': ['Welcome', 'مرحباً', '欢迎'],
        'Kelola dan pantau seluruh surat yang masuk ke instansi.': ['Manage and monitor all incoming letters to the agency.', 'إدارة ومراقبة جميع الرسائل الواردة إلى الوكالة.', '管理和监控机构收到的所有信件。'],
        'Total Surat': ['Total Letters', 'إجمالي الرسائل', '信件总数'],
        'Terkonfirmasi': ['Confirmed', 'مؤكد', '已确认'],
        'Butuh Disposisi': ['Needs Disposition', 'يحتاج إلى توجيه', '需要批示'],
        'Diarsipkan': ['Archived', 'مؤرشف', '已存档'],
        'Cari surat, pengirim...': ['Search letter, sender...', 'ابحث عن رسالة، مرسل...', '搜索信件、发件人...'],
        'Semua': ['All', 'الكل', '全部'],
        'Awaiting Direction': ['Awaiting Direction', 'في انتظار التوجيه', '等待批示'],
        'Registered': ['Registered', 'مسجل', '已登记'],
        'Tambah Surat': ['Add Letter', 'إضافة رسالة', '添加信件'],
        'Agenda': ['Agenda', 'جدول الأعمال', '议程'],
        'Surat & Pengirim': ['Letter & Sender', 'الرسالة والمرسل', '信件与发件人'],
        'Tanggal Diterima': ['Received Date', 'تاريخ الاستلام', '收到日期'],
        'Dari:': ['From:', 'من:', '来自：'],
        'Menampilkan': ['Showing', 'عرض', '显示'],
        'dari': ['of', 'من', '从'],
        'surat': ['letters', 'رسائل', '信件'],
    };

    var DICT_DYNAMIC = {
        'Pemberkasan': ['Filing', 'أرشفة', '归档'],
        'Peminjaman Aset': ['Asset Loan', 'استعارة الأصول', '资产借用'],
        'Kendaraan Dinas': ['Official Vehicle', 'مركبة رسمية', '公务车辆'],
        'Permintaan ATK': ['Stationery Request', 'طلب قرطاسية', '文具申请'],
        'Booking Ruangan': ['Room Booking', 'حجز غرفة', '会议室预订'],
        'Perjalanan Dinas': ['Official Travel', 'رحلة رسمية', '公务旅行'],
        'Undangan Rapat Koordinasi': ['Coordination Meeting Invitation', 'دعوة اجتماع تنسيقي', '协调会议邀请'],
    };

    var IDX = { en: 0, ar: 1, zh: 2 };
    var LOCALES = { id: 'id-ID', en: 'en-US', ar: 'ar-EG', zh: 'zh-CN' };
    var cur = 'id';
    var SKIP = { SCRIPT: 1, STYLE: 1, TEXTAREA: 1, NOSCRIPT: 1, CODE: 1, PRE: 1 };

    function translateStatic(text, lang) {
        if (lang === 'id' || !text) return text;
        var clean = text.replace(/\s+/g, ' ').trim();
        if (!clean) return text;
        if (DICT_STATIC[clean]) {
            var m = text.match(/^(\s*)[\s\S]*?(\s*)$/);
            return (m ? m[1] : '') + DICT_STATIC[clean][IDX[lang]] + (m ? m[2] : '');
        }
        if (DICT_DYNAMIC[clean]) {
            var m2 = text.match(/^(\s*)[\s\S]*?(\s*)$/);
            return (m2 ? m2[1] : '') + DICT_DYNAMIC[clean][IDX[lang]] + (m2 ? m2[2] : '');
        }
        return text;
    }

    async function translateDynamic(el, text, lang) {
        if (lang === 'id' || !text) return;
        var clean = text.replace(/\s+/g, ' ').trim();
        if (!clean) return;

        if (DICT_DYNAMIC[clean]) {
            el.innerText = DICT_DYNAMIC[clean][IDX[lang]];
            return;
        }

        var cacheKey = 'trans_' + lang + '_' + encodeURIComponent(clean);
        var cached = null;
        try { cached = localStorage.getItem(cacheKey); } catch (e) {}
        if (cached) {
            el.innerText = cached;
            return;
        }

        try {
            var token = document.querySelector('meta[name="csrf-token"]');
            var response = await fetch('/translate-batch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token ? token.content : '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ lang: lang, texts: [clean] })
            });
            var data = await response.json();
            if (data.translations && data.translations[clean]) {
                var result = data.translations[clean];
                el.innerText = result;
                try { localStorage.setItem(cacheKey, result); } catch (e) {}
            }
        } catch (e) {
            console.warn('Translate API error:', e);
        }
    }

    function applyTranslations(lang) {
        if (lang === 'id') return;

        var staticSelectors = ['.sidebar', '.topbar', '.page-head', '.stat-grid', '.table-toolbar', 'thead', '.sbp', '.footer', '.btn-open', '.badge', 'label', 'th'].join(',');
        document.querySelectorAll(staticSelectors).forEach(function (el) {
            if (el.closest('[data-no-i18n]')) return;
            var walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null);
            var node;
            while ((node = walker.nextNode())) {
                if (!node.nodeValue.trim()) continue;
                if (node.parentElement && node.parentElement.closest('[data-no-i18n]')) continue;
                if (SKIP[node.parentElement.tagName]) continue;
                var translated = translateStatic(node.nodeValue, lang);
                if (translated !== node.nodeValue) node.nodeValue = translated;
            }
        });

        document.querySelectorAll('[placeholder], [title], [aria-label]').forEach(function (el) {
            if (el.closest('[data-no-i18n]')) return;
            ['placeholder', 'title', 'aria-label'].forEach(function (attr) {
                if (!el.hasAttribute(attr)) return;
                var val = el.getAttribute(attr);
                var translated = translateStatic(val, lang);
                if (translated !== val) el.setAttribute(attr, translated);
            });
        });

        document.querySelectorAll('[data-translate]').forEach(function (el) {
            if (el.dataset.isTranslated === lang) return;
            var text = el.innerText.trim();
            if (text.length > 0 && text.length < 500) {
                translateDynamic(el, text, lang);
                el.dataset.isTranslated = lang;
            }
        });
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
        document.querySelectorAll('.lang-item').forEach(function (b) {
            b.classList.toggle('active', b.getAttribute('data-lang') === lang);
        });

        document.querySelectorAll('[data-is-translated]').forEach(function (el) {
            delete el.dataset.isTranslated;
        });

        applyTranslations(lang);
        updateDate(lang);
        window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang: lang, rtl: rtl } }));
    }

    function updateDate(lang) {
        var el = document.getElementById('tb-date');
        if (!el) return;
        try {
            el.textContent = new Date().toLocaleDateString(LOCALES[lang] || 'id-ID', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
        } catch (e) {}
    }

    function init() {
        var saved = 'id';
        try { saved = localStorage.getItem('lang') || 'id'; } catch (e) {}
        if (!LOCALES[saved]) saved = 'id';
        set(saved);
    }

    return {
        set: set,
        get: function () { return cur; },
        apply: function () { applyTranslations(cur); },
        init: init,
        extend: function (staticDict, dynamicDict) {
            if (staticDict) Object.assign(DICT_STATIC, staticDict);
            if (dynamicDict) Object.assign(DICT_DYNAMIC, dynamicDict);
        }
    };
})();

window.toggleTheme = function() {
    var dark = document.body.classList.toggle('dark-mode');
    try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
};

window.setLanguage = function(lang) {
    var langBox = document.getElementById('langBox');
    if (langBox) langBox.classList.remove('open');
    I18N.set(lang);
};

window.showProgress = function() {
    var bar = document.getElementById('nprogress-bar');
    if (bar) { bar.style.width = '30%'; setTimeout(function () { bar.style.width = '70%'; }, 200); }
};

window.hideProgress = function() {
    var bar = document.getElementById('nprogress-bar');
    if (bar) { bar.style.width = '100%'; setTimeout(function () { bar.style.width = '0%'; }, 300); }
};

window.runScripts = function(container) {
    container.querySelectorAll('script').forEach(function (oldScript) {
        var newScript = document.createElement('script');
        Array.from(oldScript.attributes).forEach(function (attr) { newScript.setAttribute(attr.name, attr.value); });
        newScript.textContent = oldScript.textContent;
        oldScript.parentNode.replaceChild(newScript, oldScript);
    });
};

document.addEventListener('DOMContentLoaded', function() {
    I18N.init();

    var langBox = document.getElementById('langBox');
    if (langBox) {
        document.getElementById('langBtn').addEventListener('click', function (e) {
            e.stopPropagation();
            langBox.classList.toggle('open');
        });
    }
    document.addEventListener('click', function (e) {
        if (langBox && !langBox.contains(e.target)) langBox.classList.remove('open');
    });

    var userBox = document.getElementById('userBox');
    if (userBox) {
        document.getElementById('userBtn').addEventListener('click', function (e) {
            e.stopPropagation();
            userBox.classList.toggle('open');
        });
    }
    document.addEventListener('click', function (e) {
        if (userBox && !userBox.contains(e.target)) userBox.classList.remove('open');
    });

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
                I18N.apply();
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
                I18N.apply();
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
