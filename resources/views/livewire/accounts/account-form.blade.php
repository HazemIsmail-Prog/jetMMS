<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="md" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border />
            </x-slot>

            <x-slot name="content">
                <form wire:submit="save">

                    <div class=" space-y-3">
                        <div>
                            <x-label for="name_ar">{{ __('messages.name_ar') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.name_ar" autocomplete="off"
                                type="text" id="name_ar" />
                            <x-input-error for="form.name_ar" />
                        </div>
                        <div>
                            <x-label for="name_en">{{ __('messages.name_en') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.name_en" autocomplete="off"
                                type="text" id="name_en" />
                            <x-input-error for="form.name_en" />
                        </div>
                        <div>
                            <x-label for="type">{{ __('messages.type') }}</x-label>
                            <x-select required disabled class="w-full py-0" wire:model="form.type" id="type">
                                <option value="">---</option>
                                <option value="debit">{{ __('messages.debit') }}</option>
                                <option value="credit">{{ __('messages.credit') }}</option>
                            </x-select>
                            <x-input-error for="form.type" />
                        </div>
                        <div>
                            <x-label for="active" class="flex items-center">
                                <x-checkbox wire:model="form.active" id="active" />
                                <span class="ms-2 ">{{ __('messages.active') }}</span>
                            </x-label>
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-button>{{ __('messages.save') }}</x-button>
                    </div>

                </form>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
