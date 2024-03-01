<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.knet_collection') }}
            </h2>
        </div>
    </x-slot>

    @if ($this->unCollectedPayments->count() > 0)

        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.date') }}</x-th>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-th>{{ __('messages.receiver') }}</x-th>
                    <x-th>{{ __('messages.knet_ref_number') }}</x-th>
                    <x-th>{{ __('messages.amount') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->unCollectedPayments as $payment)
                    <x-tr>
                        <x-td>{{ $payment->created_at->format('d-m-Y') }}</x-td>
                        <x-td>{{ $payment->invoice->order->technician->name }}</x-td>
                        <x-td>{{ $payment->user->name }}</x-td>
                        <x-td>{{ $payment->knet_ref_number }}</x-td>
                        <x-td>{{ number_format($payment->amount, 3) }}</x-td>
                        <x-td class=" text-end">
                            <x-button wire:confirm="ascsacsac"
                                wire:click="collect_payment({{ $payment }})">{{ __('messages.collect') }}</x-button>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
            <tfoot>
                <x-tr>
                    <x-th></x-th>
                    <x-th></x-th>
                    <x-th></x-th>
                    <x-th>{{ __('messages.total') }}</x-th>
                    <x-th>{{ number_format($this->unCollectedPayments->sum('amount'), 3) }}</x-th>
                    <x-th></x-th>
                </x-tr>
            </tfoot>
        </x-table>
    @else
        <h2 class="font-semibold text-xl flex gap-3 items-center justify-center text-green-600 dark:text-green-500 leading-tight">
            {{ __('messages.no_uncollected_payments') }}
        </h2>
    @endif



</div>
