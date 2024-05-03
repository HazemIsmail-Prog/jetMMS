<!-- Languages Dropdown -->
<div class="relative">
    <x-dropdown width="48">
        <x-slot name="trigger">
            {{-- <span class="inline-flex rounded-md"> --}}
                <button type="button"
                    class=" align-middle border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    <x-svgs.globe class="w-6 h-6"/>
                    
                    {{-- <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg> --}}
                </button>
            {{-- </span> --}}
        </x-slot>

        <x-slot name="content">
            <!-- Account Management -->
            <div class="block px-4 py-2 text-xs text-gray-400">
                {{ __('messages.select_language') }}
            </div>

            @foreach (config('languages') as $localeCode => $properties)
                <x-dropdown-link href="{{ route('lang.swith', $localeCode) }}"
                    class="{{ $localeCode == app()->getLocale() ? 'bg-gray-100 dark:bg-gray-800' : '' }}">
                    <div class="flex justify-between">
                        <div class="flex gap-3">
                            @if ($localeCode == 'ar')
                                <x-svgs.ar />
                            @else
                                <x-svgs.en />
                            @endif
                            {{ $properties }}
                        </div>
                        @if ($localeCode == app()->getLocale())
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                </x-dropdown-link>
            @endforeach
        </x-slot>
    </x-dropdown>
</div>
