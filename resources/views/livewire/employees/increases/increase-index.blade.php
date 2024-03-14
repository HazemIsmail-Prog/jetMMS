<div class=" border dark:border-gray-700 rounded-lg p-4">

    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.increases') }}
            <span id="counter"></span>
        </h2>
        @can('create', App\Models\Increase::class)
            <x-button class=" no-print"
                wire:click="$dispatch('showIncreaseFormModal',{employee:{{ $employee }}})">{{ __('messages.add_increase') }}</x-button>
        @endcan
    </div>
    <x-section-border />

    @if ($employee->increases->count() > 0)
        <div class=" overflow-x-auto sm:rounded-lg">
            <x-table>
                <x-thead>
                    <tr>
                        <x-th>{{ __('messages.increase_date') }}</x-th>
                        <x-th>{{ __('messages.amount') }}</x-th>
                        <x-th>{{ __('messages.type') }}</x-th>
                        <x-th>{{ __('messages.notes') }}</x-th>
                        <x-th></x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach ($this->increases as $increase)
                        <x-tr>
                            <x-td>{{ $increase->increase_date->format('d-m-Y') }}</x-td>
                            <x-td>{{ $increase->amount }}</x-td>
                            <x-td>{{ $increase->type->title() }}</x-td>
                            <x-td>{{ $increase->notes }}</x-td>
                            <x-td>
                                <div class=" flex items-center justify-end gap-2">

                                    @can('update', $increase)
                                        <x-badgeWithCounter increase="{{ __('messages.edit') }}"
                                            wire:click="$dispatch('showIncreaseFormModal',{increase:{{ $increase }}})">
                                            <x-svgs.edit class="h-4 w-4" />
                                        </x-badgeWithCounter>
                                    @endcan

                                    @can('viewAnyAttachment', $increase)
                                        <x-badgeWithCounter :counter="$increase->attachments_count" title="{{ __('messages.attachments') }}"
                                            wire:click="$dispatch('showAttachmentModal',{model:'Increase',id:{{ $increase->id }}})">
                                            <x-svgs.attachment class="w-4 h-4" />
                                        </x-badgeWithCounter>
                                    @endcan

                                    @can('delete', $increase)
                                        <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                            wire:confirm="{{ __('messages.are_u_sure') }}"
                                            wire:click="delete({{ $increase->id }})">
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
        <x-label class=" text-center">{{ __('messages.no_increases_found') }}</x-label>
    @endif

</div>
