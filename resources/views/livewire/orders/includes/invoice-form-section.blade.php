<form wire:submit="save">

    {{-- Services Form Section --}}
    <div class=" p-2 border dark:border-gray-700 rounded-lg mt-4">
        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.services') }}</h3>
        <div class=" divide-y dark:divide-gray-700">
            @forelse (collect($selected_services)->where('service_type', 'service') as $service)
                <div class="flex items-center p-2">
                    <div class=" w-2/6 text-xs">{{ $service['name'] }}</div>
                    <div class=" w-3/6">
                        <x-input step="0.01" required class="w-full p-1 text-xs" type="number"
                            wire:model.live="selected_services.{{ $service['service_id'] }}.quantity" dir="ltr"
                            placeholder="{{ __('messages.quantity') }}" />
                        <x-input step="0.001" min="{{ $service['min_price'] }}" max="{{ $service['max_price'] }}"
                            required class="w-full p-1 text-xs" type="number"
                            wire:model.live="selected_services.{{ $service['service_id'] }}.price" dir="ltr"
                            placeholder="{{ $service['min_price'] }} - {{ $service['max_price'] }}" />
                        <div class=" text-center px-2">
                            {{ number_format($service['service_total'], 3) }}
                        </div>
                    </div>
                    <div class=" w-1/6 flex justify-end text-red-600">
                        <x-svgs.trash wire:click="delete_service({{ $service['service_id'] }})" class="w-4 h-4" />
                    </div>
                </div>

            @empty
                <div class="flex items-center justify-center font-bold text-red-600 p-2">
                    {{ __('messages.no_services_selected') }}
                </div>
            @endforelse
        </div>
    </div>


    {{-- Parts Form Section --}}
    <div class=" p-2 border dark:border-gray-700 rounded-lg mt-4">
        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.parts') }}</h3>
        <div class=" divide-y dark:divide-gray-700">
            @forelse (collect($selected_services)->where('service_type', 'part') as $service)
                <div class="flex items-center p-2">
                    <div class=" w-2/6 text-xs">{{ $service['name'] }}</div>
                    <div class=" w-3/6">
                        <x-input step="0.01" required class="w-full p-1 text-xs" type="number"
                            wire:model.live="selected_services.{{ $service['service_id'] }}.quantity" dir="ltr"
                            placeholder="{{ __('messages.quantity') }}" />
                        <x-input step="0.001" min="{{ $service['min_price'] }}" max="{{ $service['max_price'] }}"
                            required class="w-full p-1 text-xs" type="number"
                            wire:model.live="selected_services.{{ $service['service_id'] }}.price" dir="ltr"
                            placeholder="{{ $service['min_price'] }} - {{ $service['max_price'] }}" />
                        <div class=" text-center px-2">
                            {{ number_format($service['service_total'], 3) }}
                        </div>
                    </div>
                    <div class=" w-1/6 flex justify-end text-red-600">
                        <x-svgs.trash wire:click="delete_service({{ $service['service_id'] }})" class="w-4 h-4" />
                    </div>
                </div>

            @empty
                <div class="flex items-center justify-center font-bold text-red-600 p-2">
                    {{ __('messages.no_parts_selected') }}
                </div>
            @endforelse
        </div>
    </div>

    {{-- Total Amount --}}
    <div class=" p-4 border dark:border-gray-700 rounded-lg mt-4 text-center">
        <h3 class="font-bold text-green-800 dark:text-green-400">
            {{ number_format(collect($selected_services)->sum('service_total'), 3) }}</h3>

    </div>
    <div class=" flex items-center gap-4 mt-4">


        <x-button>{{ __('messages.save') }}</x-button>
        <x-secondary-button type="button" wire:click="hideInvoiceForm">{{ __('messages.cancel') }}</x-button>
    </div>

</form>
