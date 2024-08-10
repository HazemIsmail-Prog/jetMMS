<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ $contract_type == 'construction' ? __('messages.construction_contracts') :
                __('messages.subscription_contracts') }}
                <span id="counter"></span>
            </h2>
            <span id="excel"></span>
        </div>
    </x-slot>

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->contracts->total() }}
    </span>
    @endteleport



    {{-- @teleport('#excel')
    <div>
        @if ($this->contracts->total() <= $maxExportSize) <x-button wire:confirm="{{ __('messages.are_u_sure') }}"
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
    @endteleport --}}

    @if ($this->contracts->hasPages())
    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>
    @teleport('#pagination')
    <div class="">{{ $this->contracts->links() }}</div>
    @endteleport
    @endif

    @livewire('contracts.contract-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    {{-- Filters --}}

    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">

        <div>
            <x-label for="contract_number">{{ __('messages.contract_number') }}</x-label>
            <x-input id="contract_number" wire:model.live="filters.contract_number" type="number" dir="ltr"
                class="w-36 min-w-full text-center py-0" />
        </div>

        <div>
            <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
            <x-input class="w-36 min-w-full text-center py-0" id="customer_name"
                wire:model.live="filters.customer_name" />
        </div>
        <div>
            <x-label for="area">{{ __('messages.area') }}</x-label>
            <x-searchable-select class="!py-[5px]" id="area" :list="$this->areas" wire:model.live="filters.areas"
                multipule />
        </div>
        <div>
            <x-label for="block">{{ __('messages.block') }}</x-label>
            <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="block" wire:model.live="filters.block" />
        </div>
        <div>
            <x-label for="street">{{ __('messages.street') }}</x-label>
            <x-input dir="ltr" class="w-36 min-w-full text-center py-0" id="street" wire:model.live="filters.street" />
        </div>

        <div>
            <x-label for="creator">{{ __('messages.creator') }}</x-label>
            <x-searchable-select class="!py-[5px]" id="creator" :list="$this->creators"
                wire:model.live="filters.creators" multipule />
        </div>
        <div>
            <x-label for="start_contract_date">{{ __('messages.contract_date') }}</x-label>
            <x-input id="start_contract_date" class="w-36 min-w-full text-center py-0" type="date"
                wire:model.live="filters.start_contract_date" />
            <x-input id="end_contract_date" class="w-36 min-w-full text-center py-0" type="date"
                wire:model.live="filters.end_contract_date" />
        </div>

    </div>




    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.contract_number') }}</x-th>
                <x-th>{{ __('messages.customer') }}</x-th>
                <x-th>{{ __('messages.contract_date') }}</x-th>
                <x-th>{{ __('messages.contract_duration') }}</x-th>
                <x-th>{{ __('messages.contract_value') }}</x-th>
                <x-th>{{ __('messages.collected_amount') }}</x-th>
                <x-th>{{ __('messages.remaining_amount') }}</x-th>
                <x-th>{{ __('messages.notes') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->contracts as $contract)
            <x-tr class="{{ $contract->active ? 'text-green-500' : 'text-red-500' }}">
                <x-td>{{ $contract->contract_number }}</x-td>
                <x-td>
                    <div class=" flex-1">
                        <div>{{ $contract->customer->name }}</div>
                        <div>{{ $contract->address->full_address }}</div>
                        @if ($contract->customer->notes)
                        <div class=" text-red-400 font-normal">{{ $contract->customer->notes }}</div>
                        @endif
                    </div>
                </x-td>
                <x-td>{{ $contract->contract_date }}</x-td>
                <x-td>{{ $contract->contract_duration }}</x-td>
                <x-td>{{ $contract->contract_value }}</x-td>
                <x-td>{{ $contract->collected_amount }}</x-td>
                <x-td>{{ $contract->contract_value - $contract->collected_amount }}</x-td>
                <x-td>{{ $contract->notes }}</x-td>

                <x-td>
                    <div class=" flex items-center justify-end gap-2">

                        @can('update', $contract)
                        <x-badgeWithCounter title="{{ __('messages.edit') }}"
                            wire:click="$dispatch('showContractFormModal',{contract:{{ $contract }},customer:{{ $contract->customer }}})">
                            <x-svgs.edit class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        @can('delete', $contract)
                        <x-badgeWithCounter title="{{ __('messages.delete') }}" wire:confirm="{{__('
                            messages.are_u_sure')}}"
                            wire:click="delete({{$contract->id}})">
                            <x-svgs.trash class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        @can('viewAnyAttachment', $contract)
                        <x-badgeWithCounter :counter="$contract->attachments_count"
                            title="{{ __('messages.attachments') }}"
                            wire:click="$dispatch('showAttachmentModal',{model:'Contract',id:{{ $contract->id }}})">
                            <x-svgs.attachment class="w-4 h-4" />
                        </x-badgeWithCounter>
                        @endcan


                    </div>
                </x-td>
            </x-tr>
            @endforeach
        </tbody>
    </x-table>
</div>
