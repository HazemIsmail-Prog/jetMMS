<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal" :dismissible="false">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal && $customer)
            <div class=" flex items-center justify-between">
                <h2
                    class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $customer->name }}
                </h2>
            </div>
            <x-section-border />


            <form wire:submit.prevent="save" wire:loading.class="opacity-50">

                {{-- Customer Data --}}
                <div class=" space-y-3">
                    <div>
                        <x-label for="address">{{ __('messages.address') }}</x-label>
                        <x-select class="w-full py-0" id="address" wire:model="form.address_id">
                            @if ($customer->addresses->count() > 1)
                            <option selected value="">---</option>
                            @endif
                            @foreach ($customer->addresses as $address)
                            <option value="{{ $address->id }}">
                                {{ $address->full_address() }}
                            </option>
                            @endforeach
                        </x-select>
                        <x-input-error for="form.address_id" />
                    </div>
                </div>

                <div class="space-y-3 mt-3">

                    <div>
                        <x-label for="contract_type">{{ __('messages.contract_type') }}</x-label>
                        <x-select class="w-full py-0" id="contract_type" wire:model="form.contract_type">
                            <option selected value="">---</option>
                            <option value="subscription">{{ __('messages.subscription') }}</option>
                            <option value="construction">{{ __('messages.construction') }}</option>
                        </x-select>
                        <x-input-error for="form.contract_type" />
                    </div>

                    <div>
                        <x-label for="building_type">{{ __('messages.building_type') }}</x-label>
                        <x-select class="w-full py-0" id="building_type" wire:model="form.building_type">
                            <option selected value="">---</option>
                            <option value="residential">{{ __('messages.residential') }}</option>
                            <option value="commercial">{{ __('messages.commercial') }}</option>
                        </x-select>
                        <x-input-error for="form.building_type" />
                    </div>

                    <div>
                        <x-label for="contract_number">{{ __('messages.contract_number') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.contract_number" autocomplete="off" type="text"
                            id="contract_number" />
                        <x-input-error for="form.contract_number" />
                    </div>

                    <div>
                        <x-label for="contract_date">{{ __('messages.contract_date') }}</x-label>
                        <x-input class="w-full py-0" wire:model="form.contract_date" autocomplete="off" type="date"
                            id="contract_date" />
                        <x-input-error for="form.contract_date" />
                    </div>

                    <div>
                        <x-label for="contract_duration">{{ __('messages.contract_duration') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.contract_duration" autocomplete="off"
                            type="number" id="contract_duration" />
                        <x-input-error for="form.contract_duration" />
                    </div>

                    <div>
                        <x-label for="contract_value">{{ __('messages.contract_value') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.contract_value" autocomplete="off" type="number"
                            step='0.001' id="contract_value" />
                        <x-input-error for="form.contract_value" />
                    </div>

                    <div>
                        <x-label for="units_count">{{ __('messages.units_count') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.units_count" autocomplete="off"
                            type="number" id="units_count" />
                        <x-input-error for="form.units_count" />
                    </div>

                    <div>
                        <x-label for="central_count">{{ __('messages.central_count') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.central_count" autocomplete="off"
                            type="number" id="central_count" />
                        <x-input-error for="form.central_count" />
                    </div>



                    <div>
                        <x-label for="collected_amount">{{ __('messages.collected_amount') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.collected_amount" autocomplete="off" type="number"
                            step='0.001' id="collected_amount" />
                        <x-input-error for="form.collected_amount" />
                    </div>

                    <div>
                        <x-label for="contract_expiration_date">{{ __('messages.contract_expiration_date') }}</x-label>
                        <x-input class="w-full py-0" wire:model="form.contract_expiration_date" autocomplete="off"
                            type="date" id="contract_expiration_date" />
                        <x-input-error for="form.contract_expiration_date" />
                    </div>

                    <div>
                        <x-label for="notes">{{ __('messages.notes') }}</x-label>
                        <x-input class="w-full py-0" wire:model="form.notes" autocomplete="off" type="text"
                            id="notes" />
                        <x-input-error for="form.notes" />
                    </div>

                    <div>
                        <x-label for="sp_included" class="flex items-center">
                            <x-checkbox wire:model="form.sp_included" id="sp_included" />
                            <span class="ms-2 ">{{ __('messages.sp_included') }}</span>
                        </x-label>
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
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
