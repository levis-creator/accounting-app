<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function create(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = Auth::id();

            $transaction = Transaction::create($data);

            $this->updateAccountBalance(
                $transaction->account,
                $transaction->amount,
                $transaction->type
            );

            return $transaction;
        });
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        return DB::transaction(function () use ($transaction, $data) {
            // Reverse the old balance impact
            $this->updateAccountBalance(
                $transaction->account,
                $transaction->amount,
                $transaction->type,
                reverse: true
            );

            $transaction->update($data);

            // Apply the new balance impact
            $this->updateAccountBalance(
                $transaction->account,
                $transaction->amount,
                $transaction->type
            );

            return $transaction;
        });
    }

    public function delete(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $this->updateAccountBalance(
                $transaction->account,
                $transaction->amount,
                $transaction->type,
                reverse: true
            );

            $transaction->delete();
        });
    }

    private function updateAccountBalance(Account $account, float $amount, string $type, bool $reverse = false): void
    {
        if ($type === 'income') {
            $account->balance += $reverse ? -$amount : $amount;
        } else {
            $account->balance += $reverse ? $amount : -$amount;
        }

        $account->save();
    }
}
