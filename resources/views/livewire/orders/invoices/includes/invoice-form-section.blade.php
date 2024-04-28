<form wire:submit.prevent="save" wire:loading.class="opacity-50">

    {{-- Services Form Section --}}
    <div class=" p-2 border dark:border-gray-700 rounded-lg mt-4">
        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.services') }}</h3>
        <div class=" divide-y dark:divide-gray-700">
            @forelse (collect($selected_services)->where('service_type', 'service') as $service)
                <div class="flex items-center p-2">
                    <div class=" w-2/6 text-xs">{{ $service['name'] }}</div>
                    <div class=" w-3/6">
                        <x-input step="0.01" required class="w-full p-1 text-xs" type="number"
                            wire:model.live.debounce.500ms="selected_services.{{ $service['service_id'] }}.quantity"
                            dir="ltr" placeholder="{{ __('messages.quantity') }}" />
                        <x-input step="0.001" min="{{ $service['min_price'] }}"
                            max="{{ $service['max_price'] }}" required class="w-full p-1 text-xs"
                            type="number"
                            step="0.001"
                            wire:model.live.debounce.500ms="selected_services.{{ $service['service_id'] }}.price"
                            dir="ltr"
                            placeholder="{{ $service['min_price'] }} - {{ $service['max_price'] }}" />
                        <div class=" text-center px-2">
                            {{ number_format($service['service_total'], 3) }}
                        </div>
                    </div>
                    <div class=" w-1/6 flex justify-end text-red-600">
                        <x-svgs.trash wire:click="delete_service({{ $service['service_id'] }})"
                            class="w-4 h-4" />
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
    <div class="p-2 border dark:border-gray-700 rounded-lg mt-4">
        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.parts') }}</h3>

        @forelse ($parts as $index => $part)
            <div wire:key="part-{{ $index }}"
                class="flex items-start gap-2 border dark:border-gray-700 rounded-lg p-2 mb-2">
                <x-input required class="w-full p-1 text-xs" type="text"
                    wire:model="parts.{{ $index }}.name"
                    placeholder="{{ __('messages.name') }}" />
                <div class="flex flex-col gap-1 items-center w-full">
                    <x-input required class="w-full p-1 text-xs" type="number"
                        wire:model.live.debounce.500ms="parts.{{ $index }}.quantity" dir="ltr"
                        placeholder="{{ __('messages.quantity') }}" />
                    <x-input required class="w-full p-1 text-xs" type="number"
                        wire:model.live.debounce.500ms="parts.{{ $index }}.price" dir="ltr" step="0.001"
                        placeholder="{{ __('messages.amount') }}" />
                    <p>{{ number_format($parts[$index]['total'], 3) }}</p>
                </div>
                {{-- <x-select required class="w-full p-1 text-xs"
                    wire:model="parts.{{ $index }}.type">
                    <option value="">{{ __('messages.part_source') }}</option>
                    <option value="internal">{{ __('messages.internal') }}</option>
                    <option value="external">{{ __('messages.external') }}</option>
                </x-select> --}}
                <button type="button" class="p-1" wire:click="deletePartRow({{ $index }})">
                    <x-svgs.trash class="w-4 h-4 text-red-600" />
                </button>
            </div>
        @empty
            <div class="flex items-center justify-center font-bold text-red-600 p-2">
                {{ __('messages.no_parts_selected') }}
            </div>
        @endforelse

        <div class="flex justify-center">
            <x-button type="button" class="py-1"
                wire:click="addPartRow">{{ __('messages.add_part') }}</x-button>
        </div>
    </div>

    {{-- Delivery Form Section --}}
    <div class=" p-2 border dark:border-gray-700 rounded-lg mt-4">
        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.delivery') }}</h3>
        <x-input class="w-full" wire:model.live.debounce.500ms="delivery" type="number" step="0.001" min="0"
            dir="ltr" />
    </div>

    {{-- Total Amount --}}
    <div class=" p-4 border dark:border-gray-700 rounded-lg mt-4 text-center">
        <h3 class="font-bold text-green-800 dark:text-green-400">
            {{ number_format(collect($selected_services)->sum('service_total') + ($parts ? collect($parts)->sum('total') : 0) + ($delivery == '' ? 0 : $delivery), 3) }}
        </h3>

    </div>
    <div class=" flex items-center gap-4 mt-4">


        <x-button>{{ __('messages.save') }}</x-button>
        <x-secondary-button type="button"
            wire:click="hideInvoiceForm">{{ __('messages.cancel') }}</x-button>
    </div>

</form>