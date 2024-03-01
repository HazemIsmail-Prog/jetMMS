<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit="save">
                <div class=" space-y-3">
                    <div>
                        <x-label for="name_ar">{{ __('messages.name_ar') }}</x-label>
                        <x-input required class="w-full py-0" wire:model="form.name_ar" autocomplete="off" type="text"
                            id="name_ar" />
                        <x-input-error for="form.name_ar" />
                    </div>
                    <div>
                        <x-label for="name_en">{{ __('messages.name_en') }}</x-label>
                        <x-input required class="w-full py-0" wire:model="form.name_en" autocomplete="off"
                            type="text" id="name_en" />
                        <x-input-error for="form.name_en" />
                    </div>
                </div>
                <div class="mt-3">
                    <x-button>{{ __('messages.save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-dialog-modal>
</div>
