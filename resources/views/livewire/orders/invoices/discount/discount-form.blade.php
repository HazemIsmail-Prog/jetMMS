<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ __('messages.edit_discount') }}</div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                <form wire:submit="save">
                    <div class=" space-y-3">
                        <div>
                            <x-label for="discount">{{ __('messages.discount') }}</x-label>
                            <x-input class="w-full" required type="number" wire:model="discount" id="discount"
                                dir="ltr" min="0" max="{{ $invoice->services_amount }}" step="0.001" />
                            <p class=" text-xs">{{ __('messages.discount_must_be_less_than_or_equal_services') }}</p>

                            <x-input-error for="discount" />
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
