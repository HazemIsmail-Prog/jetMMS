<div>
    <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.payments') }}</h3>

    @if ($this->payments->count() > 0)
        <table class="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-1 py-1">{{ __('messages.receiver') }}</th>
                    <th scope="col" class="px-1 py-1 text-start">
                        <div>{{ __('messages.date') }}</div>
                    </th>
                    <th scope="col" class="px-1 py-1 text-right">
                        {{ __('messages.amount') }}
                    </th>
                    <th scope="col" class="px-1 py-1 text-right"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->payments as $payment)
                    <tr>
                        <td>{{ $payment->user->name }}</td>
                        <td class="px-1 py-1">
                            <div class=" whitespace-nowrap">{{ $payment->created_at->format('d-m-Y') }}</div>
                            <div>{{ $payment->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-1 py-1 text-right">
                            <div>{{ number_format($payment->amount, 3) }}</div>
                            <div>{{ $payment->method }}</div>
                        </td>
                        <td class="px-1 py-1">
                            <x-svgs.trash wire:click="delete({{ $payment }})"
                                wire:confirm="{{ __('messages.are_u_sure') }}" class=" w-4 h-4 text-red-600" />
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
        <x-button type="button"
            wire:click="$dispatch('showPaymentFormModal',{invoice:{{ $invoice }}})">{{ __('messages.create_payment') }}</x-button>
    @endif
</div>
