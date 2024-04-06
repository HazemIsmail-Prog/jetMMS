<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.shifts') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>

        </div>
    </x-slot>

    @can('create', App\Models\Shift::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showShiftFormModal')">
                {{ __('messages.add_shift') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->shifts->total() }}
        </span>
    @endteleport

    @if ($this->shifts->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->shifts->links() }}</div>
        @endteleport
    @endif

    @livewire('shifts.shift-form')

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th>{{ __('messages.start_time') }}</x-th>
                <x-th>{{ __('messages.end_time') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->shifts as $shift)
                <x-tr>
                    <x-td>{{ $shift->name }}</x-td>
                    <x-td>{{ $shift->start_time }}</x-td>
                    <x-td>{{ $shift->end_time }}</x-td>

                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $shift)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showShiftFormModal',{shift:{{ $shift }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $shift)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $shift }})">
                                    <x-svgs.trash class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan
                        </div>
                    </x-td>
                </x-tr>
            @endforeach
        </tbody>
    </x-table>
</div>
