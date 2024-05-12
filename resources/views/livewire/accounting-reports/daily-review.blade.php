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
            <x-label for="date">{{ __('messages.date') }}</x-label>
            <x-input type="date" class="w-full" id="date" wire:model.live="date" />
        </div>
    </div>

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
                                    $amount = $this->invoices->where('order.technician_id',$technician->id)->sum('amount');
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
                                    $amount = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',$title->id)->pluck('id'))->sum('amount');
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
                            $amount = $this->invoices->where('order.department_id',$department->id)->sum('amount');                 
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
                    $amount = $this->invoices->sum('amount');
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