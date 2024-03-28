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
    @can('create', App\Models\Marketing::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showMarketingFormModal')">
                {{ __('messages.add_marketing') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->marketings->total() }}
        </span>
    @endteleport

    @if ($this->marketings->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->marketings->links() }}</div>
        @endteleport
    @endif

    @livewire('marketing.marketing-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        <div>
            <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
            <x-input class="w-full text-center py-0" id="customer_name" wire:model.live="filters.name" />
        </div>
        <div>
            <x-label for="phone">{{ __('messages.phone') }}</x-label>
            <x-input dir="ltr" class="w-full text-center py-0" id="phone" wire:model.live="filters.phone" />
        </div>
        <div>
            <x-label for="creator">{{ __('messages.creator') }}</x-label>
            <x-searchable-select class=" !py-1" id="creator" :list="$this->creators" model="filters.creators" live />
        </div>
        <div>
            <x-label for="type">{{ __('messages.type') }}</x-label>
            <x-select id="type" wire:model.live="filters.type" class="w-full text-center py-0">
                <option value="">---</option>
                <option value="marketing">{{ __('messages.marketing') }}</option>
                <option value="information">{{ __('messages.information') }}</option>
                <option value="service_not_available">{{ __('messages.service_not_available') }}</option>
            </x-select>
        </div>
        <div>
            <x-label for="start_created_at">{{ __('messages.created_at') }}</x-label>
            <x-input id="start_created_at" class="w-full text-center py-0" type="date"
                wire:model.live="filters.start_created_at" />
            <x-input id="end_created_at" class="w-full text-center py-0" type="date"
                wire:model.live="filters.end_created_at" />
        </div>
    </div>

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.created_at') }}</x-th>
                    <x-th>{{ __('messages.creator') }}</x-th>
                    <x-th>{{ __('messages.customer_name') }}</x-th>
                    <x-th>{{ __('messages.phone') }}</x-th>
                    <x-th>{{ __('messages.address') }}</x-th>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-th>{{ __('messages.type') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->marketings as $marketing)
                    <x-tr>
                        <x-td>{!! $marketing->formated_created_at !!}</x-td>
                        <x-td>{{ $marketing->user->name }}</x-td>
                        <x-td>{{ $marketing->name }}</x-td>
                        <x-td>{{ $marketing->phone }}</x-td>
                        <x-td>{{ $marketing->address }}</x-td>
                        <x-td>{{ $marketing->notes }}</x-td>
                        <x-td>{{ __('messages.' . $marketing->type) }}</x-td>
                        <x-td>
                            <div class=" flex items-center justify-end gap-2">
                                @can('update', $marketing)
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showMarketingFormModal',{marketing:{{ $marketing }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan
                                @can('delete', $marketing)
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $marketing }})">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</div>
