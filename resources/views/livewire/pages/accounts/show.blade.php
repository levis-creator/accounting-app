<?php

use Livewire\Volt\Component;

new class extends Component {
    public ?string $accountId = null;
    public function mount(?string $account = null): void
    {
        $this->accountId = $account;

        if ($this->accountId) {
            // Load account data if needed
        }
    }
}; ?>

<div>
    <livewire:components.page-header title="Account Details" :show-create-button="true"
        redirect-url="{{ route('pages.accounts.edit', ['account' => $accountId]) }}" button-label="Edit Account" />
    <div class="flex flex-col gap-4">
        <livewire:components.forms.accountform :accountId="$accountId" :disable="true" :delete="true" />
    </div>
</div>
