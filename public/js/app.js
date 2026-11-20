/* =====================================================================
   SIM-BU CHAR CONVERT — Konversi Karakter untuk Data Unik
   ===================================================================== */


   window.CharConvert = (function () {
    'use strict';

    var DIGITS = { ar: '٠١٢٣٤٥٦٧٨٩', zh: '〇一二三四五六七八九' };

    var LATIN_TO_AR = {
        'a':'ا','b':'بي','c':'سي','d':'دي','e':'إي','f':'إف','g':'جي','h':'إتش',
        'i':'آي','j':'جيه','k':'كيه','l':'إل','m':'إم','n':'إن','o':'أو','p':'بي',
        'q':'كيو','r':'آر','s':'إس','t':'تي','u':'يو','v':'في','w':'دبليو','x':'إكس',
        'y':'واي','z':'زد'
    };

    var LATIN_TO_ZH = {
        'a':'诶','b':'比','c':'西','d':'迪','e':'伊','f':'艾弗','g':'吉','h':'艾尺',
        'i':'艾','j':'杰','k':'开','l':'艾勒','m':'艾姆','n':'艾恩','o':'欧','p':'皮',
        'q':'丘','r':'阿尔','s':'艾斯','t':'提','u':'优','v':'维','w':'达不溜','x':'艾克斯',
        'y':'歪','z':'贼德'
    };

    function convert(text, lang, mode) {
        if (!text || lang === 'id') return text;
        var str = String(text);
        if (DIGITS[lang]) {
            str = str.replace(/[0-9]/g, function (d) { return DIGITS[lang][parseInt(d, 10)]; });
        }
        if (mode === 'full') {
            if (lang === 'ar') {
                str = str.replace(/[a-zA-Z]/g, function (c) { return LATIN_TO_AR[c.toLowerCase()] || c; });
            } else if (lang === 'zh') {
                str = str.replace(/[a-zA-Z]/g, function (c) { return LATIN_TO_ZH[c.toLowerCase()] || c; });
            }
        }
        return str;
    }

    function apply() {
        var lang = (window.I18N && window.I18N.get) ? window.I18N.get() : 'id';
        document.querySelectorAll('[data-convert]').forEach(function (el) {
            var mode = el.dataset.convert || 'digit';
            if (!el.dataset.original) el.dataset.original = el.innerText;
            var orig = el.dataset.original;
            if (lang === 'id') {
                el.innerText = orig;
            } else {
                el.innerText = convert(orig, lang, mode);
            }
        });
    }

    return { convert: convert, apply: apply };
})();

/* =====================================================================
   SIM-BU TEXT MATCHES — Pencarian Multi-Bahasa
   ===================================================================== */
window.textMatches = function (el, query) {
    if (!query) return true;
    var q = query.trim().toLowerCase();
    if (!q) return true;

    var original = (el.getAttribute('data-search') || el.getAttribute('data-name') || '').toLowerCase();
    if (original.indexOf(q) > -1) return true;

    var visible = (el.innerText || el.textContent || '').toLowerCase();
    if (visible.indexOf(q) > -1) return true;

    return false;
};

/* =====================================================================
   SIM-BU CORE JS — i18n, Theme, SPA, Pagination
   ===================================================================== */
