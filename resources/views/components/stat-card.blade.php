@props(['label', 'value', 'icon' => 'chart-bar', 'tone' => 'indigo', 'hint' => null])
@php
$tones = ['indigo'=>'bg-indigo-50 text-indigo-600','green'=>'bg-emerald-50 text-emerald-600','amber'=>'bg-amber-50 text-amber-600','rose'=>'bg-rose-50 text-rose-600','sky'=>'bg-sky-50 text-sky-600'];
@endphp
<div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $value }}</p>
            @if($hint)<p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>@endif
        </div>
        <span class="rounded-lg p-2 {{ $tones[$tone] ?? $tones['indigo'] }}">
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="h-6 w-6" />
        </span>
    </div>
</div>
