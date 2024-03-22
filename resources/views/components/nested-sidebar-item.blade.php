@props(['route', 'title', 'param' => null])

@php
    if ($param) {
        $active = request()->is(explode('.', $route)[0] . '/' . $param);
    } else {
        $active = request()->route()->getName() == $route;
    }
@endphp


<li class="mb-1 last:mb-0">
    <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if ($active) {{ '!text-indigo-500 active' }} @endif"
        href="{{ route($route, $param) }}">
        <span class="text-sm font-medium duration-200">{{ $title }}</span>
    </a>
</li>
