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
                    <x-th class=" border border-gray-700 dark:border-gray-100" colspan="4">{{ __('messages.invoices') }}
                    </x-th>
                    <x-th class=" border border-gray-700 dark:border-gray-100" colspan="4">{{ __('messages.accounts') }}
                    </x-th>
                </tr>
                <tr>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.amount') }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.cash')
                        }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.knet') }}
                    </x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.remaining_amount') }}
                    </x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.net_knet') }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.knet_charges') }}
                    </x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{
                        __('messages.knet_total') }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.cash_total')
                        }}</x-th>
                </tr>
            </x-thead>





            <tbody>

                {{-- company_technicians rows --}}
                @foreach ($department->technicians->where('title_id',11) as $technician)
                <x-tr>
                    @php
                    $amount = $this->invoices->where('order.technician_id',$technician->id)->sum('amount');
                    $cash = $this->invoices->where('order.technician_id',$technician->id)->sum('cash_amount');
                    $knet = $this->invoices->where('order.technician_id',$technician->id)->sum('knet_amount');
                    $remaining = $this->invoices->where('order.technician_id',$technician->id)->sum('remaining_amount');
                    $bank = $this->bankTransactions->where('user_id',$technician->id)->sum('absolute');
                    $bank_charges = $this->bankChargesTransactions->where('user_id',$technician->id)->sum('absolute');
                    $cashTransactions = $this->cashTransactions->where('user_id',$technician->id)->sum('debit');
                    @endphp
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $technician->name }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-td>
                </x-tr>
                @endforeach

                {{-- company_technicians totals --}}
                @if ($department->technicians->where('title_id',11)->count() > 0 &&
                $department->technicians->where('title_id',26)->count() > 0)
                <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">
                    @php
                    $amount = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('amount');
                    $cash = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('cash_amount');
                    $knet = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('knet_amount');
                    $remaining = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('remaining_amount');
                    $bank = $this->bankTransactions->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('absolute');
                    $bank_charges = $this->bankChargesTransactions->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('absolute');
                    $cashTransactions = $this->cashTransactions->whereIn('user_id',$department->technicians->where('title_id',11)->pluck('id'))->sum('debit');
                    @endphp
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ __('messages.company_technicians')}}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-th>
                </tr>
                @endif

                {{-- commission_technicians rows --}}
                @foreach ($department->technicians->where('title_id',26) as $technician)
                <x-tr>
                    @php
                    $amount = $this->invoices->where('order.technician_id',$technician->id)->sum('amount');
                    $cash = $this->invoices->where('order.technician_id',$technician->id)->sum('cash_amount');
                    $knet = $this->invoices->where('order.technician_id',$technician->id)->sum('knet_amount');
                    $remaining = $this->invoices->where('order.technician_id',$technician->id)->sum('remaining_amount');
                    $bank = $this->bankTransactions->where('user_id',$technician->id)->sum('absolute');
                    $bank_charges = $this->bankChargesTransactions->where('user_id',$technician->id)->sum('absolute');
                    $cashTransactions = $this->cashTransactions->where('user_id',$technician->id)->sum('debit');
                    @endphp
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $technician->name }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100">{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-td>
                    <x-td class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-td>
                </x-tr>
                @endforeach

                {{-- commission_technicians totals --}}
                @if ($department->technicians->where('title_id',11)->count() > 0 &&
                $department->technicians->where('title_id',26)->count() > 0)
                <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">
                    @php
                    $amount = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('amount');
                    $cash = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('cash_amount');
                    $knet = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('knet_amount');
                    $remaining = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('remaining_amount');
                    $bank = $this->bankTransactions->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('absolute');
                    $bank_charges = $this->bankChargesTransactions->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('absolute');
                    $cashTransactions = $this->cashTransactions->whereIn('user_id',$department->technicians->where('title_id',26)->pluck('id'))->sum('debit');
                    @endphp
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ __('messages.commission_technicians')}}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-th>
                </tr>
                @endif
            </tbody>

            {{-- single table total --}}
            <x-tfoot>
                <tr>
                    @php
                    $amount = $this->invoices->where('order.department_id',$department->id)->sum('amount');
                    $cash = $this->invoices->where('order.department_id',$department->id)->sum('cash_amount');
                    $knet = $this->invoices->where('order.department_id',$department->id)->sum('knet_amount');
                    $remaining = $this->invoices->where('order.department_id',$department->id)->sum('remaining_amount');
                    $bank = $this->bankTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('absolute');
                    $bank_charges = $this->bankChargesTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('absolute');
                    $cashTransactions = $this->cashTransactions->whereIn('user_id',$department->technicians->pluck('id'))->sum('debit');
                    @endphp
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ __('messages.total')}}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-th>
                </tr>
            </x-tfoot>







        </x-table>
    </div>
    @endforeach


    <x-table>
        <x-thead>
            <tr>
                <x-th class=" border border-gray-700 dark:border-gray-100" rowspan="3">{{ __('messages.grand_total')}}</x-th>
                <x-th class=" border border-gray-700 dark:border-gray-100" colspan="4">{{ __('messages.invoices') }}</x-th>
                <x-th class=" border border-gray-700 dark:border-gray-100" colspan="4">{{ __('messages.accounts') }}</x-th>
            </tr>
            <tr>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.amount') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.cash')}}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.knet') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.remaining_amount') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.net_knet') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.knet_charges') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{__('messages.knet_total') }}</x-th>
                <x-th class="border border-gray-700 dark:border-gray-100 !w-1/12">{{ __('messages.cash_total')}}</x-th>
            </tr>
            <tr>
                @php
                $amount = $this->invoices->sum('amount');
                $cash = $this->invoices->sum('cash_amount');
                $knet = $this->invoices->sum('knet_amount');
                $remaining = $this->invoices->sum('remaining_amount');
                $bank = $this->bankTransactions->sum('absolute');
                $bank_charges = $this->bankChargesTransactions->sum('absolute');
                $cashTransactions = $this->cashTransactions->sum('debit');
                @endphp
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100">{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$knet != (string)($bank + $bank_charges) ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-th>
                    <x-th class="border border-gray-700 dark:border-gray-100 {{ (string)$cash != (string)$cashTransactions ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-th>
            </tr>
        </x-thead>
    </x-table>


    <div class=" border border-green-500 rounded-lg p-5 my-5 flex flex-col items-center justify-center gap-3">

        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.bank_deposit_required') }}
        </h2>
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ number_format($this->cashTransactions->sum('debit') - $this->cashTransactions->sum('credit'),3) }}
        </h2>
    </div>

</div>