<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.invoices') }}
                <span id="counter"></span>
            </h2>
            <span id="excel"></span>
        </div>
    </x-slot>

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>

    @livewire('orders.invoices.invoice-modal')
    @livewire('orders.invoices.invoice-form')
    @livewire('orders.invoices.payments.payment-form')
    @livewire('orders.invoices.discount.discount-form')

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->invoices->total() }}
    </span>
    @endteleport

    @teleport('#excel')
    <div>
        @if ($this->invoices->total() <= $maxExportSize) <x-button wire:confirm="{{ __('messages.are_u_sure') }}"
            wire:loading.class=" animate-pulse duration-75 cursor-not-allowed" wire:click="excel"
            wire:loading.attr="disabled">
            <span class="hidden text-red-400 dark:text-red-600" wire:loading.remove.class=" hidden" wire:target="excel">
                {{ __('messages.exporting') }}
            </span>
            <span wire:loading.remove wire:target="excel">{{ __('messages.export_to_excel') }}</span>
            </x-button>
            @else
            <x-button disabled class=" cursor-not-allowed"
                title="{{ __('messages.max_export_size', ['maxExportSize' => $maxExportSize]) }}">{{
                __('messages.export_to_excel') }}</x-button>
            @endif
    </div>
    @endteleport

    @teleport('#pagination')
    <div class=" flex items-center justify-between gap-2">
        <x-select wire:model.live="perPage">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="500">500</option>
        </x-select>
        <div class=" flex-1">{{ $this->invoices->links() }}</div>
    </div>
    @endteleport

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-3">

        <div>
            <x-label for="invoice_number">{{ __('messages.invoice_number') }}</x-label>
            <x-input wire:model.live="filters.invoice_id" id="invoice_number" type="number" dir="ltr"
                class="w-full text-center py-0" />
        </div>

        <div>
            <x-label for="order_number">{{ __('messages.order_number') }}</x-label>
            <x-input wire:model.live="filters.order_id" id="order_number" type="number" dir="ltr"
                class="w-full text-center py-0" />
        </div>

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
            <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
            <x-input class="w-full text-center py-0" id="customer_name" wire:model.live="filters.customer_name" />
        </div>

        <div>
            <x-label for="customer_phone">{{ __('messages.customer_phone') }}</x-label>
            <x-input dir="ltr" class="w-full text-center py-0" id="customer_phone"
                wire:model.live="filters.customer_phone" />
        </div>

        <div>
            <x-label for="payment_status">{{ __('messages.payment_status') }}</x-label>
            <x-select id="payment_status" required wire:model.live="filters.payment_status" class=" w-full py-0">
                <option value="">---</option>
                @foreach (App\Enums\PaymentStatusEnum::cases() as $status)
                <option value="{{ $status->value }}">{{ $status->title() }}</option>
                @endforeach
            </x-select>
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
                <x-th>{{ __('messages.invoice_number') }}</x-th>
                <x-th>{{ __('messages.order_number') }}</x-th>
                <x-th>{{ __('messages.created_at') }}</x-th>
                <x-th>{{ __('messages.department') }}</x-th>
                <x-th>{{ __('messages.technician') }}</x-th>
                <x-th>{{ __('messages.customer_name') }}</x-th>
                <x-th>{{ __('messages.customer_phone') }}</x-th>
                <x-th>{{ __('messages.services') }}</x-th>
                <x-th>{{ __('messages.discount') }}</x-th>
                <x-th>{{ __('messages.services_after_discount') }}</x-th>
                <x-th>{{ __('messages.internal_parts') }}</x-th>
                <x-th>{{ __('messages.external_parts') }}</x-th>
                <x-th>{{ __('messages.delivery') }}</x-th>
                <x-th>{{ __('messages.amount') }}</x-th>
                <x-th>{{ __('messages.cash') }}</x-th>
                <x-th>{{ __('messages.knet') }}</x-th>
                <x-th>{{ __('messages.paid_amount') }}</x-th>
                <x-th>{{ __('messages.remaining_amount') }}</x-th>
                <x-th>{{ __('messages.payment_status') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->invoices as $invoice)
            <x-tr>
                @php
                    $servicesAmount = $invoice->servicesAmountSum;
                    $discountAmount = $invoice->discount;
                    $servicesAfterDiscount = $invoice->servicesAmountSum - $invoice->discount;
                    $internalPartsAmount = $invoice->invoiceDetailsPartsAmountSum + $invoice->internalPartsAmountSum;
                    $externalPartsAmount = $invoice->externalPartsAmountSum;
                    $deliveryAmount = $invoice->delivery;
                    $cashAmount = $invoice->totalCashAmountSum;
                    $knetAmount = $invoice->totalKnetAmountSum;
                    $totalPaidAmount = $invoice->totalPaidAmountSum;
                    $totalAmount = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
                    $remainingAmount = $totalAmount - $totalPaidAmount;
                @endphp
                <x-td>
                    <a target="_blank" class="btn" href="{{ route('invoice.detailed_pdf', encrypt($invoice->id)) }}">{{
                        $invoice->formated_id }}</a>
                </x-td>
                <x-td>
                    <x-badgeWithCounter :counter="$invoice->order_invoices_count > 1
                            ? $invoice->order_invoices_count
                            : null" title="{{ __('messages.invoices') }}"
                        wire:click="$dispatch('showInvoicesModal',{order:{{ $invoice->order }}})">
                        {{ $invoice->order->formated_id }}
                    </x-badgeWithCounter>
                </x-td>
                <x-td>
                    <span dir="ltr" class=" cursor-pointer"
                        wire:click="dateClicked('{{ $invoice->created_at->format('Y-m-d') }}')">
                        {!! $invoice->formated_created_at !!}
                    </span>
                </x-td>
                <x-td>
                    <span class=" cursor-pointer"
                        wire:click="$set('filters.department_id',[{{ $invoice->order->department_id }}])">
                        {{ $invoice->order->department->name }}
                    </span>
                </x-td>
                <x-td>
                    <span class=" cursor-pointer"
                        wire:click="$set('filters.technician_id',[{{ $invoice->order->technician_id }}])">
                        {{ $invoice->order->technician->name }}
                    </span>
                </x-td>
                <x-td>{{ $invoice->order->customer->name }}</x-td>
                <x-td>{{ $invoice->order->phone->number }}</x-td>
                <x-td class="!text-right">{{ $servicesAmount > 0 ? number_format($servicesAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $discountAmount > 0 ? number_format($discountAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $servicesAfterDiscount > 0 ? number_format($servicesAfterDiscount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $internalPartsAmount > 0 ? number_format($internalPartsAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $externalPartsAmount > 0 ? number_format($externalPartsAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $deliveryAmount > 0 ? number_format($deliveryAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $totalAmount > 0 ? number_format($totalAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $cashAmount > 0 ? number_format($cashAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $knetAmount > 0 ? number_format($knetAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $totalPaidAmount > 0 ? number_format($totalPaidAmount,3) : '-' }}</x-td>
                <x-td class="!text-right">{{ $remainingAmount > 0 ? number_format($remainingAmount,3) : '-' }}</x-td>
                <x-td>{{ $invoice->payment_status->title() }}</x-td>
                <x-td>
                    <div class=" flex items-center justify-end gap-2">
                        @if ($invoice->collectedPayments === 0)
                            @if ($invoice->order_invoices_count > 1)
                                @can('delete',$invoice)
                                    <x-badgeWithCounter title="{{ __('messages.delete_invoice') }}"
                                        wire:confirm="{{ __('messages.delete_invoice_confirmation') }}"
                                        wire:click="delete({{ $invoice->id }})">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                    @endcan
                            @endif
                        @endif
                        @if (auth()->id() == 1)
                            <x-badgeWithCounter
                                wire:click="refreshStatus({{ $invoice->id }})">
                                <x-svgs.arrow-path class="h-4 w-4" />
                            </x-badgeWithCounter>
                        @endif
                    </div>
                </x-td>
            </x-tr>
            @endforeach
        </tbody>
        <x-tfoot>
                @php
                    $servicesAmount = $this->invoices->sum('servicesAmountSum');
                    $discountAmount = $this->invoices->sum('discount');
                    $servicesAfterDiscount = $this->invoices->sum('servicesAmountSum') - $this->invoices->sum('discount');
                    $internalPartsAmount = $this->invoices->sum('invoiceDetailsPartsAmountSum') + $this->invoices->sum('internalPartsAmountSum');
                    $externalPartsAmount = $this->invoices->sum('externalPartsAmountSum');
                    $deliveryAmount = $this->invoices->sum('delivery');
                    $cashAmount = $this->invoices->sum('totalCashAmountSum');
                    $knetAmount = $this->invoices->sum('totalKnetAmountSum');
                    $totalPaidAmount = $this->invoices->sum('totalPaidAmountSum');
                    $totalAmount = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
                    $remainingAmount = $totalAmount - $totalPaidAmount;
                @endphp
            <tr>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th></x-th>
                <x-th class="!text-right">{{ $servicesAmount > 0 ? number_format($servicesAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $discountAmount > 0 ? number_format($discountAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $servicesAfterDiscount > 0 ? number_format($servicesAfterDiscount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $internalPartsAmount > 0 ? number_format($internalPartsAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $externalPartsAmount > 0 ? number_format($externalPartsAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $deliveryAmount > 0 ? number_format($deliveryAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $totalAmount > 0 ? number_format($totalAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $cashAmount > 0 ? number_format($cashAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $knetAmount > 0 ? number_format($knetAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $totalPaidAmount > 0 ? number_format($totalPaidAmount,3) : '-' }}</x-th>
                <x-th class="!text-right">{{ $remainingAmount > 0 ? number_format($remainingAmount,3) : '-' }}</x-th>
                <x-th></x-th>
                <x-th></x-th>
            </tr>
        </x-tfoot>
    </x-table>
</div>