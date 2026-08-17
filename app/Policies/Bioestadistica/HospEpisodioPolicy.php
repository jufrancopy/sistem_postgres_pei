<?php

namespace App\Policies\Bioestadistica;

use App\Models\Bioestadistica\HospEpisodio;
use App\Models\User;

class HospEpisodioPolicy
{
    public function view(User $user, HospEpisodio $episodio): bool
    {
        return $user->can('bio.hosp.view') && $episodio->isAccessibleBy($user);
    }

    public function create(User $user): bool
    {
        return $user->can('bio.hosp.manage');
    }

    public function update(User $user, HospEpisodio $episodio): bool
    {
        return $user->can('bio.hosp.manage') && $episodio->isAccessibleBy($user);
    }

    public function delete(User $user, HospEpisodio $episodio): bool
    {
        return $user->can('bio.hosp.manage') && $episodio->isAccessibleBy($user);
    }

    public function viewPii(User $user, HospEpisodio $episodio): bool
    {
        return $user->can('bio.hosp.view_pii') && $episodio->isAccessibleBy($user);
    }

    public function export(User $user, $episodio = null): bool
    {
        return $user->can('bio.hosp.export');
    }
}
