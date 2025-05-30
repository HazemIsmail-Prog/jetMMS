<?php

namespace App\Policies;

use App\Models\Letter;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LetterPolicy
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
        return $user->hasPermission('letters_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('letters_create');
    }

    public function update(User $user, Letter $letter): bool
    {
        return $user->hasPermission('letters_edit');
    }

    public function delete(User $user, Letter $letter): bool
    {
        return $user->hasPermission('letters_delete');
    }

    public function viewAnyAttachment(User $user, Letter $letter): bool
    {
        return $user->hasPermission('letters_attachment');
    }
    public function createAttachment(User $user, Letter $letter): bool
    {
        return $user->hasPermission('letters_create_attachment');
    }
    public function updateAttachment(User $user, Letter $letter): bool
    {
        return $user->hasPermission('letters_update_attachment');
    }
    public function deleteAttachment(User $user, Letter $letter): bool
    {
        return $user->hasPermission('letters_delete_attachment');
    }
    
    
}
