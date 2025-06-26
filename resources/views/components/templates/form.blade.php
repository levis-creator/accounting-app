<div class="p-4 bg-white dark:bg-zinc-700 rounded-lg shadow-md">
    <form {{ $attributes->merge(['wire:submit.prevent' => 'submit']) }}>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{ $slot }}
        </div>
        <div class="mt-4 flex justify-start gap-4">
            @if (!$disable)
                <flux:button type="submit" variant="primary">
                    {{ $edit ? 'Save Changes' : 'Add' }}
                </flux:button>
            @endif
            <flux:button wire:click="goToIndex">
                {{ !$edit ? 'Back' : 'Cancel' }}
            </flux:button>
            @if ($delete)
                <flux:button  wire:click="deleteItem">
                    Delete
                </flux:button>
            @endif
        </div>
    </form>
</div>
