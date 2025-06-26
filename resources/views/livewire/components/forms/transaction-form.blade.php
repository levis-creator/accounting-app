<?php

use Livewire\Volt\Component;
use App\Livewire\Forms\TransactionForm;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public TransactionForm $form;
    public ?string $transactionId = null;
    public bool $edit;
    public ?bool $disable;
    public bool $delete = false;

    public array $accounts = [];
    public array $categories = [];

    public array $types = [];

    public function submit(): void
    {
        $this->edit ? $this->form->edit() : $this->form->create();

        $this->redirect(
            $this->edit
                ? route('pages.transactions.show', ['transaction' => $this->transactionId])
                : route('pages.transactions.index')
        );
    }

    public function mount(?string $transactionId = null, bool $edit = false, bool $disable = false): void
    {
        $this->transactionId = $transactionId;
        $this->edit = $edit;
        $this->disable = $disable;

        $this->types = [
            ['value' => 'income', 'label' => 'Income'],
            ['value' => 'expense', 'label' => 'Expense'],
        ];

        // Fix: use map to format each account/category as an object with id/name
        $this->accounts = Account::where('user_id', Auth::id())
            ->get(['id', 'name'])
            ->map(fn($acc) => ['id' => $acc->id, 'name' => $acc->name])
            ->toArray();

        $this->categories = Category::where('user_id', Auth::id())
            ->get(['id', 'name'])
            ->map(fn($cat) => ['id' => $cat->id, 'name' => $cat->name])
            ->toArray();

        if ($transactionId) {
            $this->form->mount($transactionId);
        }
    }

    public function goToIndex(): void
    {
        $this->redirect(route('pages.transactions.index'));
    }

    public function deleteItem(): void
    {
        if ($this->transactionId) {
            $this->form->deleteTransaction($this->transactionId);
            $this->redirect(route('pages.transactions.index'));
        }
    }
};
?>


<x-templates.form :edit="$edit" :disable="$disable" :delete="$delete">
    <flux:input
        name="form.label"
        label="Label"
        placeholder="e.g. Grocery, Salary, Loan Repayment"
        wire:model.defer="form.label"
        :disabled="$disable"
        required
    />

    <flux:input
        type="number"
        step="0.01"
        name="form.amount"
        label="Amount"
        placeholder="0.00"
        wire:model.defer="form.amount"
        :disabled="$disable"
        required
    />

    <x-shared.flux-select
        name="form.type"
        label="Transaction Type"
        :disabled="$disable"
        required
        :options="$types"
        optionValue="value"
        optionLabel="label"
        wire:model.defer="form.type"
    />

    <x-shared.flux-select
        name="form.account_id"
        label="Account"
        :disabled="$disable"
        required
        :options="$accounts"
        optionValue="id"
        optionLabel="name"
        wire:model.defer="form.account_id"
    />

    <x-shared.flux-select
        name="form.category_id"
        label="Category"
        :disabled="$disable"
        required
        :options="$categories"
        optionValue="id"
        optionLabel="name"
        wire:model.defer="form.category_id"
    />

    <flux:input
        type="date"
        name="form.transaction_date"
        label="Transaction Date"
        wire:model.defer="form.transaction_date"
        :disabled="$disable"
        required
    />

    <div class="col-span-1 md:col-span-2">
        <flux:textarea
            name="form.description"
            label="Description"
            placeholder="Add details..."
            wire:model.defer="form.description"
            :disabled="$disable"
        />
    </div>

</x-templates.form>
