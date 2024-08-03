<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.phone_devices') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>

    @can('create', App\Models\PhoneDevice::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showPhoneDeviceFormModal')">
                {{ __('messages.add_phoneDevice') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->phoneDevices->total() }}
        </span>
    @endteleport

    @if ($this->phoneDevices->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->phoneDevices->links() }}</div>
        @endteleport
    @endif

    @livewire('phone_devices.device-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    @livewire('phone_devices.actions.action-index')
    @livewire('phone_devices.actions.action-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        <div>
            <x-label for="serial_no">{{ __('messages.serial_no') }}</x-label>
            <x-input id="serial_no" type="text" wire:model.live="filters.serial_no" class="w-full text-start py-0" />
        </div>
    </div>

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.sn') }}</x-th>
                <x-th>{{ __('messages.serial_no') }}</x-th>
                <x-th>{{ __('messages.brand') }}</x-th>
                <x-th>{{ __('messages.model') }}</x-th>
                <x-th>{{ __('messages.sim_no') }}</x-th>
                <x-th>{{ __('messages.receiver') }}</x-th>
                <x-th>{{ __('messages.department') }}</x-th>
                <x-th>{{ __('messages.notes') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->phoneDevices as $phoneDevice)
                <x-tr>
                    <x-th>{{ $loop->iteration }}</x-th>
                    <x-th>{{ $phoneDevice->serial_no }}</x-th>
                    <x-th>{{ $phoneDevice->brand }}</x-th>
                    <x-th>{{ $phoneDevice->model }}</x-th>
                    <x-th>{{ $phoneDevice->sim_no }}</x-th>
                    <x-td>{{ $phoneDevice->latest_device_action->to->name ?? '-' }}</x-td>
                    <x-td>{{ $phoneDevice->latest_device_action->to->department->name ?? '-' }}</x-td>
                    <x-td>{{ $phoneDevice->notes ?? '-' }}</x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $phoneDevice)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showPhoneDeviceFormModal',{phoneDevice:{{ $phoneDevice }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $phoneDevice)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $phoneDevice }})">
                                    <x-svgs.trash class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAny', App\Models\PhoneDevice::class)
                                <x-badgeWithCounter counter="{{ $phoneDevice->device_actions_count }}"
                                    title="{{ __('messages.phone_device_actions') }}"
                                    wire:click="$dispatch('showPhoneDeviceActionsModal',{phoneDevice:{{ $phoneDevice }}})">
                                    <x-svgs.list class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAnyAttachment', $phoneDevice)
                                <x-badgeWithCounter :counter="$phoneDevice->attachments_count" title="{{ __('messages.attachments') }}"
                                    wire:click="$dispatch('showAttachmentModal',{model:'PhoneDevice',id:{{ $phoneDevice->id }}})">
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
