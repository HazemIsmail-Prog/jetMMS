<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.marketings') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>

        </div>
    </x-slot>

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>

    @livewire('marketing.marketing-form')

    @teleport('#addNew')
        <x-button wire:click="$dispatch('showMarketingFormModal')">
            {{ __('messages.add_marketing') }}
        </x-button>
    @endteleport

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->marketings->total() }}
        </span>
    @endteleport

    @teleport('#pagination')
        <div class="mt-4">{{ $this->marketings->links() }}</div>
    @endteleport

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class=" text-center">
                        <x-input class="w-36 min-w-full text-center py-0" type="text" name="datefilter"
                            value="" data-start="filters.start_created_at" data-end="filters.end_created_at"
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
                        <x-input class="w-36 min-w-full text-center py-0" id="customer_name"
                        wire:model.live="filters.name" placeholder="{{ __('messages.customer_name') }}" />
                        
                        
                    </th>
                    <th scope="col" class=" text-center">
                        <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="phone"
                        wire:model.live="filters.phone" placeholder="{{ __('messages.phone') }}" />
                    </th>
                    <th scope="col" class=" text-center">
                        <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="address"
                        wire:model.live="filters.address" placeholder="{{ __('messages.address') }}" />
                        
                    </th>
                    
                    
                    <th scope="col" class=" text-center">
                        {{ __('messages.notes') }}
                    </th>
                    <th scope="col" class=" text-center">
                        <x-select wire:model.live="filters.type" class="w-36 min-w-full text-center py-0">
                            <option value="">{{ __('messages.type') }}</option>
                            <option value="marketing">{{ __('messages.marketing') }}</option>
                            <option value="information">{{ __('messages.information') }}</option>
                            <option value="service_not_available">{{ __('messages.service_not_available') }}</option>
                        </x-select>
                    </th>
                    <th scope="col" class=" no-print"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->marketings as $marketing)
                <tr
                class="bg-white bmarketing-b dark:bg-gray-800 dark:bmarketing-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-1 text-center whitespace-nowrap">
                    {{ $marketing->created_at->format('d-m-Y H:i') }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $marketing->user->name }}
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $marketing->name }}</div>
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap ">
                            <div>{{ $marketing->phone }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $marketing->address }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $marketing->notes }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ __('messages.'.$marketing->type) }}</div>
                        </td>

                        <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                            <div class=" flex items-center gap-2">

                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showMarketingFormModal',{marketing:{{ $marketing }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>

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
