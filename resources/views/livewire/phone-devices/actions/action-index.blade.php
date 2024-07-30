<div>
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal">
        <x-slot name="title">
            <div class=" flex items-center justify-between">
                <div>{{ __('messages.phone_device_actions') }}</div>
                @can('create', App\Models\PhoneDeviceAction::class)
                    <x-button type="button"
                        wire:click="$dispatch('showActionFormModal',{phoneDevice:{{ $phoneDevice }}})">{{ __('messages.add_phone_device_action') }}</x-button>
                @endcan
            </div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __('messages.date') }} / {{ __('messages.time') }}</x-th>
                            <x-th>{{ __('messages.from') }}</x-th>
                            <x-th>{{ __('messages.to') }}</x-th>
                            <x-th>{{ __('messages.notes') }}</x-th>
                            <x-th>
                                <span class="sr-only">Edit</span>
                            </x-th>
                        </tr>
                    </x-thead>
                    <tbody>
                        @foreach ($this->actions as $action)
                            <x-tr>
                                <x-td>
                                    <div>{{ $action->date->format('d-m-Y') }}</div>
                                    <div class=" text-xs">{{ $action->time->format('H:i') }}</div>
                                </x-td>
                                <x-td>{{ @$action->from->name ?? '-' }}</x-td>
                                <x-td>{{ $action->to->name ?? '-' }}</x-td>
                                <x-td>{{ $action->notes }}</x-td>
                                <x-td>
                                    <div class="flex items-center justify-end gap-2">

                                        @can('update', $action)
                                            <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                                wire:click="$dispatch('showActionFormModal',{phoneDeviceAction:{{ $action }},phoneDevice:{{ $phoneDevice }}})">
                                                <x-svgs.edit class="h-4 w-4" />
                                            </x-badgeWithCounter>
                                        @endcan

                                        @can('delete', $action)
                                            <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                                wire:confirm="{{ __('messages.are_u_sure') }}"
                                                wire:click="delete({{ $action }})">
                                                <x-svgs.trash class="h-4 w-4" />
                                            </x-badgeWithCounter>
                                        @endcan

                                    </div>
                                </x-td>
                            </x-tr>
                        @endforeach
                    </tbody>
                </x-table>
                <div class="mt-4">{{ $this->actions->links() }}</div>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
