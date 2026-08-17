<?php

namespace App\Policies\Bioestadistica;

use App\Models\Bioestadistica\Dashboard;
use App\Models\User;

class DashboardPolicy
{
    public function view(User $user, Dashboard $dashboard): bool
    {
        if (! $user->can('bio.dashboard.view')) {
            return false;
        }

        return $dashboard->isInstitutional() || (int) $dashboard->user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('bio.dashboard.manage');
    }

    public function update(User $user, Dashboard $dashboard): bool
    {
        if ($dashboard->isInstitutional()) {
            return $user->can('bio.dashboard.manage');
        }

        return (int) $dashboard->user_id === (int) $user->id && $user->can('bio.dashboard.personalize');
    }

    public function delete(User $user, Dashboard $dashboard): bool
    {
        return $this->update($user, $dashboard);
    }
}
