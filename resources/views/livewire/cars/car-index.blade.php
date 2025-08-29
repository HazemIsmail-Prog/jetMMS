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

    @can('create', App\Models\Car::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showCarFormModal')">
                {{ __('messages.add_car') }}
            </x-button>
        @endteleport
    @endcan

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

    @livewire('cars.car-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    @livewire('cars.actions.action-index')
    @livewire('cars.actions.action-form')

    @livewire('cars.services.service-index')
    @livewire('cars.services.service-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        <div>
            <x-label for="car_code">{{ __('messages.car_code') }}</x-label>
            <x-input id="car_code" type="text" wire:model.live="filters.car_code" class="w-full text-start py-0" />
        </div>
        <div>
            <x-label for="year">{{ __('messages.year') }}</x-label>
            <x-input id="year" type="text" wire:model.live="filters.year" class="w-full text-start py-0" />
        </div>
        <div>
            <x-label for="company">{{ __('messages.company') }}</x-label>
            <x-searchable-select class=" !py-[5px]" id="company" :list="$this->companies" wire:model.live="filters.company_id" multipule />
        </div>
    </div>

    <x-table>
        <x-thead>
            <tr>
                <x-th>#</x-th>
                <x-th>{{ __('messages.car_code') }}</x-th>
                <x-th>{{ __('messages.brand') }}</x-th>
                <x-th>{{ __('messages.car_type') }}</x-th>
                <x-th>{{ __('messages.year') }}</x-th>
                <x-th>{{ __('messages.receiver') }}</x-th>
                <x-th>{{ __('messages.department') }}</x-th>
                <x-th>{{ __('messages.company') }}</x-th>
                <x-th>{{ __('messages.management_no') }}/{{ __('messages.plate_no') }}</x-th>
                <x-th>{{ __('messages.insurance_expiration_date') }}</x-th>
                <x-th>{{ __('messages.notes') }}</x-th>
                <x-th>{{ __('messages.total_car_services_cost') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->cars as $car)
                <x-tr>
                    <x-td>{{ $loop->iteration }}</x-td>
                    <x-td>{{ $car->code }}</x-td>
                    <x-td>{{ $car->brand->name }}</x-td>
                    <x-td>{{ $car->type->name }}</x-td>
                    <x-td>{{ $car->year }}</x-td>
                    <x-td>{{ $car->latest_car_action->to->name ?? '-' }}</x-td>
                    <x-td>{{ $car->latest_car_action->to->department->name ?? '-' }}</x-td>
                    <x-td>{{ $car->company->name ?? '-' }}</x-td>
                    <x-td>{{ $car->management_no }}/{{ $car->plate_no }}</x-td>
                    <x-td>{!! $car->formated_insurance_expiration_date !!}</x-td>
                    <x-td>{{ $car->notes ?? '-' }}</x-td>
                    <x-td>{{ $car->car_services_sum_cost > 0 ? number_format($car->car_services_sum_cost, 3) : '-' }}</x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $car)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showCarFormModal',{car:{{ $car }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $car)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $car }})">
                                    <x-svgs.trash class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAny', App\Models\CarAction::class)
                                <x-badgeWithCounter counter="{{ $car->car_actions_count }}"
                                    title="{{ __('messages.car_actions') }}"
                                    wire:click="$dispatch('showCarActionsModal',{car:{{ $car }}})">
                                    <x-svgs.list class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAny', App\Models\CarService::class)
                                <x-badgeWithCounter counter="{{ $car->car_services_count }}"
                                    title="{{ __('messages.car_services') }}"
                                    wire:click="$dispatch('showCarServicesModal',{car:{{ $car }}})">
                                    <x-svgs.scissors class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAnyAttachment', $car)
                                <x-badgeWithCounter :counter="$car->attachments_count" title="{{ __('messages.attachments') }}"
                                    wire:click="$dispatch('showAttachmentModal',{model:'Car',id:{{ $car->id }}})">
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
