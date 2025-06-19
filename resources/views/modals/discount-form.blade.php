<div 
    x-data="invoiceDiscountFormModal"
    x-on:open-invoice-discount-form-modal.window="openModal"
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
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('messages.edit_discount') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Invoice #' + selectedInvoice.id"></p>
                    </div>


                    

                    <!-- Form -->
                    <form @submit.prevent="save" class="mt-6 flex flex-col gap-4">

                        <div>
                            <x-label for="discount" :value="__('messages.current_discount')" />
                            <x-input id="discount" type="number" step="0.001" min="0" x-bind:max="maxAllowedDiscount" class="w-full" x-model="discount" :label="__('messages.discount')" />
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Max allowed discount is ' + maxAllowedDiscount"></p>
                 
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
        function invoiceDiscountFormModal() {
            return {
                dismissible: true,
                selectedInvoice: null,
                showModal: false,
                discount: 0,
                loading: false,

                openModal(e) {
                    this.resetForm();
                    this.selectedInvoice = e.detail.invoice;
                    this.discount = this.selectedInvoice.discount;
                    this.showModal = true;
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedInvoice = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.discount = 0;
                    this.loading = false;
                },

                get maxAllowedDiscount() {
                    return this.selectedInvoice.invoice_details_services_amount;      
                },

                save() {
                    if (this.loading) return;
                    
                    if (!this.validateForm()) {
                        return;
                    }

                    this.loading = true;

                    const formData = {
                        discount: this.discount
                    };

                    axios.put(`/orders/${this.selectedInvoice.order_id}/invoices/${this.selectedInvoice.id}`, formData)
                        .then(response => {
                            this.$dispatch('invoice-discount-updated',{
                                invoice: response.data.data
                            });
                            this.hideModal();
                        })
                        .catch(error => {
                            console.error('Error updating invoice:', error);
                            alert(error.response?.data?.error || 'An error occurred while updating the invoice');
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                validateForm() {
                    // Check if discount is greater than max allowed
                    if (this.discount > this.maxAllowedDiscount) {
                        alert('Discount cannot be greater than services amount');
                        return false;
                    }

                    // Check if discount is negative
                    if (this.discount < 0) {
                        alert('Discount cannot be negative');
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