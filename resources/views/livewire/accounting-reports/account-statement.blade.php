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
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __('messages.voucher_number') }}</x-th>
                            <x-th>{{ __('messages.type') }}</x-th>
                            <x-th>{{ __('messages.date') }}</x-th>
                            <x-th>{{ __('messages.cost_center') }}</x-th>
                            <x-th>{{ __('messages.contact') }}</x-th>
                            <x-th>{{ __('messages.narration') }}</x-th>
                            <x-th>{{ __('messages.debit') }}</x-th>
                            <x-th>{{ __('messages.credit') }}</x-th>
                            <x-th>{{ __('messages.balance') }}</x-th>
                        </tr>
                    </x-thead>
                    <tbody>
                        @php
                            $balance = $this->openning['balance'];
                        @endphp
                        <x-tr>
                            <x-td></x-td>
                            <x-td></x-td>
                            <x-td></x-td>
                            <x-td></x-td>
                            <x-td></x-td>
                            <x-td>{{ __('messages.opening_balance') }}</x-td>
                            <x-td>
                                {{ $this->openning['debit'] == 0 ? '-' : number_format($this->openning['debit'], 3) }}
                            </x-td>
                            <x-td>
                                {{ $this->openning['credit'] == 0 ? '-' : number_format($this->openning['credit'], 3) }}
                            </x-td>
                            <x-td @class([
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
                            </x-td>
                        </x-tr>

                        @foreach ($account->voucher_details as $row)
                            @php
                                $balance +=
                                    $account->type == 'debit'
                                        ? $row->debit - $row->credit
                                        : $row->credit - $row->debit;
                            @endphp
                            <x-tr>
                                <x-td>{{ $row->voucher->id }}</x-td>
                                <x-td>{{ $row->voucher->type }}</x-td>
                                <x-td>{!! $row->voucher->formated_date !!}</x-td>
                                <x-td>{{ $row->cost_center->name ?? '-' }}</x-td>
                                <x-td>{{ $row->contact->name ?? '-' }}</x-td>
                                <x-td>{{ $row->narration }}</x-td>
                                <x-td>{{ $row->formated_debit }}</x-td>
                                <x-td>{{ $row->formated_credit }}</x-td>
                                <x-td @class([
                                    '',
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
                                </x-td>
                            </x-tr>
                        @endforeach
                    </tbody>
                    <tfoot class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <x-tr>
                            <x-th colspan="6"></x-th>
                            <x-th>{{ $account->formated_debit_sum }}</x-th>
                            <x-th>{{ $account->formated_credit_sum }}</x-th>
                            <x-th @class([
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
                            </x-th>
                        </x-tr>
                    </tfoot>
                </x-table>
            </div>
        @endforeach
    </div>

</div>