<?php

namespace App\Livewire\Orders\Invoices;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceCard extends Component
{
    public Invoice $invoice;

    public function mount()
    {
        $this->invoice->load('invoice_part_details');
    }

    #[Computed()]
    public function payments()
    {
        return Payment::query()
            ->where('invoice_id', $this->invoice->id)
            ->with('user')
            ->get();
    }

    public function deleteInvoice(Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            $invoice->payments()->delete();
            $invoice->delete();  // Observer Applied
            DB::commit();
            $this->dispatch('invoicesUpdated');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    public function deletePayment(Payment $payment)
    {
        DB::beginTransaction();
        try {
            if (!$payment->is_collected) {
                $payment->delete(); // Observer Applied
                $this->invoice->update(['payment_status' => $this->invoice->computePaymentStatus()]);
                DB::commit();
                $this->dispatch('paymentsUpdated');
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    #[On('invoicesUpdated')]
    #[On('paymentsUpdated')]
    #[On('discountUpdated')]
    #[On('echo:payments.{invoice.id},RefreshInvoicePaymentsScreenEvent')]
    public function render()
    {
        return view('livewire.orders.invoices.invoice-card');
    }
}
