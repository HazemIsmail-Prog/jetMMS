<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            // Basic
            'id' => $this->id,
            'status_id' => $this->status_id,
            'technician_id' => $this->technician_id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'reason' => $this->reason,

            // Relations
            'status' => new StatusResource($this->whenLoaded('status')),
            'technician' => new UserResource($this->whenLoaded('technician')),
            'creator' => new UserResource($this->whenLoaded('creator')),


            // Formatted
            'date' => $this->created_at->format('d-m-Y'),
            'time' => $this->created_at->format('H:i'),
        ];
    }
}
