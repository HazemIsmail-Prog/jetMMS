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

            <x-table class=" table-fixed">
                <x-thead>
                    <tr>
                        <x-th class="border border-black" rowspan="2">{{ __('messages.department') }}</x-th>
                        <x-th class="border border-black" colspan="4">{{ __('messages.invoices') }}</x-th>
                        <x-th class="border border-black" colspan="2">{{ __('messages.accounts') }}</x-th>
                        <x-th class="border border-black" colspan="3">{{ __('messages.cost_centers') }}</x-th>
                    </tr>
                    <tr>
                        <x-th class="border border-black">{{ __('messages.amount') }}</x-th>
                        <x-th class="border border-black">{{ __('messages.cash') }}</x-th>
                        <x-th class="border border-black">{{ __('messages.knet') }}</x-th>
                        <x-th class="border border-black">{{ __('messages.remaining_amount') }}</x-th>
                        <x-th class="border border-black">{{ __('messages.income_account_id') }}</x-th>
                        <x-th class="border border-black">{{ __('messages.cost_account_id') }}</x-th>
                        <x-th class="border border-black">{{ __('messages.service') }}</x-th>
                        <x-th class="border border-black">{{ __('messages.parts') }}</x-th>
                        <x-th class="border border-black">{{ __('messages.delivery') }}</x-th>
                    </tr>
                </x-thead>
                <tbody>
                    @foreach ($this->departments as $department)
                        <x-tr>
                            <x-td class="border border-black !whitespace-normal">{{ $department->name }}</x-td>
                            <x-td class="border border-black">{{ $this->invoices->where('order.department_id',$department->id)->sum('amount') > 0 ? number_format($this->invoices->where('order.department_id',$department->id)->sum('amount'),3) : '-' }}</x-td>
                            <x-td class="border border-black">{{ $this->invoices->where('order.department_id',$department->id)->sum('cash_amount') > 0 ? number_format($this->invoices->where('order.department_id',$department->id)->sum('cash_amount'),3) : '-' }}</x-td>
                            <x-td class="border border-black">{{ $this->invoices->where('order.department_id',$department->id)->sum('knet_amount') > 0 ? number_format($this->invoices->where('order.department_id',$department->id)->sum('knet_amount'),3) : '-' }}</x-td>
                            <x-td class="border border-black">{{ $this->invoices->where('order.department_id',$department->id)->sum('remaining_amount') > 0 ? number_format($this->invoices->where('order.department_id',$department->id)->sum('remaining_amount'),3) : '-' }}</x-td>


                            <x-td class="border border-black">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->sum('absolute'),3) : '-' }}</x-td>
                            <x-td class="border border-black">{{ $this->voucherDetails->where('account_id',$department->cost_account_id)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->cost_account_id)->sum('absolute'),3) : '-' }}</x-td>
                            <x-td class="border border-black">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',1)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',1)->sum('absolute'),3) : '-' }}</x-td>
                            <x-td class="border border-black">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',2)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',2)->sum('absolute'),3) : '-' }}</x-td>
                            <x-td class="border border-black">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',3)->sum('absolute') > 0 ? number_format($this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',3)->sum('absolute'),3) : '-' }}</x-td>
                        </x-tr>
                    @endforeach
                </tbody>
                <x-tfoot>
                    <tr>
                        <x-th class="border border-black">{{ __('messages.total') }}</x-th>
                        <x-th class="border border-black">{{ $this->invoices->sum('amount') > 0 ? number_format($this->invoices->sum('amount'),3) : '-' }}</x-th>
                        <x-th class="border border-black">{{ $this->invoices->sum('cash_amount') > 0 ? number_format($this->invoices->sum('cash_amount'),3) : '-' }}</x-th>
                        <x-th class="border border-black">{{ $this->invoices->sum('knet_amount') > 0 ? number_format($this->invoices->sum('knet_amount'),3) : '-' }}</x-th>
                        <x-th class="border border-black">{{ $this->invoices->sum('remaining_amount') > 0 ? number_format($this->invoices->sum('remaining_amount'),3) : '-' }}</x-th>

                        <x-th class="border border-black">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-black">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('cost_account_id'))->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('cost_account_id'))->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-black">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',1)->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',1)->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-black">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',2)->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',2)->sum('absolute'),3) : '-' }}</x-th>
                        <x-th class="border border-black">{{ $this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',3)->sum('absolute') > 0 ? number_format($this->voucherDetails->whereIn('account_id',$this->departments->pluck('income_account_id'))->where('cost_center_id',3)->sum('absolute'),3) : '-' }}</x-th>
                    </tr>
                </x-tfoot>
            </x-table>

        {{-- </div> --}}
    {{-- @endforeach --}}

</div>
