<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.cash_collection') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->unCollectedPayments->total() }}
        </span>
    @endteleport

    @if ($this->unCollectedPayments->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->unCollectedPayments->links() }}</div>
        @endteleport
    @endif

    @if ($this->unCollectedPayments->count() > 0)
        <div class=" overflow-x-auto sm:rounded-lg">
            <x-table>
                <x-thead>
                    <tr>
                        <x-th>{{ __('messages.date') }}</x-th>
                        <x-th>{{ __('messages.technician') }}</x-th>
                        <x-th>{{ __('messages.receiver') }}</x-th>
                        <x-th>{{ __('messages.amount') }}</x-th>
                        <x-th></x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach ($this->unCollectedPayments as $payment)
                        <x-tr>
                            <x-td>{!! $payment->formated_created_at !!}</x-td>
                            <x-td>{{ $payment->invoice->order->technician->name }}</x-td>
                            <x-td>{{ $payment->user->name }}</x-td>
                            <x-td>{{ $payment->formated_amount }}</x-td>
                            <x-td class=" text-end">
                                <x-button wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="collect_payment({{ $payment }})">{{ __('messages.collect') }}</x-button>
                            </x-td>
                        </x-tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <x-tr>
                        <x-th></x-th>
                        <x-th></x-th>
                        <x-th>{{ __('messages.total') }}</x-th>
                        <x-th>{{ number_format($this->unCollectedPayments->sum('amount'), 3) }}</x-th>
                        <x-th></x-th>
                    </x-tr>
                </tfoot>
            </x-table>
        </div>
    @else
        <h2
            class="font-semibold text-xl flex gap-3 items-center justify-center text-green-600 dark:text-green-500 leading-tight">
            {{ __('messages.no_uncollected_payments') }}
        </h2>
    @endif


</div>
