<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.invoices') }}
                <span id="counter"></span>
            </h2>
        </div>
    </x-slot>

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>

    @livewire('orders.invoices.invoice-index')
    @livewire('orders.invoices.invoice-form')
    @livewire('orders.invoices.payments.payment-form')

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->invoices->total() }}
        </span>
    @endteleport

    @teleport('#pagination')
        <div class="mt-4">{{ $this->invoices->links() }}</div>
    @endteleport

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3"> <x-input
            wire:model.live="filters.invoice_id" type="number" dir="ltr" class="w-36 min-w-full text-center py-0"
            placeholder="{{ __('messages.invoice_number') }}" />
        <x-input wire:model.live="filters.order_id" type="number" dir="ltr"
            class="w-36 min-w-full text-center py-0" placeholder="{{ __('messages.order_number') }}" />
        <x-input class="w-36 min-w-full text-center py-0" type="text" name="datefilter" value=""
            data-start="filters.start_created_at" data-end="filters.end_created_at"
            placeholder="{{ __('messages.created_at') }}" />
        <x-select class="w-36 min-w-full text-center py-0" id="department_id" wire:ignore
            wire:model.live="filters.department_id">
            <option value="">{{ __('messages.department') }}</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}
                </option>
            @endforeach
        </x-select>
        <x-select class="w-36 min-w-full text-center py-0" id="technician_id" wire:ignore
            wire:model.live="filters.technician_id">
            <option value="">{{ __('messages.technician') }}</option>
            @foreach ($technicians->sortBy('name') as $technician)
                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
            @endforeach
        </x-select>
        <x-input class="w-36 min-w-full text-center py-0" id="customer_name" wire:model.live="filters.customer_name"
            placeholder="{{ __('messages.customer_name') }}" />
        <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="customer_phone"
            wire:model.live="filters.customer_phone" placeholder="{{ __('messages.customer_phone') }}" />
        <x-select required wire:model.live="filters.payment_status" class=" w-full py-0">
            <option value="">{{ __('messages.payment_status') }}</option>
            @foreach (App\Enums\PaymentStatusEnum::cases() as $status)
                <option value="{{ $status->value }}">{{ $status->title() }}</option>
            @endforeach
        </x-select>
    </div>

    <div class=" overflow-x-auto sm:rounded-lg">
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
                        <x-td>{{ str_pad($invoice->id, 8, '0', STR_PAD_LEFT) }}</x-td>
                        <x-td>
                            <x-badgeWithCounter :counter="$this->invoices->where('order_id', $invoice->order_id)->count() > 1
                                ? $this->invoices->where('order_id', $invoice->order_id)->count()
                                : null" title="{{ __('messages.invoices') }}"
                                wire:click="$dispatch('showInvoicesModal',{order:{{ $invoice->order }}})">
                                {{ str_pad($invoice->order_id, 8, '0', STR_PAD_LEFT) }}
                            </x-badgeWithCounter>
                        </x-td>
                        <x-td class=" whitespace-nowrap">
                            <span dir="ltr" class=" cursor-pointer"
                                wire:click="dateClicked('{{ $invoice->created_at->format('Y-m-d') }}')">
                                {{ $invoice->formated_created_at }}
                            </span>
                        </x-td>
                        <x-td class=" whitespace-nowrap">
                            <span class=" cursor-pointer"
                                wire:click="$set('filters.department_id',{{ $invoice->order->department_id }})">
                                {{ $invoice->order->department->name }}
                            </span>
                        </x-td>
                        <x-td class=" whitespace-nowrap">
                            <span class=" cursor-pointer"
                                wire:click="$set('filters.technician_id',{{ $invoice->order->technician_id }})">
                                {{ $invoice->order->technician->name }}
                            </span>
                        </x-td>
                        <x-td class=" whitespace-nowrap">{{ $invoice->order->customer->name }}</x-td>
                        <x-td>{{ $invoice->order->phone->number }}</x-td>
                        <x-td>{{ $invoice->formated_services_amount }}</x-td>
                        <x-td>{{ $invoice->formated_discount_amount }}</x-td>
                        <x-td>{{ $invoice->formated_service_amount_after_discount }}</x-td>
                        <x-td>{{ $invoice->formated_internal_parts_amount }}</x-td>
                        <x-td>{{ $invoice->formated_external_parts_amount }}</x-td>
                        <x-td>{{ $invoice->formated_delivery_amount }}</x-td>
                        <x-td>{{ $invoice->formated_amount }}</x-td>
                        <x-td>{{ $invoice->formated_cash_amount }}</x-td>
                        <x-td>{{ $invoice->formated_knet_amount }}</x-td>
                        <x-td>{{ $invoice->formated_total_paid_amount }}</x-td>
                        <x-td>{{ $invoice->formated_remaining_amount }}</x-td>
                        <x-td>{{ $invoice->payment_status->title() }}</x-td>
                        <x-td>
                            <div class=" flex items-center justify-end gap-2">
                                @if ($this->invoices->where('order_id', $invoice->order_id)->count() > 1)
                                    <x-badgeWithCounter title="{{ __('messages.delete_invoice') }}"
                                        wire:confirm="{{ __('messages.delete_invoice_confirmation') }}"
                                        wire:click="delete({{ $invoice->id }})">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endif
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</div>
