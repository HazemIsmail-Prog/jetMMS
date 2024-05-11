<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.trial_balance') }}
            </h2>
        </div>
    </x-slot>

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">

        <div>
            <x-label for="start_date">{{ __('messages.date') }}</x-label>
            <x-input class="w-full text-center py-0" type="date" id="start_date" wire:model.live="filters.start_date" />
            <x-input class="w-full text-center py-0" type="date" id="end_date" wire:model.live="filters.end_date" />

        </div>

    </div>


    <x-table>
        <x-thead>
            <tr>
                <x-borderd-th rowspan="2" class="!w-1/12">{{ __('messages.account') }}</x-borderd-th>
                <x-borderd-th colspan="2" class="!w-1/12">{{ __('messages.opening_balance') }}</x-borderd-th>
                <x-borderd-th colspan="2" class="!w-1/12">{{ __('messages.transactions_balance') }}</x-borderd-th>
                <x-borderd-th colspan="2" class="!w-1/12">{{ __('messages.closing_balance') }}</x-borderd-th>
            </tr>
            <tr>
                <x-borderd-th class="!w-1/12">{{ __('messages.debit') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.credit') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.debit') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.credit') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.debit') }}</x-borderd-th>
                <x-borderd-th class="!w-1/12">{{ __('messages.credit') }}</x-borderd-th>
            </tr>
        </x-thead>

        <tbody>
            @foreach ($this->accounts as $account)
            <x-tr>
                @php

                    $opening_debit = $account->opening_debit - $account->opening_credit > 0 ? $account->opening_debit - $account->opening_credit : 0;
                    $opening_credit = $account->opening_credit - $account->opening_debit > 0 ? $account->opening_credit - $account->opening_debit : 0;

                    $transactions_debit = $account->transactions_debit - $account->transactions_credit > 0 ? $account->transactions_debit - $account->transactions_credit : 0;
                    $transactions_credit = $account->transactions_credit - $account->transactions_debit > 0 ? $account->transactions_credit - $account->transactions_debit : 0;

                    $closing_debit = $opening_debit + $transactions_debit;
                    $closing_credit = $opening_credit + $transactions_credit;
                
                @endphp

                <x-borderd-td>{{ $account->name }}</x-borderd-td>
                <x-borderd-td>{{ $opening_debit > 0 ? number_format($opening_debit,3) : '-'}}</x-borderd-td>
                <x-borderd-td>{{ $opening_credit > 0 ? number_format($opening_credit,3) : '-'}}</x-borderd-td>
                <x-borderd-td class=" bg-gray-200 dark:bg-gray-700">{{ $transactions_debit > 0 ? number_format($transactions_debit,3) : '-'}}</x-borderd-td>
                <x-borderd-td class=" bg-gray-200 dark:bg-gray-700">{{ $transactions_credit > 0 ? number_format($transactions_credit,3) : '-'}}</x-borderd-td>
                <x-borderd-td>{{ $closing_debit > 0 ? number_format($closing_debit,3) : '-'}}</x-borderd-td>
                <x-borderd-td>{{ $closing_credit > 0 ? number_format($closing_credit,3) : '-'}}</x-borderd-td>
            </x-tr>
            @endforeach
        </tbody>

    </x-table>

</div>