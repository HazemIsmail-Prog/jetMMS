<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="md" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border/>
            </x-slot>

            <x-slot name="content">
                <form wire:submit="save">

                    <div class=" space-y-3">
                        <div>
                            <x-label for="increase_date">{{ __('messages.increase_date') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.increase_date" autocomplete="off"
                                type="date" id="increase_date" />
                            <x-input-error for="form.increase_date" />
                        </div>
                        <div>
                            <x-label for="amount">{{ __('messages.amount') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.amount" autocomplete="off"
                                type="number" id="amount" />
                            <x-input-error for="form.amount" />
                        </div>
                        <div>
                            <x-label for="type">{{ __('messages.type') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.type" autocomplete="off"
                                type="text" id="type" />
                            <x-input-error for="form.type" />
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
    @endif
</div>
