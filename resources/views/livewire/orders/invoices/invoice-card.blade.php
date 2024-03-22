<div class=" p-4 border dark:border-gray-700 rounded-lg">

    {{-- Header Section --}}
    <div class=" flex items-center justify-between mb-3">
        <h3 class="font-semibold text-gray-900 dark:text-white">
            {{ str_pad($invoice->id, 8, '0', STR_PAD_LEFT) }}
        </h3>


        <div class=" flex items-center gap-2">
            <!-- Print Dropdown -->
            <div class="relative">
                <x-dropdown width="48">
                    <x-slot name="trigger">
                        <x-badgeWithCounter title="{{ __('messages.print_invoice') }}">
                            <x-svgs.printer class="h-4 w-4" />
                        </x-badgeWithCounter>
                    </x-slot>
                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('messages.print_invoice') }}
                        </div>
                        <x-dropdown-link target="_blank" href="{{ route('invoice.detailed_pdf', $invoice) }}">
                            {{ __('messages.print_detailed_invoice') }}
                        </x-dropdown-link>
                        <x-dropdown-link target="_blank" href="{{ route('invoice.pdf', $invoice) }}">
                            {{ __('messages.print_non_detailed_invoice') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
            @can('delete', $invoice)
                <x-badgeWithCounter wire:click="delete({{ $invoice }})" wire:confirm="{{ __('messages.are_u_sure') }}"
                    title="{{ __('messages.print_invoice') }}">
                    <x-svgs.trash class="h-4 w-4 text-red-600" />
                </x-badgeWithCounter>
            @endcan
        </div>
    </div>

    {{-- Invoice Table --}}
    <table class="w-full text-xs text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
        @if (
            $invoice->invoice_details->load('service')->where('service.type', 'part')->count() > 0 ||
                $invoice->invoice_part_details->count() > 0)
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
            @foreach ($invoice->invoice_part_details as $row)
                <tr>
                    <td class=" px-1 text-start">{{ $row->name }}</td>
                    <td class=" px-1 text-center">{{ $row->quantity }}</td>
                    <td class=" px-1 text-right">{{ number_format($row->price, 3) }}</td>
                    <td class=" px-1 text-right">{{ number_format($row->total, 3) }}</td>
                </tr>
            @endforeach
        @endif

        {{-- Totals Section --}}
        <tr class=" border-t dark:border-gray-700">
            <th>{{ __('messages.delivery') }}</th>
            <th></th>
            <th></th>
            <th class=" text-right">{{ number_format($invoice->delivery, 3) }}
            </th>
        </tr>
        @if ($invoice->discount > 0)
            <tr>
                <th>{{ __('messages.discount') }}</th>
                <th></th>
                <th></th>
                <th class=" text-right text-red-500 line-through">{{ number_format($invoice->discount, 3) }}
                </th>
            </tr>
        @endif
        <tr>
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

    @if ($invoice->payments->count() == 0 && !in_array(auth()->user()->title_id, [10, 11]))
        <div class=" text-center">
            <x-button type="button"
                wire:click="$dispatch('showDiscountFormModal',{invoice:{{ $invoice }}})">{{ __('messages.edit_discount') }}</x-button>
            <x-section-border />
        </div>
    @endif

    @livewire('orders.invoices.payments.payment-index', ['invoice' => $invoice], key($invoice->id . rand()))




</div>
