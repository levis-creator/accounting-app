<?php

use Livewire\Volt\Component;

new class extends Component {
    public ?string $transactionId = null;
    public function mount(?string $transaction = null): void
    {
        $this->transactionId = $transaction;

        if ($this->transactionId) {
            // Load transaction data if needed
        }
    }
}; ?>

<div>
    <livewire:components.page-header title="transaction Details" :show-create-button="true"
        redirect-url="{{ route('pages.transactions.edit', ['transaction' => $transactionId]) }}" button-label="Edit transaction" />
    <div class="flex flex-col gap-4">
        <livewire:components.forms.transaction-form :transactionId="$transactionId" :disable="true" :delete="true" />
    </div>
</div>
