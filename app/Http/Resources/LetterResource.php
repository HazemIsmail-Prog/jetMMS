<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LetterResource extends JsonResource
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
            'type' => $this->type,
            'translated_type' => __('messages.' . $this->type),
            'type_color_class' => $this->type == 'incoming' ? 'text-green-500' : 'text-red-500',
            'date' => $this->date,
            'sender' => $this->sender,
            'receiver' => $this->receiver,
            'reference' => $this->reference,
            'subject' => $this->subject,
            'attachments_count' => $this->attachments_count,
            'can_edit' => auth()->user()->can('update', $this->resource),
            'can_delete' => auth()->user()->can('delete', $this->resource),
            'can_view_attachments' => auth()->user()->can('viewAnyAttachment', $this->resource),
            'can_create_attachment' => auth()->user()->can('createAttachment', $this->resource),
            'can_update_attachment' => auth()->user()->can('updateAttachment', $this->resource),
            'can_delete_attachment' => auth()->user()->can('deleteAttachment', $this->resource),
        ];
    }
}
