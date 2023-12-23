<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="2xl" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border />
            </x-slot>

            <x-slot name="content">

                @if (!$showForm)
                    <x-button wire:click="show_form">{{ __('messages.add_attachment') }}</x-button>
                @endif

                {{-- Attachment Form --}}
                @if ($showForm)
                    <form wire:submit="save">
                        <div class=" grid grid-cols-3 items-center gap-2">
                            <div class="flex flex-col flex-1">
                                <x-input required class="p-1" type="text"
                                    placeholder="{{ __('messages.description_ar') }}"
                                    wire:model="attachment.description_ar" id="description_ar" dir="rtl" />
                                <x-input-error for="attachment.description_ar" />
                            </div>
                            <div class="flex flex-col flex-1">
                                <x-input required class="p-1" type="text"
                                    placeholder="{{ __('messages.description_en') }}"
                                    wire:model="attachment.description_en" id="description_en" dir="ltr" />
                                <x-input-error for="attachment.description_en" />
                            </div>

                            <div class="flex flex-col flex-1">
                                <label for="attachment.file" @class([
                                    'flex flex-col items-center justify-center w-full border-2  border-dashed rounded-lg cursor-pointer border-gray-300 bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600' => !$attachment[
                                        'file'
                                    ],
                                    'flex flex-col items-center justify-center w-full border-2  border-dashed rounded-lg cursor-pointer border-green-300 bg-green-50 dark:hover:bg-bray-800 dark:bg-green-700 hover:bg-green-100 dark:border-green-600 dark:hover:border-green-500 dark:hover:bg-green-600' =>
                                        $attachment['file'],
                                ])>
                                    <div class="flex flex-col items-center justify-center p-1 ">
                                        <p @class([
                                            'text-sm text-gray-500 dark:text-gray-400 font-semibold' => !$attachment[
                                                'file'
                                            ],
                                            'text-sm text-green-500 dark:text-green-400 font-semibold' =>
                                                $attachment['file'],
                                        ])>

                                            {{ $attachment['file'] ? __('messages.upload_done') : __('messages.select_file') }}
                                        </p>
                                    </div>
                                    <input id="attachment.file" type="file" class="hidden"
                                        wire:model="attachment.file" />
                                </label>
                                <x-input-error for="attachment.file" />
                            </div>


                            <div class="flex flex-col">
                                <x-label for="expirationDate">{{ __('messages.expirationDate') }}</x-label>
                                <x-input class="p-1" type="date" wire:model="attachment.expirationDate"
                                    id="expirationDate" />
                                <x-input-error for="attachment.expirationDate" />
                            </div>
                            <div class="flex flex-none flex-col">
                                <div class="flex flex-none gap-2 items-center">
                                    <x-checkbox wire:model.live="attachment.alertable" id="alertable" />
                                    <x-label for="alertable">{{ __('messages.alertable') }}</x-label>
                                </div>
                                @if ($attachment['alertable'])
                                    <div x-show="show" class="flex flex-col flex-auto">
                                        <x-input class="p-1" type="number" wire:model="attachment.alertBefore"
                                            id="alertBefore" placeholder="{{ __('messages.alertBefore') }}"
                                            dir="ltr" />
                                        <x-input-error for="attachment.alertBefore" />
                                    </div>
                                @endif
                            </div>
                            <div class=" col-span-3">
                                <x-button wire:loading.attr="disabled">{{ __('messages.save') }}</x-button>
                                <x-secondary-button wire:click="$set('showForm',false)"
                                    type="button">{{ __('messages.cancel') }}</x-secondary-button>
                            </div>
                        </div>
                    </form>
                @endif

                <x-section-border />

                {{-- Attachments Table --}}
                @if ($currentRecord)
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-1 whitespace-nowrap text-start">
                                    {{ __('messages.description') }}</th>
                                <th scope="col" class="px-6 py-1 whitespace-nowrap text-center">
                                    {{ __('messages.expirationDate') }}</th>
                                <th scope="col" class="px-6 py-1 whitespace-nowrap text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->attachments as $attachment)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $attachment->description }}
                                    </th>
                                    <td class="px-6 py-1 text-center whitespace-nowrap">
                                        {{ $attachment->expirationDate?->format('d-m-Y') }}
                                    </td>
                                    <td class="px-6 py-1 text-end whitespace-nowrap">
                                        <div class=" flex items-center justify-end gap-2">
                                            <a target="__blank" href="{{ $attachment->full_path }}">
                                                <x-svgs.view class="w-6 h-6" />
                                            </a>
                                            <x-svgs.edit wire:click="edit({{ $attachment }})" class="w-6 h-6" />
                                            <x-svgs.trash wire:confirm="{{ __('messages.are_u_sure') }}"
                                                wire:click="delete({{ $attachment }})" class="w-6 h-6" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </x-slot>

        </x-dialog-modal>
    @endif
</div>
