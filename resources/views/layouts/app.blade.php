<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ $settings->favicon_path }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Google Icons --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,100,0,200" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body x-data="{
    sidebarExpanded: localStorage.getItem('sidebarExpanded') !== null ? JSON.parse(localStorage.getItem('sidebarExpanded')) : true,
    isLTR: document.documentElement.dir === 'ltr',
    isRTL: document.documentElement.dir === 'rtl',
}" x-init="() => {
    if (window.innerWidth < 786) {
        sidebarExpanded = false;
    }
    $watch('sidebarExpanded', value => {
        localStorage.setItem('sidebarExpanded', JSON.stringify(value));
    });
}"
    class="{{ app()->getLocale() == 'ar' ? 'font-lateef' : ' font-Nunito' }}  antialiased h-dvh overflow-hidden bg-gray-100 dark:bg-gray-900">

    {{-- Chatting --}}
    {{-- @persist('chats') --}}
    @livewire('chats.chat-button')
    @livewire('chats.chat-modal')
    {{-- @endpersist --}}

    
    @livewire('sidebar')

    {{-- Main --}}
    <div style="height: 100dvh;" x-cloak class=" flex flex-col transition-all"
        :class="{ ' ms-0 md:ms-64 ': sidebarExpanded }">
        {{-- Topbar --}}

        @livewire('navigation-menu')
        {{-- Header --}}
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-full mx-auto p-3 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        {{-- Content --}}
        <main class=" flex-1 overflow-y-auto ">
            <div class="py-2 lg:py-6 ">
                <div class="max-w-full mx-auto px-2 lg:px-6 ">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden sm:rounded-lg p-0 lg:p-4">
                        <x-alert />
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
        <!-- Page Footer -->
        @if (isset($footer))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-full mx-auto p-2 sm:px-6 lg:px-10">
                    {{ $footer }}
                </div>
            </header>
        @endif
    </div>



    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {

            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }

                // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }

        });
    </script>

    @livewireScripts
</body>

</html>
