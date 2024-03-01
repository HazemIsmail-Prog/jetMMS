{{-- Search --}}
<x-input wire:model.live="search" class=" w-full" placeholder="{{ __('messages.search') }}" />

{{-- Services and Parts Container --}}
<div class=" mt-2 p-3 border dark:border-gray-700 rounded-lg flex flex-col md:flex-row gap-4">

    {{-- Services Containter --}}
    <div class=" flex-1 ">
        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.services') }}</h3>
        <ul
            class="w-full overflow-y-auto h-72 hidden-scrollbar text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @foreach ($this->services->where('type', 'service')->sortBy('name') as $service)
                <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="service-{{ $service->id }}" type="checkbox"
                            value="{{ $service->id }}"
                            wire:model.live="select_service.{{ $service->id }}"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="service-{{ $service->id }}"
                            class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $service->name }}</label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Parts Container --}}
    {{-- <div class=" flex-1 ">
        <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.parts') }}</h3>
        <ul
            class="w-full overflow-y-auto h-72 hidden-scrollbar text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @foreach ($this->services->where('type', 'part')->sortBy('name') as $service)
                <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                    <div class="flex items-center ps-3">
                        <input id="service-{{ $service->id }}" type="checkbox"
                            value="{{ $service->id }}"
                            wire:model.live="select_service.{{ $service->id }}"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                        <label for="service-{{ $service->id }}"
                            class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $service->name }}</label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div> --}}

</div>