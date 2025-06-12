<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;

class ContactPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Contact $contact): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Contact $contact): bool
    {
        return false;
    }

    public function trash(User $user, Contact $contact): bool
    {
        return false;
    }

    public function restore(User $user, Contact $contact): bool
    {
        return false;
    }

    public function delete(User $user, Contact $contact): bool
    {
        return false;
    }
}
