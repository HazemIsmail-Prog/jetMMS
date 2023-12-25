<div class=" border dark:border-gray-700 rounded-lg p-4">

    {{-- Modals --}}
    @livewire('employees.increase-form')


    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.increases') }}
            <span id="counter"></span>
        </h2>
        <x-button class=" no-print"
            wire:click="$dispatch('showIncreaseFormModal',{employee:{{ $employee }}})">{{ __('messages.add_increase') }}</x-button>
    </div>
    <x-section-border />

    @if ($employee->increases->count() > 0)
        <div class=" overflow-x-auto sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.increase_date') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.amount') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.type') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.notes') }}
                        </th>
                        <th scope="col" class=" no-print"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->increases as $increase)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $increase->increase_date->format('d-m-Y') }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $increase->amount }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $increase->type }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $increase->notes }}</div>
                            </td>

                            <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                                <div class=" flex items-center justify-end gap-2">

                                    <x-badgeWithCounter increase="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showIncreaseFormModal',{increase:{{ $increase }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                    <x-badgeWithCounter :counter="$increase->attachments_count" title="{{ __('messages.attachments') }}"
                                        wire:click="$dispatch('showAttachmentModal',{model:'Increase',id:{{ $increase->id }}})">
                                        <x-svgs.attachment class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $increase->id }})">
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
        <x-label class=" text-center">{{ __('messages.no_increases_found') }}</x-label>
    @endif

</div>
