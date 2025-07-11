<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'name' => $this->name,
            'section_name' => app()->getLocale() == 'ar' ? $this->section_name_ar : $this->section_name_en,
            'desc' => app()->getLocale() == 'ar' ? $this->desc_ar : $this->desc_en,
        ];
    }
}
