<div 
    x-data="invoiceReconciliationFormModal"
    x-on:open-invoice-reconciliation-form-modal.window="openModal"
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
            class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-md sm:mx-auto"
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
    
            <template x-if="selectedInvoice">
                <div class="p-4"> 
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('messages.add_reconciliation') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Invoice #' + selectedInvoice.id"></p>
                    </div>


                    

                    <!-- Form -->
                    <form @submit.prevent="save" class="mt-6 flex flex-col gap-4">
                        <div class=" space-y-3">
                            <div>
                                <x-label for="amount">{{ __('messages.amount') }}</x-label>
                                <x-input class="w-full" required type="number" x-model.number="form.amount" id="amount" dir="ltr"
                                    min="0" step="0.001" x-bind:max="calculatedRemainingAmount" />
                                <x-input-error for="form.amount" />
                            </div>
                            <div>
                                <x-label for="type">{{ __('messages.type') }}</x-label>
                                <x-select class="w-full" required x-model="form.type" id="type">
                                    <option value="">---</option>
                                    <option value="deduction">{{ __('messages.deduct_from_employee') }}</option>
                                    <option value="technician_refund">{{ __('messages.technician_refund') }}</option>
                                    <option value="contractor_refund">{{ __('messages.contractor_refund') }}</option>
                                    <option value="costs">{{ __('messages.costs') }}</option>
                                </x-select>
                                <x-input-error for="form.type" />
                            </div>

                            <!-- Contact -->
                            <div x-show="form.type == 'deduction'">
                                <x-label for="related_user_id">{{ __('messages.contact') }}</x-label>
                                <div class="col-span-2" x-data="{get items() { return users; }, selectedItemId:form.related_user_id, placeholder: ''}" x-model="selectedItemId" x-modelable="form.related_user_id">
                                    <x-single-searchable-select />
                                </div>
                            </div>

                        </div>
                        <div class="flex justify-end gap-2">
                            <x-button x-bind:disabled="loading" type="submit">
                                {{ __('messages.save') }}
                                <span x-show="loading" class="spinner ms-2"></span>
                            </x-button>
                            <x-secondary-button type="button" @click="hideModal">{{ __('messages.cancel') }}</x-secondary-button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>

    <script>
        function invoiceReconciliationFormModal() {
            return {
                dismissible: true,
                selectedInvoice: null,
                showModal: false,
                form: {},
                loading: false,
                users: @js($users),

                async getInvoiceResource(invoice) {
                    const response = await axios.get(`/orders/${invoice.order_id}/invoices/${invoice.id}`);
                    return response.data.data;
                },

                async openModal(e) {
                    this.resetForm();
                    this.showModal = true;
                    this.selectedInvoice = await this.getInvoiceResource(e.detail.invoice);
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedInvoice = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.form = {
                        amount: 0,
                        type: '',
                        related_user_id: null,
                    };
                    this.loading = false;
                },

                get calculatedRemainingAmount() {
                    // round to 3 decimal places
                    return Number((this.selectedInvoice.remaining_balance).toFixed(3));
                },

                save() {
                    if (this.loading) return;
                    
                    if (!this.validateForm()) {
                        return;
                    }

                    this.loading = true;

                    // console.log(this.form);
                    // return;

                    axios.post(`/reconciliations/${this.selectedInvoice.id}`, this.form)
                        .then(response => {
                            this.$dispatch('invoice-reconciliations-updated',{
                                invoice: response.data.data,
                            });
                            this.hideModal();
                        })
                        .catch(error => {
                            console.error('Error creating invoice reconciliation:', error);
                            alert(error.response?.data?.error || 'An error occurred while creating the invoice reconciliation');
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                validateForm() {
                    // Check if amount is greater than max allowed
                    if (this.form.amount > this.calculatedRemainingAmount) {
                        alert('Amount cannot be greater than total amount');
                        return false;
                    }

                    // Check if amount is negative or zero
                    if (this.form.amount <= 0) {
                        alert('Amount cannot be negative or zero');
                        return false;
                    }

                    return true;
                },
            }
        }
    </script>

    <style>
        .spinner {
            border: 2px solid #f3f3f3;
            border-radius: 50%;
            border-top: 2px solid #3498db;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</div>