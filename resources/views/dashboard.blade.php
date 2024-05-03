<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.dashboard') }}
        </h2>
    </x-slot>

    <div class=" grid grid-flow-dense grid-cols-1 gap-y-3 lg:grid-cols-4 lg:gap-x-3">
        <div class=" col-span-full">
            @livewire('operations-reports.daily-statistics', key('daily-statistics'.rand()))
        </div>
        <div class=" col-span-2">
            @livewire('operations-reports.orders-chart', key('orders-chart'.rand()))
        </div>
        <div class=" col-span-2">
            @livewire('operations-reports.customers-chart', key('customers-chart'.rand()))
        </div>
        <div class=" col-span-2">
            @livewire('operations-reports.orders-status-counter', key('orders-status-counter'.rand()))
        </div>
        <div class=" col-span-2">
            @livewire('operations-reports.marketing-counter', key('marketing-counter'.rand()))
        </div>
        <div class=" col-span-2">
            @livewire('operations-reports.department-technician-counter', key('department-technician-counter'.rand()))
        </div>
    </div>


    {{-- <x-welcome /> --}}

</x-app-layout>
