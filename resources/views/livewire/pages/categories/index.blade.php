<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<x-slot:title>
    Categories
</x-slot:title>
<div>
    <livewire:components.page-header  title="Categories" :show-create-button="true"
    redirect-url="{{ route('pages.categories.create') }}" button-label="Add Categories" />
    <livewire:tables.category-table />
</div>
