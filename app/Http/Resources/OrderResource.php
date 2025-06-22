<?php

namespace App\Http\Resources;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $user = auth()->user();

        $userCanHoldOrder = $user->hasPermission('orders_hold');
        $userCanCancelOrder = $user->hasPermission('orders_cancel');
        $userCanViewProgress = $user->hasPermission('orders_progress');
        $userCanViewComments = $user->hasPermission('orders_comments');
        $userCanViewInvoices = $user->hasPermission('orders_invoices');
        $userCanEditCustomer = $user->hasPermission('customers_edit');
        $userCanEditOrder = $user->hasPermission('orders_edit') || $this->created_by == $user->id;
        $userCanViewOrderDetails = true;
        $userCanSendSurvey = $user->hasPermission('orders_send_survey');
        $formatted_id = str_pad($this->id, 8, '0', STR_PAD_LEFT);

        $phone = $this->whenLoaded('phone') ? new PhoneResource($this->whenLoaded('phone')) : null;
        $whatsapp_message = $userCanSendSurvey && $this->status_id == Status::COMPLETED && isset($phone->number) ? $this->whatsappMessage($formatted_id, $phone->number, $this->id) : null;
        


        return [

            // Permissions
            'can_edit_order' => $userCanEditOrder,
            'can_send_survey' => $userCanSendSurvey,
            'can_edit_customer' =>$userCanEditCustomer,
            'view_order_comments' => $userCanViewComments,
            'view_order_progress' => $userCanViewProgress,
            'view_order_invoices' => $userCanViewInvoices,
            'can_view_order_details' => $userCanViewOrderDetails,
            'can_hold_order' => $this->invoices_count == 0 && $userCanHoldOrder,
            'can_cancel_order' => $this->invoices_count == 0 && $userCanCancelOrder,

            // Basic
            'id' => $this->id,
            'tag' => $this->tag,
            'notes' => $this->notes,
            'index' => $this->index,
            'reason' => $this->reason,
            'phone_id' => $this->phone_id,
            'status_id' => $this->status_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'address_id' => $this->address_id,
            'customer_id' => $this->customer_id,
            'completed_at' => $this->completed_at,
            'cancelled_at' => $this->cancelled_at,
            'technician_id' => $this->technician_id,
            'department_id' => $this->department_id,
            'order_description' => $this->order_description,
            'estimated_start_date' => $this->estimated_start_date->format('Y-m-d'),
            
            
            // Formated
            'formatted_id' => $formatted_id,  // wanna fill it with zeros
            
            'formatted_estimated_start_date' => $this->estimated_start_date?->format('d-m-Y'),
            
            'formatted_created_at' => $this->created_at?->format('d-m-Y | H:i'),
            'formatted_creation_date' => $this->created_at?->format('d-m-Y'),
            'formatted_creation_time' => $this->created_at?->format('H:i'),
            
            'formatted_completed_at' => $this->completed_at?->format('d-m-Y | H:i'),
            'formatted_completion_date' => $this->completed_at?->format('d-m-Y'),
            'formatted_completion_time' => $this->completed_at?->format('H:i'),
            
            'formatted_cancelled_at' => $this->cancelled_at?->format('d-m-Y | H:i'),
            'formatted_cancellation_date' => $this->cancelled_at?->format('d-m-Y'),
            'formatted_cancellation_time' => $this->cancelled_at?->format('H:i'),
            
            // Computed
            'is_future' => $this->estimated_start_date->isFuture(),
            'in_progress' => $this->status_id == Status::RECEIVED || $this->status_id == Status::ARRIVED,
            'is_draggable' => $this->status_id !== Status::RECEIVED && $this->status_id !== Status::ARRIVED,
            'whatsapp_message' => $whatsapp_message,

            // Belongs to Relations
            'invoices_count' => $this->whenCounted('invoices'),
            'status' => new StatusResource($this->whenLoaded('status')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'technician' => new UserResource($this->whenLoaded('technician')),
            'phone' => $phone,
            'address' => new AddressResource($this->whenLoaded('address')),

            // Has Many Relations
            'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')),
        ];
    }


    public function whatsappMessage($formatted_id, $phone_number,$id)
    {
        $line1 = '*مسك الدار للمقاولات العامة للمباني*';
        $line2 = '%0a';
        $line3 = 'تم تنفيذ طلبكم رقم ' . $formatted_id;
        $line4 = '%0a';
        $line5 = 'يمكنك تقييم الطلب وتحميل الفواتير من خلال الرابط التالي';
        $line6 = '%0a';
        $line7 = '%0a';
        $welcomeMessage = $line1 . $line2 . $line3 . $line4 . $line5 . $line6 . $line7;
        $number = '965' . $phone_number;
        $encryptedOrderId = route('customer.page', encrypt($id));
        return 'https://api.whatsapp.com/send?phone=' . $number . '&text=' . $welcomeMessage . $encryptedOrderId;
    }
}
