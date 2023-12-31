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
    @teleport('#addNew')
        <x-button wire:click="$dispatch('showServiceFormModal')">
            {{ __('messages.add_service') }}
        </x-button>
    @endteleport

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

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="text-start">
                        <x-input wire:model.live="filters.name" class="w-full py-0"
                            placeholder="{{ __('messages.name') }}" />
                    </th>
                    <th scope="col" class="px-6 py-1 text-start">
                        {{ __('messages.min_price') }}
                    </th>
                    <th scope="col" class="px-6 py-1 text-start">
                        {{ __('messages.max_price') }}
                    </th>
                    <th scope="col" class="px-6 py-1 text-start">
                        <x-select wire:model.live="filters.department_id" class="w-full py-0">
                            <option value="">{{ __('messages.department') }}</option>
                            @foreach ($this->departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th scope="col" class="px-6 py-1 text-start">
                        <x-select wire:model.live="filters.type" class="w-full py-0">
                            <option value="">{{ __('messages.type') }}</option>
                            <option value="service">{{ __('messages.services') }}</option>
                            <option value="part">{{ __('messages.parts') }}</option>
                        </x-select>
                    </th>
                    <th scope="col" class=" no-print"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->services as $service)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $service->name }}</div>
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            <div>{{ number_format($service->min_price, 3) }}</div>
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap ">
                            <div>{{ number_format($service->max_price, 3) }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $service->department->name }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ __('messages.' . $service->type . 's') }}</div>
                        </td>

                        <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                            <div class=" flex items-center gap-2">

                                <x-badgeWithCounter service="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showServiceFormModal',{service:{{ $service }}})">
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
