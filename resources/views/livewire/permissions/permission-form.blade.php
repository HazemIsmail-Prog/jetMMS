<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border/>
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
                <form wire:submit.prevent="save" wire:loading.class="opacity-50">
                    <div class=" space-y-3">
                        <div>
                            <x-label for="name">{{ __('messages.name') }}</x-label>
                            <x-input dir="ltr" required class="w-full py-0" wire:model="form.name" autocomplete="off"
                                type="text" id="name" />
                            <x-input-error for="form.name" />
                        </div>
                        <div>
                            <x-label for="section_name_ar">{{ __('messages.section_name_ar') }}</x-label>
                            <x-input dir="rtl" required class="w-full py-0" wire:model="form.section_name_ar" autocomplete="off"
                                type="text" id="section_name_ar" />
                            <x-input-error for="form.section_name_ar" />
                        </div>
                        <div>
                            <x-label for="section_name_en">{{ __('messages.section_name_en') }}</x-label>
                            <x-input dir="ltr" required class="w-full py-0" wire:model="form.section_name_en" autocomplete="off"
                                type="text" id="section_name_en" />
                            <x-input-error for="form.section_name_en" />
                        </div>
                        <div>
                            <x-label for="desc_ar">{{ __('messages.desc_ar') }}</x-label>
                            <x-input dir="rtl" required class="w-full py-0" wire:model="form.desc_ar" autocomplete="off"
                                type="text" id="desc_ar" />
                            <x-input-error for="form.desc_ar" />
                        </div>
                        <div>
                            <x-label for="desc_en">{{ __('messages.desc_en') }}</x-label>
                            <x-input dir="ltr" required class="w-full py-0" wire:model="form.desc_en" autocomplete="off"
                                type="text" id="desc_en" />
                            <x-input-error for="form.desc_en" />
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
