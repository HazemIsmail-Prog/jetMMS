@props(['route', 'title','icon'])
@php
    $active = explode('.',request()->route()->getName())[0] == explode('.',$route)[0];
@endphp
<li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if ($active) {{ 'bg-slate-900' }} @endif">
    <a class="block truncate transition duration-150" href="{{ route($route) }}">
        <div
            class="flex items-center {{ $active ? 'text-indigo-500' : 'text-slate-400 hover:text-white' }}">
            <span class="material-symbols-outlined">{{ $icon }}</span>
            <span class="text-sm font-medium ms-3 duration-200">{{ $title }}</span>
        </div>
    </a>
</li>
