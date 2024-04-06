<div class=" overflow-x-auto overflow-clip border border-gray-300 dark:border-gray-700 rounded-lg">
    <table {!! $attributes->merge([
        'class' => 'w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400',
    ]) !!}>
        {{ $slot }}
    </table>
</div>
