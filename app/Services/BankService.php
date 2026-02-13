<?php

namespace App\Services;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Collection;

class BankService
{
    /**
     * Get all banks with country relationship ordered by latest.
     */
    public function getAllBanks(): Collection
    {
        return Bank::with('country')->latest()->get();
    }

    /**
     * Create a new bank account.
     */
    public function createBank(array $data): Bank
    {
        return Bank::create($data);
    }

    /**
     * Find a bank by ID.
     */
    public function getBankById(int $id): Bank
    {
        return Bank::findOrFail($id);
    }

    /**
     * Update an existing bank.
     */
    public function updateBank(Bank $bank, array $data): bool
    {
        return $bank->update($data);
    }

    /**
     * Delete a bank.
     */
    public function deleteBank(Bank $bank): ?bool
    {
        return $bank->delete();
    }
}
