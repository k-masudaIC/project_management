<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'pm', 'contractor'], true);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if (in_array($user->role, ['admin', 'pm'], true)) {
            return true;
        }

        return $user->role === 'contractor' && $invoice->user_id === $user->id;
    }

    public function generate(User $user): bool
    {
        return in_array($user->role, ['admin', 'pm'], true);
    }

    public function markPaid(User $user): bool
    {
        return in_array($user->role, ['admin', 'pm'], true);
    }
}
