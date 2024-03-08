<div class=" border dark:border-gray-700 rounded-lg p-4">

    {{-- Modals --}}
    @livewire('employees.leaves.leave-form')


    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.leaves') }}
            <span id="counter"></span>
        </h2>
        <x-button class=" no-print"
            wire:click="$dispatch('showLeaveFormModal',{employee:{{ $employee }}})">{{ __('messages.add_leave') }}</x-button>
    </div>
    <x-section-border />

    @if ($employee->leaves->count() > 0)
        <div class=" overflow-x-auto sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.date') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.leave_days_count') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.type') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.status') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.notes') }}
                        </th>
                        <th scope="col" class=" no-print"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->leaves as $leave)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $leave->start_date->format('d-m-Y') }}</div>
                                <div>{{ $leave->end_date->format('d-m-Y') }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $leave->leave_days_count }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $leave->type->title() }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $leave->status->title() }}</div>
                            </td>
                            <td class="px-6 py-1 text-start ">
                                <div>{{ $leave->notes }}</div>
                            </td>

                            <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                                <div class=" flex items-center justify-end gap-2">

                                    <x-badgeWithCounter leave="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showLeaveFormModal',{leave:{{ $leave }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                    <x-badgeWithCounter :counter="$leave->attachments_count" title="{{ __('messages.attachments') }}"
                                        wire:click="$dispatch('showAttachmentModal',{model:'Leave',id:{{ $leave->id }}})">
                                        <x-svgs.attachment class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $leave->id }})">
                                        <x-svgs.trash class="w-4 h-4" />
                                    </x-badgeWithCounter>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-label class=" text-center">{{ __('messages.no_leaves_found') }}</x-label>
    @endif

</div>
