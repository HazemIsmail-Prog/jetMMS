<?php

namespace App\Policies;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RatingPolicy
{

    // public function before(User $user, string $ability): bool|null
    // {
    //     if ($user->id === 1) {
    //         return true;
    //     }

    //     return null;
    // }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('rating_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('rating_create');
    }
}
