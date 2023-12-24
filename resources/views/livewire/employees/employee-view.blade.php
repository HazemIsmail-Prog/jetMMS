<div>

    <div class=" grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
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
        {{-- Leaves --}}
        <div class=" col-span-full">
            <livewire:employees.leave-index :$employee :key="'leaves-' . $employee->id . '-' . now()">
        </div>
    </div>


</div>
