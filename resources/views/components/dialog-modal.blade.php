@props(['id' => null, 'maxWidth' => null,'dismissible' => true])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }} :dismissible="$dismissible">
    <div class="px-3 py-3">
        @if (isset($title))
            <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $title }}
            </div>
        @endif

        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ $content }}
        </div>
    </div>
    @if (isset($footer))
        <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 dark:bg-gray-800 text-end">
            {{ $footer }}
        </div>
    @endif
</x-modal>
