<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.dashboard') }}
        </h2>
    </x-slot>

    <div class=" grid grid-flow-dense grid-cols-1 gap-y-3 lg:grid-cols-4 lg:gap-x-3">
        <div class=" col-span-full">
            @include('livewire.operations-reports.daily-statistics')
        </div>
        <div class=" col-span-2">
            @include('livewire.operations-reports.orders-chart')
        </div>
        <div class=" col-span-2">
            @include('livewire.operations-reports.customers-chart')
        </div>
        <div class=" col-span-full">
            @include('livewire.operations-reports.technicians-completion-average')
        </div>
        <div class=" col-span-2">
            @include('livewire.operations-reports.orders-status-counter')
        </div>
        <div class=" col-span-2">
            @include('livewire.operations-reports.marketing-counter')
        </div>
        <div class=" col-span-2">
            @include('livewire.operations-reports.department-technician-counter')
        </div>
        <div class=" col-span-2">
            @include('livewire.operations-reports.deleted-invoices')
        </div>
    </div>

</x-app-layout>
