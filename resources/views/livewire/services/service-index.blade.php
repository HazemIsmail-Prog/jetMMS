<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.services') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>

        </div>
    </x-slot>

    @can('create', App\Models\Service::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showServiceFormModal')">
                {{ __('messages.add_service') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->services->total() }}
        </span>
    @endteleport

    @if ($this->services->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->services->links() }}</div>
        @endteleport
    @endif

    @livewire('services.service-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">
        <div>
            <x-label for="name">{{ __('messages.name') }}</x-label>
            <x-input id="name" wire:model.live="filters.name" class="w-full py-0" />
        </div>
        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-select id="department" wire:model.live="filters.department_id" class="w-full py-0">
                <option value="">---</option>
                @foreach ($this->departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-label for="type">{{ __('messages.type') }}</x-label>
            <x-select id="type" wire:model.live="filters.type" class="w-full py-0">
                <option value="">---</option>
                <option value="service">{{ __('messages.services') }}</option>
                <option value="part">{{ __('messages.parts') }}</option>
            </x-select>
        </div>
    </div>

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th>{{ __('messages.min_price') }}</x-th>
                <x-th>{{ __('messages.max_price') }}</x-th>
                <x-th>{{ __('messages.department') }}</x-th>
                <x-th>{{ __('messages.type') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->services as $service)
                <x-tr>
                    <x-td>{{ $service->name }}</x-td>
                    <x-td>{{ number_format($service->min_price, 3) }}</x-td>
                    <x-td>{{ number_format($service->max_price, 3) }}</x-td>
                    <x-td>{{ $service->department->name }}</x-td>
                    <x-td>{{ __('messages.' . $service->type . 's') }}</x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $service)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showServiceFormModal',{service:{{ $service }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $service)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $service }})">
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
