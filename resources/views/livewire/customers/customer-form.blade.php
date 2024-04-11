<div>
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
                <form wire:submit.prevent="save" wire:loading.class="opacity-50">
                    <div class=" space-y-3">
                        <div class=" flex items-start gap-2">
                            <div class="flex flex-col">
                                <x-label for="name">{{ __('messages.name') }}</x-label>
                                <x-input type="text" wire:model="form.name" id="name" dir="rtl" />
                                <x-input-error for="form.name" />
                            </div>

                            <div class="flex flex-col">
                                <x-label for="notes">{{ __('messages.notes') }}</x-label>
                                <x-input type="text" wire:model="form.notes" id="notes" dir="rtl" />
                                <x-input-error for="form.notes" />
                            </div>
                        </div>

                        <div class=" p-4 flex flex-col gap-2 items-start border dark:border-gray-700 rounded-lg">
                            <x-label>{{ __('messages.phone') }}</x-label>
                            @foreach ($form->phones as $index => $phone)
                                <div class=" flex items-center gap-2">
                                    <div>
                                        <x-select wire:model="form.phones.{{ $index }}.type">
                                            <option value="mobile">{{ __('messages.mobile') }}</option>
                                            <option value="phone">{{ __('messages.phone') }}</option>
                                        </x-select>
                                        <x-input-error for="form.phones.{{ $index }}.type" />
                                    </div>
                                    <div>
                                        <x-input wire:model="form.phones.{{ $index }}.number" dir="ltr"
                                            type="number" />
                                        <x-input-error for="form.phones.{{ $index }}.number" />
                                    </div>

                                    @if ($index > 0)
                                        <x-svgs.trash class=" my-auto"
                                            wire:click="delete_row('phone',{{ $index }})"
                                            class="w-5 h-5 text-red-400 font-bold" />
                                    @endif

                                </div>
                            @endforeach
                            <x-button type="button"
                                wire:click="add_row('phone')">{{ __('messages.add phone') }}</x-button>
                        </div>

                        <div
                            class=" p-4 flex flex-col gap-2 items-start border dark:border-gray-700 rounded-lg overflow-x-auto">
                            <x-label>{{ __('messages.address') }}</x-label>
                            @foreach ($form->addresses as $index => $address)
                                <div class=" flex items-center gap-2">
                                    <div>
                                        <div class="w-64">
                                            <x-searchable-select :list="$this->areas" :title="__('messages.area')"
                                                wire:model="form.addresses.{{ $index }}.area_id" />
                                        </div>
                                        <x-input-error for="form.addresses.{{ $index }}.area_id" />
                                    </div>

                                    <div>
                                        <x-input wire:model="form.addresses.{{ $index }}.block"
                                            placeholder="{{ __('messages.block') }}" />
                                        <x-input-error for="form.addresses.{{ $index }}.block" />
                                    </div>

                                    <div>
                                        <x-input wire:model="form.addresses.{{ $index }}.street"
                                            placeholder="{{ __('messages.street') }}" />
                                        <x-input-error for="form.addresses.{{ $index }}.street" />
                                    </div>

                                    <x-input wire:model="form.addresses.{{ $index }}.jadda"
                                        placeholder="{{ __('messages.jadda') }}" />
                                    <x-input wire:model="form.addresses.{{ $index }}.building"
                                        placeholder="{{ __('messages.building') }}" />
                                    <x-input wire:model="form.addresses.{{ $index }}.floor"
                                        placeholder="{{ __('messages.floor') }}" />
                                    <x-input wire:model="form.addresses.{{ $index }}.apartment"
                                        placeholder="{{ __('messages.apartment') }}" />
                                    <x-input wire:model="form.addresses.{{ $index }}.notes"
                                        placeholder="{{ __('messages.notes') }}" />
                                    @if ($index > 0)
                                        <x-svgs.trash class=" my-auto"
                                            wire:click="delete_row('address',{{ $index }})"
                                            class="w-5 h-5 text-red-400 font-bold" />
                                    @endif
                                </div>
                            @endforeach
                            <x-button type="button"
                                wire:click="add_row('address')">{{ __('messages.add address') }}</x-button>
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
