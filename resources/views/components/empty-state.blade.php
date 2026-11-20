@props(['title' => 'Belum ada data', 'description' => null])
<div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
    <p class="font-medium text-gray-700">{{ $title }}</p>
    @if($description)<p class="mt-1 text-sm text-gray-500">{{ $description }}</p>@endif
</div>
