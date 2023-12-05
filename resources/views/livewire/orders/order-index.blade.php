<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.orders') }}
                <span id="counter"></span>
            </h2>
        </div>
    </x-slot>

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>

    @livewire('orders.invoice-modal')
    @livewire('orders.comment-modal')
    @livewire('orders.status-history-modal')

    {{-- @include('livewire.orders.includes.filters') --}}

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->orders->total() }}
        </span>
    @endteleport

    @teleport('#pagination')
        <div class="mt-4">{{ $this->orders->links() }}</div>
    @endteleport

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="">
                        <x-input wire:model.live="filters.order_number" type="number" dir="ltr"
                            class="w-36 min-w-full text-center py-0" placeholder="{{ __('messages.order_number') }}" />
                    </th>
                    <th scope="col" class=" text-center">
                        <x-input class="w-36 min-w-full text-center py-0" type="text" name="datefilter" value=""
                            data-start="filters.start_created_at" data-end="filters.end_created_at"
                            placeholder="{{ __('messages.created_at') }}" />
                    </th>
                    <th scope="col" class=" text-center">
                        <x-select wire:model.live="filters.creators" class="w-36 min-w-full text-center py-0">
                            <option value="">{{ __('messages.creator') }}</option>
                            @foreach ($creators as $creator)
                                <option value="{{ $creator->id }}">{{ $creator->name }}</option>
                            @endforeach
                        </x-select>

                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.estimated_start_date') }}
                    </th>
                    <th scope="col" class=" text-center">
                        <x-select class="w-36 min-w-full text-center py-0" id="statuses" wire:ignore
                            wire:model.live="filters.statuses">
                            <option value="">{{ __('messages.status') }}</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </x-select>

                    </th>
                    <th scope="col" class="">
                        <x-select class="w-36 min-w-full text-center py-0" id="departments" wire:ignore
                            wire:model.live="filters.departments">
                            <option value="">{{ __('messages.department') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}
                                </option>
                            @endforeach
                        </x-select>

                    </th>
                    <th scope="col" class=" text-center">
                        <x-select class="w-36 min-w-full text-center py-0" id="technicians" wire:ignore
                            wire:model.live="filters.technicians">
                            <option value="">{{ __('messages.technician') }}</option>
                            @foreach ($technicians->sortBy('name') as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                            @endforeach
                        </x-select>

                    </th>
                    <th scope="col" class=" text-center">
                        <x-input class="w-36 min-w-full text-center py-0" type="text" name="datefilter" value=""
                            data-start="filters.start_completed_at" data-end="filters.end_completed_at"
                            placeholder="{{ __('messages.completed_date') }}" />

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
                        <x-select class="w-36 min-w-full text-center py-0" id="areas" wire:model.live="filters.areas">
                            <option value="">{{ __('messages.address') }}</option>
                            @foreach ($areas->sortBy->name as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </x-select>
                        
                    </th>
                    <th scope="col" class=" text-center">
                        {{ __('messages.remaining_amount') }}
                    </th>
                    <th scope="col" class=" no-print"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->orders as $order)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th class=" text-center px-6 py-1 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $order->id }}
                        </th>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $order->created_at->format('d-m-Y H:i') }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $order->creator->name }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $order->estimated_start_date->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap" style="color: {{ $order->status->color }}">
                            {{ $order->status->name }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $order->department->name }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $order->technician->name ?? '-' }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap ">
                            <div>{{ $order->completed_at ? $order->completed_at->format('d-m-Y H:i') : '' }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $order->customer->name }}</div>
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap ">
                            <div>{{ $order->phone->number }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $order->address->full_address }}</div>
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap ">
                            {{ $order->remaining_amount > 0 ? $order->remaining_amount : '-' }}
                        </td>
                        <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                            <div class=" flex items-center gap-2">
                                <div wire:click="$dispatch('showInvoicesModal',{order_id:{{ $order->id }}})"
                                    class="flex items-center gap-1">
                                    <x-svgs.invoice class="h-4 w-4" />
                                    <span
                                        class="text-xs">{{ $order->invoices_count > 0 ? $order->invoices_count : '' }}</span>
                                </div>
                                <div wire:click="$dispatch('showStatusHistoryModal',{order_id:{{ $order->id }}})"
                                    class="flex items-center">
                                    <x-svgs.list class="h-4 w-4" />
                                </div>
                                <div wire:click="$dispatch('showCommentsModal',{order_id:{{ $order->id }}})"
                                    class="flex items-center gap-1">
                                    <x-svgs.comment class="h-4 w-4" />
                                    <span
                                        class="text-xs">{{ $order->all_comments > 0 ? $order->all_comments : '' }}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
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
</div>
