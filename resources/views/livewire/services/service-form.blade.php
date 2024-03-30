<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
                <form wire:submit.prevent="save" wire:loading.class="opacity-50">

                    <div class=" space-y-3">
                        <div>
                            <x-label for="name_ar">{{ __('messages.name_ar') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.name_ar" autocomplete="off"
                                type="text" id="name_ar" />
                            <x-input-error for="form.name_ar" />
                        </div>
                        <div>
                            <x-label for="name_en">{{ __('messages.name_en') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.name_en" autocomplete="off"
                                type="text" id="name_en" />
                            <x-input-error for="form.name_en" />
                        </div>
                        <div>
                            <x-label for="min_price">{{ __('messages.min_price') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.min_price" autocomplete="off"
                                type="number" id="min_price" />
                            <x-input-error for="form.min_price" />
                        </div>
                        <div>
                            <x-label for="max_price">{{ __('messages.max_price') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.max_price" autocomplete="off"
                                type="number" id="max_price" />
                            <x-input-error for="form.max_price" />
                        </div>
                        <div>
                            <x-label for="department_id">{{ __('messages.department') }}</x-label>
                            <x-searchable-select model="form.department_id" :list="$this->departments" position="relative" />
                            <x-input-error for="form.department_id" />
                        </div>
                        <div>
                            <x-label for="type">{{ __('messages.type') }}</x-label>
                            <x-select required class="w-full py-0" wire:model="form.type" autocomplete="off"
                                type="number" id="type">
                                <option value="">---</option>
                                <option value="service">{{ __('messages.service') }}</option>
                                <option value="part">{{ __('messages.parts') }}</option>
                            </x-select>
                            <x-input-error for="form.type" />
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
