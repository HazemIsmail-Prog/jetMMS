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
                    <x-th class=" border border-gray-700 dark:border-gray-100" rowspan="2">{{ __('messages.technician')
                        }}</x-th>
                    <x-th class=" border border-gray-700 dark:border-gray-100" colspan="2">{{ __('messages.invoices') }}
                    </x-th>
                    <x-th class=" border border-gray-700 dark:border-gray-100" colspan="3">{{
                        __('messages.cost_centers') }}</x-th>
                    <x-th class=" border border-gray-700 dark:border-gray-100" colspan="2">{{ __('messages.accounts') }}
                    </x-th>
                </tr>
                <tr>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.amount') }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.parts_difference')
                        }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.services') }}
                    </x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.parts') }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.delivery') }}
                    </x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{
                        __('messages.income_account_id') }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.cost_account_id')
                        }}</x-th>
                </tr>
            </x-thead>





            <tbody>

                @foreach ($department->technicians->where('title_id',11) as $technician)
                <x-tr>
                    @php
                    $amount = $this->invoices->where('order.technician_id',$technician->id)->sum('amount');
                    $parts_difference =
                    round($this->partsAccountTransactions->where('user_id',$technician->id)->sum('debit') -
                    $this->partsAccountTransactions->where('user_id',$technician->id)->sum('credit'),3);
                    $income =
                    $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->sum('absolute');
                    $cost =
                    $this->voucherDetails->where('account_id',$department->cost_account_id)->where('user_id',$technician->id)->sum('absolute');
                    $services =
                    $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',1)->sum('absolute');
                    $parts =
                    $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',2)->sum('absolute');
                    $delivery =
                    $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',3)->sum('absolute');
                    @endphp
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $technician->name }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3)
                        : '-' }}</x-td>
                    <x-td
                        class="border border-gray-700 dark:border-gray-100 {{ $parts_difference < 0 ? 'text-red-500' : '' }}">
                        {{ $parts_difference == 0 ? '-' :
                        number_format($parts_difference,3) }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $services > 0 ?
                        number_format($services,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $parts > 0 ? number_format($parts,3) :
                        '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $delivery > 0 ?
                        number_format($delivery,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $income > 0 ? number_format($income,3)
                        : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $cost > 0 ? number_format($cost,3) :
                        '-' }}</x-td>
                </x-tr>
                @endforeach


                @if ($department->technicians->where('title_id',11)->count() > 0 &&
                $department->technicians->where('title_id',26)->count() > 0)
                @php
                $amount =
                $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('amount');
                $parts_difference =
                round($this->partsAccountTransactions->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('debit')
                -
                $this->partsAccountTransactions->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('credit'),3);
                $income =
                $this->voucherDetails->where('account_id',$department->income_account_id)->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('absolute');
                $cost =
                $this->voucherDetails->where('account_id',$department->cost_account_id)->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('absolute');
                $services =
                $this->voucherDetails->where('account_id',$department->income_account_id)->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->where('cost_center_id',1)->sum('absolute');
                $parts =
                $this->voucherDetails->where('account_id',$department->income_account_id)->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->where('cost_center_id',2)->sum('absolute');
                $delivery =
                $this->voucherDetails->where('account_id',$department->income_account_id)->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->where('cost_center_id',3)->sum('absolute');
                @endphp
                <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ __('messages.company_technicians') }}
                    </x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3)
                        : '-' }}</x-th>
                    <x-th
                        class="border border-gray-700 dark:border-gray-100 {{ $parts_difference < 0 ? 'text-red-500' : '' }}">
                        {{ $parts_difference == 0 ? '-' :
                        number_format($parts_difference,3) }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $services > 0 ?
                        number_format($services,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $parts > 0 ? number_format($parts,3) :
                        '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $delivery > 0 ?
                        number_format($delivery,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $income > 0 ? number_format($income,3)
                        : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $cost > 0 ? number_format($cost,3) :
                        '-' }}</x-th>
                </tr>
                @endif


                @foreach ($department->technicians->where('title_id',26) as $technician)
                <x-tr>
                    @php
                    $amount = $this->invoices->where('order.technician_id',$technician->id)->sum('amount');
                    $parts_difference =
                    round($this->partsAccountTransactions->where('user_id',$technician->id)->sum('debit') -
                    $this->partsAccountTransactions->where('user_id',$technician->id)->sum('credit'),3);
                    $income =
                    $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->sum('absolute');
                    $cost =
                    $this->voucherDetails->where('account_id',$department->cost_account_id)->where('user_id',$technician->id)->sum('absolute');
                    $services =
                    $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',1)->sum('absolute');
                    $parts =
                    $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',2)->sum('absolute');
                    $delivery =
                    $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',3)->sum('absolute');
                    @endphp
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $technician->name }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3)
                        : '-' }}</x-td>
                    <x-td
                        class=" border border-gray-700 dark:border-gray-100 {{ $parts_difference < 0 ? 'text-red-500' : '' }}">
                        {{ $parts_difference == 0 ? '-' :
                        number_format($parts_difference,3) }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $services > 0 ?
                        number_format($services,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $parts > 0 ? number_format($parts,3) :
                        '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $delivery > 0 ?
                        number_format($delivery,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $income > 0 ? number_format($income,3)
                        : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $cost > 0 ? number_format($cost,3) :
                        '-' }}</x-td>
                </x-tr>
                @endforeach
                @if ($department->technicians->where('title_id',11)->count() > 0 &&
                $department->technicians->where('title_id',26)->count() > 0)
                @php
                $amount =
                $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('amount');
                $parts_difference =
                round($this->partsAccountTransactions->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('debit')
                -
                $this->partsAccountTransactions->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('credit'),3);
                $income =
                $this->voucherDetails->where('account_id',$department->income_account_id)->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('absolute');
                $cost =
                $this->voucherDetails->where('account_id',$department->cost_account_id)->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('absolute');
                $services =
                $this->voucherDetails->where('account_id',$department->income_account_id)->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->where('cost_center_id',1)->sum('absolute');
                $parts =
                $this->voucherDetails->where('account_id',$department->income_account_id)->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->where('cost_center_id',2)->sum('absolute');
                $delivery =
                $this->voucherDetails->where('account_id',$department->income_account_id)->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->where('cost_center_id',3)->sum('absolute');
                @endphp
                <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ __('messages.commission_technicians')
                        }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3)
                        : '-' }}</x-th>
                    <x-th
                        class="border border-gray-700 dark:border-gray-100 {{ $parts_difference < 0 ? 'text-red-500' : '' }}">
                        {{ $parts_difference == 0 ? '-' :
                        number_format($parts_difference,3) }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $services > 0 ?
                        number_format($services,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $parts > 0 ? number_format($parts,3) :
                        '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $delivery > 0 ?
                        number_format($delivery,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $income > 0 ? number_format($income,3)
                        : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $cost > 0 ? number_format($cost,3) :
                        '-' }}</x-th>
                </tr>
                @endif
                @php
                $amount = $this->invoices->where('order.department_id',$department->id)->sum('amount');
                $parts_difference =
                round($this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('debit')
                -
                $this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('credit'),3);
                $income = $this->voucherDetails->where('account_id',$department->income_account_id)->sum('absolute');
                $cost = $this->voucherDetails->where('account_id',$department->cost_account_id)->sum('absolute');
                $services =
                $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',1)->sum('absolute');
                $parts =
                $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',2)->sum('absolute');
                $delivery =
                $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',3)->sum('absolute');
                @endphp
                <tr class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ __('messages.total') }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3)
                        : '-' }}</x-th>
                    <x-th
                        class="border border-gray-700 dark:border-gray-100 {{ $parts_difference < 0  ? 'text-red-500' : ''}}">
                        {{ $parts_difference == 0 ? '-' :
                        number_format($parts_difference,3) }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $services > 0 ?
                        number_format($services,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $parts > 0 ? number_format($parts,3) :
                        '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $delivery > 0 ?
                        number_format($delivery,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $income > 0 ? number_format($income,3)
                        : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $cost > 0 ? number_format($cost,3) :
                        '-' }}</x-th>
                </tr>


            </tbody>







        </x-table>
    </div>
    @endforeach
    <x-table>
        <x-thead>
            <tr>
                <x-th class=" border border-gray-700 dark:border-gray-100" rowspan="3">{{ __('messages.grand_total')
                    }}</x-th>
                <x-th class=" border border-gray-700 dark:border-gray-100" colspan="2">{{ __('messages.invoices') }}
                </x-th>
                <x-th class=" border border-gray-700 dark:border-gray-100" colspan="3">{{
                    __('messages.cost_centers') }}</x-th>
                <x-th class=" border border-gray-700 dark:border-gray-100" colspan="2">{{ __('messages.accounts') }}
                </x-th>
            </tr>
            <tr>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.amount') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.parts_difference')
                    }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.services') }}
                </x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.parts') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.delivery') }}
                </x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{
                    __('messages.income_account_id') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.cost_account_id')
                    }}</x-th>
            </tr>
            <tr>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ $this->invoices->sum('amount') > 0 ? number_format($this->invoices->sum('amount'),3) : '-'
                    }}</x-th>
                <x-th
                    class="border border-gray-700 dark:border-gray-100 !w-1/12 {{ round($this->partsAccountTransactions->sum('debit') - $this->partsAccountTransactions->sum('credit'),3) < 0 ? 'text-red-500' : '' }}">
                    {{ round($this->partsAccountTransactions->sum('debit') -
                    $this->partsAccountTransactions->sum('credit'),3) == 0 ? '-' :
                    number_format(round($this->partsAccountTransactions->sum('debit') -
                    $this->partsAccountTransactions->sum('credit'),3),3) }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{
                    $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',1)->sum('absolute')
                    > 0 ?
                    number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',1)->sum('absolute'),3)
                    : '-' }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{
                    $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',2)->sum('absolute')
                    > 0 ?
                    number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',2)->sum('absolute'),3)
                    : '-' }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{
                    $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',3)->sum('absolute')
                    > 0 ?
                    number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',3)->sum('absolute'),3)
                    : '-' }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{
                    $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->sum('absolute')
                    > 0 ?
                    number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->sum('absolute'),3)
                    : '-' }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{
                    $this->voucherDetails->whereIn('account_id',$this->departments->pluck('cost_account_id'))->sum('absolute')
                    > 0 ?
                    number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('cost_account_id'))->sum('absolute'),3)
                    : '-' }}</x-th>
            </tr>
        </x-thead>
    </x-table>
</div>