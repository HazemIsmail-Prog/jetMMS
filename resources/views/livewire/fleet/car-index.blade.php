<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.cars') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>
    @teleport('#addNew')
        <x-button wire:click="$dispatch('showCarFormModal')">
            {{ __('messages.add_car') }}
        </x-button>
    @endteleport

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->cars->total() }}
        </span>
    @endteleport

    @if ($this->cars->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->cars->links() }}</div>
        @endteleport
    @endif

    @livewire('fleet.car-form')

    @livewire('fleet.action-index')
    @livewire('fleet.action-form')

    @livewire('fleet.service-index')
    @livewire('fleet.service-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        <div>
            <x-label for="car_code">{{ __('messages.car_code') }}</x-label>
            <x-input id="car_code" type="text" wire:model.live="filters.car_code" class="w-full text-start py-0" />
        </div>
    </div>

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.car_code') }}</x-th>
                    <x-th>{{ __('messages.brand') }}</x-th>
                    <x-th>{{ __('messages.car_type') }}</x-th>
                    <x-th>{{ __('messages.receiver') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.management_no') }}/{{ __('messages.plate_no') }}</x-th>
                    <x-th>{{ __('messages.insurance_expiration_date') }}</x-th>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->cars as $car)
                    <x-tr>
                        <x-th>{{ $car->code }}</x-th>
                        <x-td>{{ $car->brand->name }}</x-td>
                        <x-td>{{ $car->type->name }}</x-td>
                        <x-td>{{ $car->latest_car_action->to->name ?? '-' }}</x-td>
                        <x-td>{{ $car->latest_car_action->to->department->name ?? '-' }}</x-td>
                        <x-td>{{ $car->management_no }}/{{ $car->plate_no }}</x-td>
                        <x-td>{{ $car->insurance_expiration_date->format('d-m-Y') }}</x-td>
                        <x-td>{{ $car->notes ?? '-' }}</x-td>
                        <x-td>
                            <div class="flex items-center justify-end gap-2">
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showCarFormModal',{car:{{ $car }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                                <x-badgeWithCounter counter="{{ $car->car_actions_count }}"
                                    title="{{ __('messages.car_actions') }}"
                                    wire:click="$dispatch('showCarActionsModal',{car:{{ $car }}})">
                                    <x-svgs.list class="h-4 w-4" />
                                </x-badgeWithCounter>
                                <x-badgeWithCounter counter="{{ $car->car_services_count }}"
                                    title="{{ __('messages.car_services') }}"
                                    wire:click="$dispatch('showCarServicesModal',{car:{{ $car }}})">
                                    <x-svgs.scissors class="h-4 w-4" />
                                </x-badgeWithCounter>
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</div>
