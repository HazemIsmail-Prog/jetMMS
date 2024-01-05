<div>

    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ $employee->user->name }}
                <span id="counter"></span>
            </h2>
            <x-anchor class="no-print" href="{{ url()->previous() }}">{{ __('messages.back') }}</x-anchor>
        </div>
    </x-slot>

    {{-- Modals --}}
    @livewire('attachment-modal')


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
        <div class=" col-span-3">
            <livewire:employees.leave-index :$employee :key="'leaves-' . $employee->id . '-' . now()">
        </div>

        {{-- Increases --}}
        <div class=" col-span-3">
            <livewire:employees.increase-index :$employee :key="'increases-' . $employee->id . '-' . now()">
        </div>

        {{-- Salay Actions --}}
        {{-- <div class=" col-span-3">
            <livewire:employees.salary-action-index :$employee :key="'salary-actions-' . $employee->id . '-' . now()">
        </div> --}}

        {{-- Absence --}}
        <div class=" col-span-3">
            <livewire:employees.absence-index :$employee :key="'absence-' . $employee->id . '-' . now()">
        </div>
    </div>


</div>
