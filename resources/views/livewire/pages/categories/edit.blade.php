<?php

use Livewire\Volt\Component;

new class extends Component {
    public ?string $category = null;
    public ?string $categoryId = null;


    public function mount(?string $category = null)
    {
        $this->categoryId = $category;

        if ($this->categoryId) {
            // Load category data if needed
        }
    }
}; ?>

<div>
    <livewire:components.page-header title="Edit category" />
    <div class="flex flex-col gap-4">
        <livewire:components.forms.category-form :categoryId="$categoryId" edit/>
    </div>
</div>
