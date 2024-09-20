<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.document_types') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>

    @can('create', App\Models\DocumentType::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showDocumentTypeFormModal')">
                {{ __('messages.add_document_type') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->documentTypes->total() }}
        </span>
    @endteleport

    @if ($this->documentTypes->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->departments->links() }}</div>
        @endteleport
    @endif

    @livewire('documents.document-type-form')

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->documentTypes as $documentType)
                <x-tr>
                    <x-td>{{ $documentType->name }}</x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $documentType)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showDocumentTypeFormModal',{documentType:{{ $documentType }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $documentType)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $documentType }})">
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
