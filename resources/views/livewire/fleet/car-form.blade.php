<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $car->id ? __('messages.edit_car') : __('messages.add_car') }}
            </h2>
        </div>
    </x-slot>


    <form wire:submit="save" class=" space-y-4">



        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- Company Data --}}
            <div class=" space-y-4 border p-4 rounded-lg border-gray-300 dark:border-gray-700">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('messages.company_data') }}
                </h2>
                <x-section-border />

                <div class="flex flex-col">
                    <x-label for="code">{{ __('messages.car_code') }}</x-label>
                    <x-input type="number" wire:model="form.code" id="code" />
                    <x-input-error for="form.code" />
                </div>

                <div class="flex flex-col">
                    <x-label for="company_id">{{ __('messages.company') }}</x-label>
                    <x-select wire:model="form.company_id" id="company_id">
                        <option value="">---</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name_ar }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="form.company_id" />
                </div>

                <div class="flex flex-col">
                    <x-label for="management_no">{{ __('messages.management_no') }}</x-label>
                    <x-input type="number" wire:model="form.management_no" id="management_no" />
                    <x-input-error for="form.management_no" />
                </div>

                <div class="flex flex-col">
                    <x-label for="plate_no">{{ __('messages.plate_no') }}</x-label>
                    <x-input type="number" wire:model="form.plate_no" id="plate_no" />
                    <x-input-error for="form.plate_no" />
                </div>

                <div class="flex flex-col">
                    <x-label for="insurance_expiration_date">{{ __('messages.insurance_expiration_date') }}</x-label>
                    <x-input type="date" wire:model="form.insurance_expiration_date"
                        id="insurance_expiration_date" />
                    <x-input-error for="form.insurance_expiration_date" />
                </div>

                <div class="flex flex-col">
                    <x-label for="adv_expiration_date">{{ __('messages.adv_expiration_date') }}</x-label>
                    <x-input type="date" wire:model="form.adv_expiration_date" id="adv_expiration_date" />
                    <x-input-error for="form.adv_expiration_date" />
                </div>
            </div>

            {{-- Manufacture Data --}}
            <div class=" space-y-4 border p-4 rounded-lg border-gray-300 dark:border-gray-700">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('messages.manufacture_data') }}
                </h2>
                <x-section-border />

                <div class="flex flex-col">
                    <x-label for="car_brand_id">{{ __('messages.brand') }}</x-label>
                    <x-select wire:model="form.car_brand_id" id="car_brand_id">
                        <option value="">---</option>
                        @foreach ($car_brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name_ar }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="form.car_brand_id" />
                </div>

                <div class="flex flex-col">
                    <x-label for="car_type_id">{{ __('messages.type') }}</x-label>
                    <x-select wire:model="form.car_type_id" id="car_type_id">
                        <option value="">---</option>
                        @foreach ($car_types as $type)
                            <option value="{{ $type->id }}">{{ $type->name_ar }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="form.car_type_id" />
                </div>

                <div class="flex flex-col">
                    <x-label for="year">{{ __('messages.year') }}</x-label>
                    <x-input type="number" wire:model="form.year" id="year" />
                    <x-input-error for="form.year" />
                </div>

                <div class="flex flex-col">
                    <x-label for="passengers_no">{{ __('messages.passengers_no') }}</x-label>
                    <x-input type="number" wire:model="form.passengers_no" id="passengers_no" />
                    <x-input-error for="form.passengers_no" />
                </div>

            </div>

            {{-- Driver Data --}}
            <div class=" space-y-4 border p-4 rounded-lg border-gray-300 dark:border-gray-700">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('messages.driver_data') }}
                </h2>
                <x-section-border />

                <div class="flex flex-col">
                    <x-label for="driver_id">{{ __('messages.driver') }}</x-label>
                    <x-select wire:model="form.driver_id" id="driver_id">
                        <option value="">---</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name_ar }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="form.driver_id" />
                </div>

                <div class="flex flex-col">
                    <x-label for="technician_id">{{ __('messages.technician') }}</x-label>
                    <x-select wire:model="form.technician_id" id="technician_id">
                        <option value="">---</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name_ar }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error for="form.technician_id" />
                </div>

            </div>

            <div class=" col-span-full">
                <x-section-border />
            </div>

            <div class="flex flex-col col-span-full">
                <x-label for="notes">{{ __('messages.notes') }}</x-label>
                <x-input type="text" wire:model="form.notes" id="notes" />
                <x-input-error for="form.notes" />
            </div>

            <div class=" col-span-full">
                <x-label for="has_installment" class="flex items-center">
                    <x-checkbox wire:model.live="form.has_installment" id="has_installment" />
                    <span class="ms-2 ">{{ __('messages.has_installment') }}</span>
                </x-label>
            </div>

            @if ($form->has_installment)
                <div class="flex flex-col">
                    <x-label for="installment_company">{{ __('messages.installment_company') }}</x-label>
                    <x-input type="text" wire:model="form.installment_company" id="installment_company" />
                    <x-input-error for="form.installment_company" />
                </div>
            @endif

            <div class=" col-span-full">
                <x-label for="active" class="flex items-center">
                    <x-checkbox wire:model="form.active" id="active" />
                    <span class="ms-2 ">{{ __('messages.active') }}</span>
                </x-label>
            </div>

        </div>

        <div class="text-end">
            <x-secondary-anchor href="{{ route('car.index') }}">{{ __('messages.back') }}</x-secondary-anchor>
            <x-button>{{ __('messages.save') }}</x-button>
        </div>

    </form>
</div>
