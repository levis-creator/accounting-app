<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>
<x-slot:title>
    Transactions
</x-slot:title>
<div>
    <livewire:components.page-header  title="Transactions" :show-create-button="true"
        redirect-url="{{ route('pages.transactions.create') }}" button-label="Add Transaction" />
    <livewire:tables.transaction-table />
</div>
