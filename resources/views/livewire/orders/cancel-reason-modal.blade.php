<div>
    <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($order)
                <form wire:submit.prevent="save" class=" space-y-5">
                    <p class="font-medium">{{ $modalDescription }}</p>
                    <div>
                        <x-input list="reason-list" id="reason" wire:model="reason" class="w-full text-start" type="text" />
                        <datalist id="reason-list">
                            <option value="تأخير"></option>
                            <option value="اختلاف بالسعر"></option>
                            <option value="لا يرد"></option>
                            <option value="خدمة غير متوفرة"></option>
                            <option value="نازل بالخطأ"></option>
                            <option value="من قبل العميل"></option>
                            <option value="متابعة عمل"></option>
                            <option value="معاينة غير مدفوعة"></option>
                        </datalist>
                        <x-input-error for="reason" />
                    </div>
                    <x-button>{{ __('messages.confirm_cancel') }}</x-button>
                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
