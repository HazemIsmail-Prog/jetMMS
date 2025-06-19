<?php

namespace App\Http\Resources;

use App\Models\Contract;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        $userCanCreateOrders = $user->hasPermission('orders_create');
        $userCanEdit = $user->hasPermission('customers_edit');
        $userCanDelete = $user->hasPermission('customers_delete');
        $userCanCreateContracts = $user->hasPermission('contracts_create');

        return [

            // Permissions
            'can_create_orders' => $userCanCreateOrders,
            'can_edit' => $userCanEdit,
            'can_delete' => $userCanDelete,
            'can_create_contracts' => $userCanCreateContracts,


            // Basic
            'id' => $this->id,
            'name' => $this->name,
            'notes' => $this->notes,


            // Counts
            'contracts_count' => $this->whenCounted('contracts'),
            'in_progress_orders_count' => $this->whenCounted('in_progress_orders_count'),
            'total_orders_count' => $this->whenCounted('orders'),
            
            // HasMany
            'phones' => PhoneResource::collection($this->whenLoaded('phones')),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),

            // Formatted
            'formatted_created_at' => $this->created_at?->format('d-m-Y'),

        ];
    }
}
