<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ __('messages.add_car_action') }}</div>
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
                            <x-label for="time">{{ __('messages.time') }}</x-label>
                            <x-input id="time" wire:model="form.time" type="time" class="w-full" />
                            <x-input-error for="form.time" />
                        </div>

                        <div>
                            <x-label for="from">{{ __('messages.from') }}</x-label>
                            <x-searchable-select id="from" :list="$this->users" wire:model.live="form.from_id" />
                            <x-input-error for="form.from_id" />
                        </div>

                        <div>
                            <x-label for="to">{{ __('messages.to') }}</x-label>
                            <x-searchable-select id="to" :list="$this->users" wire:model.live="form.to_id" />
                            <x-input-error for="form.to_id" />
                        </div>

                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input id="notes" wire:model="form.notes" type="text" class="w-full" />
                            <x-input-error for="form.notes" />
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
