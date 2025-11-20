<div 
    x-data="incomePaymentsModal"
    x-on:open-income-payments-modal.window="openModal"
    x-on:income-payment-created.window="getIncomePayments"
    x-on:income-payment-updated.window="getIncomePayments"
>
    <!-- Modal -->
    <div x-on:close.stop="hideModal" x-on:keydown.escape.window="dismissible ? hideModal() : null" x-show="showModal"
        class="jetstream-modal fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
        style="display: none;">
        <div x-show="showModal" class="fixed inset-0 transform transition-all" x-on:click="dismissible ? hideModal() : null"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>
    
        <div x-show="showModal"
            class="mb-6 bg-white dark:bg-gray-800 rounded-md shadow-xl transform transition-all sm:w-full sm:max-w-5xl sm:mx-auto"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
    
            <!-- Close Button -->
            <button x-on:click="hideModal" type="button" class="
                absolute
                -top-2
                -start-2
                inline-flex
                items-center
                p-1
                rounded-full
                bg-gray-800
                dark:bg-gray-200
                text-white
                dark:text-gray-800
                focus:outline-none
                transition
                ease-in-out
                duration-150
            ">
                <x-svgs.close class="w-5 h-5" />
            </button>

            <!-- Modal Content -->
            <div class="p-4">
                <!-- title and add attachment button -->
                <div class=" flex items-center justify-between">
                    <div class="text-lg font-bold">{{__('messages.payments')}}</div>
                    <template x-if="showAddPaymentButton && selectedIncomeInvoice.can_create_payments">
                        <x-button type="button" @click="openPaymentFormModal(null)">{{ __('messages.add_income_payment') }}</x-button>                    
                    </template>
                </div>
                <x-section-border />
                <!-- income payments table -->
                <template x-if="incomePayments.length > 0">
                    <x-table>
                        <x-thead>
                            <tr>
                                <x-th>{{ __('messages.date') }}</x-th>
                                <x-th>{{ __('messages.amount') }}</x-th>
                                <x-th>{{ __('messages.payment_method') }}</x-th>
                                <x-th>{{ __('messages.narration') }}</x-th>
                                <x-th>{{ __('messages.created_by') }}</x-th>
                                <x-th></x-th>
                            </tr>
                        </x-thead>
                        <tbody>
                            <template x-for="incomePayment in incomePayments" :key="incomePayment.id">
                                <x-tr>
                                    <x-td x-text="incomePayment.formatted_date"></x-td>
                                    <x-td x-text="incomePayment.amount"></x-td>
                                    <x-td>
                                        <div class="flex flex-col items-start gap-1">
                                            <div x-text="incomePayment.translated_method" class="text-sm text-gray-500"></div>
                                            <div x-text="getAccountNameById(incomePayment.bank_account_id)" class="text-xs text-gray-500"></div>
                                        </div>
                                    </x-td>
                                    <x-td class=" !whitespace-normal" x-text="incomePayment.narration"></x-td>
                                    <x-td x-text="incomePayment.creator.name"></x-td>
                                    <x-td>
                                        <div class=" flex items-center justify-end gap-2">
                                            <template x-if="incomePayment.can_edit">
                                                <x-badgeWithCounter
                                                    title="{{ __('messages.edit') }}"
                                                    @click="openPaymentFormModal(incomePayment)">
                                                    <x-svgs.edit class="w-4 h-4" />
                                                </x-badgeWithCounter>
                                            </template>
                                            <template x-if="incomePayment.can_delete">
                                                <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}"
                                                    title="{{ __('messages.delete') }}"
                                                    @click="deletePayment(incomePayment.id)">
                                                    <x-svgs.trash class="w-4 h-4" />
                                                </x-badgeWithCounter>
                                            </template>
                                        </div>
                                    </x-td>
                                </x-tr>
                            </template>
                        </tbody>
                    </x-table>
                </template>

                <template x-if="incomePayments.length === 0">
                    <div class="text-center text-gray-500">{{ __('messages.no_payments_found') }}</div>
                </template>
            </div>

        </div>
    </div>

    <script>

        function incomePaymentsModal() {
            return {
                otherIncomeCategories: @js($otherIncomeCategories),
                bankAccounts: @js($bankAccounts),
                dismissible: true,
                selectedIncomeInvoice: null,
                showModal: false,
                incomePayments: [],
                form: null,
                loading: false,
                getIncomePayments() {
                    axios.get(`/income-invoices/${this.selectedIncomeInvoice.id}/payments`)
                        .then(response => {
                            this.incomePayments = response.data.data;
                        })
                        .catch(error => {
                            alert(error.response.data.message);
                        });
                },

                get showAddPaymentButton() {
                    return this.selectedIncomeInvoice?.amount > this.incomePayments?.reduce((acc, payment) => acc + parseFloat(payment.amount), 0);
                },
                getAccountNameById(bankAccountId) {
                    return this.bankAccounts.find(bankAccount => bankAccount.id === bankAccountId)?.name;
                },
                openModal(e) {
                    this.selectedIncomeInvoice = e.detail.selectedIncomeInvoice || null;
                    this.getIncomePayments();
                    this.showModal = true;
                },
                hideModal() {
                    this.showModal = false;
                    this.incomePayments = [];
                    this.selectedIncomeInvoice = null;
                },
                openPaymentFormModal(selectedIncomePayment=null) {
                    this.$dispatch('open-income-payment-form-modal', {selectedIncomeInvoice: this.selectedIncomeInvoice, selectedIncomePayment: selectedIncomePayment});
                },
                deletePayment(incomePaymentId) {
                    if(!confirm('{{ __('messages.are_u_sure') }}')) {
                        return;
                    }
                    this.loading = true;
                    axios.delete(`/income-invoices/${this.selectedIncomeInvoice.id}/payments/${incomePaymentId}`)
                        .then(response => {
                            this.incomePayments = this.incomePayments.filter(incomePayment => incomePayment.id !== incomePaymentId);
                            this.$dispatch('income-payment-deleted', {incomePayment: response.data.data});
                            this.loading = false;
                        })
                        .catch(error => {
                            alert(error.response.data.message);
                            this.loading = false;
                        });
                },
            }
        }
    </script>
</div>