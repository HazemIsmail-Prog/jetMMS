<div class=" p-4 border dark:border-gray-700 rounded-lg">

    {{-- Header Section --}}
    <div class=" flex items-center justify-between mb-3">
        <h3 class="font-semibold text-gray-900 dark:text-white">
            {{ $invoice->formated_id }}
        </h3>

        {{-- Print and Delete Section --}}
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
                        <!-- PDF Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('messages.print_invoice') }}
                        </div>
                        <x-dropdown-link target="_blank"
                            href="{{ route('invoice.detailed_pdf', encrypt($invoice->id)) }}">
                            {{ __('messages.print_detailed_invoice') }}
                        </x-dropdown-link>
                        <x-dropdown-link target="_blank" href="{{ route('invoice.pdf', encrypt($invoice->id)) }}">
                            {{ __('messages.print_non_detailed_invoice') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
            @if ($invoice->can_deleted)
                <x-badgeWithCounter wire:click="deleteInvoice({{ $invoice }})"
                    wire:confirm="{{ __('messages.are_u_sure') }}" title="{{ __('messages.print_invoice') }}">
                    <x-svgs.trash class="h-4 w-4 text-red-600" />
                </x-badgeWithCounter>
            @endif
        </div>

    </div>

    {{-- Invoice Table --}}
    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table class=" table-auto">
            <x-thead>
                <tr>
                    <x-th></x-th>
                    <x-th>{{ __('messages.quantity') }}</x-th>
                    <x-th>{{ __('messages.unit_price') }}</x-th>
                    <x-th>{{ __('messages.total') }}</x-th>
                </tr>
            </x-thead>

            {{-- Services Section --}}
            @if ($invoice->invoice_details->load('service')->where('service.type', 'service')->count() > 0)
                <x-tr>
                    <x-th colspan="4">{{ __('messages.services') }}</x-th>
                </x-tr>
                @foreach ($invoice->invoice_details->where('service.type', 'service') as $row)
                    <x-tr>
                        <x-td class=" !whitespace-normal">{{ $row->service->name }}</x-td>
                        <x-td>{{ $row->quantity }}</x-td>
                        <x-td>{{ number_format($row->price, 3) }}</x-td>
                        <x-td>{{ number_format($row->total, 3) }}</x-td>
                    </x-tr>
                @endforeach
            @endif

            {{-- Parts Section --}}
            @if (
                $invoice->invoice_details->load('service')->where('service.type', 'part')->count() > 0 ||
                    $invoice->invoice_part_details->count() > 0)
                <x-tr>
                    <x-th colspan="4">{{ __('messages.parts') }}</x-th>
                </x-tr>
                @foreach ($invoice->invoice_details->where('service.type', 'part') as $row)
                    <x-tr>
                        <x-td class=" !whitespace-normal">{{ $row->service->name }}</x-td>
                        <x-td>{{ $row->quantity }}</x-td>
                        <x-td>{{ number_format($row->price, 3) }}</x-td>
                        <x-td>{{ number_format($row->total, 3) }}</x-td>
                    </x-tr>
                @endforeach
                @foreach ($invoice->invoice_part_details as $row)
                    <x-tr>
                        <x-td class=" !whitespace-normal">{{ $row->name }}</x-td>
                        <x-td>{{ $row->quantity }}</x-td>
                        <x-td>{{ number_format($row->price, 3) }}</x-td>
                        <x-td>{{ number_format($row->total, 3) }}</x-td>
                    </x-tr>
                @endforeach
            @endif

            {{-- Totals Section --}}
            @if ($invoice->delivery > 0)
                <tr>
                    <x-th colspan="3" class=" text-end">{{ __('messages.delivery') }}</x-th>
                    <x-th>{{ number_format($invoice->delivery, 3) }}</x-th>
                </tr>
            @endif

            @if ($invoice->discount > 0)
                <tr>
                    <x-th colspan="3" class=" text-end">{{ __('messages.discount') }}</x-th>
                    <x-th class="text-red-500 line-through">{{ number_format($invoice->discount, 3) }}</x-th>
                </tr>
            @endif
            <tr>
                <x-th colspan="3" class=" text-end">{{ __('messages.total') }}</x-th>
                <x-th>{{ number_format($invoice->amount, 3) }}</x-th>
            </tr>
            <tr>
                <x-th colspan="3" class=" text-end">{{ __('messages.paid_amount') }}</x-th>
                <x-th>{{ $invoice->payments->sum('amount') > 0 ? number_format($invoice->payments->sum('amount'), 3) : '-' }}</x-th>
            </tr>
            <tr>
                <x-th colspan="3" class=" text-end">{{ __('messages.remaining_amount') }}</x-th>
                <x-th>{{ $invoice->remaining_amount > 0 ? number_format($invoice->remaining_amount, 3) : '-' }}</x-th>
            </tr>
        </x-table>
    </div>
    <x-section-border />

    {{-- Discount Button --}}
    <!-- if the invoice is in same day, show the discount button -->
    @if ($invoice->can_apply_discount && $invoice->created_at->isToday())
        <div class=" text-center">
            <x-button type="button"
                wire:click="$dispatch('showDiscountFormModal',{invoice:{{ $invoice }}})">{{ __('messages.edit_discount') }}</x-button>
            <x-section-border />
        </div>
    @endif

    {{-- Payments Index --}}
    @include('livewire.orders.invoices.payments.__payment-index')

</div>
