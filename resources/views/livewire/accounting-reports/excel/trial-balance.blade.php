    <table>
        <thead>
            <tr>
                <th>{{ __('messages.account') }}</th>
                <th>{{ __('messages.opening_balance') }} - {{ __('messages.debit') }}</th>
                <th>{{ __('messages.opening_balance') }} - {{ __('messages.credit') }}</th>
                <th>{{ __('messages.transactions_balance') }} - {{ __('messages.debit') }}</th>
                <th>{{ __('messages.transactions_balance') }} - {{ __('messages.credit') }}</th>
                <th>{{ __('messages.closing_balance') }} - {{ __('messages.debit') }}</th>
                <th>{{ __('messages.closing_balance') }} - {{ __('messages.credit') }}</th>
            </tr>
        </thead>

        <tbody>
            @php
                $total_opening_debit = 0;
                $total_opening_credit = 0;

                $total_transactions_debit = 0;
                $total_transactions_credit = 0;

                $total_closing_debit = 0;
                $total_closing_credit = 0;
            @endphp
            @foreach ($data as $account)
                <tr>
                    @php

                        $opening_debit =
                            $account->opening_debit - $account->opening_credit > 0
                                ? $account->opening_debit - $account->opening_credit
                                : 0;
                        $opening_credit =
                            $account->opening_credit - $account->opening_debit > 0
                                ? $account->opening_credit - $account->opening_debit
                                : 0;

                        $transactions_debit = $account->transactions_debit > 0 ? $account->transactions_debit : 0;
                        $transactions_credit = $account->transactions_credit > 0 ? $account->transactions_credit : 0;

                        $closing_debit = $opening_debit - $opening_credit + $transactions_debit - $transactions_credit;
                        $closing_credit = $opening_credit - $opening_debit + $transactions_credit - $transactions_debit;

                        $total_opening_debit += $opening_debit;
                        $total_opening_credit += $opening_credit;

                        $total_transactions_debit += $transactions_debit;
                        $total_transactions_credit += $transactions_credit;

                        $total_closing_debit += $closing_debit;
                        $total_closing_credit += $closing_credit;

                    @endphp

                    <td>{{ $account->name }}</td>
                    <td>{{ $opening_debit <= 0 ? '' : $opening_debit }}</td>
                    <td>{{ $opening_credit <= 0 ? '' : $opening_credit}}</td>
                    <td>{{ $transactions_debit <= 0 ? '' : $transactions_debit }}</td>
                    <td>{{ $transactions_credit <= 0 ? '' : $transactions_credit }}</td>
                    <td>{{ $closing_debit <= 0 ? '' : $closing_debit }}</td>
                    <td>{{ $closing_credit <= 0 ? '' : $closing_credit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
