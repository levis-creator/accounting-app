<?php

namespace App\Livewire\Forms;

use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AccountForm extends GenericForm
{

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|in:cash,bank,mpesa,others')]
    public string $type = 'cash';

    #[Validate('required|numeric|min:0')]
    public float $balance = 0.00;
    public function create(): void
    {
        $this->validate();

        // Logic to save the account details, e.g., using a model
        parent::store([
            ...$this->all(),
            'user_id' => Auth::id(),
        ], Account::class);
        $this->reset();
    }
    public function setAccount(Account $account): void
    {
        $this->setModel($account, $account->only('name', 'type', 'balance'));
    }
    public function edit(): void
    {
        $this->validate();

        // Logic to update the account details, e.g., using a model
        parent::update([
            ...$this->all(),
            'user_id' => Auth::id(),
        ]);
        $this->reset();
    }

    public function mount($accountId = null)
    {
        if ($accountId) {
            $this->mountModel(Account::class, $accountId);
        }
    }
    public function deleteAccount(string $accountId): void
    {
        parent::delete($accountId);
    }
}
