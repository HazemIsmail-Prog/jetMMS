<div 
    x-data="incomeInvoiceFormModal"
    x-on:open-income-invoice-form-modal.window="openModal"
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
            class="mb-6 bg-white dark:bg-gray-800 rounded-md shadow-xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto"
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

            <template x-if="showModal">
                <div class="p-6">
                    <!-- Header with Professional Styling -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-light text-gray-900 dark:text-white" x-text="selectedIncomeInvoice? '{{ __('messages.edit_income_invoice') }}' : '{{ __('messages.add_income_invoice') }}'"></h2>
                        <p class="ml-4 text-sm text-gray-600 dark:text-gray-400" x-text="selectedIncomeInvoice?.id"></p>
                    </div>

                    <form @submit.prevent="save" class="space-y-3">

                        <div>
                            <x-label for="other_income_category_id">{{ __('messages.other_income_category') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:otherIncomeCategories,
                                    selectedItemId:form.other_income_category_id,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemId"
                                x-modelable="form.other_income_category_id"
                            >
                                <x-single-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.other_income_category_id" x-text="formErrors.other_income_category_id"></p>
                        </div>

                        <div>
                            <x-label for="manual_number">{{ __('messages.manual_id') }}</x-label>
                            <x-input class="w-full" type="text" x-model="form.manual_number" id="manual_number" dir="ltr" />
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.manual_number" x-text="formErrors.manual_number"></p>
                        </div>
   
                        <div>
                            <x-label for="date">{{ __('messages.date') }}</x-label>
                            <x-input class="w-full" type="date" x-model="form.date" id="date" dir="ltr" />
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.date" x-text="formErrors.date"></p>
                        </div>

                        <div>
                            <x-label for="amount">{{ __('messages.amount') }}</x-label>
                            <x-input class="w-full" type="number" min="0" step="0.001" x-model="form.amount" id="amount" />
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.amount" x-text="formErrors.amount"></p>
                        </div>

                        <div>
                            <x-label for="narration">{{ __('messages.narration') }}</x-label>
                            <textarea class="resize-y w-full px-2 py-1 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" x-model="form.narration" id="narration"></textarea>
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.narration" x-text="formErrors.narration"></p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <x-secondary-button type="button" @click="hideModal">
                                {{ __('messages.cancel') }}
                            </x-secondary-button>
                            <x-button type="submit">
                                <span x-show="loading" class="spinner mr-2"></span>
                                {{ __('messages.save') }}
                            </x-button>
                        </div>

                    </form>
                </div>
            </template>

        </div>
    </div>

    <script>

        function incomeInvoiceFormModal() {
            return {
                otherIncomeCategories: @js($otherIncomeCategories),
                dismissible: true,
                selectedIncomeInvoice: null,
                showModal: false,
                form: null,
                loading: false,
                formErrors: {},
                openModal(e) {
                    this.resetForm();
                    this.selectedIncomeInvoice = e.detail.selectedIncomeInvoice || null;
                    this.form = {...this.selectedIncomeInvoice};
                    this.showModal = true;
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedIncomeInvoice = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.formErrors = {};
                    this.form = {
                        id: null,
                        manual_number: null,
                        date: null,
                        amount: null,
                        narration: null,
                        other_income_category_id: null,
                    };
                    this.loading = false;
                },

                validateForm() {

                    this.formErrors = {};

                    // check if the date is empty
                    if(!this.form.date) {
                        this.formErrors.date = '{{ __('messages.date_is_required') }}';
                    }

                    // check if the amount is empty
                    if(!this.form.amount) {
                        this.formErrors.amount = '{{ __('messages.amount_is_required') }}';
                    }

                    // check if the other_income_category_id is empty
                    if(!this.form.other_income_category_id) {
                        this.formErrors.other_income_category_id = '{{ __('messages.other_income_category_id_is_required') }}';
                    }

                    // check if errors is empty
                    if(Object.keys(this.formErrors).length > 0) {
                        return false;
                    }

                    return true;
                },

                save() {

                    
                    if (this.loading) return;
                    
                    if (!this.validateForm()) {
                        return;
                    }



                    this.loading = true;

                    let url = '/income-invoices';
                    let method = 'post';
                    let event = 'income-invoice-created';
                    if(this.selectedIncomeInvoice) {
                        url = `/income-invoices/${this.selectedIncomeInvoice.id}`;
                        method = 'put';
                        event = 'income-invoice-updated';
                    }




                    axios[method](url, this.form)
                        .then(response => {
                            this.$dispatch(event,{incomeInvoice: response.data.data});
                            this.hideModal();
                        })
                        .catch(error => {
                            if(error.response?.data?.errors) {
                                this.formErrors = error.response?.data?.errors;
                            }else{
                                console.error('Error saving income invoice:', error);
                                alert(error.response?.data?.error || 'An error occurred while saving the income invoice');
                            }
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },


            }
        }
    </script>
</div>