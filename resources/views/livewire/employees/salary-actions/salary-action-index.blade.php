<div class=" border dark:border-gray-700 rounded-lg p-4">

    {{-- Modals --}}
    @livewire('employees.salary_actions.salary-action-form')


    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.salaryActions') }}
            <span id="counter"></span>
        </h2>
        <x-button class=" no-print"
            wire:click="$dispatch('showSalaryActionFormModal',{employee:{{ $employee }}})">{{ __('messages.add_salaryAction') }}</x-button>
    </div>
    <x-section-border />

    @if ($employee->salaryActions->count() > 0)
        <div class=" overflow-x-auto sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.date') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.amount') }}
                        </th>
                        <th scope="col" class="px-6 py-1 text-start">
                            {{ __('messages.reason') }}
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
                    @foreach ($this->salaryActions as $salaryAction)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $salaryAction->date->format('d-m-Y') }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div class="{{ $salaryAction->type->color() }}">{{ number_format($salaryAction->amount,3) }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $salaryAction->reason }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div class="{{ $salaryAction->status->color() }}">{{ $salaryAction->status->title() }}</div>
                            </td>
                            <td class="px-6 py-1 text-start whitespace-nowrap ">
                                <div>{{ $salaryAction->notes }}</div>
                            </td>

                            <td class="px-6 py-1 text-end align-middle whitespace-nowrap no-print">
                                <div class=" flex items-center justify-end gap-2">

                                    @if ($salaryAction->status)
                                        
                                    @endif
                                    <x-badgeWithCounter salaryAction="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showSalaryActionFormModal',{salaryAction:{{ $salaryAction }}})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                    <x-badgeWithCounter :counter="$salaryAction->attachments_count" title="{{ __('messages.attachments') }}"
                                        wire:click="$dispatch('showAttachmentModal',{model:'SalaryAction',id:{{ $salaryAction->id }}})">
                                        <x-svgs.attachment class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $salaryAction->id }})">
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
        <x-label class=" text-center">{{ __('messages.no_salaryActions_found') }}</x-label>
    @endif

</div>
