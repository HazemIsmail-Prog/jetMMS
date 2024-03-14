<div>
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
                <div>

                    <div class=" grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-5">
                        <div>
                            <x-label class=" font-normal">{{ __('messages.name') }}</x-label>
                            <x-label>{{ $employee->user->name }}</x-label>
                        </div>
                        <div>
                            <x-label class=" font-normal">{{ __('messages.cid') }}</x-label>
                            <x-label>{{ $employee->cid }}</x-label>
                        </div>
                        <div>
                            <x-label class=" font-normal">{{ __('messages.passport_no') }}</x-label>
                            <x-label>{{ $employee->passport_no }}</x-label>
                        </div>
                        <div>
                            <x-label class=" font-normal">{{ __('messages.recidencyExpirationDate') }}</x-label>
                            <x-label>{{ $employee->recidencyExpirationDate }}</x-label>
                        </div>
                        <div>
                            <x-label class=" font-normal">{{ __('messages.passportIssueDate') }}</x-label>
                            <x-label>{{ $employee->passportIssueDate }}</x-label>
                        </div>

                        <div>
                            <x-label class=" font-normal">{{ __('messages.passportExpirationDate') }}</x-label>
                            <x-label>{{ $employee->passportExpirationDate }}</x-label>
                        </div>
                        <div>
                            <x-label class=" font-normal">{{ __('messages.company') }}</x-label>
                            <x-label>{{ $employee->company->name }}</x-label>
                        </div>
                        <div>
                            <x-label class=" font-normal">{{ __('messages.status') }}</x-label>
                            <x-label>{{ $employee->status->title() }}</x-label>
                        </div>
                        <div>
                            <x-label class=" font-normal">{{ __('messages.salary') }}</x-label>
                            <x-label>{{ number_format($employee->salary, 3) }}</x-label>
                        </div>
                        <div>
                            <x-label class=" font-normal">{{ __('messages.leave_balance') }}</x-label>
                            <x-label>{{ number_format($employee->LeaveDaysBalance, 3) }}</x-label>
                        </div>
                        <div class="col-span-full"></div>
                        {{-- Leaves --}}
                        @can('viewAny', App\Models\Leave::class)
                            <div class=" col-span-3">
                                <livewire:employees.leaves.leave-index :$employee :key="'leaves-' . $employee->id . '-' . now()">
                            </div>
                        @endcan

                        {{-- Increases --}}
                        @can('viewAny', App\Models\Increase::class)
                            <div class=" col-span-3">
                                <livewire:employees.increases.increase-index :$employee :key="'increases-' . $employee->id . '-' . now()">
                            </div>
                        @endcan

                        {{-- Salay Actions --}}
                        @can('viewAny', App\Models\SalaryAction::class)
                            <div class=" col-span-3">
                                <livewire:employees.salary_actions.salary-action-index :$employee :key="'salary-actions-' . $employee->id . '-' . now()">
                            </div>
                        @endcan

                        {{-- Absence --}}
                        @can('viewAny', App\Models\Absence::class)
                            <div class=" col-span-3">
                                <livewire:employees.absences.absence-index :$employee :key="'absence-' . $employee->id . '-' . now()">
                            </div>
                        @endcan
                    </div>
                </div>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
