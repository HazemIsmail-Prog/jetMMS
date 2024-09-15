<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.daily_review') }}
            </h2>

        </div>
    </x-slot>

    <div class=" flex items-end gap-3 no-print">

        <div class=" col-span-1 md:col-span-2 xl:col-span-4">
            <x-label for="start_date">{{ __('messages.date') }}</x-label>
            <x-input type="date" class="w-full" id="start_date" wire:model="start_date" />
            <x-input type="date" class="w-full" id="end_date" wire:model="end_date" />
        </div>

        <x-button type="button"
        wire:click="$dispatch('dateUpdated')">{{ __('messages.refresh') }}</x-button> 
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
    
                            @foreach ($this->technicians->where('department_id',$department->id)->where('title_id',$title->id) as $technician)
    
                                <x-tr>
                                    @php
                                        $amount = round($technician->grandTotal,3);
    
                                        $parts_difference = round($technician->PartDifferenceDebit - $technician->PartDifferenceCredit,3);
                                        $income = abs($technician->incomeAccountDebit - $technician->incomeAccountCredit);
                                        $cost = abs($technician->costAccountDebit - $technician->costAccountCredit);
                                        $services = abs($technician->servicesCostCenterDebit - $technician->servicesCostCenterCredit);
                                        $parts = abs($technician->partsCostCenterDebit - $technician->partsCostCenterCredit);
                                        $delivery = abs($technician->deliveryCostCenterDebit - $technician->deliveryCostCenterCredit);
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
                                        $technicians = $this->technicians->where('department_id',$department->id)->where('title_id',$title->id);
                                        $amount = round($technicians->sum('grandTotal'),3);
    
                                        $parts_difference = round($technicians->sum('PartDifferenceDebit') - $technicians->sum('PartDifferenceCredit'),3);
                                        $income = abs($technicians->sum('incomeAccountDebit') - $technicians->sum('incomeAccountCredit'));
                                        $cost = abs($technicians->sum('costAccountDebit') - $technicians->sum('costAccountCredit'));
                                        $services = abs($technicians->sum('servicesCostCenterDebit') - $technicians->sum('servicesCostCenterCredit'));
                                        $parts = abs($technicians->sum('partsCostCenterDebit') - $technicians->sum('partsCostCenterCredit'));
                                        $delivery = abs($technicians->sum('deliveryCostCenterDebit') - $technicians->sum('deliveryCostCenterCredit'));
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
                                $technicians = $this->technicians->where('department_id',$department->id);
                                $amount = round($technicians->sum('grandTotal'),3);
    
                                $parts_difference = round($technicians->sum('PartDifferenceDebit') - $technicians->sum('PartDifferenceCredit'),3);
                                $income = abs($technicians->sum('incomeAccountDebit') - $technicians->sum('incomeAccountCredit'));
                                $cost = abs($technicians->sum('costAccountDebit') - $technicians->sum('costAccountCredit'));
                                $services = abs($technicians->sum('servicesCostCenterDebit') - $technicians->sum('servicesCostCenterCredit'));
                                $parts = abs($technicians->sum('partsCostCenterDebit') - $technicians->sum('partsCostCenterCredit'));
                                $delivery = abs($technicians->sum('deliveryCostCenterDebit') - $technicians->sum('deliveryCostCenterCredit'));
    
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
                        $amount = round($this->technicians->sum('grandTotal'),3);
    
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