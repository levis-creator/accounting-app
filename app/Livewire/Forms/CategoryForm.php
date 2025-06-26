<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class CategoryForm extends GenericForm
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|in:income,expense')]
    public string $type = 'expense';

    public function create(): void
    {
        $this->validate();

        parent::store([
            ...$this->all(),
            'user_id' => Auth::id(),
        ], Category::class);

        $this->reset();
    }

    public function edit(): void
    {
        $this->validate();

        parent::update([
            ...$this->all(),
            'user_id' => Auth::id(),
        ]);

        $this->reset();
    }

    public function mount(?string $categoryId = null): void
    {
        if ($categoryId) {
            $this->mountModel(Category::class, $categoryId);
        }
    }

    public function deleteCategory(string $categoryId): void
    {
        parent::delete($categoryId);
    }
}
