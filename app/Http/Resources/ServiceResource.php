<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'department_id' => $this->department_id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => config('app.locale') == 'ar' ? $this->name_ar : $this->name_en,
            'active' => $this->active,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'type' => $this->type,
            'cost' => $this->cost,
        ];
    }
}
