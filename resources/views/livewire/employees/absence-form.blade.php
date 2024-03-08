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
                            <x-label for="deduction_days">{{ __('messages.deduction_days') }}</x-label>
                            <x-input class="w-full py-0" wire:model.live="form.deduction_days" autocomplete="off"
                                type="text" id="deduction_days" />
                            <x-input-error for="form.deduction_days" />
                        </div>

                        <div>
                            <x-label for="deduction_amount">{{ __('messages.deduction_amount') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.deduction_amount" autocomplete="off"
                                type="text" id="deduction_amount" />
                            <x-input-error for="form.deduction_amount" />
                        </div>

                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.notes" autocomplete="off" type="text"
                                id="notes" />
                            <x-input-error for="form.notes" />
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
