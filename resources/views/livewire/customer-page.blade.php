<div class=" space-y-4 max-w-2xl mx-auto">
    @livewire('ratings.rating-form')

    <div class="font-semibold text-xl  text-gray-800 dark:text-gray-200">
        <p>{{ __('messages.order_number') }}</p>
        <p>{{ $order->formated_id }}</p>
    </div>

    @if (!$order->rating)
        <div wire:click="$dispatch('showRatingFormModal',{order:{{ $order }}})"
            class=" cursor-pointer flex items-center justify-between px-4 py-6 border dark:border-gray-700 rounded-lg">
            <h2 class=" font-semibold text-xl  text-gray-800 dark:text-gray-200">
                {{ __('messages.press_here_to_rate') }}
            </h2>
            <x-svgs.star class="h-8 text-yellow-400 fill-yellow-400" />
        </div>
    @endif

    <div class="px-4 py-6 border dark:border-gray-700 rounded-lg">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200">
            {{ __('messages.invoices') }}
        </h2>

        <x-section-border/>

        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.invoice_number') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($order->invoices as $invoice)
                    <x-tr>
                        <x-td>{{ $invoice->formated_id }}</x-td>
                        <x-td>
                            <div class=" flex items-center justify-end">
                                <a target="__blank" href="{{ route('invoice.pdf', encrypt($invoice->id)) }}">{{ __('messages.view') }}</a>
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
        
    </div>
</div>
