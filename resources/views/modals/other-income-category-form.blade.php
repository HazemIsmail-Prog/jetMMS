<div 
    x-data="otherIncomeCategoryFormModal"
    x-on:open-other-income-category-form-modal.window="openModal"
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
                        <h2 class="text-3xl font-light text-gray-900 dark:text-white" x-text="selectedOtherIncomeCategory? '{{ __('messages.edit_other_income_category') }}' : '{{ __('messages.add_other_income_category') }}'"></h2>
                        <p class="ml-4 text-sm text-gray-600 dark:text-gray-400" x-text="selectedOtherIncomeCategory?.name"></p>
                    </div>

                    <form @submit.prevent="save" class="space-y-3">

                        <div>
                            <x-label for="name">{{ __('messages.name') }}</x-label>
                            <x-input class="w-full" type="text" x-model="form.name" id="name" dir="rtl" />
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.name" x-text="formErrors.name"></p>
                        </div>
   
   
                        <div>
                            <x-label for="income_account_id">{{ __('messages.income_account_id') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:accounts,
                                    selectedItemId:form.income_account_id,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemId"
                                x-modelable="form.income_account_id"
                            >
                                <x-single-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.income_account_id" x-text="formErrors.income_account_id"></p>
                        </div>
  
                        <div>
                            <x-label for="expense_account_id">{{ __('messages.expense_account_id') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:accounts,
                                    selectedItemId:form.expense_account_id,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemId"
                                x-modelable="form.expense_account_id"
                            >
                                <x-single-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.expense_account_id" x-text="formErrors.expense_account_id"></p>
                        </div>

                        <div>
                            <x-label for="cash_account_id">{{ __('messages.cash_account_id') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:accounts,
                                    selectedItemId:form.cash_account_id,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemId"
                                x-modelable="form.cash_account_id"
                            >
                                <x-single-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.cash_account_id" x-text="formErrors.cash_account_id"></p>
                        </div>

                        <div>
                            <x-label for="knet_account_id">{{ __('messages.knet_account_id') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:accounts,
                                    selectedItemId:form.knet_account_id,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemId"
                                x-modelable="form.knet_account_id"
                            >
                                <x-single-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.knet_account_id" x-text="formErrors.knet_account_id"></p>
                        </div>

                        <div>
                            <x-label for="bank_charges_account_id">{{ __('messages.bank_charges_account_id') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:accounts,
                                    selectedItemId:form.bank_charges_account_id,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemId"
                                x-modelable="form.bank_charges_account_id"
                            >
                                <x-single-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.bank_charges_account_id" x-text="formErrors.bank_charges_account_id"></p>
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

        function otherIncomeCategoryFormModal() {
            return {
                accounts: @js($accounts),
                dismissible: true,
                selectedOtherIncomeCategory: null,
                showModal: false,
                form: null,
                loading: false,
                formErrors: {},
                openModal(e) {
                    this.resetForm();
                    this.selectedOtherIncomeCategory = e.detail.selectedOtherIncomeCategory || null;
                    this.form = {...this.selectedOtherIncomeCategory};
                    this.showModal = true;
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedOtherIncomeCategory = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.formErrors = {};
                    this.form = {
                        id: null,
                        name: null,
                        income_account_id: null,
                        expense_account_id: null,
                        cash_account_id: null,
                        knet_account_id: null,
                        bank_charges_account_id: null,
                    };
                    this.loading = false;
                },

                validateForm() {

                    this.formErrors = {};

                    // check if the name_ar is empty
                    if(!this.form.name) {
                        this.formErrors.name = '{{ __('messages.name_is_required') }}';
                    }

                    // check if the income_account_id is empty
                    if(!this.form.income_account_id) {
                        this.formErrors.income_account_id = '{{ __('messages.income_account_is_required') }}';
                    }

                    // check if the expense_account_id is empty
                    if(!this.form.expense_account_id) {
                        this.formErrors.expense_account_id = '{{ __('messages.expense_account_is_required') }}';
                    }

                    // check if the cash_account_id is empty
                    if(!this.form.cash_account_id) {
                        this.formErrors.cash_account_id = '{{ __('messages.cash_account_is_required') }}';
                    }

                    // check if the knet_account_id is empty
                    if(!this.form.knet_account_id) {
                        this.formErrors.knet_account_id = '{{ __('messages.knet_account_is_required') }}';
                    }

                    // check if the bank_charges_account_id is empty
                    if(!this.form.bank_charges_account_id) {
                        this.formErrors.bank_charges_account_id = '{{ __('messages.bank_charges_account_is_required') }}';
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

                    let url = '/other-income-categories';
                    let method = 'post';
                    let event = 'other-income-category-created';
                    if(this.selectedOtherIncomeCategory) {
                        url = `/other-income-categories/${this.selectedOtherIncomeCategory.id}`;
                        method = 'put';
                        event = 'other-income-category-updated';
                    }




                    axios[method](url, this.form)
                        .then(response => {
                            this.$dispatch(event,{otherIncomeCategory: response.data.data});
                            this.hideModal();
                        })
                        .catch(error => {
                            if(error.response?.data?.errors) {
                                this.formErrors = error.response?.data?.errors;
                            }else{
                                console.error('Error saving other income category:', error);
                                alert(error.response?.data?.error || 'An error occurred while saving the other income category');
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