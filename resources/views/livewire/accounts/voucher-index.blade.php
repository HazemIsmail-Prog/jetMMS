<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ $title }}
                <span id="counter"></span>
            </h2>
            @can('create', App\Models\Voucher::class)
                <span id="addNew"></span>
            @endcan

        </div>
    </x-slot>
    @teleport('#addNew')
        <x-button wire:click="$dispatch('showVoucherFormModal')">
            {{ $add_button_label }}
        </x-button>
    @endteleport

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->vouchers->total() }}
        </span>
    @endteleport

    @if ($this->vouchers->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->vouchers->links() }}</div>
        @endteleport
    @endif

    @livewire('accounts.voucher-form')

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class=" text-center">
                        {{ __('messages.voucher_number') }}
                    </th>
                    <th scope="col" class=" text-center">
                        <x-input class="w-36 min-w-full text-center py-0" type="text" name="datefilter"
                            value="" data-start="filters.start_created_at" data-end="filters.end_created_at"
                            placeholder="{{ __('messages.date') }}" />
                    </th>
                    <th scope="col" class="px-6 py-1 text-start">
                        {{ __('messages.notes') }}
                    </th>
                    <th scope="col" class="px-6 py-1 text-right">
                        {{ __('messages.amount') }}
                    </th>
                    <th scope="col" class=" no-print"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->vouchers as $voucher)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $voucher->id }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $voucher->date->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $voucher->notes }}</div>
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            <div>{{ number_format($voucher->amount, 3) }}</div>
                        </td>

                        <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                            <div class=" flex items-center justify-end gap-2">

                                @can('update', $voucher)
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showVoucherFormModal',{voucher:{{ $voucher }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan
                                @can('delete', $voucher)
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $voucher }})">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

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
