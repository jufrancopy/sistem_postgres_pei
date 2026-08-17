<?php

namespace App\Policies\Bioestadistica;

use App\Models\User;

class ImportJobPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('bio.import.view');
    }

    public function view(User $user, $job = null): bool
    {
        return $user->can('bio.import.view');
    }

    public function execute(User $user, $job = null): bool
    {
        return $user->can('bio.import.execute');
    }
}
