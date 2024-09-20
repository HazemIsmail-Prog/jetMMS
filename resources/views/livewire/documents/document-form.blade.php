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
                            <x-label for="document_type_id">{{ __('messages.document_type_id') }}</x-label>
                            <x-searchable-select id="document_type_id" :list="$this->documentTypes"
                                wire:model="form.document_type_id" />
                            <x-input-error for="form.document_type_id" />
                        </div>
                        <div>
                            <x-label for="document_number">{{ __('messages.document_number') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.document_number" autocomplete="off"
                                type="text" dir="ltr" id="document_number" />
                            <x-input-error for="form.document_number" />
                        </div>
                        <div>
                            <x-label for="document_serial_from">{{ __('messages.document_serial_from') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.document_serial_from"
                                autocomplete="off" dir="ltr" type="number" min="1" id="document_serial_from" />
                            <x-input-error for="form.document_serial_from" />
                        </div>
                        <div>
                            <x-label for="document_serial_to">{{ __('messages.document_serial_to') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.document_serial_to"
                                autocomplete="off" dir="ltr" type="number" min="1" id="document_serial_to" />
                            <x-input-error for="form.document_serial_to" />
                        </div>
                        <div>
                            <x-label for="document_pages">{{ __('messages.document_pages') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.document_pages" autocomplete="off"
                                type="number" min="1" dir="ltr" id="document_pages" />
                            <x-input-error for="form.document_pages" />
                        </div>
                        <div>
                            <x-label for="receiver_id">{{ __('messages.receiver_id') }}</x-label>
                            <x-searchable-select id="receiver_id" :list="$this->receivers" wire:model="form.receiver_id" />
                            <x-input-error for="form.receiver_id" />
                        </div>
                        <div>
                            <x-label for="receiving_date">{{ __('messages.receiving_date') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.receiving_date" autocomplete="off"
                                type="date" id="receiving_date" />
                            <x-input-error for="form.receiving_date" />
                        </div>
                        <div>
                            <x-label for="back_date">{{ __('messages.back_date') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.back_date" autocomplete="off" type="date"
                                id="back_date" />
                            <x-input-error for="form.back_date" />
                        </div>
                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.notes" autocomplete="off" type="text"
                                id="notes" />
                            <x-input-error for="form.notes" />
                        </div>
                        <div>
                            <x-label for="status">{{ __('messages.status') }}</x-label>
                            <x-select class="w-full py-0" wire:model="form.status" autocomplete="off" type="text"
                            id="status">
                            <option value="active">{{__('messages.active')}}</option>
                            <option value="inactive">{{__('messages.inactive')}}</option>
                            <option value="expired">{{__('messages.expired')}}</option>
                        </x-select>
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
