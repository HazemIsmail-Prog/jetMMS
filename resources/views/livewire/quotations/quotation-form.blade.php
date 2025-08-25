<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal" :dismissible="false">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)


            <form wire:submit.prevent="save" wire:loading.class="opacity-50">

                <div class="space-y-3 mt-3">

                    <div>
                        <x-label for="quotation_number">{{ __('messages.quotation_number') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.quotation_number" autocomplete="off" type="text"
                            id="quotation_number" />
                        <x-input-error for="form.quotation_number" />
                    </div>

                    <div>
                        <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.customer_name" autocomplete="off"
                            type="text" id="customer_name" />
                        <x-input-error for="form.customer_name" />
                    </div>

                    <div>
                        <x-label for="amount">{{ __('messages.amount') }}</x-label>
                        <x-input class="w-full py-0" dir="ltr" wire:model="form.amount" autocomplete="off" type="number"
                            step='0.001' id="amount" />
                        <x-input-error for="form.amount" />
                    </div>

                    <div>
                        <x-label for="description">{{ __('messages.description') }}</x-label>
                        <x-input class="w-full py-0" wire:model="form.description" autocomplete="off" type="text"
                            id="description" />
                        <x-input-error for="form.description" />
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
