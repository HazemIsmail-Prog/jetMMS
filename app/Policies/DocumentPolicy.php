<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
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
        return $user->hasPermission('documents_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('documents_create');
    }

    public function update(User $user, Document $document): bool
    {
        return $user->hasPermission('documents_edit');
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->hasPermission('documents_delete');
    }

    public function viewAnyAttachment(User $user, Document $document): bool
    {
        return $user->hasPermission('documents_attachment');
    }
}