window.I18N = (function () {
    'use strict';

    // =====================================================
    // 1. KAMUS STATIS
    // =====================================================
    var DICT_STATIC = {
        // Sidebar Section
        'MENU UTAMA': ['MAIN MENU', 'القائمة الرئيسية', '主菜单'],
        'MODUL': ['MODULES', 'الوحدات', '模块'],
        'AKUN': ['ACCOUNT', 'الحساب', '账户'],

        // Sidebar Groups
        'Tugas & Persetujuan': ['Tasks & Approvals', 'المهام والموافقات', '任务与审批'],
        'Persuratan': ['Correspondence', 'المراسلات', '公文管理'],
        'Arsip': ['Archive', 'الأرشيف', '档案'],
        'Inventaris': ['Inventory', 'المخزون', '库存'],
        'BMD': ['Regional Assets', 'ممتلكات الإقليم', '地方资产'],
        'Kendaraan': ['Vehicles', 'المركبات', '车辆'],
        'Fasilitas': ['Facilities', 'المرافق', '设施'],
        'Protokol': ['Protocol', 'المراسم', '礼宾'],
        'Travel': ['Travel', 'السفر', '差旅'],
        'Pengajuan Saya': ['My Requests', 'طلباتي', '我的申请'],
        'Administrasi': ['Administration', 'الإدارة', '行政管理'],
        'Laporan': ['Reports', 'التقارير', '报告'],

        // Sidebar Items
        'Dashboard': ['Dashboard', 'لوحة التحكم', '仪表板'],
        'Disposisi Saya': ['My Dispositions', 'توجيهاتي', '我的批示'],
        'Tugas Saya': ['My Tasks', 'مهامي', '我的任务'],
        'Inbox Persetujuan': ['Approval Inbox', 'صندوق الموافقات', '审批收件箱'],
        'Review Hasil Pegawai': ['Review Employee Results', 'مراجعة نتائج الموظفين', '审核员工结果'],
        'Laporan Auditor': ['Auditor Reports', 'تقارير المدقق', '审计报告'],
        'Surat Masuk': ['Incoming Mail', 'البريد الوارد', '收文'],
        'Surat Keluar': ['Outgoing Mail', 'البريد الصادر', '发文'],
        'Daftar Arsip': ['Archive List', 'قائمة الأرشيف', '档案清单'],
        'Klasifikasi': ['Classification', 'التصنيف', '分类'],
        'Aset': ['Assets', 'الأصول', '资产'],
        'Peminjaman': ['Loans', 'الإعارة', '借用'],
        'Item ATK': ['Stationery Items', 'مواد القرطاسية', '文具物品'],
        'Permintaan ATK': ['Stationery Requests', 'طلبات القرطاسية', '文具申请'],
        'Data BMD': ['Regional Asset Data', 'بيانات ممتلكات الإقليم', '地方资产数据'],
        'Permohonan': ['Requests', 'الطلبات', '申请'],
        'Ruangan': ['Rooms', 'القاعات', '房间'],
        'Booking': ['Booking', 'الحجز', '预订'],
        'Kegiatan': ['Events', 'الفعاليات', '活动'],
        'Pinjam Aset': ['Borrow Assets', 'استعارة الأصول', '借用资产'],
        'Minta ATK': ['Request Stationery', 'طلب القرطاسية', '申领文具'],
        'Booking Ruangan': ['Room Booking', 'حجز القاعات', '会议室预订'],
        'Perjalanan Dinas': ['Official Travel', 'السفر الرسمي', '公务出差'],
        'Kelola User': ['Manage Users', 'إدارة المستخدمين', '管理用户'],
        'Kelola Role': ['Manage Roles', 'إدارة الأدوار', '管理角色'],
        'Audit Log': ['Audit Log', 'سجل التدقيق', '审计日志'],

        // Topbar
        'Keluar': ['Logout', 'تسجيل الخروج', '退出'],
        'Tanpa role': ['No role', 'بدون دور', '无角色'],
        'Tanpa Role': ['No Role', 'بدون دور', '无角色'],
        'Pemda · Bagian Umum': ['Local Gov · General Affairs', 'الحكومة المحلية · الشؤون العامة', '地方政府 · 总务部'],

        // Dashboard
        'SIM-BU · Bagian Umum': ['SIM-BU · General Affairs', 'SIM-BU · الشؤون العامة', 'SIM-BU · 总务部'],
        'Selamat Pagi': ['Good Morning', 'صباح الخير', '早上好'],
        'Selamat Siang': ['Good Afternoon', 'مساء الخير', '下午好'],
        'Selamat Sore': ['Good Evening', 'مساء الخير', '傍晚好'],
        'Selamat Malam': ['Good Night', 'مساء الخير', '晚上好'],
        'Selamat datang di SIM-BU. Ada beberapa pengajuan dan disposisi baru yang menunggu tindakan Anda hari ini.': ['Welcome to SIM-BU. New requests and dispositions are waiting for your action today.', 'مرحباً بك في SIM-BU. هناك طلبات وتوجيهات جديدة تنتظر إجراءاتك اليوم.', '欢迎来到 SIM-BU。今天有几个新的请求和处理意见等待您操作。'],
        'Tinjau Sekarang': ['Review Now', 'مراجعة الآن', '立即查看'],
        'Menunggu': ['Pending', 'قيد الانتظار', '待处理'],
        'Disetujui': ['Approved', 'تمت الموافقة', '已批准'],
        'Ditolak': ['Rejected', 'مرفوض', '已拒绝'],
        'Akses Cepat': ['Quick Access', 'وصول سريع', '快速访问'],
        'Persetujuan': ['Approvals', 'الموافقات', '审批'],
        'Bantuan': ['Help', 'مساعدة', '帮助'],
        'Panduan': ['Guide', 'دليل', '指南'],
        'Rekap Sistem': ['System Recap', 'ملخص النظام', '系统摘要'],
        'Inbox Pejabat': ['Official Inbox', 'صندوق الوارد الرسمي', '官员收件箱'],
        'Aset & ATK': ['Assets & Stationery', 'الأصول والقرطاسية', '资产与文具'],
        'Cari fitur, misalnya surat, inventaris, ruangan... (Enter untuk membuka)': ['Search features, e.g., mail, inventory, rooms...', 'ابحث عن الميزات...', '搜索功能...'],
        'Fitur tidak ditemukan.': ['No features found.', 'لم يتم العثور على ميزات.', '未找到功能。'],
        'Pengajuan Terbaru': ['Recent Requests', 'الطلبات الأخيرة', '最近的请求'],
        'Lihat Semua': ['View All', 'عرض الكل', '查看全部'],
        'Ringkas': ['Collapse', 'طي', '收起'],
        'Semua': ['All', 'الكل', '全部'],
        'No. Pengajuan': ['Request No.', 'رقم الطلب', '请求编号'],
        'Kategori': ['Category', 'الفئة', '类别'],
        'Tanggal': ['Date', 'التاريخ', '日期'],
        'Status': ['Status', 'الحالة', '状态'],
        'Aksi': ['Actions', 'الإجراءات', '操作'],
        'No': ['No', 'م', '序号'],
        'Nama': ['Name', 'الاسم', '姓名'],
        'Email': ['Email', 'البريد الإلكتروني', '电子邮件'],
        'Waktu': ['Time', 'الوقت', '时间'],
        'Keterangan': ['Notes', 'ملاحظات', '备注'],
        'Perihal': ['Subject', 'الموضوع', '事由'],
        'Nomor Surat': ['Letter No.', 'رقم الخطاب', '文号'],
        'Pengirim': ['Sender', 'المرسل', '发件人'],
        'Penerima': ['Recipient', 'المستلم', '收件人'],
        'Lampiran': ['Attachment', 'المرفق', '附件'],
        'Jumlah': ['Qty', 'الكمية', '数量'],
        'Total': ['Total', 'المجموع', '总计'],
        'Agenda': ['Agenda', 'جدول الأعمال', '议程'],
        'Surat & Pengirim': ['Letter & Sender', 'الرسالة والمرسل', '信件与发件人'],
        'Tanggal Diterima': ['Received Date', 'تاريخ الاستلام', '收到日期'],
        'Tidak ada pengajuan.': ['No requests.', 'لا توجد طلبات.', '暂无请求。'],
        'Pemohon': ['Applicant', 'مقدم الطلب', '申请人'],
        'Tutup': ['Close', 'إغلاق', '关闭'],
        'Mengerti': ['Got it', 'فهمت', '知道了'],
        'Lihat Detail': ['View Details', 'عرض التفاصيل', '查看详情'],
        'Salin No. Pengajuan': ['Copy Request No.', 'نسخ رقم الطلب', '复制请求编号'],
        'Menu': ['Menu', 'القائمة', '菜单'],

        // Calendar
        'Kalender Minggu Ini': ['This Week Calendar', 'تقويم هذا الأسبوع', '本周日历'],
        'Sen': ['Mon', 'الاثنين', '周一'],
        'Sel': ['Tue', 'الثلاثاء', '周二'],
        'Rab': ['Wed', 'الأربعاء', '周三'],
        'Kam': ['Thu', 'الخميس', '周四'],
        'Jum': ['Fri', 'الجمعة', '周五'],
        'Sab': ['Sat', 'السبت', '周六'],
        'Min': ['Sun', 'الأحد', '周日'],
        'Januari': ['January', 'يناير', '一月'],
        'Februari': ['February', 'فبراير', '二月'],
        'Maret': ['March', 'مارس', '三月'],
        'April': ['April', 'أبريل', '四月'],
        'Mei': ['May', 'مايو', '五月'],
        'Juni': ['June', 'يونيو', '六月'],
        'Juli': ['July', 'يوليو', '七月'],
        'Agustus': ['August', 'أغسطس', '八月'],
        'September': ['September', 'سبتمبر', '九月'],
        'Oktober': ['October', 'أكتوبر', '十月'],
        'November': ['November', 'نوفمبر', '十一月'],
        'Desember': ['December', 'ديسمبر', '十二月'],
        'Jan': ['Jan', 'يناير', '1月'], 'Feb': ['Feb', 'فبراير', '2月'], 'Mar': ['Mar', 'مارس', '3月'],
        'Apr': ['Apr', 'أبريل', '4月'], 'Jun': ['Jun', 'يونيو', '6月'], 'Jul': ['Jul', 'يوليو', '7月'],
        'Agu': ['Aug', 'أغسطس', '8月'], 'Ags': ['Aug', 'أغسطس', '8月'], 'Sep': ['Sep', 'سبتمبر', '9月'],
        'Okt': ['Oct', 'أكتوبر', '10月'], 'Nov': ['Nov', 'نوفمبر', '11月'], 'Des': ['Dec', 'ديسمبر', '12月'],

        'Jadwal Hari Ini': ['Today\'s Schedule', 'جدول اليوم', '今天的日程'],
        'Jadwal': ['Schedule', 'جدول', '日程'],
        'Hari Ini': ['Today', 'اليوم', '今天'],
        'Akhir pekan': ['Weekend', 'عطلة نهاية الأسبوع', '周末'],
        'Tidak ada jadwal hari ini. Pilih tanggal di kalender untuk melihat jadwal hari lain.': ['No schedule today. Pick a date on the calendar.', 'لا يوجد جدول اليوم. اختر تاريخاً في التقويم.', '今天没有安排。请在日历中选择日期。'],
        'Tidak ada jadwal.': ['No schedule.', 'لا يوجد جدول.', '暂无日程。'],
        'Lihat': ['View', 'عرض', '查看'],
        'Berlangsung': ['Ongoing', 'جارٍ', '进行中'],
        'kegiatan': ['activities', 'أنشطة', '活动'],
        'selesai': ['completed', 'مكتمل', '已完成'],
        'semua selesai': ['all completed', 'الكل مكتمل', '全部完成'],

        // Schedule Items
        'Apel Pagi & Briefing': ['Morning Roll Call & Briefing', 'الاصطفاف الصباحي والإحاطة', '早会与简报'],
        'Verifikasi Surat Masuk': ['Incoming Mail Verification', 'التحقق من البريد الوارد', '收文核验'],
        'Cek Stok ATK': ['Check Stationery Stock', 'فحص مخزون القرطاسية', '检查文具库存'],
        'Rapat Anggaran': ['Budget Meeting', 'اجتماع الميزانية', '预算会议'],
        'Pemeliharaan Kendaraan Dinas': ['Official Vehicle Maintenance', 'صيانة المركبات الرسمية', '公务车辆维护'],
        'Bimbingan Peserta Magang': ['Intern Guidance', 'إرشاد المتدربين', '实习生指导'],
        'Rapat Koordinasi': ['Coordination Meeting', 'اجتماع تنسيقي', '协调会议'],
        'Evaluasi Kinerja': ['Performance Evaluation', 'تقييم الأداء', '绩效评估'],
        'Penyerahan SK': ['Decree Handover', 'تسليم القرار', '递交决定书'],
        'Audiensi Tamu Instansi': ['Agency Guest Audience', 'استقبال ضيوف الوكالة', '机构来宾招待'],
        'Serah Terima Aset': ['Asset Handover', 'تسليم الأصول', '资产交接'],
        'Senam Pagi': ['Morning Exercise', 'التمارين الصباحية', '晨间运动'],
        'Rekap Laporan Mingguan': ['Weekly Report Recap', 'ملخص التقرير الأسبوعي', '周报汇总'],
        'Evaluasi Pekan Ini': ['This Week Evaluation', 'تقييم هذا الأسبوع', '本周评估'],

        // Places
        'Halaman Kantor': ['Office Yard', 'ساحة المكتب', '办公庭院'],
        'Ruang Persuratan': ['Correspondence Room', 'غرفة المراسلات', '公文室'],
        'Gudang': ['Warehouse', 'المستودع', '仓库'],
        'Ruang Rapat Utama': ['Main Meeting Room', 'قاعة الاجتماعات الرئيسية', '主会议室'],
        'Garasi': ['Garage', 'المرآب', '车库'],
        'Ruang Kepegawaian': ['Personnel Room', 'غرفة شؤون الموظفين', '人事室'],
        'Aula Serbaguna': ['Multi-purpose Hall', 'القاعة متعددة الأغراض', '多功能厅'],
        'Lobi Utama': ['Main Lobby', 'الردهة الرئيسية', '主大厅'],
        'Ruang Umum': ['General Room', 'الغرفة العامة', '通用室'],
        'Seluruh Bagian': ['All Departments', 'جميع الأقسام', '所有部门'],
        'Keuangan': ['Finance', 'المالية', '财务部'],
        'Kepegawaian': ['Personnel', 'شؤون الموظفين', '人事部'],
        'Protokol': ['Protocol', 'المراسم', '礼宾'],

        // Contacts
        'Hubungi Bagian': ['Contact Department', 'اتصل بالقسم', '联系部门'],
        'IT Support': ['IT Support', 'دعم تقنية المعلومات', 'IT 支持'],
        'Bagian Umum': ['General Affairs', 'الشؤون العامة', '总务部'],
        'Bantuan sistem': ['System Support', 'دعم النظام', '系统支持'],
        'Persuratan & aset': ['Correspondence & Assets', 'المراسلات والأصول', '公文与资产'],
        'Data pegawai': ['Employee Data', 'بيانات الموظفين', '员工数据'],
        'Anggaran': ['Budget', 'الميزانية', '预算'],

        // Notifications
        'Notifikasi': ['Notifications', 'الإشعارات', '通知'],
        'Tidak ada notifikasi.': ['No notifications.', 'لا توجد إشعارات.', '暂无通知。'],
        'Tandai semua dibaca': ['Mark all as read', 'تحديد الكل كمقروء', '全部标为已读'],
        'Hapus semua': ['Clear all', 'مسح الكل', '清除全部'],
        'baru': ['new', 'جديد', '新'],
        'Pengajuan menunggu persetujuan': ['Request awaiting approval', 'طلب في انتظار الموافقة', '请求等待审批'],
        'Disposisi surat baru': ['New letter disposition', 'توجيه خطاب جديد', '新信件批示'],
        'Booking ruangan diajukan': ['Room booking submitted', 'تم تقديم حجز الغرفة', '已提交会议室预订'],
        'Semua notifikasi ditandai dibaca': ['All notifications marked as read', 'تم تحديد جميع الإشعارات كمقروءة', '所有通知已标为已读'],
        'Notifikasi dihapus': ['Notifications cleared', 'تم مسح الإشعارات', '通知已清除'],
        'disalin': ['copied', 'منسوخ', '已复制'],

        // Help
        'Panduan Singkat': ['Quick Guide', 'دليل سريع', '快速指南'],
        'Cara memakai dashboard SIM-BU': ['How to use SIM-BU dashboard', 'كيفية استخدام لوحة تحكم SIM-BU', '如何使用 SIM-BU 仪表板'],
        '1. Gunakan kolom pencarian (Ctrl K) untuk menemukan fitur, lalu tekan Enter untuk membukanya.': ['1. Use the search field (Ctrl K) to find features, then press Enter to open.', '١. استخدم حقل البحث (Ctrl K) للعثور على الميزات، ثم اضغط Enter لفتحها.', '1. 使用搜索栏 (Ctrl K) 查找功能，按 Enter 打开。'],
        '2. Klik angka Menunggu / Disetujui / Ditolak untuk menyaring tabel pengajuan.': ['2. Click Pending / Approved / Rejected numbers to filter.', '٢. انقر على أرقام قيد الانتظار / تمت الموافقة / مرفوض للتصفية.', '2. 点击 待处理/已批准/已拒绝 以筛选。'],
        '3. Klik tanggal di kalender untuk melihat jadwal hari tersebut.': ['3. Click a date on the calendar to view that day\'s schedule.', '٣. انقر على تاريخ في التقويم لعرض جدول ذلك اليوم.', '3. 点击日历上的日期以查看当天日程。'],
        '4. Hubungi bagian terkait lewat tombol telepon (WhatsApp) di panel kanan.': ['4. Contact the relevant department via the WhatsApp button.', '٤. اتصل بالقسم المعني عبر زر WhatsApp.', '4. 通过 WhatsApp 按钮联系相关部门。'],

        // Magang
        'Magang': ['Internship', 'التدريب', '实习'],
        'Informatika': ['Informatics', 'المعلوماتية', '信息学'],
        'Manajemen': ['Management', 'الإدارة', '管理'],
        'Akuntansi': ['Accounting', 'المحاسبة', '会计'],
        'Hukum': ['Law', 'القانون', '法学'],
        'Detail': ['Details', 'التفاصيل', '详情'],
        'Peserta Magang': ['Intern', 'متدرب', '实习生'],
        'Asal Kampus': ['University', 'الجامعة', '大学'],
        'Penempatan': ['Placement', 'التنسيب', '安置'],
        'Periode': ['Period', 'الفترة', '期间'],
        'Pembimbing': ['Supervisor', 'المشرف', '导师'],

        // Buttons
        'Tambah': ['Add', 'إضافة', '添加'],
        'Tambah Data': ['Add Data', 'إضافة بيانات', '添加数据'],
        'Tambah Surat': ['Add Letter', 'إضافة رسالة', '添加信件'],
        'Simpan': ['Save', 'حفظ', '保存'],
        'Batal': ['Cancel', 'إلغاء', '取消'],
        'Hapus': ['Delete', 'حذف', '删除'],
        'Edit': ['Edit', 'تعديل', '编辑'],
        'Kembali': ['Back', 'رجوع', '返回'],
        'Kirim': ['Send', 'إرسال', '发送'],
        'Cari': ['Search', 'بحث', '搜索'],
        'Filter': ['Filter', 'تصفية', '筛选'],
        'Unduh': ['Download', 'تنزيل', '下载'],
        'Unggah': ['Upload', 'رفع', '上传'],
        'Setuju': ['Approve', 'موافقة', '批准'],
        'Tolak': ['Reject', 'رفض', '拒绝'],
        'Selesai': ['Done', 'منتهي', '完成'],

        // Status Enum
        'Registered': ['Registered', 'مسجل', '已登记'],
        'Awaiting Direction': ['Awaiting Direction', 'في انتظار التوجيه', '等待批示'],
        'Archived': ['Archived', 'مؤرشف', '已存档'],
        'Active': ['Active', 'نشط', '启用'],
        'Inactive': ['Inactive', 'غير نشط', '停用'],
        'Submitted': ['Submitted', 'مُقدم', '已提交'],
        'Issued': ['Issued', 'صادر', '已发放'],
        'Approved': ['Approved', 'تمت الموافقة', '已批准'],
        'Rejected': ['Rejected', 'مرفوض', '已拒绝'],
        'Completed': ['Completed', 'مكتمل', '已完成'],
        'In Progress': ['In Progress', 'قيد التنفيذ', '进行中'],
        'Pending': ['Pending', 'قيد الانتظار', '待处理'],

        // Pagination
        'Tampilkan': ['Show', 'عرض', '显示'],
        'per halaman': ['per page', 'لكل صفحة', '每页'],
        'surat': ['letters', 'رسائل', '信件'],
        'data': ['data', 'بيانات', '数据'],
        'Menampilkan': ['Showing', 'عرض', '显示'],
        'dari': ['of', 'من', '从'],
        'Sebelumnya': ['Previous', 'السابق', '上一页'],
        'Berikutnya': ['Next', 'التالي', '下一页']
    };

    var DICT_DYNAMIC = {
        'Pemberkasan': ['Filing', 'أرشفة', '归档'],
        'Peminjaman Aset': ['Asset Loan', 'استعارة الأصول', '资产借用'],
        'Kendaraan Dinas': ['Official Vehicle', 'مركبة رسمية', '公务车辆'],
        'Permintaan ATK': ['Stationery Request', 'طلب قرطاسية', '文具申请'],
        'Booking Ruangan': ['Room Booking', 'حجز غرفة', '会议室预订'],
        'Perjalanan Dinas': ['Official Travel', 'رحلة رسمية', '公务旅行'],
        'Undangan Rapat Koordinasi': ['Coordination Meeting Invitation', 'دعوة اجتماع تنسيقي', '协调会议邀请']
    };

    if (window.I18N_EXTRA) {
        Object.keys(window.I18N_EXTRA).forEach(function (k) { DICT_STATIC[k] = window.I18N_EXTRA[k]; });
    }

    var IDX = { en: 0, ar: 1, zh: 2 };
    var LOCALES = { id: 'id-ID', en: 'en-US', ar: 'ar-EG', zh: 'zh-CN' };
    var cur = 'id';
    var SKIP = { SCRIPT: 1, STYLE: 1, TEXTAREA: 1, NOSCRIPT: 1, CODE: 1, PRE: 1 };

    var textStore = new WeakMap();
    var attrStore = new WeakMap();
    var LOWER_MAP = {};
    var PHRASE_REGEX = null;
    var cache = {};
    var pendingBatch = {};

    function escapeRegex(s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }

    function rebuildIndex() {
        LOWER_MAP = {};
        var keys = [];
        Object.keys(DICT_STATIC).forEach(function (k) {
            LOWER_MAP[k.toLowerCase()] = DICT_STATIC[k];
            if (k.length >= 3) keys.push(k);
        });
        Object.keys(DICT_DYNAMIC).forEach(function (k) {
            if (!LOWER_MAP[k.toLowerCase()]) LOWER_MAP[k.toLowerCase()] = DICT_DYNAMIC[k];
            if (k.length >= 3) keys.push(k);
        });
        keys.sort(function (a, b) { return b.length - a.length; });
        if (keys.length) PHRASE_REGEX = new RegExp(keys.map(escapeRegex).join('|'), 'gi');
    }
    rebuildIndex();

    function detectScript(text) {
        if (!text) return 'empty';
        if (/[\u0600-\u06FF]/.test(text)) return 'arabic';
        if (/[\u4E00-\u9FFF]/.test(text)) return 'hanzi';
        if (/[a-zA-Z]/.test(text)) return 'latin';
        if (/[0-9]/.test(text)) return 'numeric';
        return 'other';
    }

    function translateStatic(text, lang) {
        if (lang === 'id' || !text) return text;
        var clean = text.replace(/\s+/g, ' ').trim();
        if (!clean) return text;

        var script = detectScript(clean);
        if (script === 'numeric' || script === 'other') return text;
        if (lang === 'ar' && script === 'arabic') return text;
        if (lang === 'zh' && script === 'hanzi') return text;

        var dict = LOWER_MAP[clean.toLowerCase()];
        if (dict) {
            var m = text.match(/^(\s*)[\s\S]*?(\s*)$/);
            return (m ? m[1] : '') + dict[IDX[lang]] + (m ? m[2] : '');
        }
        if (PHRASE_REGEX) {
            PHRASE_REGEX.lastIndex = 0;
            return text.replace(PHRASE_REGEX, function (match) {
                var d = LOWER_MAP[match.toLowerCase()];
                return d ? d[IDX[lang]] : match;
            });
        }
        return text;
    }

    function getCache(text, lang) {
        var key = lang + ':' + text.toLowerCase().trim();
        if (cache[key] !== undefined) return cache[key];
        try {
            var ls = localStorage.getItem('trans_' + key);
            if (ls) { cache[key] = ls; return ls; }
        } catch (e) {}
        return null;
    }

    function setCache(text, lang, translated) {
        var key = lang + ':' + text.toLowerCase().trim();
        cache[key] = translated;
        try { localStorage.setItem('trans_' + key, translated); } catch (e) {}
    }

    async function flushBatch(lang) {
        if (lang === 'id') return;
        var set = pendingBatch[lang];
        if (!set || set.size === 0) return;
        var texts = Array.from(set).slice(0, 50);
        texts.forEach(function (t) { set.delete(t); });

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
                body: JSON.stringify({ lang: lang, texts: texts })
            });
            var data = await response.json();
            if (data.translations) {
                Object.keys(data.translations).forEach(function (orig) {
                    setCache(orig, lang, data.translations[orig]);
                });
                document.querySelectorAll('[data-translate]').forEach(function (el) {
                    var orig = el.getAttribute('data-original');
                    if (!orig) return;
                    var translated = getCache(orig, lang);
                    if (translated) el.innerText = translated;
                });
            }
        } catch (e) { console.warn('Batch translate failed:', e); }
        if (set.size > 0) setTimeout(function () { flushBatch(lang); }, 300);
    }

    function translateDynamic(el, text, lang) {
        if (lang === 'id' || !text) return;
        if (el.closest('[data-convert]')) return;
        var clean = text.replace(/\s+/g, ' ').trim();
        if (!clean) return;

        var script = detectScript(clean);
        if (script === 'numeric' || script === 'other') return;
        if (lang === 'ar' && script === 'arabic') return;
        if (lang === 'zh' && script === 'hanzi') return;

        var cached = getCache(clean, lang);
        if (cached) { el.innerText = cached; return; }

        var hit = LOWER_MAP[clean.toLowerCase()];
        if (hit) {
            var translated = hit[IDX[lang]];
            el.innerText = translated;
            setCache(clean, lang, translated);
            return;
        }

        el.setAttribute('data-original', clean);
        if (!pendingBatch[lang]) pendingBatch[lang] = new Set();
        pendingBatch[lang].add(clean);
    }

    function applyTranslations(lang) {
        if (lang === 'id') {
            var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null);
            var node;
            while ((node = walker.nextNode())) {
                var orig = textStore.get(node);
                if (orig !== undefined && node.nodeValue !== orig) node.nodeValue = orig;
            }
            document.querySelectorAll('[placeholder], [title], [aria-label]').forEach(function (el) {
                var store = attrStore.get(el);
                if (!store) return;
                Object.keys(store).forEach(function (attr) {
                    if (el.getAttribute(attr) !== store[attr]) el.setAttribute(attr, store[attr]);
                });
            });
            return;
        }

        var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null);
        var node;
        while ((node = walker.nextNode())) {
            if (!node.nodeValue.trim()) continue;
            if (SKIP[node.parentElement.tagName]) continue;
            if (node.parentElement.closest('[data-no-i18n]')) continue;
            if (node.parentElement.closest('[data-translate]')) continue;
            if (node.parentElement.closest('[data-convert]')) continue;

            if (!textStore.has(node)) textStore.set(node, node.nodeValue);
            var orig = textStore.get(node);
            var translated = translateStatic(orig, lang);
            if (node.nodeValue !== translated) node.nodeValue = translated;
        }

        document.querySelectorAll('[placeholder], [title], [aria-label]').forEach(function (el) {
            if (el.closest('[data-no-i18n]')) return;
            ['placeholder', 'title', 'aria-label'].forEach(function (attr) {
                if (!el.hasAttribute(attr)) return;
                var store = attrStore.get(el) || {};
                if (!store[attr]) { store[attr] = el.getAttribute(attr); attrStore.set(el, store); }
                var translated = translateStatic(store[attr], lang);
                if (el.getAttribute(attr) !== translated) el.setAttribute(attr, translated);
            });
        });

        document.querySelectorAll('[data-translate]').forEach(function (el) {
            if (el.dataset.isTranslated === lang) return;
            if (!el.dataset.original) el.dataset.original = el.innerText.trim();
            var text = el.dataset.original;
            if (text.length > 0 && text.length < 500) {
                translateDynamic(el, text, lang);
                el.dataset.isTranslated = lang;
            }
        });

        if (pendingBatch[lang] && pendingBatch[lang].size > 0) flushBatch(lang);
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

        applyTranslations(lang);
        if (window.CharConvert) window.CharConvert.apply();
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
        apply: function () {
            applyTranslations(cur);
            if (window.CharConvert) window.CharConvert.apply();
        },
        init: init,
        extend: function (s, d) {
            if (s) Object.assign(DICT_STATIC, s);
            if (d) Object.assign(DICT_DYNAMIC, d);
            rebuildIndex();
        }
    };
})();

