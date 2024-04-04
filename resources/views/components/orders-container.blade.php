@props(['title', 'total' => null, 'technician' => null, 'list', 'box_id'])

<div class="h-full w-64 flex flex-col gap-1 flex-shrink-0">

    <x-orders-container-header :title="$title" :technician="$technician" :total="$total" />

    {{-- Box --}}
    <div id="{{ $box_id }}"
        class="box border dark:border-gray-700 rounded-lg flex-1 p-2 flex flex-col overflow-y-auto gap-2 hidden-scrollbar">
        @foreach ($list as $order)
        <x-order-box :order="$order" />
            {{-- @include('livewire.dispatching.__order-box') --}}
        @endforeach
    </div>

</div>
