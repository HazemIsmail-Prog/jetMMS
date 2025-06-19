<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'invoice_id' => $this->invoice_id,
            'amount' => (float)$this->amount,
            'method' => $this->method,
            'knet_ref_number' => $this->knet_ref_number,
            'is_collected' => $this->is_collected,
            'created_at' => $this->created_at,
            'user_id' => $this->user_id,

            // Relations
            'user' => new UserResource($this->whenLoaded('user')),

            // Formatted
            'formatted_date' => $this->created_at->format('d-m-Y'),
            'formatted_time' => $this->created_at->format('H:i'),

            // Permissions
            'can_delete' => auth()->user()->can('delete', $this->resource),
        ];
    }
}
