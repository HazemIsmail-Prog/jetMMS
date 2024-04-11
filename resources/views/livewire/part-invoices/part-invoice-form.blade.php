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
                            <x-label for="manual_id">{{ __('messages.manual_id') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.manual_id" autocomplete="off"
                                type="number" id="manual_id" />
                            <x-input-error for="form.manual_id" />
                        </div>
                        <div>
                            <x-label for="date">{{ __('messages.date') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.date" autocomplete="off"
                                type="date" id="date" />
                            <x-input-error for="form.date" />
                        </div>

                        <div>
                            <x-label for="supplier_id">{{ __('messages.supplier') }}</x-label>
                            <x-searchable-select id=supplier_id :list="$this->suppliers" wire:model="form.supplier_id" />
                            <x-input-error for="form.supplier_id" />
                        </div>

                        <div>
                            <x-label for="contact_id">{{ __('messages.contact') }}</x-label>
                            <x-searchable-select id=contact_id :list="$this->contacts" wire:model="form.contact_id" />
                            <x-input-error for="form.contact_id" />
                        </div>

                        <div>
                            <x-label for="cost_amount">{{ __('messages.cost_amount') }}</x-label>
                            <x-input required min="0.001" step="0.001" class="w-full py-0" wire:model="form.cost_amount" autocomplete="off"
                                type="number" id="cost_amount" />
                            <x-input-error for="form.cost_amount" />
                        </div>

                        <div>
                            <x-label for="sales_amount">{{ __('messages.sales_amount') }}</x-label>
                            <x-input required min="0.001" step="0.001" class="w-full py-0" wire:model="form.sales_amount" autocomplete="off"
                                type="number" id="sales_amount" />
                            <x-input-error for="form.sales_amount" />
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
