<div 
    x-data="customerContractFormModal"
    x-on:open-customer-contract-form-modal.window="openModal"
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
    
            <template x-if="selectedCustomer">
                <div class="p-4"> 
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('messages.add_contract') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedCustomer.name"></p>
                    </div>


                    

                    <!-- Form -->
                    <form @submit.prevent="save" class="mt-6 flex flex-col gap-4">


                        <div>
                            <x-label for="address" :value="__('messages.address')" />
                            <x-select id="address" class="w-full" x-model="form.address_id">
                                <option value="">---</option>
                                <template x-for="address in selectedCustomer.addresses" :key="address.id">
                                    <option :value="address.id" x-text="address.full_address"></option>
                                </template>
                            </x-select>
                        </div>
                        
                        <div>
                            <x-label for="contract_type">{{ __('messages.contract_type') }}</x-label>
                            <x-select id="contract_type" class="w-full" x-model="form.contract_type">
                                <option selected value="">---</option>
                                <option value="subscription">{{ __('messages.subscription') }}</option>
                                <option value="construction">{{ __('messages.construction') }}</option>
                            </x-select>
                        </div>
                        
                        <div>
                            <x-label for="building_type">{{ __('messages.building_type') }}</x-label>
                            <x-select id="building_type" class="w-full" x-model="form.building_type">
                                <option selected value="">---</option>
                                <option value="residential">{{ __('messages.residential') }}</option>
                                <option value="commercial">{{ __('messages.commercial') }}</option>
                            </x-select>
                        </div>

                        <div>
                            <x-label for="contract_number">{{ __('messages.contract_number') }}</x-label>
                            <x-input id="contract_number" class="w-full" x-model="form.contract_number" type="text" />
                        </div>

                        <div>
                            <x-label for="contract_date">{{ __('messages.contract_date') }}</x-label>
                            <x-input id="contract_date" class="w-full" x-model="form.contract_date" type="date" />
                        </div>


                        <div>
                            <x-label for="contract_duration">{{ __('messages.contract_duration') }}</x-label>
                            <x-input id="contract_duration" class="w-full" x-model="form.contract_duration" type="number" />
                        </div>

                        <div>
                            <x-label for="contract_value">{{ __('messages.contract_value') }}</x-label>
                            <x-input id="contract_value" class="w-full" x-model="form.contract_value" type="number" />
                        </div>

                        <div>
                            <x-label for="units_count">{{ __('messages.units_count') }}</x-label>
                            <x-input id="units_count" class="w-full" x-model="form.units_count" type="number" />
                        </div>
                        
                        <div>
                            <x-label for="central_count">{{ __('messages.central_count') }}</x-label>
                            <x-input id="central_count" class="w-full" x-model="form.central_count" type="number" />
                        </div>

                        <div>
                            <x-label for="collected_amount">{{ __('messages.collected_amount') }}</x-label>
                            <x-input id="collected_amount" class="w-full" x-model="form.collected_amount" type="number" />
                        </div>

                        <div>
                            <x-label for="contract_expiration_date">{{ __('messages.contract_expiration_date') }}</x-label>
                            <x-input id="contract_expiration_date" class="w-full" x-model="form.contract_expiration_date" type="date" />
                        </div>

                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input id="notes" class="w-full" x-model="form.notes" type="text" />
                        </div>

                        <div>
                            <x-label for="sp_included" class="flex items-center">
                                <x-checkbox x-model="form.sp_included" id="sp_included" />
                                <span class="ms-2 ">{{ __('messages.sp_included') }}</span>
                            </x-label>
                        </div>

                        <div>
                            <x-label for="active" class="flex items-center">
                                <x-checkbox x-model="form.active" id="active" />
                                <span class="ms-2 ">{{ __('messages.active') }}</span>
                            </x-label>
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
        function customerContractFormModal() {
            return {
                dismissible: true,
                selectedCustomer: null,
                selectedContract: null,
                showModal: false,
                form: null,
                loading: false,

                openModal(e) {
                    this.resetForm();
                    this.selectedCustomer = e.detail.customer;
                    this.selectedContract = e.detail.contract;
                    this.showModal = true;
                    this.$nextTick(() => {
                        this.form.address_id = this.selectedCustomer.formatted_addresses.length < 2 ? this.selectedCustomer.formatted_addresses[0].id : null;
                    });
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedCustomer = null;
                    this.selectedContract = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.form = {
                        address_id: null,
                        contract_type: null,
                        contract_date: null,
                        contract_duration: null,
                        contract_value: null,
                        contract_expiration_date: null,
                        contract_number: null,
                        building_type: null,
                        units_count: null,
                        central_count: null,
                        collected_amount: null,
                        notes: null,
                        active: false,
                        sp_included: false,
                    };
                    this.loading = false;
                },

                validateForm() {

                    // check address
                    if (!this.form.address_id) {
                        alert('Please select an address');
                        return false;
                    }

                    // check contract type
                    if (!this.form.contract_type) {
                        alert('Please select a contract type');
                        return false;
                    }
                    
                    // check building type
                    if (!this.form.building_type) {
                        alert('Please select a building type');
                        return false;
                    }

                    // check contract number
                    if (!this.form.contract_number) {
                        alert('Please enter a contract number');
                        return false;
                    }
                    
                    // check contract date
                    if (!this.form.contract_date) {
                        alert('Please select a contract date');
                        return false;
                    }
                    
                    // check contract duration
                    if (!this.form.contract_duration) {
                        alert('Please enter a contract duration');
                        return false;
                    }
                    
                    // check contract value
                    if (!this.form.contract_value) {
                        alert('Please enter a contract value');
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

                    axios.post(`/createCustomerContract/${this.selectedCustomer.id}`, this.form)
                        .then(response => {
                            this.$dispatch('customer-contract-created',{
                                contract: response.data.data
                            });
                            this.hideModal();
                        })
                        .catch(error => {
                            console.error('Error creating customer contract:', error);
                            alert(error.response?.data?.message || 'An error occurred while creating the customer contract');
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