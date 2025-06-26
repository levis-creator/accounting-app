<?php

namespace App\Livewire\Forms;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class TransactionForm extends GenericForm
{
    #[Validate('required|string|max:255')]
    public string $label = '';
    #[Validate('required|in:income,expense')]
    public string $type = 'income';

    #[Validate('required|numeric|min:0.01')]
    public float $amount = 0.00;

    #[Validate('required|uuid|exists:accounts,id')]
    public ?string $account_id= null;

    #[Validate('required|uuid|exists:categories,id')]
    public ?string $category_id= null;

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required|date')]
    public string $transaction_date = '';

    public function create(): void
    {
        $this->validate();

        parent::store([
            ...$this->all(),
            'user_id' => Auth::id(),
        ], Transaction::class);

        $this->reset();
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

        parent::update([
            ...$this->all(),
            'user_id' => Auth::id(),
        ]);

        $this->reset();
    }

    public function mount($transactionId = null): void
    {
        if ($transactionId) {
            $this->mountModel(Transaction::class, $transactionId);
        }
    }

    public function deleteTransaction(string $transactionId): void
    {
        parent::delete($transactionId);
    }
}
