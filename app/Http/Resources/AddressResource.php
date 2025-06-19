<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'area_id' => $this->area_id,
            'block' => $this->block,
            'street' => $this->street,
            'jadda' => $this->jadda,
            'building' => $this->building,
            'floor' => $this->floor,
            'apartment' => $this->apartment,
            'full_address' => $this->getFullAddress($this),
        ];
    }

    private function getFullAddress($address)
    {
        $parts = [];
        $parts[] = $address->area->name_ar;
        
        if ($address->block) {
            $parts[] = __('messages.short_block') . ' ' . $address->block;
        }
        if ($address->street) {
            $parts[] = __('messages.short_street') . ' ' . $address->street;
        }
        if ($address->jadda) {
            $parts[] = __('messages.short_jadda') . ' ' . $address->jadda;
        }
        if ($address->building) {
            $parts[] = __('messages.short_building') . ' ' . $address->building;
        }
        if ($address->floor) {
            $parts[] = __('messages.floor') . ' ' . $address->floor;
        }
        if ($address->apartment) {
            $parts[] = __('messages.apartment') . ' ' . $address->apartment;
        }

        return implode(' - ', array_filter($parts));
    }
}
