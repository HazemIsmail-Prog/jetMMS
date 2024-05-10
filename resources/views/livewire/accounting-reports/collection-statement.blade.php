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
                    <x-borderd-th rowspan="2">{{ __('messages.technician')}}</x-borderd-th>
                    <x-borderd-th colspan="4">{{ __('messages.invoices') }}</x-borderd-th>
                    <x-borderd-th colspan="4">{{ __('messages.accounts') }}</x-borderd-th>
                </tr>
                <tr>
                    <x-borderd-th class="!w-1/12">{{ __('messages.amount') }}</x-borderd-th>
                    <x-borderd-th class="!w-1/12">{{ __('messages.cash')}}</x-borderd-th>
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
                                $amount = $this->invoices->where('order.technician_id',$technician->id)->sum('amount');
                                $cash = $this->invoices->where('order.technician_id',$technician->id)->sum('cash_amount');
                                $knet = $this->invoices->where('order.technician_id',$technician->id)->sum('knet_amount');
                                $remaining = $this->invoices->where('order.technician_id',$technician->id)->sum('remaining_amount');
                                $bank = $this->bankTransactions->where('user_id',$technician->id)->sum('absolute');
                                $bank_charges = $this->bankChargesTransactions->where('user_id',$technician->id)->sum('absolute');
                                $cashTransactions = $this->cashTransactions->where('user_id',$technician->id)->sum('debit');
                                $cashNotEqual = (string)$cash != (string)$cashTransactions;
                                $knetNotEqual = (string)$knet != (string)($bank + $bank_charges);
                            @endphp
                            
                            <x-borderd-td>{{ $technician->name }}</x-borderd-td>
                            <x-borderd-td>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-td>
                            <x-borderd-td class=" {{ $cashNotEqual ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-borderd-td>
                            <x-borderd-td class=" {{ $knetNotEqual ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-borderd-td>
                            <x-borderd-td>{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-borderd-td>
                            <x-borderd-td>{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-borderd-td>
                            <x-borderd-td>{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-borderd-td>
                            <x-borderd-td class=" {{ $knetNotEqual ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-borderd-td>
                            <x-borderd-td class=" {{ $cashNotEqual ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-borderd-td>
                        </x-tr>
                    @endforeach

                    @if ($department->technicians->whereNotIn('title_id',$title->id)->count() > 0  && $department->technicians->whereIn('title_id',$title->id)->count() > 0)
                        <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">

                            @php
                                $amount = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',$title->id)->pluck('id'))->sum('amount');
                                $cash = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',$title->id)->pluck('id'))->sum('cash_amount');
                                $knet = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',$title->id)->pluck('id'))->sum('knet_amount');
                                $remaining = $this->invoices->whereIn('order.technician_id',$department->technicians->where('title_id',$title->id)->pluck('id'))->sum('remaining_amount');
                                $bank = $this->bankTransactions->whereIn('user_id',$department->technicians->where('title_id',$title->id)->pluck('id'))->sum('absolute');
                                $bank_charges = $this->bankChargesTransactions->whereIn('user_id',$department->technicians->where('title_id',$title->id)->pluck('id'))->sum('absolute');
                                $cashTransactions = $this->cashTransactions->whereIn('user_id',$department->technicians->where('title_id',$title->id)->pluck('id'))->sum('debit');
                                $cashNotEqual = (string)$cash != (string)$cashTransactions;
                                $knetNotEqual = (string)$knet != (string)($bank + $bank_charges);
                            @endphp

                            <x-borderd-th>{{ $title->name }}</x-borderd-th>
                            <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                            <x-borderd-th class=" {{ $cashNotEqual ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-borderd-th>
                            <x-borderd-th class=" {{ $knetNotEqual ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-borderd-th>
                            <x-borderd-th>{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-borderd-th>
                            <x-borderd-th class=" {{ $knetNotEqual ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-borderd-th>
                            <x-borderd-th class=" {{ $cashNotEqual ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-borderd-th>
                        </tr>
                    @endif
                @endforeach

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
                        $cashNotEqual = (string)$cash != (string)$cashTransactions;
                        $knetNotEqual = (string)$knet != (string)($bank + $bank_charges);
                    @endphp

                    <x-borderd-th>{{ __('messages.total')}}</x-borderd-th>
                    <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $cashNotEqual ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $knetNotEqual ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $knetNotEqual ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $cashNotEqual ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-borderd-th>
                </tr>
            </x-tfoot>

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
                <x-borderd-th class="!w-1/12">{{ __('messages.cash')}}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.knet') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.remaining_amount') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.net_knet') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.knet_charges') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{__('messages.knet_total') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.cash_total')}}</x-borderd-th>
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
                    $cashNotEqual = (string)$cash != (string)$cashTransactions;
                    $knetNotEqual = (string)$knet != (string)($bank + $bank_charges);
                @endphp
                    <x-borderd-th>{{ $amount > 0 ? number_format($amount,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $cashNotEqual ? 'bg-red-200' : '' }}">{{ $cash > 0 ? number_format($cash,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $knetNotEqual ? 'bg-red-200' : '' }}">{{ $knet > 0 ? number_format($knet,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $remaining > 0 ? number_format($remaining,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $bank > 0 ? number_format($bank,3) : '-' }}</x-borderd-th>
                    <x-borderd-th>{{ $bank_charges > 0 ? number_format($bank_charges,3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $knetNotEqual ? 'bg-red-200' : '' }}">{{ ($bank + $bank_charges) > 0 ? number_format(($bank + $bank_charges),3) : '-' }}</x-borderd-th>
                    <x-borderd-th class=" {{ $cashNotEqual ? 'bg-red-200' : '' }}">{{ $cashTransactions > 0 ? number_format($cashTransactions,3) :'-' }}</x-borderd-th>
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