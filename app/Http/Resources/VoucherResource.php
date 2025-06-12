<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        $user = auth()->user();


        $can_view = $user->hasPermission('journal_vouchers_view');
        $can_create = $user->hasPermission('journal_vouchers_create');
        $can_edit = $user->hasPermission('journal_vouchers_edit');
        $can_delete = $user->hasPermission('journal_vouchers_delete');
        $can_list_attachments = $user->hasPermission('journal_vouchers_attachment');
        $can_view_attachment = $user->hasPermission('journal_vouchers_attachment_view');
        $can_create_attachments = $user->hasPermission('journal_vouchers_attachment_create');
        $can_update_attachments = $user->hasPermission('journal_vouchers_attachment_update');
        $can_delete_attachments = $user->hasPermission('journal_vouchers_attachment_delete');

        return [

            // Basic
            'id' => $this->id,
            'manual_id' => $this->manual_id,
            'date' => $this->date,
            'notes' => $this->notes,
            'invoice_id' => $this->invoice_id,
            'part_invoice_id' => $this->part_invoice_id,
            'payment_id' => $this->payment_id,
            'date' => $this->date->format('Y-m-d'),
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,

            // Formatted
            'formatted_date' => $this->date->format('d-m-Y'),
            'formatted_created_at' => $this->created_at->format('d-m-Y'),
            'formatted_updated_at' => $this->updated_at->format('d-m-Y'),

            // Relations
            'creator' => new UserResource($this->whenLoaded('user')),
            'attachments_count' => $this->whenCounted('attachments'),
            'amount' => $this->voucher_details_sum_debit,


            // Permissions
            'can_create' => $can_create,
            'can_edit' => $can_edit,
            'can_delete' => $can_delete,
            'can_list_attachments' => $can_list_attachments,
            'can_view_attachment' => true,
            'can_create_attachment' => true,
            'can_update_attachment' => true,
            'can_delete_attachment' => true,

        ];
    }
}
