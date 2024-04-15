@props(['counter' => false])

<div {!! $attributes->merge([
    'class' =>
        'flex items-center gap-1 border border-gray-300 dark:border-gray-400 text-gray-900 dark:text-gray-200 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600',
]) !!}>
    {{ $slot }}
    @if ($counter > 0)
        <span style="font-size: 0.6rem;">{{ $counter }}</span>
    @endif
</div>
