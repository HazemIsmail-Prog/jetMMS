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
    @can('create', App\Models\Employee::class)
        @teleport('#addNew')
            <x-button class=" no-print"
                wire:click="$dispatch('showEmployeeFormModal')">{{ __('messages.add_employee') }}</x-button>
        @endteleport
    @endcan

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


    @livewire('employees.employee-form')
    @livewire('employees.employee-view')

    @livewire('employees.absences.absence-form')
    @livewire('employees.increases.increase-form')
    @livewire('employees.leaves.leave-form')
    @livewire('employees.salary_actions.salary-action-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        <div>
            <x-label for="name">{{ __('messages.name') }}</x-label>
            <x-input id="name" type="text" wire:model.live="filters.name" class="w-full text-start py-0" />
        </div>
        <div>
            <x-label for="title">{{ __('messages.title') }}</x-label>
            <x-select wire:model.live="filters.title_id" class=" w-full py-0">
                <option value="">---</option>
                @foreach ($titles as $title)
                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-select wire:model.live="filters.department_id" class=" w-full py-0">
                <option value="">---</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-label for="shift">{{ __('messages.shift') }}</x-label>
            <x-select wire:model.live="filters.shift_id" class=" w-full py-0">
                <option value="">---</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-label for="status">{{ __('messages.status') }}</x-label>
            <x-select required wire:model.live="filters.status" class=" w-full py-0">
                <option value="">---</option>
                @foreach (App\Enums\EmployeeStatusEnum::cases() as $status)
                    <option value="{{ $status->value }}">{{ $status->title() }}</option>
                @endforeach
            </x-select>
        </div>
    </div>

    <div class=" overflow-x-auto sm:rounded-lg">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.name') }}</x-th>
                    <x-th>{{ __('messages.title') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.shift') }}</x-th>
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-th>{{ __('messages.joinDate') }}</x-th>
                    <x-th>{{ __('messages.salary') }}</x-th>
                    <x-th>{{ __('messages.leave_balance') }}</x-th>
                    <x-th>{{ __('messages.net_working_days') }}</x-th>
                    <x-th>{{ __('messages.indemnity') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->employees as $employee)
                    <x-tr>
                        <x-td>{{ $employee->user->name }}</x-td>
                        <x-td>{{ $employee->user->title->name }}</x-td>
                        <x-td>{{ $employee->user->department->name ?? '-' }}</x-td>
                        <x-td>{{ $employee->user->shift->name ?? '-' }}</x-td>
                        <x-td
                            class="text-{{ $employee->status->color() }}-400">{{ $employee->status->title() }}</x-td>
                        <x-td>{{ $employee->joinDate->format('d-m-Y') }}</x-td>
                        <x-td>{{ number_format($employee->salary, 3) }}</x-td>
                        <x-td>
                            <div>{{ number_format($employee->LeaveDaysBalance, 2) }}</div>
                            <div>{{ number_format($employee->LeaveBalanceAmount, 3) }}</div>
                        </x-td>
                        <x-td>{{ $employee->NetWorkingDays }}</x-td>
                        <x-td>{{ number_format($employee->Indemnity, 3) }}</x-td>
                        <x-td>
                            <div class="flex items-center justify-end gap-2">

                                @can('view', $employee)
                                    <x-badgeWithCounter title="{{ __('messages.view') }}"
                                        wire:click="$dispatch('showEmployeeViewModal',{employee:{{ $employee }}})">
                                        <x-svgs.view class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('update', $employee)
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        wire:click="$dispatch('showEmployeeFormModal',{employee:{{ $employee }}})">
                                        <x-svgs.edit class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('viewAnyAttachment', $employee)
                                    <x-badgeWithCounter :counter="$employee->attachments_count" title="{{ __('messages.attachments') }}"
                                        wire:click="$dispatch('showAttachmentModal',{model:'Employee',id:{{ $employee->id }}})">
                                        <x-svgs.attachment class="w-4 h-4" />
                                    </x-badgeWithCounter>
                                @endcan

                                @can('delete', $employee)
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="delete({{ $employee }})">
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
</div>
