@props(['route', 'param' => null, 'title', 'icon'])

@php

    if ($param) {

        $currentParam = collect(request()->route()->parameters())->first()->id ?? null;
        $active = request()->route()->getName() == $route && $param == $currentParam;
    } else {
        $active = request()->route()->getName() == $route;
    }

@endphp


<li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if ($active) {{ 'bg-slate-900' }} @endif">
    <a wire:navigate class="block truncate transition duration-150" href="{{ route($route, $param) }}">
        <div class="flex items-center {{ $active ? 'text-indigo-500' : 'text-slate-400 hover:text-white' }}">
            <x-dynamic-component :component="'svgs.' . $icon" class="w-6 h-6" />
            <span class="text-sm font-medium ms-3 duration-200">{{ $title }}</span>
        </div>
    </a>
</li>
