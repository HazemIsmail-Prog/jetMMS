<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <div class=" text-sm">{{ @$car->driver->name }}</div>
                <x-section-border />
            </x-slot>


            <x-slot name="content">

                <div class="mt-4 space-y-4" {{-- x-data="{}"
                    x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)" --}}>


                    <div>
                        <x-label for="date">{{ __('messages.date') }}</x-label>
                        <x-input id="date" wire:model="date" type="date" class="w-full" />
                        <x-input-error for="date" />
                    </div>

                    <div>
                        <x-label for="time">{{ __('messages.time') }}</x-label>
                        <x-input id="time" wire:model="time" type="time" class="w-full" />
                        <x-input-error for="time" />
                    </div>

                    @if (!@$car->driver_id)
                        <div>
                            <x-label for="driver_id">{{ __('messages.driver') }}</x-label>
                            <x-select id="driver_id" wire:model="driver_id" class="w-full">
                                <option value="">---</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="driver_id" />
                        </div>
                    @endif

                    <div>
                        <x-label for="kilos">{{ __('messages.kilos') }}</x-label>
                        <x-input id="kilos" wire:model="kilos" type="number" class="w-full" />
                        <x-input-error for="kilos" />
                    </div>

                    <div>
                        <x-label for="fuel">{{ __('messages.fuel') }}</x-label>
                        <x-select id="fuel" wire:model="fuel" class="w-full">
                            <option value="0">{{ __('messages.empty') }}</option>
                            <option value="1">1/4</option>
                            <option value="2">1/2</option>
                            <option value="3">3/4</option>
                            <option value="4">{{ __('messages.full') }}</option>
                        </x-select>
                        <x-input-error for="fuel" />
                    </div>

                    <div>
                        <x-label for="notes">{{ __('messages.notes') }}</x-label>
                        <x-input id="notes" wire:model="notes" type="text" class="w-full" />
                        <x-input-error for="notes" />
                    </div>

                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                    {{ __('messages.cancel') }}
                </x-secondary-button>

                <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                    {{ __('messages.save') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
