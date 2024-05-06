<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.account_statement') }}
            </h2>

        </div>
    </x-slot>

    {{-- hidden divs just to compile variable colors --}}
    <div class="hidden bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300"></div>
    <div class="hidden bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"></div>
    <div class="hidden bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300"></div>
    <div class="hidden bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300"></div>
    <div class="hidden bg-indigo-100 text-indigo-800 dark:bg-indigo-700 dark:text-indigo-300"></div>
    <div class="hidden bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300"></div>
    {{-- hidden divs just to compile variable colors --}}


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
                            <x-td class="!whitespace-normal">{{ $department->name }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->invoices->where('order.department_id',$department->id)->sum('amount') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->invoices->where('order.department_id',$department->id)->sum('cash_amount') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->invoices->where('order.department_id',$department->id)->sum('knet_amount') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->invoices->where('order.department_id',$department->id)->sum('remaining_amount') }}</x-td>


                            <x-td class="!whitespace-normal">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->sum('absolute') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->voucherDetails->where('account_id',$department->cost_account_id)->sum('absolute') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',1)->sum('absolute') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',2)->sum('absolute') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->where('cost_center_id',3)->sum('absolute') }}</x-td>
                            {{-- <x-td class="!whitespace-normal">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->sum('credit') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->sum('credit') }}</x-td>
                            <x-td class="!whitespace-normal">{{ $this->voucherDetails->where('account_id',$department->income_account_id)->sum('credit') }}</x-td> --}}
                        </x-tr>
                    @endforeach
                </tbody>
                <tfoot class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <x-tr>
                        <x-td class="!whitespace-normal">{{ __('messages.total') }}</x-td>
                        <x-td class="!whitespace-normal">{{ $this->invoices->sum('amount') }}</x-td>
                        <x-td class="!whitespace-normal">{{ $this->invoices->sum('cash_amount') }}</x-td>
                        <x-td class="!whitespace-normal">{{ $this->invoices->sum('knet_amount') }}</x-td>
                        <x-td class="!whitespace-normal">{{ $this->invoices->sum('remaining_amount') }}</x-td>
                    </x-tr>
                </tfoot>
            </x-table>

        {{-- </div> --}}
    {{-- @endforeach --}}

</div>
