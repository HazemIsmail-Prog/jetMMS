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

    @livewire('orders.invoice-modal')

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->invoices->total() }}
        </span>
    @endteleport

    @teleport('#pagination')
        <div class="mt-4">{{ $this->invoices->links() }}</div>
    @endteleport

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="">
                        <x-input wire:model.live="filters.invoice_id" type="number" dir="ltr"
                            class="w-36 min-w-full text-center py-0"
                            placeholder="{{ __('messages.invoice_number') }}" />
                    </th>
                    <th scope="col" class="">
                        <x-input wire:model.live="filters.order_id" type="number" dir="ltr"
                            class="w-36 min-w-full text-center py-0" placeholder="{{ __('messages.order_number') }}" />
                    </th>
                    <th scope="col" class=" text-center">
                        <x-input class="w-36 min-w-full text-center py-0" type="text" name="datefilter"
                            value="" data-start="filters.start_created_at" data-end="filters.end_created_at"
                            placeholder="{{ __('messages.created_at') }}" />
                    </th>
                    <th scope="col" class="">
                        <x-select class="w-36 min-w-full text-center py-0" id="department_id" wire:ignore
                            wire:model.live="filters.department_id">
                            <option value="">{{ __('messages.department') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}
                                </option>
                            @endforeach
                        </x-select>

                    </th>
                    <th scope="col" class=" text-center">
                        <x-select class="w-36 min-w-full text-center py-0" id="technician_id" wire:ignore
                            wire:model.live="filters.technician_id">
                            <option value="">{{ __('messages.technician') }}</option>
                            @foreach ($technicians->sortBy('name') as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                            @endforeach
                        </x-select>

                    </th>
                    <th scope="col" class=" text-center">
                        <x-input class="w-36 min-w-full text-center py-0" id="customer_name"
                            wire:model.live="filters.customer_name" placeholder="{{ __('messages.customer_name') }}" />


                    </th>
                    <th scope="col" class=" text-center">
                        <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="customer_phone"
                            wire:model.live="filters.customer_phone"
                            placeholder="{{ __('messages.customer_phone') }}" />
                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.amount') }}
                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.services') }}
                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.parts') }}
                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.cash') }}
                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.knet') }}
                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.paid_amount') }}
                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.remaining_amount') }}
                    </th>
                    <th scope="col" class=" text-center">
                        <x-select required wire:model.live="filters.payment_status" class=" w-full py-0">
                            <option value="">{{ __('messages.payment_status') }}</option>
                            @foreach (App\Enums\PaymentStatusEnum::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->title() }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th scope="col" class=" no-print"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->invoices as $invoice)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th class=" text-center px-6 py-1 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $invoice->id }}
                        </th>
                        <th class=" text-center px-6 py-1 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <x-badgeWithCounter :counter="$this->invoices->where('order_id',$invoice->order_id)->count() > 1 ? $this->invoices->where('order_id',$invoice->order_id)->count() : null" title="{{ __('messages.invoices') }}"
                                wire:click="$dispatch('showInvoicesModal',{order_id:{{ $invoice->order->id }}})">
                                {{ $invoice->order_id }}
                            </x-badgeWithCounter>
                        </th>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            <div dir="ltr" class=" cursor-pointer"
                                wire:click="dateClicked('{{ $invoice->created_at->format('Y-m-d') }}')">
                                {{ $invoice->created_at->format('d-m-Y | H:i') }}</div>
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            <div class=" cursor-pointer"
                                wire:click="$set('filters.department_id',{{ $invoice->order->department_id }})">
                                {{ $invoice->order->department->name }}</div>
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            <div class=" cursor-pointer"
                                wire:click="$set('filters.technician_id',{{ $invoice->order->technician_id }})">
                                {{ $invoice->order->technician->name }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            {{ $invoice->order->customer->name }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap ">
                            {{ $invoice->order->phone->number }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            {{ $invoice->amount > 0 ? number_format($invoice->amount, 3) : '-' }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            {{ $invoice->services_amount > 0 ? number_format($invoice->services_amount, 3) : '-' }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            {{ $invoice->parts_amount > 0 ? number_format($invoice->parts_amount, 3) : '-' }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            {{ $invoice->cash_amount > 0 ? number_format($invoice->cash_amount, 3) : '-' }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            {{ $invoice->knet_amount > 0 ? number_format($invoice->knet_amount, 3) : '-' }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            {{ $invoice->total_paid_amount > 0 ? number_format($invoice->total_paid_amount, 3) : '-' }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            {{ $invoice->remaining_amount > 0 ? number_format($invoice->remaining_amount, 3) : '-' }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap ">
                            {{ $invoice->payment_status->title() }}
                        </td>
                        <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                            <div class=" flex items-center gap-2">
                                @if ($this->invoices->where('order_id',$invoice->order_id)->count() > 1)
                                    <x-badgeWithCounter title="{{ __('messages.delete_invoice') }}"
                                        wire:confirm="{{ __('messages.delete_invoice_confirmation') }}"
                                        wire:click="delete({{ $invoice->id }})">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</div>

@assets
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endassets

@script
    <script>
        $(function() {

            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
                @this.set($(this).data('start'), picker.startDate.format('YYYY-MM-DD'))
                @this.set($(this).data('end'), picker.endDate.format('YYYY-MM-DD'))
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                @this.set($(this).data('start'), null)
                @this.set($(this).data('end'), null)
            });

        });
    </script>
@endscript
