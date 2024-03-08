<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ __('messages.create_payment') }}</div>
            <x-section-border />
        </x-slot>


        <x-slot name="content">
            @if ($showModal)
                <form wire:submit="save">
                    <div class=" space-y-3">
                        <div>
                            <x-label for="amount">{{ __('messages.amount') }}</x-label>
                            <x-input class="w-full" required type="number" wire:model="form.amount" id="amount" dir="ltr"
                                min="0" max="{{ $invoice->remaining_amount }}" />
                            <x-input-error for="form.amount" />
                        </div>
                        <div>
                            <x-label for="method">{{ __('messages.payment_method') }}</x-label>
                            <x-select class="w-full" required wire:model.live="form.method" id="method">
                                <option value="">---</option>
                                <option value="cash">{{ __('messages.cash') }}</option>
                                <option value="knet">{{ __('messages.knet') }}</option>
                            </x-select>
                            <x-input-error for="form.method" />
                        </div>

                        @if ($form->method == 'knet')
                            <div>
                                <x-label for="knet_ref_number">{{ __('messages.knet_ref_number') }}</x-label>
                                <x-input class="w-full" type="text" wire:model="form.knet_ref_number" id="knet_ref_number"
                                    dir="ltr" />
                                <x-input-error for="form.knet_ref_number" />
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <x-button>{{ __('messages.save') }}</x-button>
                    </div>
                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
