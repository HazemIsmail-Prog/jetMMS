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
        <x-table>
            <x-thead>
                <tr>
                    <x-th>
                        <x-input wire:model.live="filters.name" class="w-full py-0"
                            placeholder="{{ __('messages.name') }}" />
                    </x-th>
                    <x-th>{{ __('messages.min_price') }}</x-th>
                    <x-th>{{ __('messages.max_price') }}</x-th>
                    <x-th>
                        <x-select wire:model.live="filters.department_id" class="w-full py-0">
                            <option value="">{{ __('messages.department') }}</option>
                            @foreach ($this->departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </x-select>
                    </x-th>
                    <x-th>
                        <x-select wire:model.live="filters.type" class="w-full py-0">
                            <option value="">{{ __('messages.type') }}</option>
                            <option value="service">{{ __('messages.services') }}</option>
                            <option value="part">{{ __('messages.parts') }}</option>
                        </x-select>
                    </x-th>
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
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showServiceFormModal',{service:{{ $service }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</div>
