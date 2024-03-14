<div class=" border dark:border-gray-700 rounded-lg p-4">

    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.absences') }}
            <span id="counter"></span>
        </h2>
        @can('create', App\Models\Absence::class)
            <x-button class=" no-print"
                wire:click="$dispatch('showAbsenceFormModal',{employee:{{ $employee }}})">{{ __('messages.add_absence') }}</x-button>
        @endcan
    </div>
    <x-section-border />

    @if ($employee->absences->count() > 0)
        <div class=" overflow-x-auto sm:rounded-lg">
            <x-table>
                <x-thead>
                    <tr>
                        <x-th>{{ __('messages.date') }}</x-th>
                        <x-th>{{ __('messages.absence_days_count') }}</x-th>
                        <x-th>{{ __('messages.deduction_days') }}</x-th>
                        <x-th>{{ __('messages.deduction_amount') }}</x-th>
                        <x-th>{{ __('messages.notes') }}</x-th>
                        <x-th></x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach ($this->absences as $absence)
                        <x-tr>
                            <x-td>
                                <div>{{ $absence->start_date->format('d-m-Y') }}</div>
                                <div>{{ $absence->end_date->format('d-m-Y') }}</div>
                            </x-td>
                            <x-td>{{ $absence->absence_days_count }}</x-td>
                            <x-td>{{ $absence->deduction_days }}</x-td>
                            <x-td>{{ $absence->deduction_amount }}</x-td>
                            <x-td>{{ $absence->notes }}</x-td>

                            <x-td>
                                <div class=" flex items-center justify-end gap-2">

                                    @can('update', $absence)
                                        <x-badgeWithCounter absence="{{ __('messages.edit') }}"
                                            wire:click="$dispatch('showAbsenceFormModal',{absence:{{ $absence }}})">
                                            <x-svgs.edit class="h-4 w-4" />
                                        </x-badgeWithCounter>
                                    @endcan

                                    @can('viewAnyAttachment', $absence)
                                        <x-badgeWithCounter :counter="$absence->attachments_count" title="{{ __('messages.attachments') }}"
                                            wire:click="$dispatch('showAttachmentModal',{model:'Absence',id:{{ $absence->id }}})">
                                            <x-svgs.attachment class="w-4 h-4" />
                                        </x-badgeWithCounter>
                                    @endcan

                                    @can('delete', $absence)
                                        <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                            wire:confirm="{{ __('messages.are_u_sure') }}"
                                            wire:click="delete({{ $absence->id }})">
                                            <x-svgs.trash class="w-4 h-4" />
                                        </x-badgeWithCounter>
                                    @endcan

                                </div>
                            </x-td>
                        </x-tr>
                    @endforeach
                </tbody>
            </x-table>
        </div>
    @else
        <x-label class=" text-center">{{ __('messages.no_absences_found') }}</x-label>
    @endif

</div>
