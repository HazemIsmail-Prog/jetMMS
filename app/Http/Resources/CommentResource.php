<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment' => $this->comment,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'order_id' => $this->order_id,

            // Relations
            'user' => new UserResource($this->whenLoaded('user')),
            'order' => new OrderResource($this->whenLoaded('order')),

            // Computed
            'is_sender' => $this->user_id == auth()->id(),

            // Formatted
            'formated_created_at' => $this->created_at->format('d-m-Y | H:i'),
            'date' => $this->created_at->format('d-m-Y'),
            'time' => $this->created_at->format('H:i'),

        ];
    }
}
