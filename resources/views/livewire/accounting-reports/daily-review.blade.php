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

        <div class="my-5">

            <x-table>
                <x-thead>
                    <tr>
                        <x-th class="border border-gray-300 dark:border-gray-700" rowspan="2">{{ __('messages.department') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700" colspan="2">{{ __('messages.invoices') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700" colspan="2">{{ __('messages.accounts') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700" colspan="3">{{ __('messages.cost_centers') }}</x-th>
                    </tr>
                    <tr>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ __('messages.amount') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ __('messages.parts_difference') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ __('messages.income_account_id') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ __('messages.cost_account_id') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ __('messages.service') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ __('messages.parts') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ __('messages.delivery') }}</x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach ($this->departments as $department)
                        <x-tr>
                            <x-td class="border border-gray-300 dark:border-gray-700 !whitespace-normal">{{ $department->name }}</x-td>
                            <x-td class="border border-gray-300 dark:border-gray-700">{{ $this->invoices->where('order.department_id',$department->id)->sum('amount') > 0 ? number_format($this->invoices->where('order.department_id',$department->id)->sum('amount'),3) : '-' }}</x-td>
                            <x-td class="border border-gray-300 dark:border-gray-700">{{ round($this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('debit') - $this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('credit'),3) == 0 ? '-' : number_format(round($this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('debit') - $this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('credit'),3),3)  }}</x-td>
                            <x-td class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->sum('absolute'),3) : '-' }}</x-td>
                            <x-td class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->where('account_id',$department->cost_account_id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->cost_account_id)->sum('absolute'),3) : '-' }}</x-td>
                            <x-td class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',1)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',1)->sum('absolute'),3) : '-' }}</x-td>
                            <x-td class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',2)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',2)->sum('absolute'),3) : '-' }}</x-td>
                            <x-td class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',3)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',3)->sum('absolute'),3) : '-' }}</x-td>
                        </x-tr>
                    @endforeach
                </tbody>
                <x-tfoot>
                    <tr>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ __('messages.total') }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ $this->invoices->sum('amount') > 0 ? number_format($this->invoices->sum('amount'),3) : '-' }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ $this->partsAccountTransactions->sum('absolute') > 0 ? number_format($this->partsAccountTransactions->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('cost_account_id'))->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('cost_account_id'))->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',1)->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',1)->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',2)->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',2)->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-gray-300 dark:border-gray-700">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',3)->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',3)->sum('absolute'),3) : '-' }}</x-th>
                    </tr>
                </x-tfoot>
            </x-table>

        </div>

        @foreach ($this->departments as $department)
        <div class="my-5">
            <h2 class=" mb-3 font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ $department->name }}
            </h2>
            <x-table>
                <x-thead>
                    <tr>
                        <x-th colspan="2" rowspan="2">{{ __('messages.technician') }}</x-th>
                        <x-th colspan="2">{{ __('messages.invoices') }}</x-th>
                        <x-th colspan="2">{{ __('messages.accounts') }}</x-th>
                        <x-th colspan="3">{{ __('messages.cost_centers') }}</x-th>
                    </tr>
                    <tr>
                        <x-th>{{ __('messages.amount') }}</x-th>
                        <x-th>{{ __('messages.parts_difference') }}</x-th>
                        <x-th>{{ __('messages.income_account_id') }}</x-th>
                        <x-th>{{ __('messages.cost_account_id') }}</x-th>
                        <x-th>{{ __('messages.service') }}</x-th>
                        <x-th>{{ __('messages.parts') }}</x-th>
                        <x-th>{{ __('messages.delivery') }}</x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach ($department->technicians->where('title_id',11) as $technician)
                    <x-tr>
                        @if ($loop->index == 0)
                            <x-td rowspan="{{ $department->technicians->where('title_id',11)->count() }}">{{ $technician->title->name }}</x-td>
                        @endif
                        <x-td>{{ $technician->name }}</x-td>
                        <x-td>{{ $this->invoices->where('order.technician_id',$technician->id)->sum('amount') > 0 ? number_format($this->invoices->where('order.technician_id',$technician->id)->sum('amount'),3) : '-' }}</x-td>
                        <x-td>{{ round($this->partsAccountTransactions->where('user_id',$technician->id)->sum('debit') - $this->partsAccountTransactions->where('user_id',$technician->id)->sum('credit'),3) == 0 ? '-' : number_format(round($this->partsAccountTransactions->where('user_id',$technician->id)->sum('debit') - $this->partsAccountTransactions->where('user_id',$technician->id)->sum('credit'),3),3)  }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->sum('absolute'),3) : '-' }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->cost_account_id)->where('user_id',$technician->id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->cost_account_id)->where('user_id',$technician->id)->sum('absolute'),3) : '-' }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',1)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',1)->sum('absolute'),3) : '-' }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',2)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',2)->sum('absolute'),3) : '-' }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',3)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',3)->sum('absolute'),3) : '-' }}</x-td>
                    </x-tr>
                    @endforeach
                    @foreach ($department->technicians->where('title_id',26) as $technician)
                    <x-tr>
                        @if ($loop->index == 0)
                            <x-td rowspan="{{ $department->technicians->where('title_id',26)->count() }}">{{ $technician->title->name }}</x-td>
                        @endif
                        <x-td>{{ $technician->name }}</x-td>
                        <x-td>{{ $this->invoices->where('order.technician_id',$technician->id)->sum('amount') > 0 ? number_format($this->invoices->where('order.technician_id',$technician->id)->sum('amount'),3) : '-' }}</x-td>
                        <x-td>{{ round($this->partsAccountTransactions->where('user_id',$technician->id)->sum('debit') - $this->partsAccountTransactions->where('user_id',$technician->id)->sum('credit'),3) == 0 ? '-' : number_format(round($this->partsAccountTransactions->where('user_id',$technician->id)->sum('debit') - $this->partsAccountTransactions->where('user_id',$technician->id)->sum('credit'),3),3)  }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->sum('absolute'),3) : '-' }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->cost_account_id)->where('user_id',$technician->id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->cost_account_id)->where('user_id',$technician->id)->sum('absolute'),3) : '-' }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',1)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',1)->sum('absolute'),3) : '-' }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',2)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',2)->sum('absolute'),3) : '-' }}</x-td>
                        <x-td>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',3)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('user_id',$technician->id)->where('cost_center_id',3)->sum('absolute'),3) : '-' }}</x-td>
                    </x-tr>
                    @endforeach
                </tbody>
                <x-tfoot>
                    <tr>
                        <x-th colspan="2">{{ __('messages.total') }}</x-th>
                        <x-th>{{ $this->invoices->sum('amount') > 0 ? number_format($this->invoices->sum('amount'),3) : '-' }}</x-th>
                        <x-th>{{ round($this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('debit') - $this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('credit'),3) == 0 ? '-' : number_format(round($this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('debit') - $this->partsAccountTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('credit'),3),3)  }}</x-th>
                        <x-th>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->sum('absolute'),3) : '-' }}</x-th>
                        <x-th>{{ $this->voucherDetails->where('account_id',$department->cost_account_id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->cost_account_id)->sum('absolute'),3) : '-' }}</x-th>
                        <x-th>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',1)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',1)->sum('absolute'),3) : '-' }}</x-th>
                        <x-th>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',2)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',2)->sum('absolute'),3) : '-' }}</x-th>
                        <x-th>{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',3)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',3)->sum('absolute'),3) : '-' }}</x-th>
                    </tr>
                </x-tfoot>
            </x-table>
        </div>
            
        @endforeach

</div>
