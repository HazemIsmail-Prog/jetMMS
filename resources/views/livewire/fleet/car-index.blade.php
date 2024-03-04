<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.cars') }}
                <span
                    class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $this->cars->count() }}</span>
            </h2>
            <x-anchor class="no-print" href="{{ route('car.form') }}">{{ __('messages.add_car') }}</x-anchor>
        </div>
    </x-slot>

    @livewire('fleet.action-modal')

    <x-input wire:model.live="filters.code" class=" text-start py-0"/>

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.car_code') }}</x-th>
                    <x-th>{{ __('messages.brand') }}</x-th>
                    <x-th>{{ __('messages.car_type') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.driver') }}</x-th>
                    <x-th>{{ __('messages.management_no') }}/{{ __('messages.plate_no') }}</x-th>
                    <x-th>{{ __('messages.insurance_expiration_date') }}</x-th>
                    <x-th>{{ __('messages.car_actions') }}</x-th>
                    <x-th>Edit</x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->cars as $car)
                    <x-tr>
                        <x-th>{{ $car->code }}</x-th>
                        <x-td>{{ $car->brand->name }}</x-td>
                        <x-td>{{ $car->type->name }}</x-td>
                        <x-td>{{ $car->driver->department->name ?? '-' }}</x-td>
                        <x-td>
                            <span title="{{ $car->driver ? __('messages.click_to_unassign') : '' }}"
                                wire:click="showActionFormModal({{ $car->id }})"
                                class="font-medium  hover:underline cursor-pointer {{ $car->driver_id ? 'text-red-600 dark:text-red-500' : 'text-green-600 dark:text-green-500' }}">
                                {{ $car->driver_id ? $car->driver->name : __('messages.assign') }}
                            </span>
                        </x-td>
                        <x-td>{{ $car->management_no }}/{{ $car->plate_no }}</x-td>
                        <x-td>{{ $car->insurance_expiration_date->format('d-m-Y') }}</x-td>
                        <x-td>
                            <a href="{{ route('car.action.index', $car) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $car->actions_count }}</a>
                        </x-td>
                        <x-td>
                            <a href="{{ route('car.form', $car) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('messages.edit') }}</a>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
    <div class="mt-4">{{ $this->cars->links() }}</div>
</div>
