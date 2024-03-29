<?php

namespace App\Policies;

use App\Models\Title;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TitlePolicy
{

    public function before(User $user, string $ability): bool|null
    {
        if ($user->id === 1) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('titles_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('titles_create');
    }

    public function update(User $user, Title $title): bool
    {
        return $user->hasPermission('titles_edit');
    }

    public function delete(User $user, Title $title): bool
    {
        return $user->hasPermission('titles_delete');
    }

}
