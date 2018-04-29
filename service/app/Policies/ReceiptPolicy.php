<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Receipt;

class ReceiptPolicy
{
    /**
     * Intercept checks.
     *
     * @param User $currentUser
     * @return bool
     */
    public function before(User $currentUser)
    {
        if ($currentUser->isAdmin() && (!$currentUser->tokenCan('basic') || $currentUser->tokenCan('undefined'))) {
            return true;
        }
    }

    /**
     * Determine if a given user has permission to show.
     *
     * @param User $currentUser
     * @param Receipt $receipt
     * @return bool
     */
    public function show(User $currentUser, Receipt $receipt)
    {
        return $currentUser->id === $receipt->userId;
    }

    /**
     * Determine if a given user can update.
     *
     * @param User $currentUser
     * @param Receipt $receipt
     * @return bool
     */
    public function update(User $currentUser, Receipt $receipt)
    {
        return $currentUser->id === $receipt->userId;
    }

    /**
     * Determine if a given user can delete.
     *
     * @param User $currentUser
     * @param Receipt $receipt
     * @return bool
     */
    public function destroy(User $currentUser, Receipt $receipt)
    {
        return $currentUser->id === $receipt->userId;
    }
}