<?php

use Livewire\Volt\Component;

new class extends Component {
    public ?string $categoryId = null;
    public function mount(?string $category = null): void
    {
        $this->categoryId = $category;

        if ($this->categoryId) {
            // Load category data if needed
        }
    }
}; ?>

<div>
    <livewire:components.page-header title="category Details" :show-create-button="true"
        redirect-url="{{ route('pages.categories.edit', ['category' => $categoryId]) }}" button-label="Edit category" />
    <div class="flex flex-col gap-4">
        <livewire:components.forms.category-form :categoryId="$categoryId" :disable="true" :delete="true" />
    </div>
</div>
