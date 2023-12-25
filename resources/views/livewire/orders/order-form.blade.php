<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="md" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border />
            </x-slot>

            <x-slot name="content">
                @if ($customer)
                    <div class=" flex items-center justify-between">
                        <h2
                            class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                            {{ $customer->name }}
                        </h2>
                        {{-- @can('customers_edit') --}}
                        <a title="{{ __('messages.edit') }}" href="{{ route('customer.form', $customer) }}"
                            class="flex items-center gap-1 border dark:border-gray-700 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                            <x-svgs.edit class="w-4 h-4" />
                        </a>
                        {{-- @endcan --}}
                    </div>
                    <x-section-border />


                    <form wire:submit="save">

                        {{-- Customer Data --}}
                        <div class=" space-y-3">
                            <div>
                                <x-label for="phone">{{ __('messages.phone') }}</x-label>
                                <x-select class="w-full py-0" id="phone" wire:model="form.phone_id">
                                    @if ($customer->phones->count() > 1)
                                        <option selected value="">---</option>
                                    @endif
                                    @foreach ($customer->phones as $phone)
                                        <option value="{{ $phone->id }}">{{ $phone->number }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="form.phone_id" />
                            </div>
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
                            @if ($dup_orders_count > 0)
                                <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
                                    role="alert">
                                    {{ __('messages.Duplicate Order', ['department' => \App\Models\Department::find($form->department_id)->name]) }}
                                </div>
                            @endif

                            <div class="">
                                <x-label for="department_id">{{ __('messages.service_type') }}</x-label>
                                <x-select class="w-full py-0" id="department_id" wire:model.live="form.department_id">
                                    @if (!$order->id)
                                        <option value="">---</option>
                                    @endif
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </x-select>
                                @if ($order && $order->technician)
                                    <p class = "text-sm text-red-600 dark:text-red-400">
                                        {{ __('messages.order assigned cannot change department') }}</p>
                                @endif
                                <x-input-error for="form.department_id" />
                            </div>
                            @if (!$order->id)
                                <div>
                                    <x-label for="technician_id">{{ __('messages.technician') }}</x-label>
                                    <x-select class="w-full py-0" id="technician_id" wire:model="form.technician_id">
                                        <option value="">---</option>
                                        @foreach ($technicians as $technician)
                                            <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                        @endforeach
                                    </x-select>
                                    <x-input-error for="form.technician_id" />
                                </div>
                            @endif

                            <div>
                                <x-label for="estimated_start_date">{{ __('messages.estimated_start_date') }}</x-label>
                                <x-input class="w-full py-0" wire:model="form.estimated_start_date" autocomplete="off"
                                    type="date" id="estimated_start_date" />
                                <x-input-error for="form.estimated_start_date" />
                            </div>

                            <div>
                                <x-label for="tag">{{ __('messages.orderTag') }}</x-label>
                                <x-input class="w-full py-0" wire:model="form.tag" autocomplete="off" type="text"
                                    id="tag" />
                                <x-input-error for="form.tag" />
                            </div>

                            <div>
                                <x-label for="order_description">{{ __('messages.order_description') }}</x-label>
                                <x-input class="w-full py-0" wire:model="form.order_description" autocomplete="off"
                                    type="text" id="order_description" />
                                <x-input-error for="form.order_description" />
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
    @endif
</div>
