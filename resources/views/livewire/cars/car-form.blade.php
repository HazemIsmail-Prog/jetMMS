<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
                <form wire:submit="save">
                    <div 
                        x-data="{
                            has_installment : @entangle('form.has_installment'),
                            installment_company : @entangle('form.installment_company')
                        }"
                        class=" space-y-3">
                        <div>
                            <x-label for="code">{{ __('messages.car_code') }}</x-label>
                            <x-input class="w-full py-0" type="number" wire:model="form.code" id="code" />
                            <x-input-error for="form.code" />
                        </div>

                        <div>
                            <x-label for="company_id">{{ __('messages.company') }}</x-label>
                            <x-select class="w-full py-0" wire:model="form.company_id" id="company_id">
                                <option value="">---</option>
                                @foreach ($this->companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name_ar }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="form.company_id" />
                        </div>

                        <div>
                            <x-label for="management_no">{{ __('messages.management_no') }}</x-label>
                            <x-input class="w-full py-0" type="number" wire:model="form.management_no" id="management_no" />
                            <x-input-error for="form.management_no" />
                        </div>

                        <div>
                            <x-label for="plate_no">{{ __('messages.plate_no') }}</x-label>
                            <x-input class="w-full py-0" type="number" wire:model="form.plate_no" id="plate_no" />
                            <x-input-error for="form.plate_no" />
                        </div>

                        <div>
                            <x-label
                                for="insurance_expiration_date">{{ __('messages.insurance_expiration_date') }}</x-label>
                            <x-input class="w-full py-0" type="date" wire:model="form.insurance_expiration_date"
                                id="insurance_expiration_date" />
                            <x-input-error for="form.insurance_expiration_date" />
                        </div>

                        <div>
                            <x-label for="adv_expiration_date">{{ __('messages.adv_expiration_date') }}</x-label>
                            <x-input class="w-full py-0" type="date" wire:model="form.adv_expiration_date" id="adv_expiration_date" />
                            <x-input-error for="form.adv_expiration_date" />
                        </div>

                        <div>
                            <x-label for="car_brand_id">{{ __('messages.brand') }}</x-label>
                            <x-select class="w-full py-0" wire:model="form.car_brand_id" id="car_brand_id">
                                <option value="">---</option>
                                @foreach ($this->car_brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name_ar }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="form.car_brand_id" />
                        </div>

                        <div>
                            <x-label for="car_type_id">{{ __('messages.type') }}</x-label>
                            <x-select class="w-full py-0" wire:model="form.car_type_id" id="car_type_id">
                                <option value="">---</option>
                                @foreach ($this->car_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name_ar }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error for="form.car_type_id" />
                        </div>

                        <div>
                            <x-label for="year">{{ __('messages.year') }}</x-label>
                            <x-input class="w-full py-0" type="number" wire:model="form.year" id="year" />
                            <x-input-error for="form.year" />
                        </div>

                        <div>
                            <x-label for="passengers_no">{{ __('messages.passengers_no') }}</x-label>
                            <x-input class="w-full py-0" type="number" wire:model="form.passengers_no" id="passengers_no" />
                            <x-input-error for="form.passengers_no" />
                        </div>

                        <div>
                            <x-label for="fuel_card_serial">{{ __('messages.fuel_card_serial') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.fuel_card_serial" id="fuel_card_serial" />
                            <x-input-error for="form.fuel_card_serial" />
                        </div>
                        <div>
                            <x-label for="fuel_card_number">{{ __('messages.fuel_card_number') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.fuel_card_number" id="fuel_card_number" />
                            <x-input-error for="form.fuel_card_number" />
                        </div>
                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.notes" id="notes" />
                            <x-input-error for="form.notes" />
                        </div>

                        <div>
                            <x-label for="has_installment" class="flex items-center">
                                <x-checkbox @change="installment_company = null" x-model="has_installment" id="has_installment" />
                                <span class="ms-2 ">{{ __('messages.has_installment') }}</span>
                            </x-label>
                        </div>

                        <div x-show="has_installment">
                            <x-label for="installment_company">{{ __('messages.installment_company') }}</x-label>
                            <x-input class="w-full py-0" type="text" x-model="installment_company" id="installment_company" />
                            <x-input-error for="form.installment_company" />
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
