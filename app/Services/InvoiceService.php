<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Invoice;
use App\Services\CreateInvoiceVoucher;

class InvoiceService
{
    public static function rePostInvoice(Order $order, Invoice $invoice)
    {
        // get inv voucher and delete its details and create new details
        $voucher = $invoice->vouchers()->where('type', 'inv')->first(); // get only inv voucher because invoice may have cost voucher also
        $voucher->voucherDetails()->forceDelete();
        CreateInvoiceVoucher::createVoucherDetails($invoice, $voucher);
    }
} 