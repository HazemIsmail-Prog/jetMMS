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

        <div class=" p-4 flex flex-col gap-2 items-start border dark:border-gray-700 rounded-lg overflow-x-auto">
            <x-label>{{ __('messages.address') }}</x-label>
            @foreach ($form->addresses as $index => $address)
                <div class=" flex items-center gap-2">
                    <div>
                        <div class="w-64">
                            <x-searchable-select :list="$areas"
                                model="form.addresses.{{ $index }}.area_id" />
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
                        <x-svgs.trash class=" my-auto" wire:click="delete_row('address',{{ $index }})"
                            class="w-5 h-5 text-red-400 font-bold" />
                    @endif
                </div>
            @endforeach
            <x-button type="button" wire:click="add_row('address')">{{ __('messages.add address') }}</x-button>
        </div>
        <div class="text-end">
            <x-secondary-anchor href="{{ route('customer.index') }}">{{ __('messages.back') }}</x-secondary-anchor>
            <x-button>{{ __('messages.save') }}</x-button>
        </div>
    </form>

    <style>
        .select2-container--default .select2-selection--single {
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
</div>

@assets
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endassets

@script
    <script>
        initSelectAreaDrop();

        $wire.on('select2', () => {
            initSelectAreaDrop();
        });

        function initSelectAreaDrop() {
            setTimeout(function() {
                $('.select2').select2();
                $('.select2').on('change', function(e) {
                    property = e.target.attributes['data-property'].value;
                    value = e.target.value;
                    $wire.$set(property, value)
                });
            }, 50);
        }
    </script>
@endscript
