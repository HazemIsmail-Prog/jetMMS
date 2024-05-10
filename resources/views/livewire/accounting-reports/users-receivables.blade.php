<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.users_receivables') }}
            </h2>

        </div>
    </x-slot>

    {{-- <div class=" grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 no-print">

        <div class=" col-span-1 md:col-span-2 xl:col-span-4">
            <x-label for="date">{{ __('messages.date') }}</x-label>
            <x-input type="date" class="w-full" id="date" wire:model.live="date" />
        </div>
    </div> --}}


    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th>{{ __('messages.title')}}</x-th>
                <x-th>{{ __('messages.department') }}</x-th>
                @foreach ($this->receivableAccounts as $account)
                <x-th class="!w-1/12">{{ $account->name }}</x-th>
                @endforeach
                <x-th class="!w-1/12">{{ __('messages.total') }}</x-th>
            </tr>
        </x-thead>

        <tbody>
            @foreach ($this->users as $user)
                @php
                    $total_debit = $this->voucherDetails->where('user_id',$user->id)->sum('debit');
                    $total_credit = $this->voucherDetails->where('user_id',$user->id)->sum('credit');
                    $total_amount = round($total_debit-$total_credit,3);
                @endphp
                @if ($total_amount != 0)
                    <x-tr>
                        <x-td>{{ $user->name }}</x-td>
                        <x-td>{{ $user->title->name }}</x-td>
                        <x-td>{{ $user->department->name }}</x-td>
                        @foreach ($this->receivableAccounts as $account)
                        @php
                            $debit = $this->voucherDetails->where('account_id',$account->id)->where('user_id',$user->id)->sum('debit');
                            $credit = $this->voucherDetails->where('account_id',$account->id)->where('user_id',$user->id)->sum('credit');
                            $amount = round($debit-$credit,3);
                        @endphp
                        <x-td class="!w-1/12 {{ $amount < 0 ? 'text-red-500' : '' }}">{{ $amount == 0 ? '-' : number_format($amount,3) }}</x-td>
                        @endforeach
                        <x-td class="!w-1/12 {{ $total_amount < 0 ? 'text-red-500' : '' }}">{{ $total_amount == 0 ? '-' : number_format($total_amount,3) }}</x-td>
                    </x-tr>
                @endif
            @endforeach
        </tbody>

        <x-tfoot>
            <tr>
                <x-th colspan="3">{{ __('messages.total') }}</x-th>
                @foreach ($this->receivableAccounts as $account)
                @php
                    $debit = $this->voucherDetails->where('account_id',$account->id)->sum('debit');
                    $credit = $this->voucherDetails->where('account_id',$account->id)->sum('credit');
                    $amount = round($debit-$credit,3);
                @endphp
                <x-th class="!w-1/12 {{ $amount < 0 ? 'text-red-500' : '' }}">{{ $amount == 0 ? '-' : number_format($amount,3) }}</x-th>
                @endforeach
                @php
                    $total_debit = $this->voucherDetails->sum('debit');
                    $total_credit = $this->voucherDetails->sum('credit');
                    $total_amount = round($total_debit-$total_credit,3);
                @endphp
                <x-th class="!w-1/12 {{ $total_amount < 0 ? 'text-red-500' : '' }}">{{ $total_amount == 0 ? '-' : number_format($total_amount,3) }}</x-th>
            </tr>
        </x-tfoot>

    </x-table>

</div>