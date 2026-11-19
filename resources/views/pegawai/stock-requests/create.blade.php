@extends('layouts.app')
@section('title', 'Permintaan ATK')

@section('content')
<style>
    .stock-page { max-width:1100px; margin:auto; color:var(--text-main); }
    .st-head { margin-bottom:22px; }
    .st-head a { display:inline-block; color:var(--text-muted); text-decoration:none; font-size:12px; margin-bottom:12px; }
    .st-head h2 { font-size:23px; font-weight:750; margin:0 0 6px; }
    .st-head p { font-size:12px; color:var(--text-muted); margin:0; }
    .st-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:15px; padding:22px; margin-bottom:18px; }
    .st-card-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:18px; }
    .st-card-head h3 { font-size:14px; margin:0 0 4px; }
    .st-card-head p { font-size:11px; color:var(--text-muted); margin:0; }
    .st-field { margin-bottom:16px; }
    .st-label { display:block; font-size:11px; font-weight:650; margin-bottom:7px; }
    .st-control { box-sizing:border-box; width:100%; padding:10px 12px; border:1px solid var(--border-color); border-radius:9px; background:var(--bg-soft); color:var(--text-main); outline:none; font-size:12px; }
    .st-control:focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-soft); }
    .st-table-wrap { overflow-x:auto; }
    .st-table { width:100%; border-collapse:collapse; min-width:570px; }
    .st-table th { text-align:left; padding:11px 10px; color:var(--text-muted); font-size:10px; font-weight:700; background:var(--bg-soft); border-bottom:1px solid var(--border-color); }
    .st-table td { padding:10px; border-bottom:1px solid var(--border-color); vertical-align:middle; }
    .st-table tr:last-child td { border-bottom:0; }
    .st-table .st-control { min-width:100px; }
    .st-stock { font-size:10px; color:var(--text-muted); margin-top:5px; }
    .st-remove { width:31px; height:31px; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-soft); color:#dc6464; cursor:pointer; font-size:17px; }
    .st-remove:hover { background:#dc6464; color:#fff; }
    .st-add { border:1px dashed var(--accent); background:var(--accent-soft); color:var(--accent); border-radius:9px; padding:10px 13px; font-size:11px; font-weight:700; cursor:pointer; margin-top:13px; }
    .st-add:hover { filter:brightness(.96); }
    .st-summary { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px; border-radius:10px; background:var(--bg-soft); margin-top:15px; }
    .st-summary small { display:block; color:var(--text-muted); font-size:10px; margin-bottom:4px; }
    .st-summary strong { font-size:17px; }
    .st-actions { display:flex; justify-content:flex-end; gap:9px; margin-top:20px; }
    .st-btn { display:inline-flex; justify-content:center; align-items:center; border:1px solid var(--border-color); border-radius:9px; padding:11px 17px; font-size:11px; font-weight:700; text-decoration:none; cursor:pointer; }
    .st-btn.primary { background:var(--accent); border-color:transparent; color:#fff; }
    .st-btn.secondary { background:var(--bg-soft); color:var(--text-main); }
    .st-error { color:#dc6464; font-size:10px; margin-top:5px; }
    @media(max-width:560px) { .st-card { padding:15px; } .st-actions { flex-direction:column-reverse; } .st-btn { width:100%; box-sizing:border-box; } .st-card-head { align-items:flex-start; flex-direction:column; } }
</style>

<div class="stock-page">
    <div class="st-head">
        <a href="{{ route('pegawai.stock-requests.index') }}">← Kembali ke permintaan ATK</a>
        <h2>Permintaan Alat Tulis Kantor</h2>
        <p>Pilih barang yang dibutuhkan dan tentukan jumlah permintaan.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:18px;">
            <strong>Periksa kembali data permintaan.</strong>
            <ul style="margin:7px 0 0 18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('pegawai.stock-requests.store') }}" id="stockForm">
        @csrf

        <div class="st-card">
            <div class="st-card-head">
                <div>
                    <h3>Informasi permintaan</h3>
                    <p>Jelaskan kebutuhan dan kapan barang diperlukan.</p>
                </div>
            </div>

            <div class="st-field">
                <label class="st-label" for="purpose">Keperluan permintaan *</label>
                <textarea class="st-control" id="purpose" name="purpose" rows="3" required placeholder="Contoh: Kebutuhan administrasi kegiatan kantor...">{{ old('purpose') }}</textarea>
                @error('purpose')<div class="st-error">{{ $message }}</div>@enderror
            </div>

            <div class="st-field" style="margin-bottom:0;">
                <label class="st-label" for="needed_at">Tanggal dibutuhkan *</label>
                <input class="st-control" type="date" id="needed_at" name="needed_at" value="{{ old('needed_at') }}" required style="max-width:300px;">
                @error('needed_at')<div class="st-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="st-card">
            <div class="st-card-head">
                <div>
                    <h3>Daftar barang yang diminta</h3>
                    <p>Tambahkan barang sesuai kebutuhan. Hindari memilih barang yang sama lebih dari sekali.</p>
                </div>
                <span style="font-size:10px;color:var(--text-muted);" id="rowCount">0 barang</span>
            </div>

            <div class="st-table-wrap">
                <table class="st-table">
                    <thead>
                        <tr>
                            <th style="width:48%;">Nama barang</th>
                            <th style="width:22%;">Jumlah</th>
                            <th style="width:22%;">Stok saat ini</th>
                            <th style="width:8%;text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="stockRows">
                        @php
                            $oldItems = old('items', [['id' => '', 'qty' => 1]]);
                        @endphp

                        @foreach($oldItems as $index => $oldItem)
                            <tr class="stock-row">
                                <td>
                                    <select class="st-control item-select" name="items[{{ $index }}][id]" required>
                                        <option value="">Pilih barang</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" data-stock="{{ $item->current_stock }}" data-unit="{{ $item->unit }}" @selected(($oldItem['id'] ?? '') == $item->id)>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="st-error">@error("items.$index.id"){{ $message }}@enderror</div>
                                </td>
                                <td>
                                    <input class="st-control qty-input" type="number" name="items[{{ $index }}][qty]" min="1" value="{{ $oldItem['qty'] ?? 1 }}" required>
                                    <div class="st-error">@error("items.$index.qty"){{ $message }}@enderror</div>
                                </td>
                                <td><div class="st-stock">Pilih barang dahulu</div></td>
                                <td style="text-align:center;"><button type="button" class="st-remove" title="Hapus baris">×</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="button" class="st-add" id="addStockRow">+ Tambah barang</button>

            <div class="st-summary">
                <div><small>Total jenis barang</small><strong id="totalKinds">0</strong></div>
                <div style="text-align:right;"><small>Total jumlah diminta</small><strong id="totalQty">0</strong></div>
            </div>
        </div>

        <div class="st-actions">
            <a href="{{ route('pegawai.stock-requests.index') }}" class="st-btn secondary">Batal</a>
            <button type="submit" class="st-btn primary">Kirim Permintaan →</button>
        </div>
    </form>
</div>

<template id="stockOptionTemplate">
    <option value="">Pilih barang</option>
    @foreach($items as $item)
        <option value="{{ $item->id }}" data-stock="{{ $item->current_stock }}" data-unit="{{ $item->unit }}">{{ $item->name }}</option>
    @endforeach
</template>

<script>
(function () {
    const rows = document.getElementById('stockRows');
    const addButton = document.getElementById('addStockRow');
    const optionTemplate = document.getElementById('stockOptionTemplate');
    const form = document.getElementById('stockForm');
    if (!rows || !addButton || !optionTemplate) return;

    let nextIndex = Math.max(0, ...Array.from(rows.querySelectorAll('[name^="items["]')).map(el => {
        const match = el.name.match(/^items\[(\d+)\]/);
        return match ? Number(match[1]) + 1 : 0;
    }));

    function updateSummary() {
        const allRows = Array.from(rows.querySelectorAll('.stock-row'));
        let total = 0;
        let kinds = 0;

        allRows.forEach(row => {
            const select = row.querySelector('.item-select');
            const qty = row.querySelector('.qty-input');
            if (select.value) kinds++;
            total += Math.max(0, Number(qty.value) || 0);
        });

        document.getElementById('totalKinds').textContent = kinds;
        document.getElementById('totalQty').textContent = total;
        document.getElementById('rowCount').textContent = allRows.length + ' baris';
        updateDuplicateOptions();
    }

    function updateDuplicateOptions() {
        const selects = Array.from(rows.querySelectorAll('.item-select'));
        const selected = selects.map(s => s.value).filter(Boolean);

        selects.forEach(select => {
            Array.from(select.options).forEach(option => {
                if (!option.value) return;
                option.disabled = selected.includes(option.value) && select.value !== option.value;
            });
        });
    }

    function updateStock(row) {
        const select = row.querySelector('.item-select');
        const display = row.querySelector('.st-stock');
        const option = select.options[select.selectedIndex];

        if (!select.value) {
            display.textContent = 'Pilih barang dahulu';
        } else {
            const stock = option.dataset.stock ?? '-';
            const unit = option.dataset.unit ?? '';
            display.textContent = 'Tersedia: ' + stock + ' ' + unit;
        }
    }

    function addRow() {
        const index = nextIndex++;
        const tr = document.createElement('tr');
        tr.className = 'stock-row';

        const select = document.createElement('select');
        select.className = 'st-control item-select';
        select.name = `items[${index}][id]`;
        select.required = true;
        select.innerHTML = optionTemplate.innerHTML;

        const qty = document.createElement('input');
        qty.className = 'st-control qty-input';
        qty.type = 'number';
        qty.name = `items[${index}][qty]`;
        qty.min = '1';
        qty.value = '1';
        qty.required = true;

        const tdItem = document.createElement('td');
        tdItem.appendChild(select);

        const tdQty = document.createElement('td');
        tdQty.appendChild(qty);

        const tdStock = document.createElement('td');
        const stock = document.createElement('div');
        stock.className = 'st-stock';
        stock.textContent = 'Pilih barang dahulu';
        tdStock.appendChild(stock);

        const tdAction = document.createElement('td');
        tdAction.style.textAlign = 'center';
        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'st-remove';
        remove.textContent = '×';
        remove.title = 'Hapus baris';
        tdAction.appendChild(remove);

        tr.append(tdItem, tdQty, tdStock, tdAction);
        rows.appendChild(tr);
        updateSummary();
        select.focus();
    }

    rows.addEventListener('change', function (e) {
        if (e.target.classList.contains('item-select')) {
            updateStock(e.target.closest('.stock-row'));
            updateSummary();
        }
        if (e.target.classList.contains('qty-input')) updateSummary();
    });

    rows.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input')) updateSummary();
    });

    rows.addEventListener('click', function (e) {
        const button = e.target.closest('.st-remove');
        if (!button) return;

        const allRows = rows.querySelectorAll('.stock-row');
        if (allRows.length <= 1) {
            alert('Minimal harus ada satu baris barang.');
            return;
        }

        button.closest('.stock-row').remove();
        updateSummary();
    });

    addButton.addEventListener('click', addRow);

    form.addEventListener('submit', function (e) {
        const selects = Array.from(rows.querySelectorAll('.item-select'));
        const values = selects.map(s => s.value).filter(Boolean);
        const hasDuplicate = values.length !== new Set(values).size;

        if (hasDuplicate) {
            e.preventDefault();
            alert('Ada barang yang dipilih lebih dari satu kali. Periksa kembali daftar barang.');
            return;
        }

        if (selects.some(s => !s.value)) {
            e.preventDefault();
            alert('Pilih barang untuk setiap baris atau hapus baris yang tidak digunakan.');
        }
    });

    rows.querySelectorAll('.stock-row').forEach(row => updateStock(row));
    updateSummary();
})();
</script>
@endsection
