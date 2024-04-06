<div class=" border dark:border-gray-700 rounded-lg p-4">

    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.salaryActions') }}
            <span id="counter"></span>
        </h2>
        @can('create', App\Models\SalaryAction::class)
            <x-button class=" no-print"
                wire:click="$dispatch('showSalaryActionFormModal',{employee:{{ $employee }}})">{{ __('messages.add_salaryAction') }}</x-button>
        @endcan
    </div>
    <x-section-border />

    @if ($employee->salaryActions->count() > 0)
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.date') }}</x-th>
                    <x-th>{{ __('messages.amount') }}</x-th>
                    <x-th>{{ __('messages.reason') }}</x-th>
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->salaryActions as $salaryAction)
                    <x-tr>
                        <x-td>{{ $salaryAction->date->format('d-m-Y') }}</x-td>
                        <x-td
                            class="{{ $salaryAction->type->color() }}">{{ number_format($salaryAction->amount, 3) }}</x-td>
                        <x-td>{{ $salaryAction->reason }}</x-td>
                        <x-td class="{{ $salaryAction->status->color() }}">{{ $salaryAction->status->title() }}</x-td>
                        <x-td>{{ $salaryAction->notes }}</x-td>
                        <x-td>
                            <div class=" flex items-center justify-end gap-2">

                                @can('update', $salaryAction)
                                    <x-badgeWithCounter salaryAction="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showSalaryActionFormModal',{salaryAction:{{ $salaryAction }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('viewAnyAttachment', $salaryAction)
                                    <x-badgeWithCounter :counter="$salaryAction->attachments_count" title="{{ __('messages.attachments') }}"
                                        wire:click="$dispatch('showAttachmentModal',{model:'SalaryAction',id:{{ $salaryAction->id }}})">
                                        <x-svgs.attachment class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('delete', $salaryAction)
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $salaryAction->id }})">
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
        <x-label class=" text-center">{{ __('messages.no_salaryActions_found') }}</x-label>
    @endif

</div>
