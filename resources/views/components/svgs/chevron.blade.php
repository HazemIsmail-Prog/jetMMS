<svg class="w-3 h-3 shrink-0 ms-1 fill-current text-slate-400 @if (in_array(Route::current()->getName(), [
        'roles.index',
        'permissions.index',
        'users.index',
        'voters.index',
        'schools.index',
        'providers.index',
    ])) {{ 'rotate-180' }} @endif"
    :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
</svg>