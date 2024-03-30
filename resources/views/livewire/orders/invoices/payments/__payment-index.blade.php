<div>
    <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.payments') }}</h3>

    @if ($this->payments->count() > 0)
        <div class=" overflow-x-auto sm:rounded-lg">

            <x-table>
                <x-thead>
                    <tr>
                        <x-th>{{ __('messages.receiver') }}</x-th>
                        <x-th>{{ __('messages.date') }}</x-th>
                        <x-th>{{ __('messages.amount') }}</x-th>
                        <x-th></x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach ($this->payments as $payment)
                        <x-tr>
                            <x-td>{{ $payment->user->name }}</x-td>
                            <x-td>
                                <div>{{ $payment->created_at->format('d-m-Y') }}</div>
                                <div>{{ $payment->created_at->format('H:i') }}</div>
                            </x-td>
                            <x-td>
                                <div>{{ number_format($payment->amount, 3) }}</div>
                                <div>{{ $payment->method }}</div>
                            </x-td>
                            <x-td>
                                @if (!$payment->is_collected)
                                    @can('delete', $payment)
                                        <x-svgs.trash wire:click="deletePayment({{ $payment }})"
                                            wire:confirm="{{ __('messages.are_u_sure') }}" class=" w-4 h-4 text-red-600" />
                                    @endcan
                                @endif
                            </x-td>
                        </x-tr>
                    @endforeach
                </tbody>
            </x-table>
        </div>
    @else
        <div class="flex items-center justify-center font-bold text-red-600 p-2">
            {{ __('messages.no_payments_found') }}
        </div>
    @endif

    @can('create', App\Models\Payment::class)
        @if ($invoice->remaining_amount > 0)
            <x-section-border />
            @if ($invoice)
                <x-button type="button"
                    wire:click="$dispatch('showPaymentFormModal',{invoice:{{ $invoice }}})">{{ __('messages.create_payment') }}</x-button>
            @endif
        @endif
    @endcan
</div>
