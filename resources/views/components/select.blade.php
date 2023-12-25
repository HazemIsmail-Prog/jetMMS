@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'px-2 py-1 border-gray-300 text-center text-md dark:border-gray-700 dark:bg-gray-900 text-gray-500 dark:text-gray-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
]) !!}>
    {{ $slot }}
</select>
