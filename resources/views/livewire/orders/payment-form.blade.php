<div>
    @if (!$showForm)
        <div class=" flex items-center justify-center">
            <x-button class="mb-4" wire:click="showPaymentForm">{{ __('messages.create_payment') }}</x-button>
        </div>
    @endif
    @if ($showForm)
        {{-- Payment Form --}}
        <form wire:submit="save_payment" class=" flex flex-col border dark:border-gray-700 rounded-lg p-4 gap-2">
            <div class="  flex gap-2">
                <div class="flex flex-col flex-1">
                    <x-label for="amount">{{ __('messages.amount') }}</x-label>
                    <x-input required type="number" wire:model="payment.amount" id="amount" dir="ltr"
                        min="0" max="{{ $invoice->remaining_amount }}" />
                    <x-input-error for="payment.amount" />
                </div>
                <div class="flex flex-col flex-1">
                    <x-label for="method">{{ __('messages.payment_method') }}</x-label>
                    <x-select required wire:model="payment.method" id="method">
                        <option value="">---</option>
                        <option value="cash">{{ __('messages.cash') }}</option>
                        <option value="knet">{{ __('messages.knet') }}</option>
                    </x-select>
                    <x-input-error for="payment.method" />
                </div>
            </div>
            <div>
                <x-button>{{ __('messages.save') }}</x-button>
                <x-secondary-button wire:click="hidePaymentForm">{{ __('messages.cancel') }}</x-secondary-button>
            </div>
        </form>
    @endif
</div>
