<?php

use Livewire\Volt\Component;

new class extends Component {
    public ?string $transaction = null;
    public ?string $transactionId = null;


    public function mount(?string $transaction = null)
    {
        $this->transactionId = $transaction;

        if ($this->transactionId) {
            // Load transaction data if needed
        }
    }
}; ?>

<div>
    <livewire:components.page-header title="Edit transaction" />
    <div class="flex flex-col gap-4">
        <livewire:components.forms.transaction-form :transactionId="$transactionId" edit/>
    </div>
</div>
