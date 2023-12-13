@props(['counter' => false])

<div {!! $attributes->merge([
    'class' =>
        'flex items-center gap-1 border dark:border-gray-700 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600',
]) !!}>
    {{ $slot }}
    @if ($counter > 0)
        <span style="font-size: 0.6rem;">{{ $counter }}</span>
    @endif
</div>
