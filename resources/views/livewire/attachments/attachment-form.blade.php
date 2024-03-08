<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                <form wire:submit="save">
                    <div class=" space-y-3">

                        <div>
                            <x-label for="description_ar">{{ __('messages.description_ar') }}</x-label>
                            <x-input required class="w-full" type="text" wire:model="form.description_ar"
                                id="description_ar" dir="rtl" />
                            <x-input-error for="form.description_ar" />
                        </div>

                        <div>
                            <x-label for="description_en">{{ __('messages.description_en') }}</x-label>
                            <x-input required class="w-full" type="text" wire:model="form.description_en"
                                id="description_en" dir="ltr" />
                            <x-input-error for="form.description_en" />
                        </div>

                        <div>
                            <label for="form.file" @class([
                                'flex flex-col items-center justify-center w-full border-2  border-dashed rounded-lg cursor-pointer border-gray-300 bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600' => !$attachment[
                                    'file'
                                ],
                                'flex flex-col items-center justify-center w-full border-2  border-dashed rounded-lg cursor-pointer border-green-300 bg-green-50 dark:hover:bg-bray-800 dark:bg-green-700 hover:bg-green-100 dark:border-green-600 dark:hover:border-green-500 dark:hover:bg-green-600' =>
                                    $form->file,
                            ])>
                                <div class="flex flex-col items-center justify-center w-full p-1 ">
                                    <p @class([
                                        'text-sm text-gray-500 dark:text-gray-400 font-semibold' => !$form->file,
                                        'text-sm text-green-500 dark:text-green-400 font-semibold' => $form->file,
                                    ])>

                                        {{ $form->file ? __('messages.upload_done') : __('messages.select_file') }}
                                    </p>
                                </div>
                                <input id="form.file" type="file" class="hidden" wire:model="form.file" />
                            </label>
                            <x-input-error for="form.file" />
                        </div>

                        <div>
                            <x-label for="expirationDate">{{ __('messages.expirationDate') }}</x-label>
                            <x-input class="w-full" type="date" wire:model="form.expirationDate"
                                id="expirationDate" />
                            <x-input-error for="form.expirationDate" />
                        </div>

                        <div class="flex flex-none gap-2 items-center">
                            <x-checkbox wire:model.live="form.alertable" id="alertable" />
                            <x-label for="alertable">{{ __('messages.alertable') }}</x-label>
                        </div>

                        @if ($form->alertable)
                            <div>
                                <x-label for="alertBefore">{{ __('messages.alertBefore') }}</x-label>
                                <x-input class="w-full" type="date" type="number" min="1"
                                    dir="ltr" wire:model="form.alertBefore" id="alertBefore" />
                                <x-input-error for="form.alertBefore" />
                            </div>
                        @endif

                        <div class="mt-3">
                            <x-button>{{ __('messages.save') }}</x-button>
                        </div>

                    </div>
                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>