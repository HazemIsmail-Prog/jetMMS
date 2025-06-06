<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.pending_invoices_report') }}
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
        @if ($this->invoices->hasPages())
        <div class=" flex-1">{{ $this->invoices->links() }}</div>
        @endif
    </div>
    @endteleport



    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">

        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-searchable-select id="department" class="!py-[5px]" :list="$this->departments"
                wire:model.live="filters.department_id" multipule />
        </div>

        <div>
            <x-label for="technician">{{ __('messages.technician') }}</x-label>
            <x-searchable-select id="technician" class="!py-[5px]" :list="$this->technicians"
                wire:model.live="filters.technician_id" multipule />
        </div>

        <div>
            <x-label for="start_created_at">{{ __('messages.created_at') }}</x-label>
            <x-input class="w-full text-center py-0" type="date" id="start_created_at"
                wire:model.live="filters.start_created_at" />
            <x-input class="w-full text-center py-0" type="date" id="end_created_at"
                wire:model.live="filters.end_created_at" />

        </div>

    </div>

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.date') }}</x-th>
                <x-th>{{ __('messages.invoice_number') }}</x-th>
                <x-th>{{ __('messages.order_number') }}</x-th>
                <x-th>{{ __('messages.department') }}</x-th>
                <x-th>{{ __('messages.technician') }}</x-th>
                <x-th>{{ __('messages.amount') }}</x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->invoices as $invoice)
            @php
                $amount = (round(($invoice->queryServicesAmount - $invoice->discount) + $invoice->delivery
                + $invoice->queryPartsAmountFromDetails + $invoice->queryPartsAmountFromParts -
                $invoice->queryPaymentsAmount,3));
            @endphp
            @if($amount > 0)
                <x-tr wire:key="payment-{{$invoice->id}}-{{rand()}}">

                    <x-td>{!! $invoice->formated_created_at !!}</x-td>
                    <x-td> <a target="_blank" class="btn"
                            href="{{ route('invoice.detailed_pdf', encrypt($invoice->id)) }}">{{
                            $invoice->formated_id }}</a>
                    </x-td>
                    <x-td>{{ $invoice->order_number }}</x-td>
                    <x-td>{{ $invoice->department_name }}</x-td>
                    <x-td>{{ $invoice->technician_name }}</x-td>
                    <x-td>{{ number_format($amount,3) }}</x-td>
                </x-tr>
            @endif
            @endforeach
        </tbody>
        <x-tfoot>
            <tr>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th>{{ __('messages.total') }}</x-th>
                <x-th>{{ number_format((round(($this->invoices->sum('queryServicesAmount') - $this->invoices->sum('discount')) + $this->invoices->sum('delivery')
                    + $this->invoices->sum('queryPartsAmountFromDetails') + $this->invoices->sum('queryPartsAmountFromParts') -
                    $this->invoices->sum('queryPaymentsAmount'),3)),3) }}</x-th>
            </tr>
        </x-tfoot>
    </x-table>



</div>
