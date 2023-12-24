<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="md" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
            </x-slot>

            <x-slot name="content">
                <form wire:submit="save">

                    <div class=" space-y-3">
                        <div>
                            <x-label for="start_date">{{ __('messages.start_date') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.start_date" autocomplete="off"
                                type="date" id="start_date" />
                            <x-input-error for="form.start_date" />
                        </div>
                        <div>
                            <x-label for="end_date">{{ __('messages.end_date') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.end_date" autocomplete="off"
                                type="date" id="end_date" />
                            <x-input-error for="form.end_date" />
                        </div>
                        <div>
                            <x-label for="type">{{ __('messages.type') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.type" autocomplete="off"
                                type="text" id="type" />
                            <x-input-error for="form.type" />
                        </div>
                        <div>
                            <x-label for="status">{{ __('messages.status') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.status" autocomplete="off"
                                type="text" id="status" />
                            <x-input-error for="form.status" />
                        </div>
                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.notes" autocomplete="off"
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
