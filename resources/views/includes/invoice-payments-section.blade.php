<div x-data="invoicePaymentsComponent">
    <template x-if="invoice">
        <div>
            <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.payments') }}</h3>

            <!-- if no payments, show message -->
            <template x-if="invoice.payments.length == 0">
                <div class="flex items-center justify-center font-bold text-red-600 p-2">
                    {{ __('messages.no_payments_found') }}
                </div>
            </template>

            <!-- if payments, show table -->
            <template x-if="invoice.payments.length > 0">
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __('messages.receiver') }}</x-th>
                            <x-th>{{ __('messages.date') }}</x-th>
                            <x-th>{{ __('messages.amount') }}</x-th>
                            <x-th></x-th>
                        </tr>
                    </x-thead>
                    <tbody>
                        <template x-for="payment in invoice.payments" :key="payment.id">
                            <x-tr>
                                <x-td x-text="payment.user.name"></x-td>
                                <x-td>
                                    <div x-text="payment.formatted_date"></div>
                                    <div x-text="payment.formatted_time"></div>
                                </x-td>
                                <x-td>
                                    <div x-text="formatNumber(payment.amount, 3)"></div>
                                    <div x-text="payment.method"></div>
                                </x-td>
                                <x-td>
                                    <template x-if="payment.can_delete && is_deleting_id !== payment.id">
                                        <x-svgs.trash @click="deletePayment(payment)" class=" w-4 h-4 text-red-600" />
                                    </template>
                                    <template x-if="is_deleting_id === payment.id">
                                        <svg class="w-4 h-4 text-red-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                </x-td>
                            </x-tr>
                        </template>
                    </tbody>
                </x-table>
            </template>

            <template x-if="invoice.can_create_payments">

                <!-- get calculatedRemainingAmount from parent component -->
                <template x-if="calculatedRemainingAmount > 0">
                    <div>
                        <x-section-border />
                        <x-button @click="openPaymentFormModal" type="button">{{ __('messages.create_payment') }}</x-button>
                    </div>
                </template>

                
            </template>
        </div>
    </template>
    
    <script>
        function invoicePaymentsComponent() {
            return {
                is_deleting_id: null,

                openPaymentFormModal() {
                    this.$dispatch('open-invoice-payment-form-modal', {
                        invoice: this.invoice
                    });
                },

                formatNumber(number, decimals) {
                    return number.toFixed(decimals);
                },

                deletePayment(payment) {
                    if(!confirm('{{ __('messages.are_you_sure_you_want_to_delete_this_payment') }}')) {
                        return;
                    }

                    this.is_deleting_id = payment.id;

                    axios.delete(`/orders/${this.invoice.order_id}/invoices/${this.invoice.id}/payments/${payment.id}`)
                        .then(response => {
                            this.invoice = response.data.data;
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                        })
                        .finally(() => {
                            this.is_deleting_id = null;
                        });
                }
            }
        }
    </script>
</div>