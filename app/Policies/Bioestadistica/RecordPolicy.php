<?php

namespace App\Policies\Bioestadistica;

use App\Models\Bioestadistica\Record;
use App\Models\User;

class RecordPolicy
{
    public function view(User $user, Record $record): bool
    {
        return $user->can('bio.record.view') && $record->isAccessibleBy($user);
    }

    public function create(User $user): bool
    {
        return $user->can('bio.record.create');
    }

    public function update(User $user, Record $record): bool
    {
        if (! $user->can('bio.record.update') || ! $record->isAccessibleBy($user)) {
            return false;
        }

        return $record->isEditable() || $user->can('bio.record.approve');
    }

    public function delete(User $user, Record $record): bool
    {
        return $user->can('bio.record.delete') && $record->isAccessibleBy($user);
    }

    public function submit(User $user, Record $record): bool
    {
        return $user->can('bio.record.submit') && $record->isAccessibleBy($user) && $record->isEditable();
    }

    public function approve(User $user, Record $record): bool
    {
        return $user->can('bio.record.approve') && $record->isAccessibleBy($user);
    }
}
