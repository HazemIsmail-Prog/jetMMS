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
                        <x-label for="client_name">{{ __('messages.client_name') }}</x-label>
                        <x-input required class="w-full py-0" wire:model="form.client_name" autocomplete="off" type="text"
                            id="client_name" />
                        <x-input-error for="form.client_name" />
                    </div>
                    <div>
                        <x-label for="initiation_date">{{ __('messages.initiation_date') }}</x-label>
                        <x-input class="w-full py-0" wire:model="form.initiation_date" autocomplete="off" type="date"
                            id="initiation_date" />
                        <x-input-error for="form.initiation_date" />
                    </div>
                    <div>
                        <x-label for="expiration_date">{{ __('messages.expiration_date') }}</x-label>
                        <x-input class="w-full py-0" wire:model="form.expiration_date" autocomplete="off" type="date"
                            id="expiration_date" />
                        <x-input-error for="form.expiration_date" />
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
</div>
