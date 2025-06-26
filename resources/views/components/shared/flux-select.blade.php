@props([
    'name',
    'label',
    'options' => [],
    'required' => false,
    'disabled' => false,
    'optionValue' => 'value',
    'optionLabel' => 'label',
])

<flux:select
    name="{{ $name }}"
    label="{{ $label }}"
    wire:model.defer="{{ $attributes->wire('model.defer')->value ?? $name }}"
    :required="$required"
    :disabled="$disabled"
>
<flux:select.option value="null">N/A</flux:select.option>
    @foreach ($options as $option)
        @php
            $value = is_array($option) ? $option[$optionValue] : ($option->{$optionValue} ?? $option);
            $label = is_array($option) ? $option[$optionLabel] : ($option->{$optionLabel} ?? $option);
        @endphp
        <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
    @endforeach
</flux:select>
