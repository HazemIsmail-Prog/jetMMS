<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.part_invoices') }}
                <span id="counter"></span>
            </h2>
            <div class="flex items-center gap-2">
                <span id="excel"></span>
                <span id="addNew"></span>
            </div>
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

    @teleport('#excel')
        <div>
            @if ($this->part_invoices->total() <= $maxExportSize)
                <x-button wire:confirm="{{ __('messages.are_u_sure') }}"
                    wire:loading.class=" animate-pulse duration-75 cursor-not-allowed" wire:click="excel"
                    wire:loading.attr="disabled">
                    <span class="hidden text-red-400 dark:text-red-600" wire:loading.remove.class=" hidden"
                        wire:target="excel">
                        {{ __('messages.exporting') }}
                    </span>
                    <span wire:loading.remove wire:target="excel">{{ __('messages.export_to_excel') }}</span>
                </x-button>
            @else
                <x-button disabled class=" cursor-not-allowed"
                    title="{{ __('messages.max_export_size', ['maxExportSize' => $maxExportSize]) }}">{{ __('messages.export_to_excel') }}</x-button>
            @endif
        </div>
    @endteleport

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>
    @teleport('#pagination')
        <div class=" flex items-center justify-between gap-2">
            <x-select wire:model.live="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
            </x-select>
            @if ($this->part_invoices->hasPages())
                <div class=" flex-1">{{ $this->part_invoices->links() }}</div>
            @endif
        </div>
    @endteleport

    @livewire('part_invoices.part_invoice-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">

        <div>
            <x-label for="supplier">{{ __('messages.supplier') }}</x-label>
            <x-searchable-select id="supplier" class="!py-[5px]" :list="$this->suppliers"
                wire:model.live="filters.supplier_id" multipule />
        </div>

        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-searchable-select id="department" class="!py-[5px]" :list="$this->departments" wire:model.live="department_id"
                multipule />
        </div>

        <div>
            <x-label for="technician">{{ __('messages.technician') }}</x-label>
            <x-searchable-select id="technician" class="!py-[5px]" :list="$this->technicians"
                wire:model.live="filters.technician_id" multipule />
        </div>

        <div>
            <x-label for="start_date">{{ __('messages.date') }}</x-label>
            <x-input class="w-full text-center py-0" type="date" id="start_date"
                wire:model.live="filters.start_date" />
            <x-input class="w-full text-center py-0" type="date" id="end_date" wire:model.live="filters.end_date" />

        </div>

    </div>

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.invoice_number') }}</x-th>
                <x-th>{{ __('messages.manual_id') }}</x-th>
                <x-th>{{ __('messages.date') }}</x-th>
                <x-th>{{ __('messages.supplier') }}</x-th>
                <x-th>{{ __('messages.contact') }}</x-th>
                <x-th>{{ __('messages.amount') }}</x-th>
                <x-th>{{ __('messages.discount') }}</x-th>
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
                    <x-td>{{ $part_invoice->formated_invoice_amount }}</x-td>
                    <x-td>{{ $part_invoice->formated_discount_amount }}</x-td>
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
        <x-tfoot>
            <tr>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th>{{ __('messages.total') }}</x-th>
                <x-th>{{ number_format($this->part_invoices->sum('invoice_amount'), 3) }}</x-th>
                <x-th>{{ number_format($this->part_invoices->sum('discount_amount'), 3) }}</x-th>
                <x-th>{{ number_format($this->part_invoices->sum('cost_amount'), 3) }}</x-th>
                <x-th>{{ number_format($this->part_invoices->sum('sales_amount'), 3) }}</x-th>
                <x-th></x-th>
            </tr>
        </x-tfoot>
    </x-table>
</div>
