<!DOCTYPE html>
<html lang="en" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $page_title }}</title>
    <style>
        html,
        body {
            font-family: "cairo", sans-serif;
            margin: 0;
            padding: 0;
            color: #555;
            /* Lighter text color */
            font-size: 14px;
            /* Smaller font size */
        }

        .invoice {
            width: 90%;
            margin: 10px auto;
            padding: 20px;
            background-color: #fff;
            /* Background color for the invoice */
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            /* Space between columns */
        }

        .invoice-header {
            border-bottom: 2px solid #00a4e0;
            padding-bottom: 10px;
            margin-bottom: 20px;
            grid-column: span 2;
            /* Spanning both columns */
        }

        .invoice-header h1 {
            margin: 0;
            color: #00a4e0;
        }

        .invoice-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-info-table td {
            padding: 6px;
            /* Adjusted padding */
        }

        .invoice-info-table strong {
            display: inline-block;
            min-width: 120px;
        }

        .to {
            margin-top: 20px;
        }

        .to strong {
            display: inline-block;
            min-width: 120px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        .invoice-table th {
            background: #00a4e0;
            color: #fff;
        }

        .invoice-total {
            margin-top: 20px;
            text-align: right;
        }

        .invoice-footer {
            border-top: 2px solid #00a4e0;
            padding-top: 10px;
            margin-top: 20px;
            grid-column: span 2;
            /* Spanning both columns */
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-start {
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .text-end {
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
        }
    </style>
</head>

<body>

    <div class="invoice-header">
        <h1>{{ __('messages.customer_invoice') }}</h1>
    </div>
    <div class="to text-start">
        <strong>{{ __('messages.customer_info') }}</strong><br>
        {{ $invoice->order->customer->name }}<br>
        {{ $invoice->order->address->full_address() }}<br>
        {{ $invoice->order->phone->number }}
    </div>
    <br>
    <table class="invoice-table">
        <thead>
            <tr>
                <th style="width: 20%;">{{ __('messages.invoice_number') }}</th>
                <th style="width: 20%;">{{ __('messages.order_number') }}</th>
                <th style="width: 20%;">{{ __('messages.date') }}</th>
                <th style="width: 20%;">{{ __('messages.department') }}</th>
                <th style="width: 20%;">{{ __('messages.technician') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: none; font-weight:bold;" class="text-center">{{ $invoice->formated_id }}</td>
                <td style="border: none; font-weight:bold;" class="text-center">{{ $invoice->order->formated_id }}</td>
                <td style="border: none; font-weight:bold;" class="text-center">
                    {{ $invoice->created_at->format('d-m-Y') }}</td>
                <td style="border: none; font-weight:bold;" class="text-center">{{ $invoice->order->department->name }}
                </td>
                <td style="border: none; font-weight:bold;" class="text-center">{{ $invoice->order->technician->name }}
                </td>
            </tr>
        </tbody>
    </table>



    <table class="invoice-table">
        <thead>
            <tr>
                <th class="text-start" style="width: 90%">{{ __('messages.service') }}</th>
                <th style="width: 10%"></th>
            </tr>
        </thead>
        <tbody>

            {{-- Services Section --}}
            @if ($invoice->invoice_details->where('service.type', 'service')->count() > 0)
                <tr>
                    <td colspan="2" class="py-1 text-start" style=" font-weight:bold;background-color:#f2f2f2">
                        {{ __('messages.services') }}
                    </td>
                </tr>
                @foreach ($invoice->invoice_details->where('service.type', 'service') as $row)
                    <tr>
                        <td>{{ $row->service->name }}</td>
                        <td></td>
                    </tr>
                @endforeach
            @endif

            {{-- Parts Section --}}
            @if (
                $invoice->invoice_details->load('service')->where('service.type', 'part')->count() > 0 ||
                    $invoice->invoice_part_details->count() > 0)
                <tr>
                    <td colspan="2" class="py-1 text-start" style=" font-weight:bold;background-color:#f2f2f2">
                        {{ __('messages.parts') }}
                        </th>
                </tr>
                @foreach ($invoice->invoice_details->where('service.type', 'part') as $row)
                    <tr>
                        <td class=" px-1 text-start">{{ $row->service->name }}</td>
                        <td></td>
                    </tr>
                @endforeach
                @foreach ($invoice->invoice_part_details as $row)
                    <tr>
                        <td class=" px-1 text-start">{{ $row->name }}</td>
                        <td></td>
                    </tr>
                @endforeach
            @endif

            {{-- Totals --}}
            @if ($invoice->discount > 0)
                <tr>
                    <td style="border: none; background:transparent; font-weight:bold" class="text-end">
                        {{ __('messages.discount') }}</td>
                    <td style="border: none; background:transparent; font-weight:bold;" class="text-right">
                        {{ number_format($invoice->discount, 3) }}
                    </td>
                </tr>
            @endif
            @if ($invoice->delivery > 0)
                <tr>
                    <td style="border: none; background:transparent; font-weight:bold" class="text-end">
                        {{ __('messages.delivery') }}</td>
                    <td style="border: none; background:transparent; font-weight:bold;" class="text-right">
                        {{ number_format($invoice->delivery, 3) }}
                    </td>
                </tr>
            @endif
            <tr>
                <td style="border: none; background:transparent; font-weight:bold" class="text-end">
                    {{ __('messages.total') }}</td>
                <td style="border: none; background:transparent; font-weight:bold" class="text-right">
                    {{ number_format($invoice->amount, 3) }}
                </td>
            </tr>
            @if ($invoice->cash_amount > 0)
                <tr>
                    <td style="border: none; background:transparent; font-weight:bold" class="text-end">
                        {{ __('messages.cash') }}</td>
                    <td style="border: none; background:transparent; font-weight:bold" class="text-right">
                        {{ number_format($invoice->cash_amount, 3) }}</td>
                </tr>
            @endif
            @if ($invoice->knet_amount > 0)
                <tr>
                    <td style="border: none; background:transparent; font-weight:bold" class="text-end">
                        {{ __('messages.knet') }}</td>
                    <td style="border: none; background:transparent; font-weight:bold" class="text-right">
                        {{ number_format($invoice->knet_amount, 3) }}</td>
                </tr>
            @endif
            <tr>
                <td style="border: none; background:transparent; font-weight:bold" class="text-end">
                    {{ __('messages.paid_amount') }}</td>
                <td style="border: none; background:transparent; font-weight:bold" class="text-right">
                    {{ number_format($invoice->payments->sum('amount'), 3) }}</td>
            </tr>
            @if ($invoice->remaining_amount > 0)
                <tr>
                    <td style="border: none; background:transparent; font-weight:bold" class="text-end">
                        {{ __('messages.remaining_amount') }}</td>
                    <td style="border: none; background:transparent; font-weight:bold" class="text-right">
                        {{ number_format($invoice->remaining_amount, 3) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="invoice-footer">
        <p>{{ __('messages.thanks_message') }}</p>
    </div>

</body>

</html>
