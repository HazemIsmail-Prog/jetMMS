<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.quotations') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>

    @can('create', App\Models\Quotation::class)
    @teleport('#addNew')
    <x-button wire:click="$dispatch('showQuotationFormModal')">
        {{ __('messages.add_quotation') }}
    </x-button>
    @endteleport
    @endcan

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->quotations->total() }}
    </span>
    @endteleport



    {{-- @teleport('#excel')
    <div>
        @if ($this->quotations->total() <= $maxExportSize) <x-button wire:confirm="{{ __('messages.are_u_sure') }}"
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

    @if ($this->quotations->hasPages())
    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>
    @teleport('#pagination')
    <div class="">{{ $this->quotations->links() }}</div>
    @endteleport
    @endif

    @livewire('quotations.quotation-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    {{-- Filters --}}

    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">

        <div>
            <x-label for="quotation_number">{{ __('messages.quotation_number') }}</x-label>
            <x-input id="quotation_number" wire:model.live="filters.quotation_number" type="number" dir="ltr"
                class="w-36 min-w-full text-center py-0" />
        </div>

        <div>
            <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
            <x-input class="w-36 min-w-full text-center py-0" id="customer_name"
                wire:model.live="filters.customer_name" />
        </div>

        <div>
            <x-label for="creator">{{ __('messages.creator') }}</x-label>
            <x-searchable-select class="!py-[5px]" id="creator" :list="$this->creators"
                wire:model.live="filters.creators" multipule />
        </div>

    </div>




    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.quotation_number') }}</x-th>
                <x-th>{{ __('messages.customer_name') }}</x-th>
                <x-th>{{ __('messages.amount') }}</x-th>
                <x-th>{{ __('messages.description') }}</x-th>
                <x-th>{{ __('messages.created_at') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->quotations as $quotation)
            <x-tr>
                <x-td>{{ $quotation->quotation_number }}</x-td>
                <x-td>{{ $quotation->customer_name }}</x-td>
                <x-td>{{ $quotation->amount }}</x-td>
                <x-td>{{ $quotation->description }}</x-td>
                <x-td>{{ $quotation->created_at->format('d/m/Y') }}</x-td>

                <x-td>
                    <div class=" flex items-center justify-end gap-2">

                        @can('update', $quotation)
                        <x-badgeWithCounter title="{{ __('messages.edit') }}"
                            wire:click="$dispatch('showQuotationFormModal',{quotation:{{ $quotation }}})">
                            <x-svgs.edit class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        @can('delete', $quotation)
                        <x-badgeWithCounter title="{{ __('messages.delete') }}" wire:confirm="{{__('
                            messages.are_u_sure')}}" wire:click="delete({{$quotation->id}})">
                            <x-svgs.trash class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        @can('viewAnyAttachment', $quotation)
                        <x-badgeWithCounter :counter="$quotation->attachments_count"
                            title="{{ __('messages.attachments') }}"
                            wire:click="$dispatch('showAttachmentModal',{model:'Quotation',id:{{ $quotation->id }}})">
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
