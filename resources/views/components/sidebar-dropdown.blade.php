@props(['title', 'icon'])

<li x-data="{ open: false, active: false }" x-init="active = $el.querySelector('.active') !== null;
open = $el.querySelector('.active') !== null;" class="px-3 py-2 rounded-lg mb-0.5 last:mb-0"
    :class="active ? 'bg-slate-900' : ''">
    <a class="block text-slate-400 hover:text-white truncate transition duration-150" href="#0"
        @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <x-dynamic-component :component="'svgs.'.$icon" class="w-6 h-6"/>
                <span class="text-sm font-medium ms-3 duration-200">{{ $title }}</span>
            </div>
            <x-svgs.chevron />
        </div>
    </a>
    <ul x-cloak class="ps-9 mt-1" :class="open ? '!block' : 'hidden'">
        {{ $slot }}
    </ul>
</li>
