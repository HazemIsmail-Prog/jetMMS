<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" wire:loading.class="opacity-50">
                <div class=" space-y-3">
                    <div>
                        <x-label for="company_id">{{ __('messages.company') }}</x-label>
                        <x-searchable-select id="company_id" :list="$this->companies" wire:model="form.company_id" />
                        <x-input-error for="form.company_id" />
                    </div>
                    <div>
                        <x-label for="year">{{ __('messages.year') }}</x-label>
                        <x-input required class="w-full py-0" wire:model="form.year" autocomplete="off" type="number"
                            id="year" />
                        <x-input-error for="form.year" />
                    </div>
                    <div>
                        <x-label for="description">{{ __('messages.description') }}</x-label>
                        <x-input class="w-full py-0" wire:model="form.description" autocomplete="off"
                            type="text" id="description" />
                        <x-input-error for="form.description" />
                    </div>
                    <div>
                        <x-label for="notes">{{ __('messages.notes') }}</x-label>
                        <x-input class="w-full py-0" wire:model="form.notes" autocomplete="off"
                            type="text" id="notes" />
                        <x-input-error for="form.notes" />
                    </div>
                </div>
                <div class="mt-3">
                    <x-button>{{ __('messages.save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-dialog-modal>
</div>
