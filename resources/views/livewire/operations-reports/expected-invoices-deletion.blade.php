<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.expected_invoices_deletion') }}
                <span id="counter"></span>
            </h2>

        </div>
    </x-slot>

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->invoices->total() }}
    </span>
    @endteleport

    @if ($this->invoices->hasPages())
    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>
    @teleport('#pagination')
    <div class="">{{ $this->invoices->links() }}</div>
    @endteleport
    @endif

    @livewire('orders.invoices.invoice-modal')
    @livewire('orders.invoices.invoice-form')
    @livewire('orders.invoices.payments.payment-form')
    @livewire('orders.invoices.discount.discount-form')

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.invoice_number') }}</x-th>
                <x-th>{{ __('messages.order_number') }}</x-th>
                <x-th>{{ __('messages.created_at') }}</x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->invoices as $invoice)
            <x-tr>
                <x-td>{{ $invoice->id }}</x-td>
                <x-td>
                    <x-badgeWithCounter :counter="$this->invoices->where('order_id', $invoice->order_id)->count() > 1
                            ? $this->invoices->where('order_id', $invoice->order_id)->count()
                            : null" title="{{ __('messages.invoices') }}"
                        wire:click="$dispatch('showInvoicesModal',{order:{{ $invoice->order }}})">
                        {{ $invoice->order->formated_id }}
                    </x-badgeWithCounter>
                </x-td>
                <x-td>{!! $invoice->formated_created_at !!}</x-td>
            </x-tr>
            @endforeach
        </tbody>
    </x-table>
</div>