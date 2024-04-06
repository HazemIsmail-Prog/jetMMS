<div class=" border dark:border-gray-700 rounded-lg p-4">

    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.leaves') }}
            <span id="counter"></span>
        </h2>
        @can('create', App\Models\Leave::class)
            <x-button class=" no-print"
                wire:click="$dispatch('showLeaveFormModal',{employee:{{ $employee }}})">{{ __('messages.add_leave') }}</x-button>
        @endcan
    </div>
    <x-section-border />

    @if ($employee->leaves->count() > 0)
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.date') }}</x-th>
                    <x-th>{{ __('messages.leave_days_count') }}</x-th>
                    <x-th>{{ __('messages.type') }}</x-th>
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->leaves as $leave)
                    <x-tr>
                        <x-td>
                            <div>{{ $leave->start_date->format('d-m-Y') }}</div>
                            <div>{{ $leave->end_date->format('d-m-Y') }}</div>
                        </x-td>
                        <x-td>{{ $leave->leave_days_count }}</x-td>
                        <x-td>{{ $leave->type->title() }}</x-td>
                        <x-td>{{ $leave->status->title() }}</x-td>
                        <x-td class="px-6 py-1 text-start ">{{ $leave->notes }}</x-td>

                        <x-td>
                            <div class=" flex items-center justify-end gap-2">

                                @can('update', $leave)
                                    <x-badgeWithCounter leave="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showLeaveFormModal',{leave:{{ $leave }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('viewAnyAttachment', $leave)
                                    <x-badgeWithCounter :counter="$leave->attachments_count" title="{{ __('messages.attachments') }}"
                                        wire:click="$dispatch('showAttachmentModal',{model:'Leave',id:{{ $leave->id }}})">
                                        <x-svgs.attachment class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('delete', $leave)
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $leave->id }})">
                                        <x-svgs.trash class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                @endcan

                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    @else
        <x-label class=" text-center">{{ __('messages.no_leaves_found') }}</x-label>
    @endif

</div>
