<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.statuses') }}
                <span id="counter"></span>
            </h2>
        </div>
    </x-slot>

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->statuses->total() }}
        </span>
    @endteleport

    @if ($this->statuses->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->statuses->links() }}</div>
        @endteleport
    @endif

    @livewire('statuses.status-form')

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.name') }}</x-th>
                    <x-th>{{ __('messages.color') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->statuses as $status)
                    <x-tr>
                        <x-td>{{ $status->name }}</x-td>
                        <x-td>
                            <div class="w-40 rounded-lg text-center text-white"
                                style="background-color: {{ $status->color }};">
                                {{ $status->color }}
                            </div>
                        </x-td>
                        <x-td>
                            <div class=" flex items-center justify-end gap-2">
                                @can('update', $status)
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showStatusFormModal',{status:{{ $status }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>


</div>
