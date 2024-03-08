<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ __('messages.add_car_action') }}</div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                <form wire:submit="save">
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
                            <x-searchable-select id="from" :list="$this->users" model="form.from_id" live />
                            <x-input-error for="form.from_id" />
                        </div>

                        <div>
                            <x-label for="to">{{ __('messages.to') }}</x-label>
                            <x-searchable-select id="to" :list="$this->users" model="form.to_id" live />
                            <x-input-error for="form.to_id" />
                        </div>

                        <div>
                            <x-label for="kilos">{{ __('messages.kilos') }}</x-label>
                            <x-input id="kilos" wire:model="form.kilos" type="number" class="w-full" />
                            <x-input-error for="form.kilos" />
                        </div>

                        <div>
                            <x-label for="fuel">{{ __('messages.fuel') }}</x-label>
                            <x-select id="fuel" wire:model="form.fuel" class="w-full">
                                <option value="0">{{ __('messages.empty') }}</option>
                                <option value="1">1/4</option>
                                <option value="2">1/2</option>
                                <option value="3">3/4</option>
                                <option value="4">{{ __('messages.full') }}</option>
                            </x-select>
                            <x-input-error for="form.fuel" />
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
