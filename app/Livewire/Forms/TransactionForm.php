<?php

namespace App\Livewire\Forms;

use App\Models\Transaction;
use Livewire\Attributes\Validate;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class TransactionForm extends GenericForm
{
    #[Validate('required|string|max:255')]
    public string $label = '';

    #[Validate('required|in:income,expense')]
    public string $type = 'income';

    #[Validate('required|numeric|min:0.01')]
    public float $amount = 0.00;

    #[Validate('required|uuid|exists:accounts,id')]
    public ?string $account_id = null;

    #[Validate('required|uuid|exists:categories,id')]
    public ?string $category_id = null;

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required|date')]
    public string $transaction_date = '';

    protected TransactionService $service;

    public function boot(TransactionService $service): void
    {
        $this->service = $service;
    }

    public function create(): void
    {
        $this->validate();

        $this->service->create($this->all());

        $this->resetForm();
    }

    public function setTransaction(Transaction $transaction): void
    {
        $this->setModel($transaction, $transaction->only([
            'label',
            'type',
            'amount',
            'account_id',
            'category_id',
            'description',
            'transaction_date'
        ]));
    }

    public function edit(): void
    {
        $this->validate();

        if ($this->model) {
            $this->service->update($this->model, [
                ...$this->all(),
                'user_id' => Auth::id(),
            ]);

            $this->resetForm();
        }
    }

    public function deleteTransaction(string $transactionId): void
    {
        $transaction = Transaction::findOrFail($transactionId);

        $this->service->delete($transaction);
    }

    #[On('deleteTransaction')]
    public function deleteFromTable($payload): void
    {
        $this->deleteTransaction($payload['transactionId']);
    }

    public function mount($transactionId = null): void
    {
        if ($transactionId) {
            $this->mountModel(Transaction::class, $transactionId);
        }
    }
}
