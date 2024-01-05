<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.employees') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>


        </div>
    </x-slot>
    @teleport('#addNew')
        <x-button class=" no-print"
            wire:click="$dispatch('showEmployeeFormModal')">{{ __('messages.add_employee') }}</x-button>
    @endteleport

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->employees->total() }}
        </span>
    @endteleport

    @if ($this->employees->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->employees->links() }}</div>
        @endteleport
    @endif

    @livewire('attachment-modal')
    @livewire('employees.employee-form')



    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th>
                        <x-input placeholder="{{ __('messages.name') }}" wire:model.live="filters.name"
                            class="w-full text-start py-0" />
                    </th>
                    <th>
                        <x-select wire:model.live="filters.title_id" class=" w-full py-0">
                            <option value="">{{ __('messages.title') }}</option>
                            @foreach ($titles as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th>
                        <x-select wire:model.live="filters.department_id" class=" w-full py-0">
                            <option value="">{{ __('messages.department') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th>
                        <x-select wire:model.live="filters.shift_id" class=" w-full py-0">
                            <option value="">{{ __('messages.shift') }}</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th>
                        <x-select required wire:model.live="filters.status" class=" w-full py-0">
                            <option value="">{{ __('messages.status') }}</option>
                            @foreach (App\Enums\EmployeeStatusEnum::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->title() }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th class="px-6 py-1 text-center">{{ __('messages.joinDate') }}</th>
                    <th class="px-6 py-1 text-right">{{ __('messages.salary') }}</th>
                    <th class="px-6 py-1 text-center">{{ __('messages.leave_balance') }}</th>
                    <th class="px-6 py-1 text-center">{{ __('messages.net_working_days') }}</th>
                    <th class="px-6 py-1 text-right">{{ __('messages.indemnity') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->employees as $employee)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $employee->user->name }}
                        </th>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $employee->user->title->name }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $employee->user->department->name ?? '-' }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $employee->user->shift->name ?? '-' }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap text-{{ $employee->status->color() }}-400">
                            {{ $employee->status->title() }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $employee->joinDate->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap">
                            {{ number_format($employee->salary,3) }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            <div>{{ number_format($employee->LeaveDaysBalance,2) }}</div>
                            <div>{{ number_format($employee->LeaveBalanceAmount,3) }}</div>
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $employee->NetWorkingDays }}
                        </td>
                        <td class="px-6 py-1 text-right whitespace-nowrap">
                            {{ number_format($employee->Indemnity,3) }}
                        </td>
                        <td class="px-6 py-1 text-end whitespace-nowrap flex items-center gap-2 no-print">
                            <x-badgeWithCounter :counter="$employee->attachments_count" title="{{ __('messages.attachments') }}"
                                wire:click="$dispatch('showAttachmentModal',{model:'Employee',id:{{ $employee->id }}})">
                                <x-svgs.attachment class="w-4 h-4" />
                            </x-badgeWithCounter>
                            <x-badgeWithCounter :counter="$employee->attachments_count" title="{{ __('messages.attachments') }}"
                                wire:click="$dispatch('showEmployeeFormModal',{employee:{{ $employee->id }}})">
                                <x-svgs.edit class="w-4 h-4" />
                            </x-badgeWithCounter>
                            <a href="{{ route('employee.view', $employee) }}"
                                class="flex items-center gap-1 border dark:border-gray-700 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                <x-svgs.view class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>





</div>
