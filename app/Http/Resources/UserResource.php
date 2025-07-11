<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en,
            'username' => $this->username,
            'title' => new TitleResource($this->whenLoaded('title')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'department_id' => $this->department_id,
            'title_id' => $this->title_id,
            'shift_id' => $this->shift_id,
            'permissions' => $this->permissions(),
            'roles' => $this->whenLoaded('roles'),
            'directPermissions' => $this->whenLoaded('directPermissions'),
            'active' => $this->active,
            'can_edit' => auth()->user()->hasPermission('users_edit'),
            'can_delete' => auth()->user()->hasPermission('users_delete'),
            'can_duplicate' => auth()->user()->hasPermission('users_create'),
        ];
    }
}
