<div>
    <x-dialog-modal maxWidth="2xl" wire:model.live="showModal">

        <x-slot name="title">
            <div class=" flex items-center justify-between">
                <div>{{ $modalTitle }}</div>
                @can('create','App\\Models\\'.$model)
                    <x-button type="button"
                        wire:click="$dispatch('showAttachmentFormModal',{model:'{{ $model }}',id:{{ $currentRecord?->id }}})">{{ __('messages.add_attachment') }}</x-button>                    
                @endcan
            </div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                @if ($currentRecord)
                    @if ($this->attachments->count() > 0)
                        @if ($showS3Error)
                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                role="alert">
                                {{ __('messages.something went wrong') }}
                            </div>
                        @endif

                        <x-table>
                            <x-thead>
                                <tr>
                                    <x-th>{{ __('messages.description') }}</x-th>
                                    <x-th>{{ __('messages.expirationDate') }}</x-th>
                                    <x-th></x-th>
                                </tr>
                            </x-thead>
                            <tbody>
                                @foreach ($this->attachments as $attachment)
                                    <x-tr>
                                        <x-td class=" !whitespace-normal">{{ $attachment->description }}</x-td>
                                        <x-td>{{ $attachment->expirationDate?->format('d-m-Y') }}</x-td>
                                        <x-td>
                                            <div class=" flex items-center justify-end gap-2">
                                                <a target="__blank" href="{{ $attachment->full_path }}">
                                                    <x-badgeWithCounter title="{{ __('messages.view') }}">
                                                        <x-svgs.view class="w-4 h-4" />
                                                    </x-badgeWithCounter>
                                                </a>
                                                @can('update',$currentRecord)
                                                    <x-badgeWithCounter
                                                        wire:click="$dispatch('showAttachmentFormModal',{attachment:{{ $attachment }}})"
                                                        title="{{ __('messages.edit') }}">
                                                        <x-svgs.edit class="w-4 h-4" />
                                                    </x-badgeWithCounter>
                                                @endcan
                                                @can('update',$currentRecord)
                                                    <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}"
                                                        wire:click="delete({{ $attachment }})"
                                                        title="{{ __('messages.delete') }}">
                                                        <x-svgs.trash class="w-4 h-4" />
                                                    </x-badgeWithCounter>
                                                @endcan
                                            </div>
                                        </x-td>
                                    </x-tr>
                                @endforeach
                            </tbody>
                        </x-table>
                        
                    @else
                        <x-label class=" text-center">{{ __('messages.no_attachments_found') }}</x-label>
                    @endif
                @endif
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
