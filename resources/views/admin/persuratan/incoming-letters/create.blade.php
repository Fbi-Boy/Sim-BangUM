@extends('layouts.app')
@section('title','Tambah Surat Masuk')

@section('content')
<style>
    /* === FORM === */
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-main); margin-bottom: 8px; }
    .form-control {
        width: 100%; padding: 11px 14px; background: var(--bg-soft); border: 1px solid var(--border-color);
        border-radius: 10px; color: var(--text-main); font-size: 13.5px; outline: none; transition: .2s;
    }
    .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); background: var(--bg-card); }
    .form-control::placeholder { color: var(--text-muted); }

    /* === UPLOADER === */
    .uploader { display: flex; flex-direction: column; gap: 12px; flex: 1; }

    .uploader-main {
        flex: 1; min-height: 220px;
        border: 2px dashed var(--border-color); background: var(--bg-soft);
        border-radius: 14px; cursor: pointer; overflow: hidden; position: relative;
        display: flex; align-items: center; justify-content: center; transition: .2s;
    }
    .uploader-main:hover { border-color: var(--accent); background: var(--accent-soft); }
    .uploader-main.dragover { border-color: var(--accent); background: var(--accent-soft); }
    .uploader-main.has-file { border-style: solid; background: #fff; border-color: var(--border-color); }
    .uploader-main.has-file:hover { border-color: var(--accent); background: #fff; }

    .uploader-empty { display: flex; flex-direction: column; align-items: center; gap: 6px; text-align: center; padding: 20px; }
    .uploader-empty svg { width: 42px; height: 42px; color: var(--accent); }
    .uploader-empty .t1 { font-size: 13.5px; font-weight: 600; color: var(--text-main); }
    .uploader-empty .t2 { font-size: 11.5px; color: var(--text-muted); }

    .uploader-preview { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: #f8fafc; }
    .uploader-preview img { width: 100%; height: 100%; object-fit: contain; display: block; }

    .uploader-icon { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 20px; }
    .uploader-icon svg { width: 56px; height: 56px; }
    .uploader-icon .ext { font-size: 12px; font-weight: 700; letter-spacing: 1px; padding: 3px 10px; border-radius: 6px; }
    .uploader-icon.pdf svg { color: #dc2626; }
    .uploader-icon.pdf .ext { background: #fee2e2; color: #dc2626; }
    .uploader-icon.doc svg { color: #2563eb; }
    .uploader-icon.doc .ext { background: #dbeafe; color: #2563eb; }

    .uploader-info {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 24px 14px 10px;
        background: linear-gradient(to top, rgba(15,23,42,.92), transparent);
        color: #fff; font-size: 12px; pointer-events: none;
    }
    .uploader-info .name { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .uploader-info .size { font-size: 10.5px; opacity: .85; margin-top: 2px; }

    .uploader-strip { display: flex; gap: 8px; overflow-x: auto; padding: 2px; min-height: 64px; }
    .uploader-strip::-webkit-scrollbar { height: 4px; }
    .uploader-strip::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 2px; }

    .thumb {
        position: relative; flex-shrink: 0;
        width: 60px; height: 60px; border-radius: 10px;
        border: 2px solid var(--border-color); background: #fff;
        cursor: pointer; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        transition: .15s;
    }
    .thumb:hover { border-color: var(--accent); }
    .thumb.active { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }
    .thumb img { width: 100%; height: 100%; object-fit: cover; }

    .thumb-ico { display: flex; flex-direction: column; align-items: center; gap: 1px; font-size: 8.5px; font-weight: 700; }
    .thumb-ico svg { width: 22px; height: 22px; }
    .thumb-ico.pdf { color: #dc2626; }
    .thumb-ico.doc { color: #2563eb; }

    .thumb-del {
        position: absolute; top: 2px; right: 2px;
        width: 18px; height: 18px; border-radius: 50%;
        background: rgba(220,38,38,.9); color: #fff;
        border: none; cursor: pointer; font-size: 12px; font-weight: 700; line-height: 1;
        display: flex; align-items: center; justify-content: center;
        opacity: .7; transition: .15s;
    }
    .thumb:hover .thumb-del, .thumb.active .thumb-del { opacity: 1; }
    .thumb-del:hover { background: #dc2626; }

    .thumb-add { border-style: dashed; color: var(--accent); font-size: 24px; font-weight: 700; }
    .thumb-add:hover { background: var(--accent-soft); }

    /* === MODAL === */
    .up-modal {
        display: none; position: fixed; inset: 0; z-index: 9999;
        background: rgba(15,23,42,.85); backdrop-filter: blur(4px);
        align-items: center; justify-content: center; padding: 24px;
    }
    .up-modal.open { display: flex; }
    .up-modal-inner {
        background: #fff; border-radius: 16px;
        max-width: 900px; width: 100%; max-height: 90vh;
        display: flex; flex-direction: column; overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,.4);
    }
    .up-modal-head { display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; border-bottom: 1px solid #e5e7eb; gap: 12px; }
    .up-modal-title { font-size: 14px; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
    .up-modal-actions { display: flex; gap: 8px; flex-shrink: 0; }
    .up-modal-btn { padding: 6px 12px; border-radius: 8px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; }
    .up-modal-btn.del { background: #fee2e2; color: #dc2626; }
    .up-modal-btn.del:hover { background: #dc2626; color: #fff; }
    .up-modal-btn.close { width: 30px; padding: 0; height: 30px; background: #f3f4f6; color: #6b7280; font-size: 18px; }
    .up-modal-btn.close:hover { background: #e5e7eb; color: #111827; }
    .up-modal-body { flex: 1; overflow: auto; padding: 16px; min-height: 300px; display: flex; align-items: center; justify-content: center; background: #f9fafb; }
    .up-modal-body img { max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 8px; }
    .up-modal-body iframe { width: 100%; height: 70vh; border: none; border-radius: 8px; background: #fff; }
    .up-modal-body .no-preview { padding: 60px 20px; text-align: center; color: #6b7280; font-size: 14px; }
</style>

<div class="page-head" style="margin-bottom: 24px;">
    <div>
        <a href="{{ route('admin.persuratan.incoming-letters.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:12.5px; font-weight:500; display:inline-flex; align-items:center; gap:6px; margin-bottom:8px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h2>Registrasi Surat Masuk</h2>
        <p>Lengkapi data surat dan unggah file PDF-nya.</p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-error" style="margin-bottom: 20px;">
        <ul style="margin-left:16px;">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.persuratan.incoming-letters.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: stretch;">

        <!-- KOLOM KIRI -->
        <div class="table-card" style="padding: 28px; margin-bottom: 0;">
            <div class="form-group">
                <label class="form-label">Nama Pengirim *</label>
                <input name="sender_name" required value="{{ old('sender_name') }}" class="form-control" placeholder="Instansi atau nama perorangan">
            </div>
            <div class="form-group">
                <label class="form-label">Perihal Surat *</label>
                <input name="subject" required value="{{ old('subject') }}" class="form-control" placeholder="Judul atau maksud surat">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Nomor Surat</label>
                    <input name="external_letter_number" value="{{ old('external_letter_number') }}" class="form-control" placeholder="001/UND/2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" name="letter_date" value="{{ old('letter_date') }}" class="form-control">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Ringkasan / Catatan</label>
                <textarea name="summary" rows="3" class="form-control" placeholder="Tulis ringkasan singkat..." style="resize: vertical;">{{ old('summary') }}</textarea>
            </div>
        </div>

        <!-- KOLOM KANAN -->
        <div class="table-card" style="padding: 28px; display: flex; flex-direction: column; margin-bottom: 0;">
            <label class="form-label" style="margin-bottom: 12px;">Lampiran Scan (PDF/Gambar)</label>

            <div class="uploader">
                <div class="uploader-main" id="uploaderMain">
                    <div class="uploader-empty" id="uploaderEmpty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <div class="t1">Pilih File / Drag & Drop</div>
                        <div class="t2">PDF, JPG, PNG · Maks 10MB</div>
                    </div>
                    <div class="uploader-preview" id="uploaderPreview" style="display:none;"></div>
                    <div class="uploader-info" id="uploaderInfo" style="display:none;">
                        <div class="name" id="uploaderName"></div>
                        <div class="size" id="uploaderSize"></div>
                    </div>
                </div>
                <div class="uploader-strip" id="uploaderStrip" style="display:none;"></div>
            </div>

            <input type="file" name="document[]" id="fileInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" multiple style="display:none;">
        </div>
    </div>

    <div style="margin-top: 24px; display: flex; gap: 12px;">
        <button type="submit" class="btn" style="padding: 12px 24px; font-size: 14px;">Simpan Surat</button>
        <a href="{{ route('admin.persuratan.incoming-letters.index') }}" class="btn" style="padding: 12px 24px; font-size: 14px; background: var(--bg-soft); color: var(--text-main); border: 1px solid var(--border-color);">Batal</a>
    </div>
</form>

<!-- MODAL -->
<div class="up-modal" id="upModal">
    <div class="up-modal-inner">
        <div class="up-modal-head">
            <div class="up-modal-title" id="upModalTitle"></div>
            <div class="up-modal-actions">
                <button type="button" class="up-modal-btn del" id="upModalDelete">Hapus</button>
                <button type="button" class="up-modal-btn close" id="upModalClose">&times;</button>
            </div>
        </div>
        <div class="up-modal-body" id="upModalBody"></div>
    </div>
</div>

<script>
(function () {
    var state = { files: [], active: 0 };
    var urlCache = new Map();

    var fileInput = document.getElementById('fileInput');
    var main = document.getElementById('uploaderMain');
    var emptyEl = document.getElementById('uploaderEmpty');
    var previewEl = document.getElementById('uploaderPreview');
    var infoEl = document.getElementById('uploaderInfo');
    var nameEl = document.getElementById('uploaderName');
    var sizeEl = document.getElementById('uploaderSize');
    var strip = document.getElementById('uploaderStrip');
    var modal = document.getElementById('upModal');
    var modalTitle = document.getElementById('upModalTitle');
    var modalBody = document.getElementById('upModalBody');
    var modalDelete = document.getElementById('upModalDelete');
    var modalClose = document.getElementById('upModalClose');

    var SVG_PDF = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
    var SVG_DOC = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>';

    function fmtSize(b) { if (b < 1024) return b + ' B'; if (b < 1048576) return (b / 1024).toFixed(1) + ' KB'; return (b / 1048576).toFixed(1) + ' MB'; }
    function isImg(f) { return f.type && f.type.indexOf('image/') === 0; }
    function isPdf(f) { return f.type === 'application/pdf' || /\.pdf$/i.test(f.name); }
    function ext(f) { var m = f.name.match(/\.([^.]+)$/); return m ? m[1].toUpperCase() : 'FILE'; }
    function getUrl(f) { if (!urlCache.has(f)) urlCache.set(f, URL.createObjectURL(f)); return urlCache.get(f); }
    function revokeUrl(f) { if (urlCache.has(f)) { URL.revokeObjectURL(urlCache.get(f)); urlCache.delete(f); } }

    function previewHTML(f) {
        if (isImg(f)) return '<img src="' + getUrl(f) + '" alt="">';
        if (isPdf(f)) return '<div class="uploader-icon pdf">' + SVG_PDF + '<span class="ext">PDF</span></div>';
        return '<div class="uploader-icon doc">' + SVG_DOC + '<span class="ext">' + ext(f) + '</span></div>';
    }
    function thumbHTML(f) {
        if (isImg(f)) return '<img src="' + getUrl(f) + '" alt="">';
        if (isPdf(f)) return '<div class="thumb-ico pdf">' + SVG_PDF + '<span>PDF</span></div>';
        return '<div class="thumb-ico doc">' + SVG_DOC + '<span>' + ext(f) + '</span></div>';
    }

    function render() {
        if (state.files.length === 0) {
            main.classList.remove('has-file');
            emptyEl.style.display = 'flex';
            previewEl.style.display = 'none';
            infoEl.style.display = 'none';
            strip.style.display = 'none';
        } else {
            main.classList.add('has-file');
            emptyEl.style.display = 'none';
            previewEl.style.display = 'flex';
            previewEl.innerHTML = previewHTML(state.files[state.active]);
            infoEl.style.display = 'block';
            nameEl.textContent = state.files[state.active].name;
            sizeEl.textContent = fmtSize(state.files[state.active].size);
            strip.style.display = 'flex';

            strip.innerHTML = '';
            state.files.forEach(function (f, i) {
                var t = document.createElement('div');
                t.className = 'thumb' + (i === state.active ? ' active' : '');
                t.innerHTML = thumbHTML(f) + '<button type="button" class="thumb-del" data-i="' + i + '">&times;</button>';
                t.addEventListener('click', function (e) {
                    if (e.target.classList.contains('thumb-del')) return;
                    state.active = i;
                    render();
                });
                strip.appendChild(t);
            });

            var add = document.createElement('div');
            add.className = 'thumb thumb-add';
            add.innerHTML = '+';
            add.addEventListener('click', function () { fileInput.click(); });
            strip.appendChild(add);

            var activeEl = strip.querySelector('.thumb.active');
            if (activeEl) activeEl.scrollIntoView({ inline: 'nearest', block: 'nearest' });
        }
    }

    function syncInput() {
        var dt = new DataTransfer();
        state.files.forEach(function (f) { dt.items.add(f); });
        fileInput.files = dt.files;
    }

    function addFiles(fs) {
        if (!fs || !fs.length) return;
        state.files = state.files.concat(Array.from(fs));
        state.active = state.files.length - fs.length;
        syncInput();
        render();
    }

fileInput.addEventListener('change', function (e) {
    var files = Array.from(e.target.files);  // 1. Snapshot dulu
    fileInput.value = '';                     // 2. Reset (biar event change bisa trigger lagi)
    addFiles(files);                          // 3. Baru tambahkan ke state
});

    strip.addEventListener('click', function (e) {
        var btn = e.target.closest('.thumb-del');
        if (!btn) return;
        e.stopPropagation();
        var i = parseInt(btn.dataset.i, 10);
        revokeUrl(state.files[i]);
        state.files.splice(i, 1);
        if (state.files.length === 0) state.active = 0;
        else if (state.active >= state.files.length) state.active = state.files.length - 1;
        else if (state.active > i) state.active--;
        syncInput();
        render();
    });

    main.addEventListener('click', function () {
        if (state.files.length === 0) fileInput.click();
        else openModal();
    });
    main.addEventListener('dragover', function (e) { e.preventDefault(); main.classList.add('dragover'); });
    main.addEventListener('dragleave', function () { main.classList.remove('dragover'); });
    main.addEventListener('drop', function (e) { e.preventDefault(); main.classList.remove('dragover'); addFiles(e.dataTransfer.files); });

    function openModal() {
        var f = state.files[state.active];
        if (!f) return;
        modalTitle.textContent = f.name;
        modalBody.innerHTML = '';
        if (isImg(f)) {
            var img = document.createElement('img');
            img.src = getUrl(f);
            modalBody.appendChild(img);
        } else if (isPdf(f)) {
            var iframe = document.createElement('iframe');
            iframe.src = getUrl(f);
            modalBody.appendChild(iframe);
        } else {
            modalBody.innerHTML = '<div class="no-preview">Preview tidak tersedia untuk file <b>' + f.name + '</b>.</div>';
        }
        modal.classList.add('open');
    }
    function closeModal() { modal.classList.remove('open'); }

    modalClose.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    modalDelete.addEventListener('click', function () {
        if (state.files.length === 0) return;
        revokeUrl(state.files[state.active]);
        state.files.splice(state.active, 1);
        if (state.files.length === 0) state.active = 0;
        else if (state.active >= state.files.length) state.active = state.files.length - 1;
        syncInput();
        render();
        closeModal();
    });

    render();
})();
</script>
@endsection
