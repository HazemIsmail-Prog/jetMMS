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

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.car_code') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.brand') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.car_type') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.department') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.driver') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.management_no') }}/{{ __('messages.plate_no') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.insurance_expiration_date') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.car_actions') }}
                    </th>
                    <th scope="col" class="px-6 py-3 no-print">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
                <tr>
                    <th>
                        <x-input wire:model.live="filters.code" class=" text-start py-0"/>
                    </th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->cars as $car)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $car->code }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $car->brand->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $car->type->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $car->driver->department->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span title="{{ $car->driver ? __('messages.click_to_unassign') : '' }}"
                                wire:click="showActionFormModal({{ $car->id }})"
                                class="font-medium  hover:underline cursor-pointer {{ $car->driver_id ? 'text-red-600 dark:text-red-500' : 'text-green-600 dark:text-green-500' }}">
                                {{ $car->driver_id ? $car->driver->name : __('messages.assign') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $car->management_no }}/{{ $car->plate_no }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $car->insurance_expiration_date->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <a wire:navigate href="{{ route('car.action.index', $car) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $car->actions_count }}</a>
                            
                        </td>
                        <td class="px-6 py-4 text-right no-print">
                            <a wire:navigate href="{{ route('car.form', $car) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('messages.edit') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $this->cars->links() }}</div>




</div>
