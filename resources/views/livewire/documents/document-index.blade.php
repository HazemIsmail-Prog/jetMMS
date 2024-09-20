<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.documents') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>

        </div>
    </x-slot>

    @can('create', App\Models\Document::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showDocumentFormModal')">
                {{ __('messages.add_document') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->documents->total() }}
        </span>
    @endteleport

    @if ($this->documents->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->documents->links() }}</div>
        @endteleport
    @endif

    @livewire('documents.document-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.document_type_id') }}</x-th>
                <x-th>{{ __('messages.document_number') }}</x-th>
                <x-th>{{ __('messages.document_serial_from') }}</x-th>
                <x-th>{{ __('messages.document_serial_to') }}</x-th>
                <x-th>{{ __('messages.document_pages') }}</x-th>
                <x-th>{{ __('messages.receiver_id') }}</x-th>
                <x-th>{{ __('messages.status') }}</x-th>
                <x-th>{{ __('messages.receiving_date') }}</x-th>
                <x-th>{{ __('messages.back_date') }}</x-th>
                <x-th>{{ __('messages.notes') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->documents as $document)
                <x-tr>
                    <x-td>{{ $document->documentType->name }}</x-td>
                    <x-td>{{ $document->document_number }}</x-td>
                    <x-td>{{ $document->document_serial_from }}</x-td>
                    <x-td>{{ $document->document_serial_to }}</x-td>
                    <x-td>{{ $document->document_pages }}</x-td>
                    <x-td>{{ $document->receiver->name }}</x-td>
                    <x-td>{{ __('messages.' . $document->status) }}</x-td>
                    <x-td>{{ $document->receiving_date }}</x-td>
                    <x-td>{{ $document->back_date }}</x-td>
                    <x-td>{{ $document->notes }}</x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $document)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showDocumentFormModal',{document:{{ $document }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAnyAttachment', $document)
                                <x-badgeWithCounter :counter="$document->attachments_count" title="{{ __('messages.attachments') }}"
                                    wire:click="$dispatch('showAttachmentModal',{model:'Document',id:{{ $document->id }}})">
                                    <x-svgs.attachment class="w-4 h-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $document)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $document }})">
                                    <x-svgs.trash class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan
                        </div>
                    </x-td>
                </x-tr>
            @endforeach
        </tbody>
    </x-table>
</div>
