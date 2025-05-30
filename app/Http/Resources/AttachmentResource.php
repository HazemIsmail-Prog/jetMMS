<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'translated_description' => app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en,
            'expirationDate' => $this->expirationDate?->format('Y-m-d'),
            'file' => $this->file,
            'full_path' => $this->full_path,
            'attachable_id' => $this->attachable_id,
            'attachable_type' => $this->attachable_type,
            'alertable' => $this->alertable,
            'alertBefore' => $this->alertBefore,
            'can_edit' => $this->can_edit,
            'can_delete' => $this->can_delete,
        ];
    }
}
