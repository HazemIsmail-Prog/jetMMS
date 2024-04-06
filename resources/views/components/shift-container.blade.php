@props(['title', 'list', 'shift_id'])

<div x-show="!hiddenContainers.find(obj => obj.id === 'shift-{{ $shift_id }}');" id="shift-{{ $shift_id }}" class="flex flex-col h-full gap-1">

    <x-orders-container-header id="shift-{{ $shift_id }}" title="{{ $title }}" />

    <div class="flex-1 flex gap-1 overflow-x-auto hidden-scrollbar">

        @foreach ($list as $technician)
            <x-orders-container title="{{ $technician->name }}" :technician="$technician" :list="$this->orders->where('technician_id', $technician->id)"
                box_id="tech{{ $technician->id }}" />
        @endforeach

    </div>

</div>
