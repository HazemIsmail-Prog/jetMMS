<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.part_invoices') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>
    @can('create', App\Models\PartInvoice::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showPartInvoiceFormModal')">
                {{ __('messages.add_part_invoice') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->part_invoices->total() }}
        </span>
    @endteleport

    @if ($this->part_invoices->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->part_invoices->links() }}</div>
        @endteleport
    @endif

    @livewire('part_invoices.part_invoice-form')

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.invoice_number') }}</x-th>
                    <x-th>{{ __('messages.manual_id') }}</x-th>
                    <x-th>{{ __('messages.date') }}</x-th>
                    <x-th>{{ __('messages.supplier') }}</x-th>
                    <x-th>{{ __('messages.contact') }}</x-th>
                    <x-th>{{ __('messages.cost_amount') }}</x-th>
                    <x-th>{{ __('messages.sales_amount') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->part_invoices as $part_invoice)
                    <x-tr>
                        <x-td>{{ $part_invoice->id }}</x-td>
                        <x-td>{{ $part_invoice->manual_id }}</x-td>
                        <x-td>{!! $part_invoice->formated_date !!}</x-td>
                        <x-td>{{ $part_invoice->supplier->name }}</x-td>
                        <x-td>{{ $part_invoice->contact->name }}</x-td>
                        <x-td>{{ $part_invoice->formated_cost_amount }}</x-td>
                        <x-td>{{ $part_invoice->formated_sales_amount }}</x-td>
                        <x-td>
                            <div class="flex items-center justify-end gap-2">
                                @can('update', $part_invoice)
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showPartInvoiceFormModal',{part_invoice:{{ $part_invoice }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan
                                @can('delete', $part_invoice)
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $part_invoice }})">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</div>
