
@props([
    'name',
    'label' => 'Tanggal',
    'value' => null,
    'placeholder' => 'Pilih tanggal',
    'required' => false,
    'disabled' => false,
    'id' => null,
])

@php
    $id = $id ?? $name;
@endphp

<div class="form-group">
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span style="color:#dc2626;">*</span>
            @endif
        </label>
    @endif

    <div class="date-picker-wrapper">
        <input
            type="text"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            class="form-control js-date-picker"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes }}
        >

        <svg class="date-picker-icon"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="1.8"
             stroke-linecap="round"
             stroke-linejoin="round">
            <rect x="3" y="5" width="18" height="16" rx="2"/>
            <path d="M16 3v4M8 3v4M3 11h18"/>
        </svg>
    </div>
</div>
