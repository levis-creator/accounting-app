<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-slot:title>
    Budgets
</x-slot:title>
<div>
    <x-title title="Budgets" showCreateButton>
    </x-title>
    <livewire:tables.budget-table />
</div>
