<?php

namespace App\Http\Resources;

use App\Enums\CancelReasonEnum;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CancelSurveyResource extends JsonResource
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
            'order_id' => $this->order_id,
            'cancel_reason' => $this->cancel_reason,
            'other_reason' => $this->other_reason,
            'created_at' => $this->created_at,
            'formatted_created_at_date' => $this->created_at->format('d-m-Y'),
            'formatted_created_at_time' => $this->created_at->format('H:i'),

            'translated_cancel_reason' => CancelReasonEnum::from($this->cancel_reason)->title(),
            'order' => new OrderResource($this->whenLoaded('order')),
        ];
    }
}
