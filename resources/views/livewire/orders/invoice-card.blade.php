<div class=" p-4 border dark:border-gray-700 rounded-lg">

    {{-- Header Section --}}
    <div class=" flex items-center justify-between">
        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $invoice->id }}</h3>
        <x-svgs.trash wire:click="deleteInvoice({{ $invoice->id }})" wire:confirm="adcflkhlkh"
            class=" w-4 h-4 text-red-600" />
    </div>

    {{-- Invoice Table --}}
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-1 py-1"></th>
                <th scope="col" class="px-1 py-1 text-center">
                    {{ __('messages.quantity') }}
                </th>
                <th scope="col" class="px-1 py-1 text-right">
                    {{ __('messages.unit_price') }}
                </th>
                <th scope="col" class="px-1 py-1 text-right">
                    {{ __('messages.total') }}
                </th>
            </tr>
        </thead>

        {{-- Services Section --}}
        @if ($invoice->invoice_details->load('service')->where('service.type', 'service')->count() > 0)
            <tr>
                <th scope="col" class="py-1 text-start">
                    {{ __('messages.services') }}
                </th>
            </tr>
            @foreach ($invoice->invoice_details->where('service.type', 'service') as $row)
                <tr>
                    <td class=" px-1 text-start">{{ $row->service->name }}</td>
                    <td class=" px-1 text-center">{{ $row->quantity }}</td>
                    <td class=" px-1 text-right">{{ number_format($row->price, 3) }}</td>
                    <td class=" px-1 text-right">{{ number_format($row->total, 3) }}</td>
                </tr>
            @endforeach
        @endif

        {{-- Parts Section --}}
        @if ($invoice->invoice_details->load('service')->where('service.type', 'part')->count() > 0)
            <tr>
                <th scope="col" class="py-1 text-start">
                    {{ __('messages.parts') }}
                </th>
            </tr>
            @foreach ($invoice->invoice_details->where('service.type', 'part') as $row)
                <tr>
                    <td class=" px-1 text-start">{{ $row->service->name }}</td>
                    <td class=" px-1 text-center">{{ $row->quantity }}</td>
                    <td class=" px-1 text-right">{{ number_format($row->price, 3) }}</td>
                    <td class=" px-1 text-right">{{ number_format($row->total, 3) }}</td>
                </tr>
            @endforeach
        @endif

        {{-- Totals Section --}}
        <tr class=" border-t dark:border-gray-700">
            <th>{{ __('messages.total') }}</th>
            <th></th>
            <th></th>
            <th class=" text-right">{{ number_format($invoice->amount, 3) }}
            </th>
        </tr>
        <tr>
            <th>{{ __('messages.paid_amount') }}</th>
            <th></th>
            <th></th>
            <th class=" text-right">
                {{ number_format($invoice->payments->sum('amount'), 3) }}</th>
        </tr>
        <tr>
            <th>{{ __('messages.remaining_amount') }}</th>
            <th></th>
            <th></th>
            <th class=" text-right">
                {{ number_format($invoice->remaining_amount, 3) }}</th>
        </tr>
    </table>
    <x-section-border />

    <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.payments') }}</h3>

    @if ($this->payments->count() > 0)
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-1 py-1">{{ __('messages.receiver') }}</th>
                    <th scope="col" class="px-1 py-1 text-center">
                        {{ __('messages.date') }}
                    </th>
                    <th scope="col" class="px-1 py-1 text-right">
                        {{ __('messages.time') }}
                    </th>
                    <th scope="col" class="px-1 py-1 text-right">
                        {{ __('messages.amount') }}
                    </th>
                    <th scope="col" class="px-1 py-1 text-right">
                        {{ __('messages.payment_method') }}
                    </th>
                    <th scope="col" class="px-1 py-1 text-right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->payments as $payment)
                    <tr>
                        <td>{{ $payment->user->name }}</td>
                        <td>{{ $payment->created_at->format('d-m-Y') }}</td>
                        <td>{{ $payment->created_at->format('H:i') }}</td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->method }}</td>
                        <td>
                            <x-svgs.trash wire:click="deletePayment({{ $payment->id }})" wire:confirm="adcflkhlkh"
                                class=" w-4 h-4 text-red-600" />
                        </td>
                    </tr>
                @endforeach
                <tr></tr>
            </tbody>



        </table>
        <x-section-border />
    @else
        <div class="flex items-center justify-center font-bold text-red-600 p-2">
            {{ __('messages.no_payments_found') }}
        </div>
    @endif

    @if ($invoice->remaining_amount > 0)
        <livewire:orders.payment-form :$invoice :key="'invoice-' . $invoice->id . '-' . now()">
    @endif


</div>
