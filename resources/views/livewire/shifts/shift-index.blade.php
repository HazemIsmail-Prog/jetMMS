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

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>

    @livewire('shifts.shift-form')

    @teleport('#addNew')
        <x-button wire:click="$dispatch('showShiftFormModal')">
            {{ __('messages.add_shift') }}
        </x-button>
    @endteleport

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->shifts->total() }}
        </span>
    @endteleport

    @teleport('#pagination')
        <div class="mt-4">{{ $this->shifts->links() }}</div>
    @endteleport

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-1 text-start">
                        {{ __('messages.name') }}
                    </th>
                    <th scope="col" class="px-6 py-1 text-start">
                        {{ __('messages.start_time') }}
                    </th>
                    <th scope="col" class="px-6 py-1 text-start">
                        {{ __('messages.end_time') }}
                    </th>
                    <th scope="col" class=" no-print"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->shifts as $shift)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $shift->name }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $shift->start_time }}</div>
                        </td>
                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $shift->end_time }}</div>
                        </td>

                        <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                            <div class=" flex items-center gap-2">

                                <x-badgeWithCounter shift="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showShiftFormModal',{shift:{{ $shift }}})">
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
