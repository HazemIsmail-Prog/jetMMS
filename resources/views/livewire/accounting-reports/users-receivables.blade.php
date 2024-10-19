<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.users_receivables') }}
                <span id="counter"></span>
            </h2>
        </div>
    </x-slot>

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->users->total() }}
    </span>
    @endteleport

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>
    @teleport('#pagination')
    <div class=" flex items-center justify-between gap-2">
        <x-select wire:model.live="perPage">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="500">500</option>
        </x-select>
        @if ($this->users->hasPages())
        <div class=" flex-1">{{ $this->users->links() }}</div>
        @endif
    </div>
    @endteleport

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">

        <div>
            <x-label for="user">{{ __('messages.name') }}</x-label>
            <x-searchable-select id="user" class="!py-[5px]" :list="$this->users_filter_list"
                wire:model.live="filters.user_id" multipule />
        </div>

        <div>
            <x-label for="title">{{ __('messages.title') }}</x-label>
            <x-searchable-select id="title" class="!py-[5px]" :list="$this->titles"
                wire:model.live="filters.title_id" multipule />
        </div>

        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-searchable-select id="department" class="!py-[5px]" :list="$this->departments"
                wire:model.live="filters.department_id" multipule />
        </div>

        <div>
            <x-label for="start_date">{{ __('messages.date') }}</x-label>
            <x-input class="w-full text-center py-0" type="date" id="start_date"
                wire:model.live="filters.start_date" />
            <x-input class="w-full text-center py-0" type="date" id="end_date"
                wire:model.live="filters.end_date" />

        </div>

    </div>


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
                    $total_debit = $user['debit_total'];
                    $total_credit = $user['credit_total'];
                    $total_amount = round($total_debit-$total_credit,3);
                @endphp

                {{-- @if ($total_amount != 0) --}}
                    <x-tr>
                        <x-td>{{ $user->name }}</x-td>
                        <x-td>{{ $user->title->name }}</x-td>
                        <x-td>{{ $user->department->name }}</x-td>
                        @foreach ($this->receivableAccounts as $account)
                            @php
                                $debit = $user['debit_sum_of_account_'.$account->id];
                                $credit = $user['credit_sum_of_account_'.$account->id];
                                $amount = round($debit-$credit,3);
                            @endphp
                            <x-td class="!w-1/12 {{ $amount < 0 ? 'text-red-500' : '' }}">{{ $amount == 0 ? '-' : number_format($amount,3) }}</x-td>
                        @endforeach
                        <x-td class="!w-1/12 {{ $total_amount < 0 ? 'text-red-500' : '' }}">{{ $total_amount == 0 ? '-' : number_format($total_amount,3) }}</x-td>
                    </x-tr>
                {{-- @endif --}}
                
            @endforeach
        </tbody>

        <x-tfoot>
            <tr>
                <x-th colspan="3">{{ __('messages.total') }}</x-th>
                @foreach ($this->receivableAccounts as $account)
                @php
                    $debit = $this->users->sum('debit_sum_of_account_'.$account->id);
                    $credit = $this->users->sum('credit_sum_of_account_'.$account->id);
                    $amount = round($debit-$credit,3);
                @endphp
                <x-th class="!w-1/12 {{ $amount < 0 ? 'text-red-500' : '' }}">{{ $amount == 0 ? '-' : number_format($amount,3) }}</x-th>
                @endforeach
                @php
                    $total_debit = $this->users->sum('debit_total');
                    $total_credit = $this->users->sum('credit_total');
                    $total_amount = round($total_debit-$total_credit,3);
                @endphp
                <x-th class="!w-1/12 {{ $total_amount < 0 ? 'text-red-500' : '' }}">{{ $total_amount == 0 ? '-' : number_format($total_amount,3) }}</x-th>
            </tr>
        </x-tfoot>

    </x-table>

</div>