<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerContractResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'address_id' => $this->address_id,
            'user_id' => $this->user_id,
            'contract_type' => $this->contract_type,
            'contract_date' => $this->contract_date,
            'contract_duration' => $this->contract_duration,
            'contract_value' => $this->contract_value,
            'contract_number' => $this->contract_number,
            'building_type' => $this->building_type,
            'units_count' => $this->units_count,
            'central_count' => $this->central_count,
            'collected_amount' => $this->collected_amount,
            'notes' => $this->notes,
            'sp_included' => $this->sp_included,
            'active' => $this->active,
            'contract_expiration_date' => $this->contract_expiration_date,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
