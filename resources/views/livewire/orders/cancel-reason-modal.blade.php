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
                        <x-select id="reason" wire:model.live="reason" class="w-full text-start">
                            <option value="">---</option>
                            <option value="تأخير">تأخير</option>
                            <option value="اختلاف بالسعر">اختلاف بالسعر</option>
                            <option value="لا يرد">لا يرد</option>
                            <option value="خدمة غير متوفرة">خدمة غير متوفرة</option>
                            <option value="نازل بالخطأ">نازل بالخطأ</option>
                            <option value="من قبل العميل">من قبل العميل</option>
                            <option value="متابعة عمل">متابعة عمل</option>
                            <option value="معاينة غير مدفوعة">معاينة غير مدفوعة</option>
                            <option value="اسباب أخرى">اسباب أخرى</option>
                        </x-select>
                        <x-input-error for="reason" />
                    </div>
                    @if ($reason == 'اسباب أخرى')
                        <div>
                            <x-input id="otherReason" wire:model="otherReason" class="w-full text-start" type="text" />
                            <x-input-error for="otherReason" />
                        </div>
                    @endif
                    <x-button>{{ __('messages.confirm_cancel') }}</x-button>
                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
