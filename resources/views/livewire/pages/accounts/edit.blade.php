<?php

use Livewire\Volt\Component;

new class extends Component {
    public ?string $account = null;
    public ?string $accountId = null;


    public function mount(?string $account = null)
    {
        $this->accountId = $account;

        if ($this->accountId) {
            // Load account data if needed
        }
    }
}; ?>

<div>
    <livewire:components.page-header title="Edit Account" />
    <div class="flex flex-col gap-4">
        <livewire:components.forms.accountform :accountId="$accountId" edit/>
    </div>
</div>
