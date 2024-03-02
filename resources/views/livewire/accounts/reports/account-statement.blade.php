<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.account_statement') }}
            </h2>

        </div>
    </x-slot>

    <div class="pb-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-6 gap-3 no-print">

        <div class=" col-span-1 md:col-span-2 xl:col-span-3">
            <x-label for="start_date">{{ __('messages.start_date') }}</x-label>
            <x-input type="date" class="w-full" id="start_date" wire:model.live="start_date" />
        </div>
        <div class=" col-span-1 md:col-span-2 xl:col-span-3">
            <x-label for="end_date">{{ __('messages.end_date') }}</x-label>
            <x-input type="date" class="w-full" id="end_date" wire:model.live="end_date" />
        </div>
        <div class=" col-span-1 sm:col-span-2 xl:col-span-2">
            <x-label for="account_id">{{ __('messages.account') }}</x-label>
            <x-searchable-select :list="$this->accounts" model="account_id" live />
        </div>
        <div class=" col-span-1 xl:col-span-2">
            <x-label for="cost_center">{{ __('messages.cost_center') }}</x-label>
            <x-searchable-select :list="$this->cost_centers" model="cost_center_id" live />
        </div>
        <div class=" col-span-1 xl:col-span-2">
            <x-label for="contact">{{ __('messages.contact') }}</x-label>
            <x-searchable-select :list="$this->contacts" model="contact_id" live />
        </div>
    </div>

    <div>
        @foreach ($this->accountsVoucherDetails as $account)
            <h3
                class="pb-5 font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ $account->name }}</h3>
            <div class="overflow-x-auto sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-1 text-start">{{ __('messages.voucher_number') }}</th>
                            <th class="px-6 py-1 text-start">{{ __('messages.type') }}</th>
                            <th class="px-6 py-1 text-start">{{ __('messages.date') }}</th>
                            <th class="px-6 py-1 text-start">{{ __('messages.cost_center') }}</th>
                            <th class="px-6 py-1 text-start">{{ __('messages.contact') }}</th>
                            <th class="px-6 py-1 text-start">{{ __('messages.narration') }}</th>
                            <th class="px-6 py-1 text-start">{{ __('messages.debit') }}</th>
                            <th class="px-6 py-1 text-start">{{ __('messages.credit') }}</th>
                            <th class="px-6 py-1 text-start">{{ __('messages.balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $balance = $this->openning['balance'];
                        @endphp
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="px-6 py-1 text-start">{{ __('messages.opening_balance') }}</td>
                            <td class="px-6 py-1 text-start">
                                {{ $this->openning['debit'] == 0 ? '-' : number_format($this->openning['debit'], 3) }}
                            </td>
                            <td class="px-6 py-1 text-start">
                                {{ $this->openning['credit'] == 0 ? '-' : number_format($this->openning['credit'], 3) }}
                            </td>
                            <td @class([
                                'px-6 py-1 text-start',
                                'text-red-500' => $this->openning['balance'] < 0,
                            ])>
                                @switch($this->openning['balance'])
                                    @case(0)
                                        {{ '-' }}
                                    @break

                                    @case($this->openning['balance'] < 0)
                                        {{ '(' . number_format(abs($this->openning['balance']), 3) . ')' }}
                                    @break

                                    @default
                                        {{ number_format($this->openning['balance'], 3) }}
                                @endswitch
                            </td>
                        </tr>

                        @foreach ($account->voucher_details as $row)
                            @php
                                $balance +=
                                    $account->type == 'debit'
                                        ? $row->debit - $row->credit
                                        : $row->credit - $row->debit;
                            @endphp
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-1 text-start whitespace-nowrap">{{ $row->voucher->id }}</td>
                                <td class="px-6 py-1 text-start">{{ $row->voucher->type }}</td>
                                <td class="px-6 py-1 text-start whitespace-nowrap">
                                    {{ $row->voucher->date->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-1 text-start">{{ $row->cost_center->name ?? '-' }}</td>
                                <td class="px-6 py-1 text-start">{{ $row->contact->name ?? '-' }}
                                </td>
                                <td class="px-6 py-1 text-start">{{ $row->narration }}</td>
                                <td class="px-6 py-1 text-start whitespace-nowrap">
                                    {{ $row->debit == 0 ? '-' : number_format($row->debit, 3) }}</td>
                                <td class="px-6 py-1 text-start whitespace-nowrap">
                                    {{ $row->credit == 0 ? '-' : number_format($row->credit, 3) }}</td>
                                <td @class([
                                    'px-6 py-1 text-start whitespace-nowrap',
                                    'text-red-500' => $balance < 0,
                                ])>
                                    @switch($balance)
                                        @case(0)
                                            {{ '-' }}
                                        @break

                                        @case($balance < 0)
                                            {{ '(' . number_format(abs($balance), 3) . ')' }}
                                        @break

                                        @default
                                            {{ number_format($balance, 3) }}
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-1 text-start" colspan="6"></th>
                            <th class="px-6 py-1 text-start">
                                {{ $account->voucher_details->sum('debit') == 0 ? '-' : number_format($account->voucher_details->sum('debit'), 3) }}
                            </th>
                            <th class="px-6 py-1 text-start">
                                {{ $account->voucher_details->sum('credit') == 0 ? '-' : number_format($account->voucher_details->sum('credit'), 3) }}
                            </th>
                            <th @class([
                                'px-6 py-1 text-start whitespace-nowrap',
                                'text-red-500' => $balance < 0,
                            ])>
                                @switch($balance)
                                    @case(0)
                                        {{ '-' }}
                                    @break

                                    @case($balance < 0)
                                        {{ '(' . number_format(abs($balance), 3) . ')' }}
                                    @break

                                    @default
                                        {{ number_format($balance, 3) }}
                                @endswitch
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endforeach
    </div>

</div>
