<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="md" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border />
            </x-slot>

            <x-slot name="content">
                <form wire:submit="save">

                    <div class=" space-y-3">
                        <div>
                            <x-label for="name">{{ __('messages.customer_name') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.name" autocomplete="off" type="text"
                                id="name" />
                            <x-input-error for="form.name" />
                        </div>
                        <div>
                            <x-label for="phone">{{ __('messages.phone') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.phone" autocomplete="off"
                                type="number" id="phone" />
                            <x-input-error for="form.phone_id" />
                        </div>
                        <div>
                            <x-label for="address">{{ __('messages.address') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.address" autocomplete="off" type="text"
                                id="address" />
                            <x-input-error for="form.address" />
                        </div>
                        <div>
                            <x-label for="type">{{ __('messages.type') }}</x-label>
                            <x-select required class="w-full py-0" wire:model="form.type" autocomplete="off"
                                type="text" id="type">
                                <option value="">---</option>
                                <option value="marketing">{{ __('messages.marketing') }}</option>
                                <option value="information">{{ __('messages.information') }}</option>
                                <option value="service_not_available">{{ __('messages.service_not_available') }}
                                </option>
                            </x-select>
                            <x-input-error for="form.type" />
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
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
