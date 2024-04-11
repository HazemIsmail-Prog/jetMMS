@props(['id', 'maxWidth','dismissible'=> true ])

@php
    $id = $id ?? md5($attributes->wire('model'));

    $maxWidth = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
        'full' => 'sm:max-w-full',
    ][$maxWidth ?? '2xl'];
@endphp

<div x-data="{ show: @entangle($attributes->wire('model')) }" x-on:close.stop="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    id="{{ $id }}" class="jetstream-modal fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;">
    <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click="{{ $dismissible ? 'show = false' : ''}}"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    <div x-show="show"
        class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto"
        x-trap.inert.noscroll="show" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        
        {{-- Close Button --}}
        <button x-on:click="show = false" type="button" class=" 
        absolute 
        -top-2 
        -start-2 
        inline-flex 
        items-center 
        p-1 
        rounded-full  
        bg-gray-800 
        dark:bg-gray-200 
        text-white 
        dark:text-gray-800 
        focus:outline-none
        transition 
        ease-in-out 
        duration-150
        ">
            <x-svgs.close class="w-5 h-5" />
        </button>
        {{ $slot }}
    </div>
</div>
