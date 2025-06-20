<?php

use Livewire\Volt\Component;

new class extends Component {
    //
    public string $title = 'Title';
    public string $value = 'KES 0';
    public string $icon = 'wallet'; // Name of <flux:icon.* />
    public float|null $percentage = null;
    public bool $increased = true;
}; ?>

<div
    class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-4 shadow-sm flex flex-col justify-between">

    <div class="flex items-start gap-4">
        <!-- Icon container -->
        <div class="bg-gray-100 dark:bg-zinc-700 p-3 rounded-full">
            <flux:icon :$icon class="stroke-gray-900 dark:stroke-white w-6 h-6" />
        </div>
        <div>
            <h3 class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $title }}</h3>
            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
        </div>
    </div>

    <div class="flex items-center justify-between mt-2 text-sm">
        <p class="text-gray-500 dark:text-gray-400">Last 30 days</p>

        @if (!is_null($percentage))
            <flux:badge variant="pill" :color="$increased ? 'green' : 'red'"
                :icon="$increased ? 'arrow-up' : 'arrow-down'">
                <span class="ml-1 font-medium">{{ $percentage }}%</span>
            </flux:badge>
        @endif
    </div>
</div>
