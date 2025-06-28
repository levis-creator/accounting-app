@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'disabled' => false,
    'optionValue' => 'value',
    'optionLabel' => 'label',
])

@php
    // Determine wire:model value (prioritize explicit one, fallback to name)
    $wireModel = $attributes->wire('model')->value ?? $name;
@endphp

<flux:select
    name="{{ $name }}"
    label="{{ $label }}"
    :required="$required"
    :disabled="$disabled"
    wire:model="{{ $wireModel }}"
    {{ $attributes->except('wire:model') }}
>
    <flux:select.option value="">N/A</flux:select.option>

    @foreach ($options as $option)
        @php
            $value = is_array($option) ? $option[$optionValue] : ($option->{$optionValue} ?? $option);
            $labelText = is_array($option) ? $option[$optionLabel] : ($option->{$optionLabel} ?? $option);
        @endphp
        <flux:select.option value="{{ $value }}">{{ $labelText }}</flux:select.option>
    @endforeach
</flux:select>
