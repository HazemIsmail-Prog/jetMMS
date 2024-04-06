@props(['title', 'technician' => null, 'list', 'box_id'])

<div x-cloak x-show="!hiddenContainers.find(obj => obj.id === '{{ $box_id }}');"
    class="h-full w-64 flex flex-col gap-1 flex-shrink-0">

    <x-orders-container-header :id="$box_id" :title="$title" :technician="$technician" :total="$list->count()" />

    {{-- Box --}}
    <div id="{{ $box_id }}"
        class="box border border-gray-400 dark:border-gray-700 rounded-lg flex-1 p-2 flex flex-col overflow-y-auto gap-2 hidden-scrollbar">
        @foreach ($list as $order)
            <x-order-box :order="$order" />
        @endforeach
    </div>

</div>
