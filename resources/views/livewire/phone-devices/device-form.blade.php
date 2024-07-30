<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal" :dismissible="false">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
                <form wire:submit.prevent="save" wire:loading.class="opacity-50">
                    <div 
                        x-data="{
                            has_installment : @entangle('form.has_installment'),
                            installment_company : @entangle('form.installment_company')
                        }"
                        class=" space-y-3">
                        <div>
                            <x-label for="serial_no">{{ __('messages.serial_no') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.serial_no" id="serial_no" />
                            <x-input-error for="form.serial_no" />
                        </div>

                        <div>
                            <x-label for="brand">{{ __('messages.brand') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.brand" id="brand" />
                            <x-input-error for="form.brand" />
                        </div>
                        
                        <div>
                            <x-label for="model">{{ __('messages.model') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.model" id="model" />
                            <x-input-error for="form.model" />
                        </div>
                        
                        <div>
                            <x-label for="sim_no">{{ __('messages.sim_no') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.sim_no" id="sim_no" />
                            <x-input-error for="form.sim_no" />
                        </div>

                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.notes" id="notes" />
                            <x-input-error for="form.notes" />
                        </div>

                        <div>
                            <x-label for="status">{{ __('messages.status') }}</x-label>
                            <x-input class="w-full py-0" type="text" wire:model="form.status" id="status" />
                            <x-input-error for="form.status" />
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
