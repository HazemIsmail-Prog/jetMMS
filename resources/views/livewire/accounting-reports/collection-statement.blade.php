<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.collection_statement') }}
            </h2>

        </div>
    </x-slot>

    <div class=" grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 no-print">

        <div class=" col-span-1 md:col-span-2 xl:col-span-4">
            <x-label for="start_date">{{ __('messages.date') }}</x-label>
            <x-input type="date" class="w-full" id="start_date" wire:model.live="start_date" />
            <x-input type="date" class="w-full" id="end_date" wire:model.live="end_date" />
        </div>
    </div>

    <div class="hidden text-center p-20"  wire:loading.class.remove="hidden" role="status">
        <svg aria-hidden="true" class="inline w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
        <span class="sr-only">Loading...</span>
    </div>

    <div wire:loading.class="hidden">

        @foreach ($this->departments as $department)
    
            <div class="my-5">
                <h2 class="mb-2 font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $department->name }}
                </h2>
    
                <x-table>
    
                    <x-thead>
    
                        <tr>
                            <x-borderd-th rowspan="2">{{ __('messages.technician')}}</x-borderd-th>
                            <x-borderd-th colspan="4">{{ __('messages.invoices') }}</x-borderd-th>
                            <x-borderd-th colspan="4">{{ __('messages.accounts') }}</x-borderd-th>
                        </tr>
                        <tr>
                            <x-borderd-th class="!w-1/12">{{ __('messages.amount') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.cash') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.knet') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.remaining_amount') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.net_knet') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.knet_charges') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{__('messages.knet_total') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.cash_total')}}</x-borderd-th>
                        </tr>
    
                    </x-thead>
    
                    <tbody>
    
                        @foreach ($this->titles as $title)
    
                            @foreach ($department->technicians->where('title_id',$title->id) as $technician)
    
                                <x-tr>
                                    @php
                                    $invoices               = $this->invoices->where('order.technician_id',$technician->id);
                                    $servicesAmount         = $invoices->sum('servicesAmountSum');
                                    $discountAmount         = $invoices->sum('discount');
                                    $servicesAfterDiscount  = $invoices->sum('servicesAmountSum') - $invoices->sum('discount');
                                    $internalPartsAmount    = $invoices->sum('invoiceDetailsPartsAmountSum') + $invoices->sum('internalPartsAmountSum');
                                    $externalPartsAmount    = $invoices->sum('externalPartsAmountSum');
                                    $deliveryAmount         = $invoices->sum('delivery');
                                    $amount                 = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
                                    $cashAmount             = round($invoices->sum('cashAmountSum'),3);
                                    $knetAmount             = round($invoices->sum('knetAmountSum'),3);
                                    $totalPaid              = round($cashAmount + $knetAmount ,3);
                                    $remainingAmount        = $amount - $totalPaid;
    
                                    $accounts               = $this->technicians->where('id',$technician->id)->first();
                                    $bankAccount            = round(abs($accounts->bankAccountDebit - $accounts->bankAccountCredit),3);
                                    $bankChargesAccount     = round(abs($accounts->bankChargesAccountDebit - $accounts->bankChargesAccountCredit),3);
                                    $totalKnet              = round($bankAccount + $bankChargesAccount);
                                    $cashAccount            = round(abs($accounts->cashAccountDebit - $accounts->cashAccountCredit),3);
                                    @endphp
    
                                    <x-borderd-td>{{ $technician->name }}</x-borderd-td>
                                    <x-borderd-td>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td class="{{ $cashAmount != $cashAccount ? 'bg-red-200' : '' }}">{{ $cashAmount > 0 ? number_format($cashAmount,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td class="{{ $knetAmount != $totalKnet ? 'bg-red-200' : '' }}">{{ $knetAmount > 0 ? number_format($knetAmount,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td>{{ $remainingAmount > 0 ? number_format($remainingAmount,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td>{{ $bankAccount > 0 ? number_format($bankAccount,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td>{{ $bankChargesAccount > 0 ? number_format($bankChargesAccount,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td class="{{ $knetAmount != $totalKnet ? 'bg-red-200' : '' }}">{{ $totalKnet > 0 ? number_format($totalKnet,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td class="{{ $cashAmount != $cashAccount ? 'bg-red-200' : '' }}">{{ $cashAccount > 0 ? number_format($cashAccount,3) : '-' }}</x-borderd-td>
    
                                </x-tr>
    
                            @endforeach
    
                            @if ($department->technicians->whereNotIn('title_id',$title->id)->count() > 0 &&
                            $department->technicians->whereIn('title_id',$title->id)->count() > 0)
    
                                <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">
    
                                    @php
                                    $invoices               = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',$title->id)->pluck('id'));
                                    $servicesAmount         = $invoices->sum('servicesAmountSum');
                                    $discountAmount         = $invoices->sum('discount');
                                    $servicesAfterDiscount  = $invoices->sum('servicesAmountSum') - $invoices->sum('discount');
                                    $internalPartsAmount    = $invoices->sum('invoiceDetailsPartsAmountSum') + $invoices->sum('internalPartsAmountSum');
                                    $externalPartsAmount    = $invoices->sum('externalPartsAmountSum');
                                    $deliveryAmount         = $invoices->sum('delivery');
                                    $amount                 = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
                                    $cashAmount             = round($invoices->sum('cashAmountSum'),3);
                                    $knetAmount             = round($invoices->sum('knetAmountSum'),3);
                                    $totalPaid              = round($cashAmount + $knetAmount ,3);
                                    $remainingAmount        = $amount - $totalPaid;
    
                                    $accounts               = $this->technicians->where('department_id',$department->id)->where('title_id',$title->id);
                                    $bankAccount            = round(abs($accounts->sum('bankAccountDebit') - $accounts->sum('bankAccountCredit')),3);
                                    $bankChargesAccount     = round(abs($accounts->sum('bankChargesAccountDebit') - $accounts->sum('bankChargesAccountCredit')),3);
                                    $totalKnet              = round($bankAccount + $bankChargesAccount);
                                    $cashAccount            = round(abs($accounts->sum('cashAccountDebit') - $accounts->sum('cashAccountCredit')),3);
                                    @endphp
    
                                    <x-borderd-th>{{ $title->name }}</x-borderd-th>
                                    <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th class="{{ $cashAmount != $cashAccount ? 'bg-red-200' : '' }}">{{ $cashAmount > 0 ? number_format($cashAmount,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th class="{{ $knetAmount != $totalKnet ? 'bg-red-200' : '' }}">{{ $knetAmount > 0 ? number_format($knetAmount,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th>{{ $remainingAmount > 0 ? number_format($remainingAmount,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th>{{ $bankAccount > 0 ? number_format($bankAccount,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th>{{ $bankChargesAccount > 0 ? number_format($bankChargesAccount,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th class="{{ $knetAmount != $totalKnet ? 'bg-red-200' : '' }}">{{ $totalKnet > 0 ? number_format($totalKnet,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th class="{{ $cashAmount != $cashAccount ? 'bg-red-200' : '' }}">{{ $cashAccount > 0 ? number_format($cashAccount,3) : '-' }}</x-borderd-th>
    
                                </tr>
    
                            @endif
    
                        @endforeach
    
                        <tr class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
    
                            @php
                            $invoices               = $this->invoices->where('order.department_id',$department->id);
                            $servicesAmount         = $invoices->sum('servicesAmountSum');
                            $discountAmount         = $invoices->sum('discount');
                            $servicesAfterDiscount  = $invoices->sum('servicesAmountSum') - $invoices->sum('discount');
                            $internalPartsAmount    = $invoices->sum('invoiceDetailsPartsAmountSum') + $invoices->sum('internalPartsAmountSum');
                            $externalPartsAmount    = $invoices->sum('externalPartsAmountSum');
                            $deliveryAmount         = $invoices->sum('delivery');
                            $amount                 = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
                            $cashAmount             = round($invoices->sum('cashAmountSum'),3);
                            $knetAmount             = round($invoices->sum('knetAmountSum'),3);
                            $totalPaid              = round($cashAmount + $knetAmount ,3);
                            $remainingAmount        = $amount - $totalPaid;
    
                            $accounts               = $this->technicians->where('department_id',$department->id);
                            $bankAccount            = round(abs($accounts->sum('bankAccountDebit') - $accounts->sum('bankAccountCredit')),3);
                            $bankChargesAccount     = round(abs($accounts->sum('bankChargesAccountDebit') - $accounts->sum('bankChargesAccountCredit')),3);
                            $totalKnet              = round($bankAccount + $bankChargesAccount);
                            $cashAccount            = round(abs($accounts->sum('cashAccountDebit') - $accounts->sum('cashAccountCredit')),3);
                            @endphp
    
                            <x-borderd-th>{{ __('messages.total') }}</x-borderd-th>
                            <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                            <x-borderd-th class="{{ $cashAmount != $cashAccount ? 'bg-red-200' : '' }}">{{ $cashAmount > 0 ? number_format($cashAmount,3) : '-' }}</x-borderd-th>
                            <x-borderd-th class="{{ $knetAmount != $totalKnet ? 'bg-red-200' : '' }}">{{ $knetAmount > 0 ? number_format($knetAmount,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $remainingAmount > 0 ? number_format($remainingAmount,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $bankAccount > 0 ? number_format($bankAccount,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $bankChargesAccount > 0 ? number_format($bankChargesAccount,3) : '-' }}</x-borderd-th>
                            <x-borderd-th class="{{ $knetAmount != $totalKnet ? 'bg-red-200' : '' }}">{{ $totalKnet > 0 ? number_format($totalKnet,3) : '-' }}</x-borderd-th>
                            <x-borderd-th class="{{ $cashAmount != $cashAccount ? 'bg-red-200' : '' }}">{{ $cashAccount > 0 ? number_format($cashAccount,3) : '-' }}</x-borderd-th>
    
                        </tr>
    
                    </tbody>
    
                </x-table>
    
            </div>
    
        @endforeach
    
        <x-table>
    
            <x-thead>
    
                <tr>
                    <x-borderd-th rowspan="3">{{ __('messages.grand_total')}}</x-borderd-th>
                    <x-borderd-th colspan="4">{{ __('messages.invoices') }}</x-borderd-th>
                    <x-borderd-th colspan="4">{{ __('messages.accounts') }}</x-borderd-th>
                </tr>
                <tr>
                    <x-borderd-th class="!w-1/12">{{ __('messages.amount') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.cash') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.knet') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.remaining_amount') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.net_knet') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.knet_charges') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{__('messages.knet_total') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.cash_total')}}</x-borderd-th>
                </tr>
                <tr>
                    @php
                    $invoices               = $this->invoices;
                    $servicesAmount         = $invoices->sum('servicesAmountSum');
                    $discountAmount         = $invoices->sum('discount');
                    $servicesAfterDiscount  = $invoices->sum('servicesAmountSum') - $invoices->sum('discount');
                    $internalPartsAmount    = $invoices->sum('invoiceDetailsPartsAmountSum') + $invoices->sum('internalPartsAmountSum');
                    $externalPartsAmount    = $invoices->sum('externalPartsAmountSum');
                    $deliveryAmount         = $invoices->sum('delivery');
                    $amount                 = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
                    $cashAmount             = round($invoices->sum('cashAmountSum'),3);
                    $knetAmount             = round($invoices->sum('knetAmountSum'),3);
                    $totalPaid              = round($cashAmount + $knetAmount ,3);
                    $remainingAmount        = $amount - $totalPaid;
    
                    $accounts               = $this->technicians;
                    $bankAccount            = round(abs($accounts->sum('bankAccountDebit') - $accounts->sum('bankAccountCredit')),3);
                    $bankChargesAccount     = round(abs($accounts->sum('bankChargesAccountDebit') - $accounts->sum('bankChargesAccountCredit')),3);
                    $totalKnet              = round($bankAccount + $bankChargesAccount);
                    $cashAccount            = round(abs($accounts->sum('cashAccountDebit') - $accounts->sum('cashAccountCredit')),3);
                    @endphp
    
                    <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class="{{ $cashAmount != $cashAccount ? 'bg-red-200' : '' }}">{{ $cashAmount > 0 ? number_format($cashAmount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class="{{ $knetAmount != $totalKnet ? 'bg-red-200' : '' }}">{{ $knetAmount > 0 ? number_format($knetAmount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $remainingAmount > 0 ? number_format($remainingAmount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $bankAccount > 0 ? number_format($bankAccount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $bankChargesAccount > 0 ? number_format($bankChargesAccount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class="{{ $knetAmount != $totalKnet ? 'bg-red-200' : '' }}">{{ $totalKnet > 0 ? number_format($totalKnet,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class="{{ $cashAmount != $cashAccount ? 'bg-red-200' : '' }}">{{ $cashAccount > 0 ? number_format($cashAccount,3) : '-' }}</x-borderd-th>
                </tr>
    
            </x-thead>
    
        </x-table>
    
    
        <div class=" border border-green-500 rounded-lg p-5 my-5 flex flex-col items-center justify-center gap-3">
    
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.bank_deposit_required') }}
            </h2>
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ number_format($this->technicians->sum('cashAccountDebit') - $this->technicians->sum('cashAccountCredit'),3) }}
            </h2>
        </div>

    </div>


</div>