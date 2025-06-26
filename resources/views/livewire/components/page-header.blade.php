<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $title = 'Page Title';
    public bool $showCreateButton = false;
    public ?string $redirectUrl = null;
    public ?string $modalComponent = null;
    public string $buttonColor = 'primary';
    public string $buttonSize = 'sm';
    public string $buttonIcon = 'plus';
    public string $buttonLabel = 'Create';
    public string $size = 'xl';
    public int $level = 1;


}; ?>

<div class="flex items-start justify-between gap-4 py-2">
    <flux:heading :size="$size" :level="$level" class="mb-4">
        {{ $title }}
    </flux:heading>

    @if ($showCreateButton)
        @if ($redirectUrl)
            <a href="{{ $redirectUrl }}">
                <flux:button
                    class="flex items-center gap-2"
                    :color="$buttonColor"
                    :size="$buttonSize"
                    :icon="$buttonIcon"

                >{{ $buttonLabel }}</flux:button>
            </a>
        @elseif ($modalComponent)
            <flux:button
                class="flex items-center gap-2"
                wire:click="$emit('openModal', '{{ $modalComponent }}')"
                :color="$buttonColor"
                :size="$buttonSize"
                :icon="$buttonIcon"
            >{{ $buttonLabel }}</flux:button>
        @endif
    @endif
</div>
