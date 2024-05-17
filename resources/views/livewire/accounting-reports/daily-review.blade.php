<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.daily_review') }}
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
                            <x-borderd-th colspan="2">{{ __('messages.invoices') }}</x-borderd-th>
                            <x-borderd-th colspan="3">{{__('messages.cost_centers') }}</x-borderd-th>
                            <x-borderd-th colspan="2">{{ __('messages.accounts') }}</x-borderd-th>
                        </tr>
                        <tr>
                            <x-borderd-th class="!w-1/12">{{ __('messages.amount') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.parts_difference')}}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.services') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.parts') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.delivery') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{__('messages.income_account_id') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.cost_account_id')}}</x-borderd-th>
                        </tr>
    
                    </x-thead>
    
                    <tbody>
    
                        @foreach ($this->titles as $title)
    
                            @foreach ($department->technicians->where('title_id',$title->id) as $technician)
    
                                <x-tr>
                                    @php
                                        $invoices = $this->invoices->where('order.technician_id',$technician->id);
                                        $servicesAmount = $invoices->sum('servicesAmountSum');
                                        $discountAmount = $invoices->sum('discount');
                                        $servicesAfterDiscount = $invoices->sum('servicesAmountSum') - $invoices->sum('discount');
                                        $internalPartsAmount = $invoices->sum('invoiceDetailsPartsAmountSum') + $invoices->sum('internalPartsAmountSum');
                                        $externalPartsAmount = $invoices->sum('externalPartsAmountSum');
                                        $deliveryAmount = $invoices->sum('delivery');
                                        $amount = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
    
                                        $parts_difference = round($this->technicians->where('id',$technician->id)->first()->PartDifferenceDebit - $this->technicians->where('id',$technician->id)->first()->PartDifferenceCredit,3);
                                        $income = abs($this->technicians->where('id',$technician->id)->first()->incomeAccountDebit - $this->technicians->where('id',$technician->id)->first()->incomeAccountCredit);
                                        $cost = abs($this->technicians->where('id',$technician->id)->first()->costAccountDebit - $this->technicians->where('id',$technician->id)->first()->costAccountCredit);
                                        $services = abs($this->technicians->where('id',$technician->id)->first()->servicesCostCenterDebit - $this->technicians->where('id',$technician->id)->first()->servicesCostCenterCredit);
                                        $parts = abs($this->technicians->where('id',$technician->id)->first()->partsCostCenterDebit - $this->technicians->where('id',$technician->id)->first()->partsCostCenterCredit);
                                        $delivery = abs($this->technicians->where('id',$technician->id)->first()->deliveryCostCenterDebit - $this->technicians->where('id',$technician->id)->first()->deliveryCostCenterCredit);
                                    @endphp
    
                                    <x-borderd-td>{{ $technician->name }}</x-borderd-td>
                                    <x-borderd-td>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td class=" {{ $parts_difference < 0 ? 'text-red-500' : '' }}">{{ $parts_difference == 0 ? '-' : number_format($parts_difference,3) }}</x-borderd-td>
                                    <x-borderd-td>{{ $services > 0 ? number_format($services,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td>{{ $parts > 0 ? number_format($parts,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td>{{ $delivery > 0 ? number_format($delivery,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td>{{ $income > 0 ? number_format($income,3) : '-' }}</x-borderd-td>
                                    <x-borderd-td>{{ $cost > 0 ? number_format($cost,3) : '-' }}</x-borderd-td>
    
                                </x-tr>
    
                            @endforeach
    
                            @if ($department->technicians->whereNotIn('title_id',$title->id)->count() > 0  && $department->technicians->whereIn('title_id',$title->id)->count() > 0)
    
                                <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">
    
                                    @php
                                        $invoices = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',$title->id)->pluck('id'));
                                        $servicesAmount = $invoices->sum('servicesAmountSum');
                                        $discountAmount = $invoices->sum('discount');
                                        $servicesAfterDiscount = $invoices->sum('servicesAmountSum') - $invoices->sum('discount');
                                        $internalPartsAmount = $invoices->sum('invoiceDetailsPartsAmountSum') + $invoices->sum('internalPartsAmountSum');
                                        $externalPartsAmount = $invoices->sum('externalPartsAmountSum');
                                        $deliveryAmount = $invoices->sum('delivery');
                                        $amount = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
    
                                        $parts_difference = round($this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('PartDifferenceDebit') - $this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('PartDifferenceCredit'),3);
                                        $income = abs($this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('incomeAccountDebit') - $this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('incomeAccountCredit'));
                                        $cost = abs($this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('costAccountDebit') - $this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('costAccountCredit'));
                                        $services = abs($this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('servicesCostCenterDebit') - $this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('servicesCostCenterCredit'));
                                        $parts = abs($this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('partsCostCenterDebit') - $this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('partsCostCenterCredit'));
                                        $delivery = abs($this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('deliveryCostCenterDebit') - $this->technicians->where('department_id',$department->id)->where('title_id',$title->id)->sum('deliveryCostCenterCredit'));
                                    @endphp
    
                                    <x-borderd-th>{{ $title->name }}</x-borderd-th>
                                    <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th class=" {{ $parts_difference < 0 ? 'text-red-500' : '' }}">{{ $parts_difference == 0 ? '-' : number_format($parts_difference,3) }}</x-borderd-th>
                                    <x-borderd-th>{{ $services > 0 ? number_format($services,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th>{{ $parts > 0 ? number_format($parts,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th>{{ $delivery > 0 ? number_format($delivery,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th>{{ $income > 0 ? number_format($income,3) : '-' }}</x-borderd-th>
                                    <x-borderd-th>{{ $cost > 0 ? number_format($cost,3) : '-' }}</x-borderd-th>
    
                                </tr>
    
                            @endif
    
                        @endforeach 
    
                        <tr class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
    
                            @php
                                $invoices = $this->invoices->where('order.department_id',$department->id);
                                $servicesAmount = $invoices->sum('servicesAmountSum');
                                $discountAmount = $invoices->sum('discount');
                                $servicesAfterDiscount = $invoices->sum('servicesAmountSum') - $invoices->sum('discount');
                                $internalPartsAmount = $invoices->sum('invoiceDetailsPartsAmountSum') + $invoices->sum('internalPartsAmountSum');
                                $externalPartsAmount = $invoices->sum('externalPartsAmountSum');
                                $deliveryAmount = $invoices->sum('delivery');
                                $amount = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
    
                                $parts_difference = round($this->technicians->where('department_id',$department->id)->sum('PartDifferenceDebit') - $this->technicians->where('department_id',$department->id)->sum('PartDifferenceCredit'),3);
                                $income = abs($this->technicians->where('department_id',$department->id)->sum('incomeAccountDebit') - $this->technicians->where('department_id',$department->id)->sum('incomeAccountCredit'));
                                $cost = abs($this->technicians->where('department_id',$department->id)->sum('costAccountDebit') - $this->technicians->where('department_id',$department->id)->sum('costAccountCredit'));
                                $services = abs($this->technicians->where('department_id',$department->id)->sum('servicesCostCenterDebit') - $this->technicians->where('department_id',$department->id)->sum('servicesCostCenterCredit'));
                                $parts = abs($this->technicians->where('department_id',$department->id)->sum('partsCostCenterDebit') - $this->technicians->where('department_id',$department->id)->sum('partsCostCenterCredit'));
                                $delivery = abs($this->technicians->where('department_id',$department->id)->sum('deliveryCostCenterDebit') - $this->technicians->where('department_id',$department->id)->sum('deliveryCostCenterCredit'));
    
                            @endphp
    
                            <x-borderd-th>{{ __('messages.total') }}</x-borderd-th>
                            <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                            <x-borderd-th class=" {{ $parts_difference < 0  ? 'text-red-500' : ''}}"> {{ $parts_difference == 0 ? '-' : number_format($parts_difference,3) }}</x-borderd-th>
                            <x-borderd-th>{{ $services > 0 ? number_format($services,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $parts > 0 ? number_format($parts,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $delivery > 0 ? number_format($delivery,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $income > 0 ? number_format($income,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $cost > 0 ? number_format($cost,3) : '-' }}</x-borderd-th>
    
                        </tr>
    
                    </tbody>
    
                </x-table>
                
            </div>
    
        @endforeach
    
        <x-table>
    
            <x-thead>
    
                <tr>
                    <x-borderd-th rowspan="3">{{ __('messages.grand_total')}}</x-borderd-th>
                    <x-borderd-th colspan="2">{{ __('messages.invoices') }}</x-borderd-th>
                    <x-borderd-th colspan="3">{{__('messages.cost_centers') }}</x-borderd-th>
                    <x-borderd-th colspan="2">{{ __('messages.accounts') }}</x-borderd-th>
                </tr>
                <tr>
                    <x-borderd-th class="!w-1/12">{{ __('messages.amount') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.parts_difference')}}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.services') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.parts') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.delivery') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{__('messages.income_account_id') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.cost_account_id')}}</x-borderd-th>
                </tr>
                <tr>
                    @php
    
                        $invoices = $this->invoices;
                        $servicesAmount = $invoices->sum('servicesAmountSum');
                        $discountAmount = $invoices->sum('discount');
                        $servicesAfterDiscount = $invoices->sum('servicesAmountSum') - $invoices->sum('discount');
                        $internalPartsAmount = $invoices->sum('invoiceDetailsPartsAmountSum') + $invoices->sum('internalPartsAmountSum');
                        $externalPartsAmount = $invoices->sum('externalPartsAmountSum');
                        $deliveryAmount = $invoices->sum('delivery');
                        $amount = round($servicesAfterDiscount + $internalPartsAmount + $externalPartsAmount + $deliveryAmount,3);
    
                        $parts_difference = round($this->technicians->sum('PartDifferenceDebit') - $this->technicians->sum('PartDifferenceCredit'),3);
                        $income = abs($this->technicians->sum('incomeAccountDebit') - $this->technicians->sum('incomeAccountCredit'));
                        $cost = abs($this->technicians->sum('costAccountDebit') - $this->technicians->sum('costAccountCredit'));
                        $services = abs($this->technicians->sum('servicesCostCenterDebit') - $this->technicians->sum('servicesCostCenterCredit'));
                        $parts = abs($this->technicians->sum('partsCostCenterDebit') - $this->technicians->sum('partsCostCenterCredit'));
                        $delivery = abs($this->technicians->sum('deliveryCostCenterDebit') - $this->technicians->sum('deliveryCostCenterCredit'));
    
                    @endphp
                    <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $parts_difference < 0  ? 'text-red-500' : ''}}"> {{ $parts_difference == 0 ? '-' : number_format($parts_difference,3) }}</x-borderd-th>
                    <x-borderd-th>{{ $services > 0 ? number_format($services,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $parts > 0 ? number_format($parts,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $delivery > 0 ? number_format($delivery,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $income > 0 ? number_format($income,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $cost > 0 ? number_format($cost,3) : '-' }}</x-borderd-th>
                </tr>
    
            </x-thead>
    
        </x-table>

    </div>


</div>