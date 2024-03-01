@props(['route','title','param'=>null])

<li class="mb-1 last:mb-0">
    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if (request()->routeIs($route)) {{ '!text-indigo-500' }} @endif"
        href="{{ route($route,$param) }}">
        <span class="text-sm font-medium duration-200">{{ $title }}</span>
    </a>
</li>
