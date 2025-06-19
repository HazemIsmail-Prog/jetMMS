<div 
    x-data="orderInvoiceCardComponent"
    x-on:invoice-discount-updated.window="updateDiscount"
    x-on:invoice-payments-updated.window="updatePayments"
>
    <div class=" p-4 border dark:border-gray-700 rounded-lg">
    
        <div class=" flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900 dark:text-white" x-text="invoice.formatted_id"></h3>
    
            <div class=" flex items-center gap-2">
                <!-- Print Dropdown -->
                <div class="relative">
                    <x-dropdown width="48">
                        <x-slot name="trigger">
                            <x-badgeWithCounter title="{{ __('messages.print_invoice') }}">
                                <x-svgs.printer class="h-4 w-4" />
                            </x-badgeWithCounter>
                        </x-slot>
                        <x-slot name="content">
                            <!-- PDF Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('messages.print_invoice') }}
                            </div>
                            <x-dropdown-link target="_blank"
                                href="#">
                                {{ __('messages.print_detailed_invoice') }}
                            </x-dropdown-link>
                            <x-dropdown-link target="_blank" href="#">
                                {{ __('messages.print_non_detailed_invoice') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                
                <!-- Delete Button -->
                <template x-if="invoice.can_deleted && invoices.length > 1 && is_deleting_id !== invoice.id">
                    <x-badgeWithCounter @click="deleteInvoice" title="{{ __('messages.delete') }}">
                        <x-svgs.trash class="h-4 w-4 text-red-600" />
                    </x-badgeWithCounter>
                </template>
                <template x-if="is_deleting_id === invoice.id">
                    <svg class="w-4 h-4 text-red-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>

            </div>
    
        </div>
    
        <div class=" overflow-x-auto sm:rounded-lg">
            <x-table class=" table-auto">
                <x-thead>
                    <tr>
                        <x-th></x-th>
                        <x-th>{{ __('messages.quantity') }}</x-th>
                        <x-th>{{ __('messages.unit_price') }}</x-th>
                        <x-th>{{ __('messages.total') }}</x-th>
                    </tr>
                </x-thead>

                <!-- Services Section -->
                <template x-if="showServicesSection">
                    <x-tr>
                        <x-th colspan="4">{{ __('messages.services') }}</x-th>
                    </x-tr>
                </template>
                <template x-if="showServicesSection">
                    <template x-for="row in invoice.invoice_details_services" :key="row.id">
                        <x-tr>
                            <x-td class=" !whitespace-normal" x-text="row.service.name"></x-td>
                            <x-td x-text="row.quantity"></x-td>
                            <x-td x-text="formatNumber(row.price, 3)"></x-td>
                            <x-td x-text="formatNumber(row.quantity * row.price, 3)"></x-td>
                        </x-tr>
                    </template>
                </template>

                <!-- Parts Section -->
                <template x-if="showPartsSection">
                    <x-tr>
                        <x-th colspan="4">{{ __('messages.parts') }}</x-th>
                    </x-tr>
                </template>
                <template x-if="showPartsSection">
                    <template x-for="row in invoice.invoice_details_parts" :key="row.id">
                        <x-tr>
                            <x-td class=" !whitespace-normal" x-text="row.service.name"></x-td>
                            <x-td x-text="row.quantity"></x-td>
                            <x-td x-text="formatNumber(row.price, 3)"></x-td>
                            <x-td x-text="formatNumber(row.quantity * row.price, 3)"></x-td>
                        </x-tr>
                    </template>
                </template>
                <template x-if="showPartsSection">
                    <template x-for="row in invoice.invoice_part_details" :key="row.id">
                        <x-tr>
                            <x-td class=" !whitespace-normal" x-text="row.name"></x-td>
                            <x-td x-text="row.quantity"></x-td>
                            <x-td x-text="formatNumber(row.price, 3)"></x-td>
                            <x-td x-text="formatNumber(row.quantity * row.price, 3)"></x-td>
                        </x-tr>
                    </template>
                </template>
    
                <!-- Totals Section -->

                <!-- Delivery -->
                <template x-if="invoice.delivery > 0">
                    <x-tr>
                        <x-th colspan="3" class=" text-end">{{ __('messages.delivery') }}</x-th>
                        <x-th x-text="formatNumber(invoice.delivery, 3)"></x-th>
                    </x-tr>
                </template>

                <!-- Discount -->
                <template x-if="invoice.discount > 0">
                    <x-tr>
                        <x-th colspan="3" class=" text-end">{{ __('messages.discount') }}</x-th>
                        <x-th class="text-red-500 line-through" x-text="formatNumber(invoice.discount, 3)"></x-th>
                    </x-tr>
                </template>

                <!-- Total -->
                <x-tr>
                    <x-th colspan="3" class=" text-end">{{ __('messages.total') }}</x-th>
                    <x-th x-text="formatNumber(calculatedAmount, 3)"></x-th>
                </x-tr>

                <!-- Paid Amount -->
                <x-tr>
                    <x-th colspan="3" class=" text-end">{{ __('messages.paid_amount') }}</x-th>
                    <x-th x-text="formatNumber(invoice.payments_amount, 3)"></x-th>
                </x-tr>

                <!-- Remaining Amount -->
                <x-tr>
                    <x-th colspan="3" class=" text-end">{{ __('messages.remaining_amount') }}</x-th>
                    <x-th x-text="formatNumber(calculatedRemainingAmount, 3)"></x-th>
                </x-tr>

            </x-table>
        </div>
        <x-section-border />
    
        <!-- Discount Button -->
        <!-- if the invoice is in same day, show the discount button -->
        <template x-if="invoice.can_discount">
            <div class=" text-center">
                <x-button @click="openDiscountFormModal" type="button">{{ __('messages.edit_discount') }}</x-button>
                <x-section-border />
            </div>
        </template>

        <template x-if="invoice.can_view_payments">
            @include('includes.invoice-payments-section')
        </template>
    
    </div>
    <script>
        function orderInvoiceCardComponent() {
            return {

                is_deleting_id: null,

                formatNumber(number, precision) {
                    return number.toFixed(precision);
                },

                showPartsSection() {
                    return this.invoice.invoice_details_parts.length > 0 || this.invoice.invoice_part_details.length > 0
                },

                showServicesSection() {
                    return this.invoice.invoice_details_services.length > 0
                },

                get invoicePartDetailsAmount() {
                    if (this.invoice.invoice_part_details.length > 0) {
                        return this.invoice.invoice_part_details.reduce((acc, row) => acc + (row.quantity * row.price), 0);
                    }
                    return 0;
                },

                get invoiceDetailsPartsAmount() {
                    if (this.invoice.invoice_details_parts.length > 0) {
                        return this.invoice.invoice_details_parts.reduce((acc, row) => acc + (row.quantity * row.price), 0);
                    }
                    return 0;
                },

                get invoiceDetailsServicesAmount() {
                    if (this.invoice.invoice_details_services.length > 0) {
                        return this.invoice.invoice_details_services.reduce((acc, row) => acc + (row.quantity * row.price), 0);
                    }
                    return 0;
                },

                get calculatedAmount() {
                    let amount = 0;
                    amount += this.invoiceDetailsPartsAmount;
                    amount += this.invoicePartDetailsAmount;
                    amount += this.invoiceDetailsServicesAmount;
                    amount += this.invoice.delivery;
                    amount -= this.invoice.discount;
                    return amount;
                },

                get calculatedRemainingAmount() {
                    return this.calculatedAmount - this.invoice.payments_amount;
                },

                deleteInvoice() {

                    if(!confirm('{{ __('messages.are_you_sure_delete_invoice') }}')) {
                        return;
                    }

                    this.is_deleting_id = this.invoice.id;

                    axios.delete(`/orders/${this.invoice.order_id}/invoices/${this.invoice.id}`)
                        .then(response => {
                            // called from parent component
                            this.getInvoices();
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                        })
                        .finally(() => {
                            this.is_deleting_id = null;
                        });
                },

                openDiscountFormModal() {
                    this.$dispatch('open-invoice-discount-form-modal',{
                        invoice: this.invoice
                    });
                },

                updateDiscount(e) {
                    if(e.detail.invoice.id == this.invoice.id) {
                        this.invoice.discount = parseFloat(e.detail.invoice.discount);
                    }
                },

                updatePayments(e) {
                    if(e.detail.invoice.id == this.invoice.id) {
                        this.invoice = e.detail.invoice;
                    }
                },
            }
        }
    </script>
</div>
