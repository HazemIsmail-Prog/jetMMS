{{-- <div class="card">
    <td>
        <select wire:model="addresses.{{ $i }}.area_id"
            data-index="{{ $i }}"
            class=" select2 form-control @error('addresses.' . $i . '.area_id') is-invalid @enderror">
            <option disabled selected value="">---</option>
            @foreach ($areas->sortBy->name as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
    </td>
</div>

@section('styles')
    <style>

        .select2-container--default .select2-selection--single{
            background: transparent;
                height: 34px;
                border-color: #d8dbe0;
                border-radius: .25rem;


        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
            text-align: center;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            function initSelectAreaDrop() {
                $('.select2').select2();
                $('.select2').on('change', function(e) {
                    index = e.target.attributes['data-index'].value;
                    value = e.target.value;
                    livewire.emit('selectedCompanyItem', index, value)
                });
            }
            initSelectAreaDrop();
            window.livewire.on('select2', () => {
                initSelectAreaDrop();
            });
        });
    </script>
@endpush --}}


<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $form->id ? __('messages.edit_customer') : __('messages.add_customer') }}
            </h2>
        </div>
    </x-slot>


    <form wire:submit="save" class=" space-y-4 mx-auto">

        <div class=" flex items-start gap-2">
            <div class="flex flex-col">
                <x-label for="name">{{ __('messages.name') }}</x-label>
                <x-input type="text" wire:model="form.name" id="name" dir="rtl" />
                <x-input-error for="form.name" />
            </div>
            {{-- <div class="flex flex-col">
                <x-label for="cid">{{ __('messages.cid') }}</x-label>
                <x-input type="text" wire:model="form.cid" id="cid" dir="rtl" />
                <x-input-error for="form.cid" />
            </div> --}}
            <div class="flex flex-col">
                <x-label for="notes">{{ __('messages.notes') }}</x-label>
                <x-input type="text" wire:model="form.notes" id="notes" dir="rtl" />
                <x-input-error for="form.notes" />
            </div>
        </div>

        <div class=" p-4 flex flex-col gap-2 items-start border rounded-lg">
            <div>{{ __('messages.phone') }}</div>
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
                        <x-input wire:model="form.phones.{{ $index }}.number" dir="ltr" type="number" />
                        <x-input-error for="form.phones.{{ $index }}.number" />
                    </div>

                    @if ($index > 0)
                        <x-svgs.trash class=" my-auto" wire:click="delete_row('phone',{{ $index }})"
                            class="w-5 h-5 text-red-400 font-bold" />
                    @endif

                </div>
            @endforeach
            <x-button type="button" wire:click="add_row('phone')">{{ __('messages.add phone') }}</x-button>
        </div>

        <div class=" p-4 flex flex-col gap-2 items-start border rounded-lg overflow-x-auto">
            <div>{{ __('messages.address') }}</div>
            @foreach ($form->addresses as $index => $address)
                <div class=" flex items-center gap-2">
                    <div>
                        <x-select wire:model="form.addresses.{{ $index }}.area_id">
                            <option value="">{{ __('messages.area') }}</option>
                            @foreach ($areas->sortBy('name') as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </x-select>
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
                        <x-svgs.trash class=" my-auto" wire:click="delete_row('address',{{ $index }})"
                            class="w-5 h-5 text-red-400 font-bold" />
                    @endif
                </div>
            @endforeach
            <x-button type="button" wire:click="add_row('address')">{{ __('messages.add address') }}</x-button>
        </div>
        {{-- <div class="flex flex-col">
            <x-label for="name_en">{{ __('messages.name_en') }}</x-label>
            <x-input type="text" wire:model="customer.name_en" id="name_en" dir="ltr" />
            <x-input-error for="customer.name_en" />
        </div>
        <div class="flex flex-col">
            <x-label for="username">{{ __('messages.username') }}</x-label>
            <x-input type="text" wire:model="customer.username" id="username" dir="ltr" />
            <x-input-error for="customer.username" />
        </div>
        <div class="flex flex-col">
            <x-label for="password">{{ __('messages.password') }}</x-label>
            <x-input type="password" wire:model="customer.password" id="password" dir="ltr" />
            <x-input-error for="customer.password" />
        </div>

        <div class="flex flex-col">
            <x-label for="title_id">{{ __('messages.title') }}</x-label>
            <x-select wire:model="customer.title_id" id="title_id">
                <option value="">---</option>

            </x-select>
            <x-input-error for="customer.title_id" />
        </div>

        <div class="flex flex-col">
            <x-label for="department_id">{{ __('messages.department') }}</x-label>
            <x-select wire:model="customer.department_id" id="department_id">
                <option value="">---</option>

            </x-select>
            <x-input-error for="customer.department_id" />
        </div>

        <div class="flex flex-col">
            <x-label for="shift_id">{{ __('messages.shift') }}</x-label>
            <x-select wire:model="customer.shift_id" id="shift_id">
                <option value="">---</option>

            </x-select>
            <x-input-error for="customer.shift_id" />
        </div>

        <div class=" col-span-full">
            <x-label for="active" class="flex items-center">
                <x-checkbox wire:model="customer.active" id="active" />
                <span class="ms-2 ">{{ __('messages.active') }}</span>
            </x-label>
        </div>


        <div class=" col-span-full">

            <x-input-error for="customer.roles" />

        </div> --}}



        <div class="text-end">
            <x-secondary-anchor href="{{ route('customer.index') }}">{{ __('messages.back') }}</x-secondary-anchor>
            <x-button>{{ __('messages.save') }}</x-button>
        </div>

    </form>
</div>
