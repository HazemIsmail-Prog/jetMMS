<?php

namespace App\Policies;

use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentTypePolicy
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
        return $user->hasPermission('document_types_menu');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('document_types_create');
    }

    public function update(User $user, DocumentType $documentType): bool
    {
        return $user->hasPermission('document_types_edit');
    }

    public function delete(User $user, DocumentType $documentType): bool
    {
        return $user->hasPermission('document_types_delete');
    }
}
