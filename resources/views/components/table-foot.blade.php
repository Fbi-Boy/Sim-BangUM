@props([
    'paginator' => null,
    'noun' => 'data',
])

<div class="table-foot">
    <span class="foot-info">
        @if($paginator && method_exists($paginator, 'total') && $paginator->total() > 0)
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} {{ $noun }}
        @else
            Tidak ada data
        @endif
    </span>

    @if($paginator && $paginator->hasPages())
        {{ $paginator->onEachSide(1)->links('components.pagination') }}
    @endif
</div>

<style>
    .table-foot {
        display: flex; justify-content: space-between; align-items: center; gap: 12px;
        flex-wrap: wrap; padding: 14px 18px; border-top: 1px solid var(--border-color);
    }
    .foot-info { font-size: 12.5px; color: var(--text-muted); }
</style>