/* ========== THEME, LANGUAGE, PROGRESS ========== */
window.toggleTheme = function () {
    var dark = document.body.classList.toggle('dark-mode');
    try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
};

window.setLanguage = function (lang) {
    var lb = document.getElementById('langBox');
    if (lb) lb.classList.remove('open');
    I18N.set(lang);
};

window.showProgress = function () {
    var bar = document.getElementById('nprogress-bar');
    if (bar) { bar.style.width = '30%'; setTimeout(function () { bar.style.width = '70%'; }, 200); }
};
window.hideProgress = function () {
    var bar = document.getElementById('nprogress-bar');
    if (bar) { bar.style.width = '100%'; setTimeout(function () { bar.style.width = '0%'; }, 300); }
};

window.runScripts = function (container) {
    container.querySelectorAll('script').forEach(function (oldScript) {
        var newScript = document.createElement('script');
        Array.from(oldScript.attributes).forEach(function (attr) { newScript.setAttribute(attr.name, attr.value); });
        newScript.textContent = oldScript.textContent;
        oldScript.parentNode.replaceChild(newScript, oldScript);
    });
};

/* ========== INIT ========== */
document.addEventListener('DOMContentLoaded', function () {
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

    document.body.addEventListener('click', function (e) {
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

    window.addEventListener('popstate', function () { navigateTo(window.location.href, false); });

    /* =====================================================================
   SIDEBAR ACTIVE SYNC — Update highlight setelah SPA navigation
   ===================================================================== */
window.updateSidebarActive = function () {
    var sidebar = document.querySelector('.sidebar');
    if (!sidebar) return;

    var path = window.location.pathname;
    var currentLink = null;
    var currentLen = -1;

    // 1. Reset semua active
    sidebar.querySelectorAll('.sidebar-item.active, .sb-subitem.active').forEach(function (el) {
        el.classList.remove('active');
    });

    // 2. Cari link yang paling cocok (path terpanjang)
    sidebar.querySelectorAll('a[href]').forEach(function (a) {
        try {
            var url = new URL(a.href);
            var linkPath = url.pathname;

            // Match exact atau sub-path
            var match = (linkPath === path)
                || (linkPath !== '/' && path.startsWith(linkPath + '/'))
                || (linkPath !== '/' && path === linkPath.replace(/\/$/, ''));

            if (!match) return;

            // Pilih yang path-nya paling panjang (paling spesifik)
            if (linkPath.length > currentLen) {
                currentLen = linkPath.length;
                currentLink = a;
            }
        } catch (e) {}
    });

    if (!currentLink) return;

    // 3. Set active
    currentLink.classList.add('active');

    // 4. Kalau di dalam grup, buka grup-nya (accordion style)
    var group = currentLink.closest('.sb-group');
    if (group) {
        // Tutup grup lain
        sidebar.querySelectorAll('.sb-group.open').forEach(function (g) {
            if (g !== group) {
                g.classList.remove('open', 'has-active');
                var btn = g.querySelector('.sb-group-btn');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });

        // Buka grup aktif
        group.classList.add('open', 'has-active');
        var groupBtn = group.querySelector('.sb-group-btn');
        if (groupBtn) groupBtn.setAttribute('aria-expanded', 'true');
    }

    // 5. Scroll ke menu aktif (kalau ada di luar viewport)
    setTimeout(function () {
        currentLink.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    }, 100);
};

    window.navigateTo = function (url, pushState) {
        if (pushState === undefined) pushState = true;
        var content = document.getElementById('app-content');
        if (!content) { window.location.href = url; return; }
        showProgress();
        content.style.opacity = '0.4';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
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
                    window.updateSidebarActive();  // ⬅️ TAMBAH INI
                } else {
                    window.location.href = url;
                }
                hideProgress();
            })
            .catch(function (err) {
                console.error(err);
                window.location.href = url;
            });
    };

    document.body.addEventListener('click', function (e) {
        var link = e.target.closest('.sbp-list a');
        if (link && link.getAttribute('href') && link.getAttribute('href') !== '#') {
            e.preventDefault();
            loadAjaxPage(link.getAttribute('href'));
        }
    });

    document.body.addEventListener('change', function (e) {
        if (e.target.classList.contains('ajax-per-page')) {
            loadAjaxPage(e.target.value);
        }
    });

    window.loadAjaxPage = function (url) {
        var container = document.getElementById('ajax-table-container') || document.querySelector('.table-card');
        if (!container) return;
        showProgress();
        container.style.opacity = '0.4';
        container.style.pointerEvents = 'none';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.text(); })
            .then(function (html) {
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
            .catch(function (error) {
                console.error('Error:', error);
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
                hideProgress();
            });
    };
});
