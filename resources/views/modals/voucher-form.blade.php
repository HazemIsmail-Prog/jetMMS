<div 
    x-data="voucherFormModal"
    x-on:open-voucher-form-modal.window="openModal"
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
            class="mb-6 bg-white dark:bg-gray-800 rounded-md shadow-xl transform transition-all sm:w-full sm:max-w-7xl sm:mx-auto"
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
                        <h2 class="text-3xl font-light text-gray-900 dark:text-white" x-text="selectedVoucher? '{{ __('messages.edit_journal_voucher') }}' : '{{ __('messages.add_journal_voucher') }}'"></h2>
                        <p class="ml-4 text-sm text-gray-600 dark:text-gray-400" x-text="selectedVoucher?.id"></p>
                    </div>

                    <form @submit.prevent="save" class="space-y-8">
                        <!-- Basic Info Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div>
                                        <x-label class="text-sm font-medium" for="manual_id">{{ __('messages.manual_id') }}</x-label>
                                        <x-input class="w-full mt-2 rounded-md border-gray-300 dark:border-gray-600" x-model="form.manual_id" autocomplete="off" type="text" id="manual_id" />
                                        <x-input-error for="form.manual_id" />
                                    </div>
                                    
                                    <div>
                                        <x-label class="text-sm font-medium" for="date">{{ __('messages.date') }}</x-label>
                                        <x-input required class="w-full mt-2 rounded-md border-gray-300 dark:border-gray-600" x-model="form.date" type="date" id="date" />
                                        <x-input-error for="form.date" />
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <x-label class="text-sm font-medium" for="notes">{{ __('messages.notes') }}</x-label>
                                        <x-input class="w-full mt-2 text-start rounded-md border-gray-300 dark:border-gray-600" x-model="form.notes" type="text" id="notes" />
                                        <x-input-error for="form.notes" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Voucher Details Section -->
                        <div class="space-y-4">
                            <template x-if="form.details.length">
                                <div class="space-y-3">
                                    <!-- Column Headers -->
                                    <div class="grid grid-cols-11 gap-x-3 px-4 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-md text-sm font-medium text-gray-600 dark:text-gray-400">
                                        <div class="col-span-3">{{ __('messages.account') }}</div>
                                        <div class="col-span-2">{{ __('messages.cost_center') }}</div>
                                        <div class="col-span-2">{{ __('messages.contact') }}</div>
                                        <div class="col-span-2">{{ __('messages.debit') }}</div>
                                        <div class="col-span-2">{{ __('messages.credit') }}</div>
                                        <div class="col-span-1"></div>
                                    </div>

                                    <!-- Detail Rows -->
                                    <template x-for="(row, index) in form.details" :key="index">
                                        <div class="grid grid-cols-11 gap-3 items-center bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-700 p-4">
                                            <!-- Account Selection -->
                                            <div class="col-span-3" x-data="{items:accounts, selectedItemId:row.account_id, placeholder: ''}" x-model="selectedItemId" x-modelable="row.account_id">
                                                <x-single-searchable-select />
                                            </div>

                                            <!-- Cost Center -->
                                            <div class="col-span-2" x-data="{items:cost_centers, selectedItemId:row.cost_center_id, placeholder: ''}" x-model="selectedItemId" x-modelable="row.cost_center_id">
                                                <x-single-searchable-select />
                                            </div>

                                            <!-- Contact -->
                                            <div class="col-span-2" x-data="{items:users, selectedItemId:row.user_id, placeholder: ''}" x-model="selectedItemId" x-modelable="row.user_id">
                                                <x-single-searchable-select />
                                            </div>

                                            <!-- Debit -->
                                            <div class="col-span-2">
                                                <x-input class="w-full rounded-md text-right" dir="ltr" step="0.001" x-model.number="row.debit" type="number" @input="handleDebitInput(index)" placeholder="0.000" />
                                            </div>

                                            <!-- Credit -->
                                            <div class="col-span-2">
                                                <x-input class="w-full rounded-md text-right" dir="ltr" step="0.001" x-model.number="row.credit" type="number" @input="handleCreditInput(index)" placeholder="0.000" />
                                            </div>

                                            <!-- narration -->
                                            <div class="col-span-10">
                                                <textarea class="resize-y w-full px-2 py-1 border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" x-model="row.narration" placeholder="{{ __('messages.narration') }}"></textarea>
                                            </div>

                                            <!-- Actions -->
                                            <div class="col-span-1 flex justify-center items-center gap-2">
                                                <button tabindex="-1" type="button" @click="duplicateRow(index)" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <x-svgs.duplicate class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                                </button>
                                                <button tabindex="-1" x-show="form.details.length > 2" type="button" @click="deleteRow(index)" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    <x-svgs.trash class="w-4 h-4 text-red-600 dark:text-red-400" />
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Summary Card -->
                                    <div class="flex gap-6 justify-end bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('messages.debit') }}:</span>
                                            <span class="text-lg" :class="totalDebit != 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'" x-text="totalDebit"></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('messages.credit') }}:</span>
                                            <span class="text-lg" :class="totalCredit != 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'" x-text="totalCredit"></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('messages.balance') }}:</span>
                                            <span class="text-lg" :class="balance == 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'" x-text="balance"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Add Entry Button -->
                            <div class="flex justify-center">
                                <button type="button" @click="addRow(1)" class="inline-flex items-center px-4 py-2 rounded-md border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                    <x-svgs.plus class="w-5 h-5 mr-2 text-gray-400" />
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('messages.add_line') }}</span>
                                </button>
                            </div>
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

        function voucherFormModal() {
            return {
                dismissible: false,
                selectedVoucher: null,
                showModal: false,
                form: null,
                loading: false,
                accounts: @js($accounts),
                cost_centers: @js($cost_centers),
                users: @js($users),
                duplicate: false,
                openModal(e) {

                    this.resetForm();
                    this.selectedVoucher = e.detail.voucher || null;
                    this.showModal = true;
                    this.$nextTick(async () => {
                        if(!this.selectedVoucher) {
                            // create mode
                            this.addRow(2);
                        }else{
                            this.form.manual_id = this.selectedVoucher.manual_id;
                            this.form.date = this.selectedVoucher.date;
                            this.form.notes = this.selectedVoucher.notes;
                            this.form.details = await this.getVoucherDetails();

                        }

                        if(e.detail.duplicate) {
                            this.duplicate = true;
                            this.selectedVoucher = null;
                        }
                    });
                },

                async getVoucherDetails() {
                    try {
                        const response = await axios.get(`/getVoucherDetails/${this.selectedVoucher.id}`);
                        return response.data.data;
                    } catch (error) {
                        console.error('Error getting voucher details:', error);
                        return [];
                    }
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedVoucher = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.duplicate = false;
                    this.form = {
                        manual_id: null,
                        date: null,
                        notes: null,
                        details: [],
                    };
                    this.loading = false;
                },

                addRow(count = 1) {
                    for(let i = 0; i < count; i++) {
                        this.form.details.push({
                            account_id: null,
                            cost_center_id: null,
                            user_id: null,
                            narration: null,
                            debit: null,
                            credit: null,
                        });
                    }
                },

                deleteRow(index) {
                    this.form.details.splice(index, 1);
                },

                duplicateRow(index) {
                    const rowToDuplicate = {...this.form.details[index]};
                    this.form.details.push(rowToDuplicate);
                },

                handleDebitInput(index) {
                    this.form.details[index].credit = 0;
                },

                handleCreditInput(index) {
                    this.form.details[index].debit = 0;
                },

                get totalDebit() {
                    return this.form.details.reduce((total, row) => total + (parseFloat(row.debit) || 0), 0).toFixed(3);
                },
                get totalCredit() {
                    return this.form.details.reduce((total, row) => total + (parseFloat(row.credit) || 0), 0).toFixed(3);
                },
                get balance() { 
                    return (this.totalDebit - this.totalCredit).toFixed(3);
                },

                validateForm() {
                    // Check minimum 2 rows
                    if (this.form.details.length < 2) {
                        alert('At least 2 entries are required');
                        return false;
                    }

                    // Check all accounts are selected
                    for (let i = 0; i < this.form.details.length; i++) {
                        if (!this.form.details[i].account_id) {
                            alert('Account must be selected for all entries');
                            return false;
                        }
                    }

                    // Check each row has valid debit/credit
                    for (let i = 0; i < this.form.details.length; i++) {
                        const row = this.form.details[i];
                        const debit = parseFloat(row.debit) || 0;
                        const credit = parseFloat(row.credit) || 0;
                        
                        if (debit === 0 && credit === 0) {
                            alert('Each entry must have either debit or credit amount');
                            return false;
                        }
                    }

                    // Check total debit equals total credit and not zero
                    const totalDebit = parseFloat(this.totalDebit);
                    const totalCredit = parseFloat(this.totalCredit);

                    console.log('totalDebit before parseFloat', this.totalDebit);
                    console.log('totalCredit before parseFloat', this.totalCredit);
                    console.log('totalDebit after parseFloat', totalDebit);
                    console.log('totalCredit after parseFloat', totalCredit);

                    if (totalDebit === 0 || totalCredit === 0) {
                        alert('Total debit and credit amounts cannot be zero');
                        return false;
                    }

                    if (totalDebit !== totalCredit) {
                        alert('Total debit must equal total credit');
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

                    let url = '/vouchers';
                    let method = 'post';
                    let event = 'voucher-created';
                    if(this.selectedVoucher && !this.duplicate) {
                        url = `/vouchers/${this.selectedVoucher.id}`;
                        method = 'put';
                        event = 'voucher-updated';
                    }

                    // set every debit and credit to 0 if it is null before sending to server
                    this.form.details.forEach(detail => {
                        detail.debit = detail.debit || 0;
                        detail.credit = detail.credit || 0;
                    });




                    axios[method](url, this.form)
                        .then(response => {
                            this.$dispatch(event,{voucher: response.data.data});
                            this.hideModal();
                        })
                        .catch(error => {
                            console.error('Error saving voucher:', error);
                            alert(error.response?.data?.error || 'An error occurred while saving the voucher');
                        })
                        .finally(() => {
                            this.loading = false;
                        });
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