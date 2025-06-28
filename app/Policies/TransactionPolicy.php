<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;

class TransactionPolicy
{
    /**
     * User can view any transaction (not used unless you list all transactions for all users).
     */
    public function viewAny(User $user): bool
    {
        return true; // ✅ or false if restricted
    }

    /**
     * User can view a transaction if they own it.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }

    /**
     * User can create a transaction.
     */
    public function create(User $user): bool
    {
        return true; // ✅ Allow authenticated users to create
    }

    /**
     * User can update their own transaction.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }

    /**
     * User can delete their own transaction.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }

    /**
     * Optional: disallow restore.
     */
    public function restore(User $user, Transaction $transaction): bool
    {
        return false;
    }

    /**
     * Optional: disallow force delete.
     */
    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return false;
    }
}
