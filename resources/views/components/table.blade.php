@props(['hiddenScrollbar' => false])

<div class="flex-grow overflow-auto {{ $hiddenScrollbar ? 'hidden-scrollbar' : '' }} border border-gray-300 dark:border-gray-700 rounded-lg">
    <table {!! $attributes->merge([
        'class' => 'w-full text-sm  text-gray-800 dark:text-gray-200',
    ]) !!}>
        {{ $slot }}
    </table>
</div>
