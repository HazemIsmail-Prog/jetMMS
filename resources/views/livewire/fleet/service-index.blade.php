<div>
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal">
        <x-slot name="title">
            <div class=" flex items-center justify-between">
                <div>{{ __('messages.car_services') }}</div>
                <x-button type="button"
                    wire:click="$dispatch('showCarServiceFormModal',{car:{{ $car }}})">{{ __('messages.add_car_service') }}</x-button>
            </div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                <div class=" overflow-x-auto sm:rounded-lg">
                    <x-table>
                        <x-thead>
                            <tr>
                                <x-th>{{ __('messages.date') }}</x-th>
                                <x-th>{{ __('messages.notes') }}</x-th>
                                <x-th>{{ __('messages.cost') }}</x-th>
                                <x-th></x-th>
                            </tr>
                        </x-thead>
                        <tbody>
                            @foreach ($this->services as $service)
                                <x-tr>
                                    <x-td>{{ $service->date->format('d-m-Y') }}</x-td>
                                    <x-td>{{ $service->notes }}</x-td>
                                    <x-td>{{ $service->cost }}</x-td>
                                    <x-td>
                                        <div class="flex items-center justify-end gap-2">
                                            <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                                wire:click="$dispatch('showCarServiceFormModal',{carService:{{ $service }},car:{{ $car }}})">
                                                <x-svgs.edit class="h-4 w-4" />
                                            </x-badgeWithCounter>
                                            <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                                wire:confirm="{{ __('messages.are_u_sure') }}"
                                                wire:click="delete({{ $service }})">
                                                <x-svgs.trash class="h-4 w-4" />
                                            </x-badgeWithCounter>
                                        </div>
                                    </x-td>
                                </x-tr>
                            @endforeach
                        </tbody>
                    </x-table>
                </div>
                <div class="mt-4">{{ $this->services->links() }}</div>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
