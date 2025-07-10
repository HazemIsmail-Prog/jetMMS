<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.dashboard') }}
        </h2>
    </x-slot>

    <div class=" grid grid-flow-dense grid-cols-1 gap-y-3 lg:grid-cols-4 lg:gap-x-3">
        @if(auth()->user()->hasPermission('view-daily-statistics'))
            <div class=" col-span-full">
                @include('livewire.operations-reports.daily-statistics')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-customers-with-no-orders-chart'))
            <div class=" col-span-2">
                @include('livewire.operations-reports.customers-with-no-orders-chart')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-customers-completed-orders-chart'))
            <div class=" col-span-2">
                @include('livewire.operations-reports.customers-completed-orders-chart')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-orders-chart'))
            <div class=" col-span-2">
                @include('livewire.operations-reports.orders-chart')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-customers-chart'))
            <div class=" col-span-2">
                @include('livewire.operations-reports.customers-chart')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-technicians-completion-average'))
            <div class=" col-span-full">
                @include('livewire.operations-reports.technicians-completion-average')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-orders-status-counter'))
            <div class=" col-span-2">
                @include('livewire.operations-reports.orders-status-counter')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-marketing-counter'))
            <div class=" col-span-2">
                @include('livewire.operations-reports.marketing-counter')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-department-technician-counter'))
            <div class=" col-span-2">
                @include('livewire.operations-reports.department-technician-counter')
            </div>
        @endif
        @if(auth()->user()->hasPermission('view-deleted-invoices'))
            <div class=" col-span-2">
                @include('livewire.operations-reports.deleted-invoices')
            </div>
        @endif
    </div>

</x-app-layout>
