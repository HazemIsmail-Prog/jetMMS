<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.edit_settings') }}
            </h2>
        </div>
    </x-slot>

    <form wire:submit="save" class=" space-y-4 max-w-md mx-auto">

        <div>
            <x-label for="logo">{{ __('messages.logo') }}</x-label>
            <x-input class="w-full" type="file" wire:model="form.logo" id="logo" />
            <x-input-error for="form.logo" />
        </div>
        <div>
            <x-label for="favicon">{{ __('messages.favicon') }}</x-label>
            <x-input class="w-full" type="file" wire:model="form.favicon" id="favicon" />
            <x-input-error for="form.favicon" />
        </div>
        <div>
            <x-label for="phone">{{ __('messages.phone') }}</x-label>
            <x-input class="w-full" type="text" wire:model="form.phone" id="phone" dir="ltr" />
            <x-input-error for="form.phone" />
        </div>
        <div>
            <x-label for="fax">{{ __('messages.fax') }}</x-label>
            <x-input class="w-full" type="text" wire:model="form.fax" id="fax" dir="ltr" />
            <x-input-error for="form.fax" />
        </div>
        <div>
            <x-label for="address_ar">{{ __('messages.address_ar') }}</x-label>
            <x-input class="w-full" type="text" wire:model="form.address_ar" id="address_ar" dir="ltr" />
            <x-input-error for="form.address_ar" />
        </div>
        <div>
            <x-label for="address_en">{{ __('messages.address_en') }}</x-label>
            <x-input class="w-full" type="text" wire:model="form.address_en" id="address_en" dir="ltr" />
            <x-input-error for="form.address_en" />
        </div>

        <div>
            <x-label for="knet_tax">{{ __('messages.knet_tax') }}</x-label>
            <x-input class="w-full" type="text" wire:model="form.knet_tax" id="knet_tax" dir="ltr" />
            <x-input-error for="form.knet_tax" />
        </div>
        <div>
            <x-label for="cash_account_id">{{ __('messages.cash_account_id') }}</x-label>
            <x-searchable-select id="cash_account_id" :list="$this->accounts" wire:model="form.cash_account_id" />
            <x-input-error for="form.cash_account_id" />
        </div>
        <div>
            <x-label for="bank_account_id">{{ __('messages.bank_account_id') }}</x-label>
            <x-searchable-select id="bank_account_id" :list="$this->accounts" wire:model="form.bank_account_id" />
            <x-input-error for="form.bank_account_id" />
        </div>
        <div>
            <x-label for="bank_charges_account_id">{{ __('messages.bank_charges_account_id') }}</x-label>
            <x-searchable-select id="bank_charges_account_id" :list="$this->accounts" wire:model="form.bank_charges_account_id" />
            <x-input-error for="form.bank_charges_account_id" />
        </div>
        <div>
            <x-label for="receivables_account_id">{{ __('messages.receivables_account_id') }}</x-label>
            <x-searchable-select id="receivables_account_id" :list="$this->accounts" wire:model="form.receivables_account_id" />
            <x-input-error for="form.receivables_account_id" />
        </div>
        <div>
            <x-label for="internal_parts_account_id">{{ __('messages.internal_parts_account_id') }}</x-label>
            <x-searchable-select id="internal_parts_account_id" :list="$this->accounts" wire:model="form.internal_parts_account_id" />
            <x-input-error for="form.internal_parts_account_id" />
        </div>

        <div>
            <x-label for="invoice_required" class="flex items-center">
                <x-checkbox wire:model="form.invoice_required" id="invoice_required" />
                <span class="ms-2 ">{{ __('messages.invoice_required') }}</span>
            </x-label>
        </div>

        <div class="text-end">
            <x-button>{{ __('messages.save') }}</x-button>
        </div>

    </form>
</div>
