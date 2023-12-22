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

    @if ($this->statuses->hasMorePages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->statuses->links() }}</div>
        @endteleport
    @endif

    @livewire('statuses.status-form')

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-1 text-start">
                        {{ __('messages.name') }}
                    </th>
                    <th scope="col" class="px-6 py-1 text-center">
                        {{ __('messages.color') }}
                    </th>
                    <th scope="col" class=" no-print"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->statuses as $status)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                        <td class="px-6 py-1 text-start whitespace-nowrap ">
                            <div>{{ $status->name }}</div>
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap ">
                            <div class=" m-auto w-40 rounded-lg text-center text-white"
                                style="background-color: {{ $status->color }};">{{ $status->color }}</div>
                        </td>

                        <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                            <div class=" flex items-center gap-2">

                                <x-badgeWithCounter status="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showStatusFormModal',{status:{{ $status }}})">
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
