<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ __('messages.add_car_service') }}</div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                <form wire:submit.prevent="save" wire:loading.class="opacity-50">
                    <div class=" space-y-3">

                        <div>
                            <x-label for="date">{{ __('messages.date') }}</x-label>
                            <x-input id="date" wire:model="form.date" type="date" class="w-full" />
                            <x-input-error for="form.date" />
                        </div>

                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input id="notes" wire:model="form.notes" type="text" class="w-full" />
                            <x-input-error for="form.notes" />
                        </div>

                        <div>
                            <x-label for="cost">{{ __('messages.cost') }}</x-label>
                            <x-input id="cost" wire:model="form.cost" type="number" step="0.001" class="w-full" />
                            <x-input-error for="form.cost" />
                        </div>

                        <div class="mt-3">
                            <x-button>{{ __('messages.save') }}</x-button>
                        </div>

                    </div>
                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
