<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $invoiceDetailsAmount = $this->whenLoaded('invoice_details', function() {
            return InvoiceDetailResource::collection($this->invoice_details)->sum('total');
        }, 0);

        $invoicePartDetailsAmount = $this->whenLoaded('invoice_part_details', function() {
            return InvoicePartDetailResource::collection($this->invoice_part_details)->sum('total');
        }, 0);

        $paymentsAmount = $this->whenLoaded('payments', function() {
            return PaymentResource::collection($this->payments)->sum('amount');
        }, 0);

        $reconciliationsAmount = $this->whenLoaded('reconciliations', function() {
            return ReconciliationResource::collection($this->reconciliations)->sum('amount');
        }, 0);



        $cashPaymentsAmount = $this->whenLoaded('payments', function() {
            return PaymentResource::collection($this->payments)->sum(function($payment) {
                return $payment->method === 'cash' ? $payment->amount : 0;
            });
        }, 0);

        $knetPaymentsAmount = $this->whenLoaded('payments', function() {
            return PaymentResource::collection($this->payments)->sum(function($payment) {
                return $payment->method === 'knet' ? $payment->amount : 0;
            });
        }, 0);

        $invoiceDetailsServicesAmount = $this->whenLoaded('invoice_details', function() {
            return InvoiceDetailResource::collection($this->invoice_details)
                ->sum(function($detail) { 
                    return $detail->service?->type === 'service' ? $detail->total : 0;
                });
        }, 0);

        $invoiceDetailsPartsAmount = $this->whenLoaded('invoice_details', function() {
            return InvoiceDetailResource::collection($this->invoice_details)
                ->sum(function($detail) {
                    return $detail->service?->type === 'part' ? $detail->total : 0;
                });
        }, 0);

        $deliveryAmount = $this->delivery ?? 0;
        $discountAmount = $this->discount ?? 0;
        $totalAmount = $invoiceDetailsAmount + $invoicePartDetailsAmount + $deliveryAmount - $discountAmount;
        $remainingBalance = $totalAmount - $paymentsAmount - $reconciliationsAmount;

        $user = auth()->user();

        $can_discount = $user->hasPermission('invoices_discount');
        $can_deleted = $user->hasPermission('invoices_delete');
        $can_create_payments = $user->hasPermission('payments_create');
        $user_can_add_reconciliation = $user->hasPermission('reconciliations_create');
        $can_list_attachments = $user->hasPermission('invoices_attachments_list');
        $can_create_attachments = $user->hasPermission('invoices_attachments_create');
        $can_update_attachments = $user->hasPermission('invoices_attachments_update');
        $can_delete_attachments = $user->hasPermission('invoices_attachments_delete');
        return [

            // Basic
            'id' => $this->id,
            'order_id' => $this->order_id,
            'created_at' => $this->created_at,
            'delivery' => $this->delivery,
            'discount' => $this->discount,
            'deleted_at' => $this->deleted_at,

            // Formatted
            'formatted_id' => str_pad($this->id, 8, '0', STR_PAD_LEFT),
            'formatted_order_id' => str_pad($this->order_id, 8, '0', STR_PAD_LEFT),
            'formatted_created_at' => $this->created_at->format('d-m-Y | H:i'),
            'detailed_pdf_url' => route('invoice.detailed_pdf', encrypt($this->id)),
            'pdf_url' => route('invoice.pdf', encrypt($this->id)),
            'payment_status' => $this->payment_status->title(),

            // Relations
            'order' => new OrderResource($this->whenLoaded('order')),
            'invoice_details' => InvoiceDetailResource::collection($this->whenLoaded('invoice_details')),

            'invoice_details_services' => $this->whenLoaded('invoice_details', function() {
                return InvoiceDetailResource::collection(
                    $this->invoice_details->filter(function($detail) {
                        return $detail->service?->type === 'service';
                    })
                );
            }),
            
            'invoice_details_parts' => $this->whenLoaded('invoice_details', function() {
                return InvoiceDetailResource::collection(
                    $this->invoice_details->filter(function($detail) {
                        return $detail->service?->type === 'part';
                    })
                );
            }),
            'invoice_part_details' => InvoicePartDetailResource::collection($this->whenLoaded('invoice_part_details')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),

            // Amounts
            'invoice_details_amount' => $invoiceDetailsAmount,
            'invoice_part_details_amount' => $invoicePartDetailsAmount,
            'invoice_details_services_amount' => $invoiceDetailsServicesAmount,
            'invoice_details_parts_amount' => $invoiceDetailsPartsAmount,
            'payments_amount' => $paymentsAmount,
            'remaining_balance' => $remainingBalance,
            'total_amount' => $totalAmount,
            'cash_payments_amount' => $cashPaymentsAmount,
            'knet_payments_amount' => $knetPaymentsAmount,
            'reconciliations_amount' => $reconciliationsAmount,
            // Permissions
            'can_discount' => $this->created_at->isToday() && $can_discount && $this->payments->count() == 0,
            'can_deleted' => $can_deleted,
            'can_view_payments' => $can_create_payments,
            'can_create_payments' => $can_create_payments,
            'user_can_add_reconciliation' => $user_can_add_reconciliation,
            'can_list_attachments' => $can_list_attachments,
            'can_create_attachment' => $can_create_attachments,
            'can_update_attachment' => $can_update_attachments,
            'can_delete_attachment' => $can_delete_attachments,
            'attachments_count' => $this->whenCounted('attachments'),
        ];
    }
}
