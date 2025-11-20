<x-app-layout>
    <x-slot name="title">
        {{ __('messages.income_invoices') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.income_invoices') }}
            </h2>
            @if(auth()->user()->hasPermission('income_invoices_create'))
                <x-button @click="$dispatch('open-create-modal')">
                    {{__('messages.add_income_invoice')}}
                </x-button>
            @endif
        </div>
    </x-slot>

    @include('modals.income-invoice-form')
    @include('modals.income-payments')
    @include('modals.income-payment-form')
    @include('modals.attachments')
    @include('modals.attachment-form')

    <div
        x-data="incomeInvoicesComponent"
        x-on:income-invoice-created.window="getIncomeInvoices(1)"
        x-on:income-invoice-updated.window="getIncomeInvoices(1)"
        x-on:income-payment-created.window="getIncomeInvoices(1)"
        x-on:income-payment-updated.window="getIncomeInvoices(1)"
        x-on:income-payment-deleted.window="getIncomeInvoices(1)"
        x-on:open-create-modal.window="openModal(null)"
    >

    <div class="flex justify-end mb-4">


        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{__('messages.invoice_number')}}</x-th>
                    <x-th>{{__('messages.manual_id')}}</x-th>
                    <x-th>{{__('messages.other_income_category')}}</x-th>
                    <x-th>{{__('messages.date')}}</x-th>
                    <x-th>{{__('messages.amount')}}</x-th>
                    <x-th>{{__('messages.narration')}}</x-th>
                    <x-th>{{__('messages.paid_amount')}}</x-th>
                    <x-th>{{__('messages.cash')}}</x-th>
                    <x-th>{{__('messages.knet')}}</x-th>
                    <x-th>{{__('messages.bank_deposit')}}</x-th>
                    <x-th>{{__('messages.remaining_amount')}}</x-th>
                    <x-th>{{__('messages.created_by')}}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="incomeInvoice in incomeInvoices" :key="incomeInvoice.id">
                    <x-tr>
                        <x-td x-text="incomeInvoice.id"></x-td>
                        <x-td x-text="incomeInvoice.manual_number"></x-td>
                        <x-td x-text="getOtherIncomeCategoryNameById(incomeInvoice.other_income_category_id)"></x-td>
                        <x-td x-text="incomeInvoice.formatted_date"></x-td>
                        <x-td x-text="formatNumber(incomeInvoice.amount)"></x-td>
                        <x-td x-text="incomeInvoice.narration" class="!whitespace-normal"></x-td>
                        <x-td x-text="formatNumber(getIncomeInvoicePayments(incomeInvoice.id).paidAmount)"></x-td>
                        <x-td x-text="formatNumber(getIncomeInvoicePayments(incomeInvoice.id).cash)"></x-td>
                        <x-td x-text="formatNumber(getIncomeInvoicePayments(incomeInvoice.id).knet)"></x-td>
                        <x-td x-text="formatNumber(getIncomeInvoicePayments(incomeInvoice.id).bankDeposit)"></x-td>
                        <x-td x-text="formatNumber(getIncomeInvoicePayments(incomeInvoice.id).remainingAmount)"></x-td>
                        <x-td x-text="incomeInvoice.creator.name"></x-td>
                        <x-td>
                            <div class="flex justify-end gap-2">
                                <template x-if="incomeInvoice.can_view_payments">
                                    <x-badgeWithCounter title="{{ __('messages.add_payment') }}"
                                        @click="openPaymentsModal(incomeInvoice)">
                                        <span class="text-sm px-2">{{ __('messages.view_payments') }}</span>
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="incomeInvoice.can_view_attachments">
                                    <x-badgeWithCounter title="{{ __('messages.attachments') }}"
                                        @click="$dispatch('open-attachment-index-modal',{model: incomeInvoice, type: 'IncomeInvoice'})">
                                        <x-svgs.attachment class="h-4 w-4" />
                                        <span x-show="incomeInvoice.attachments_count > 0" style="font-size: 0.6rem;" x-text="incomeInvoice.attachments_count"></span>
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="incomeInvoice.can_edit">
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        @click="openModal(incomeInvoice)">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="incomeInvoice.can_delete">
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        @click="deleteIncomeInvoice(incomeInvoice.id)">
                                        <x-svgs.trash class="h-4 w-4 text-red-500" />
                                    </x-badgeWithCounter>
                                </template>
                            </div>
                        </x-td>
                    </x-tr>
                </template>
            </tbody>

        </x-table>

        <!-- load more -->
        <div class="flex justify-center mt-4" x-show="currentPage < lastPage">
            <x-button @click="loadMore">
                {{__('messages.load_more')}}
            </x-button>
        </div>
    </div>


    <script>
        function incomeInvoicesComponent() {
            return {
                otherIncomeCategories: @js($otherIncomeCategories),
                bankAccounts: @js($bankAccounts),
                incomeInvoices: [],
                currentPage: 1,
                lastPage: 1,
                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.getIncomeInvoices();
                },

                getIncomeInvoicePayments(incomeInvoiceId) {

                    let cash = 0;
                    let knet = 0;
                    let bankDeposit = 0;
                    let paidAmount = 0;
                    let remainingAmount = 0;

                    this.incomeInvoices.find(incomeInvoice => incomeInvoice.id === incomeInvoiceId)?.payments.forEach(payment => {
                        if (payment.method === 'cash') {
                            cash += parseFloat(payment.amount);
                        } else if (payment.method === 'knet') {
                            knet += parseFloat(payment.amount);
                        } else if (payment.method === 'bank_deposit') {
                            bankDeposit += parseFloat(payment.amount);
                        }
                    });
                    paidAmount = cash + knet + bankDeposit;
                    remainingAmount = parseFloat(this.incomeInvoices.find(incomeInvoice => incomeInvoice.id === incomeInvoiceId)?.amount) - paidAmount;

                    return {
                        cash: cash,
                        knet: knet,
                        bankDeposit: bankDeposit,
                        paidAmount: paidAmount,
                        remainingAmount: remainingAmount
                    };
                },

                formatNumber(number) {
                    return number.toLocaleString('en-US', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
                },

                getOtherIncomeCategoryNameById(id) {
                    return this.otherIncomeCategories.find(otherIncomeCategory => otherIncomeCategory.id === id)?.name;
                },

                getIncomeInvoices(page=1) {
                    axios.get('/income-invoices?page=' + page)
                        .then(response => {
                            if (page === 1) {
                                this.incomeInvoices = [];
                                this.incomeInvoices = response.data.data;
                            } else {
                                this.incomeInvoices = [...this.incomeInvoices, ...response.data.data];
                            }
                            this.currentPage = response.data.meta.current_page;
                            this.lastPage = response.data.meta.last_page;
                        })
                        .catch(error => {
                            alert(error.response.data.message);
                        });
                },
                openModal(incomeInvoice=null) {
                    this.$dispatch('open-income-invoice-form-modal', {selectedIncomeInvoice: incomeInvoice});
                },

                openPaymentsModal(incomeInvoice) {
                    this.$dispatch('open-income-payments-modal', {selectedIncomeInvoice: incomeInvoice});
                },

                deleteIncomeInvoice(id) {
                    if (confirm('Are you sure you want to delete this income invoice?')) {
                        axios.delete(`/income-invoices/${id}`)
                            .then(response => {
                                this.getIncomeInvoices(1);
                            })
                            .catch(error => {
                                alert(error.response.data.message);
                            });
                    }
                },
                loadMore() {
                    if (this.currentPage == this.lastPage) return;                    
                    this.currentPage = (this.currentPage || 1) + 1;
                    this.getIncomeInvoices(this.currentPage);
                },
            }
        }
    </script>
</x-app-layout>
