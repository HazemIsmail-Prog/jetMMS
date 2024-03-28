<div>
    <x-dialog-modal maxWidth="2xl" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">

            @if ($order)
                <div class=" overflow-x-auto sm:rounded-lg">
                    <x-table>
                        <x-thead>
                            <tr>
                                <x-th>{{ __('messages.status') }}</x-th>
                                <x-th>{{ __('messages.technician') }}</x-th>
                                <x-th>{{ __('messages.date') }}/{{ __('messages.time') }}</x-th>
                                <x-th>{{ __('messages.creator') }}</x-th>
                            </tr>
                        </x-thead>
                        <tbody>
                            @foreach ($this->statuses as $status)
                                <x-tr>
                                    <x-td style="color: {{ $status->status->color }}">{{ $status->status->name }}</x-td>
                                    <x-td>{{ @$status->technician->name ?? '-' }}</x-td>
                                    <x-td>
                                        <div>{{ $status->created_at->format('d-m-Y') }}</div>
                                        <div class=" text-xs">{{ $status->created_at->format('H:i') }}</div>
                                    </x-td>
                                    <x-td>{{ @$status->creator->name }}</x-td>
                                </x-tr>
                            @endforeach
                        </tbody>
                    </x-table>
                </div>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
