<?php

use Livewire\Volt\Component;
use App\Livewire\Forms\CategoryForm;

new class extends Component {
    public CategoryForm $form;
    public ?string $categoryId = null;
    public bool $edit = false;
    public ?bool $disable = false;
    public bool $delete = false;

    public array $types = [];

    public function mount(?string $categoryId = null, bool $edit = false, bool $disable = false): void
    {
        $this->categoryId = $categoryId;
        $this->edit = $edit;
        $this->disable = $disable;

        $this->types = [
            ['value' => 'income', 'label' => 'Income'],
            ['value' => 'expense', 'label' => 'Expense'],
        ];

        if ($categoryId) {
            $this->form->mount($categoryId);
        }
    }

    public function submit(): void
    {
        $this->edit ? $this->form->edit() : $this->form->create();

        $this->redirect(route('pages.categories.index'));
    }

    public function goToIndex(): void
    {
        $this->redirect(route('pages.categories.index'));
    }

    public function deleteItem(): void
    {
        if ($this->categoryId) {
            $this->form->deleteCategory($this->categoryId);
            $this->redirect(route('pages.categories.index'));
        }
    }
};
?>

<x-templates.form :edit="$edit" :disable="$disable" :delete="$delete">

    <flux:input
        :disabled="$disable"
        name="form.name"
        label="Category Name"
        placeholder="e.g. Shopping, Bills"
        wire:model.defer="form.name"
        required
    />

    <x-shared.flux-select
        name="form.type"
        label="Type"
        :disabled="$disable"
        :options="$types"
        optionValue="value"
        optionLabel="label"
        wire:model.defer="form.type"
        required
    />

</x-templates.form>
