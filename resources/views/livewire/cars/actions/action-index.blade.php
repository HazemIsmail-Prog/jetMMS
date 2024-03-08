<div>
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal">
        <x-slot name="title">
            <div class=" flex items-center justify-between">
                <div>{{ __('messages.car_actions') }}</div>
                <x-button type="button"
                    wire:click="$dispatch('showActionFormModal',{car:{{ $car }}})">{{ __('messages.add_car_action') }}</x-button>
            </div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                <div class=" overflow-x-auto sm:rounded-lg">
                    <x-table>
                        <x-thead>
                            <tr>
                                <x-th>{{ __('messages.date') }} / {{ __('messages.time') }}</x-th>
                                <x-th>{{ __('messages.from') }}</x-th>
                                <x-th>{{ __('messages.to') }}</x-th>
                                <x-th>{{ __('messages.kilos') }}</x-th>
                                <x-th>{{ __('messages.fuel') }}</x-th>
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
                                    <x-td>{{ $action->kilos }}</x-td>
                                    <x-td>{{ $action->fuel }}</x-td>
                                    <x-td>{{ $action->notes }}</x-td>
                                    <x-td>
                                        <div class="flex items-center justify-end gap-2">
                                            <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                                wire:click="$dispatch('showActionFormModal',{carAction:{{ $action }},car:{{ $car }}})">
                                                <x-svgs.edit class="h-4 w-4" />
                                            </x-badgeWithCounter>
                                            <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                                wire:confirm="{{ __('messages.are_u_sure') }}"
                                                wire:click="delete({{ $action }})">
                                                <x-svgs.trash class="h-4 w-4" />
                                            </x-badgeWithCounter>
                                        </div>
                                    </x-td>
                                </x-tr>
                            @endforeach
                        </tbody>
                    </x-table>
                </div>
                <div class="mt-4">{{ $this->actions->links() }}</div>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
