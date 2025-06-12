<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherDetailResource extends JsonResource
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
            'voucher_id' => $this->voucher_id,
            'account_id' => $this->account_id,
            'cost_center_id' => $this->cost_center_id,
            'user_id' => $this->user_id,
            'debit' => round($this->debit, 3),
            'credit' => round($this->credit, 3),
            'narration' => $this->narration,
        ];
    }
}
