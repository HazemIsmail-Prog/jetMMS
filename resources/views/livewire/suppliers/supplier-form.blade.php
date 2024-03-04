<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border/>
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
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
                            <x-label for="account_id">{{ __('messages.account') }}</x-label>
                            <x-searchable-select position="relative" :list="$this->accounts" model="form.account_id" />
                            <x-input-error for="form.account_id" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <x-button>{{ __('messages.save') }}</x-button>
                    </div>
                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
