<div 
    x-data="customerFormModal"
    x-on:open-customer-form-modal.window="openModal"
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
            class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-7xl sm:mx-auto"
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

            <template x-if="gettingData">
                <div class="p-6">
                    <div class="flex justify-center items-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-gray-300 border-t-blue-600"></div>
                    </div>
                </div>
            </template>

            <template x-if="showModal && !gettingData">
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="selectedCustomer? '{{ __('messages.edit_customer') }}' : '{{ __('messages.add_customer') }}'"></h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" x-text="selectedCustomer?.name"></p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="save" class="space-y-6">
                        
                        <!-- Basic Info Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">{{ __('messages.basic_info') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-label for="name" class="text-gray-700 dark:text-gray-300">{{ __('messages.name') }}</x-label>
                                    <x-input type="text" x-model="form.name" id="name" dir="rtl" 
                                        class="mt-1 w-full transition duration-150 ease-in-out" />
                                    <x-input-error for="form.name" />
                                </div>
        
                                <div>
                                    <x-label for="notes" class="text-gray-700 dark:text-gray-300">{{ __('messages.notes') }}</x-label>
                                    <x-input type="text" x-model="form.notes" id="notes" dir="rtl"
                                        class="mt-1 w-full transition duration-150 ease-in-out" />
                                    <x-input-error for="form.notes" />
                                </div>
                            </div>
                        </div>

                        <!-- Phone Numbers Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                            <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">{{ __('messages.phone') }}</h3>

                            <div class="space-y-3">
                                <template x-for="(phone,index) in form.phones" :key="index">
                                    <div class="flex flex-wrap md:flex-nowrap items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg transition-all duration-200 hover:shadow-md">
                                        <div class="w-full md:w-1/3">
                                            <x-select x-model="phone.type" class="w-full">
                                                <option value="mobile">{{ __('messages.mobile') }}</option>
                                                <option value="phone">{{ __('messages.phone') }}</option>
                                            </x-select>
                                            <x-input-error for="phone.type" />
                                        </div>
                                        <div class="flex-1">
                                            <x-input x-model="phone.number" dir="ltr" type="number" class="w-full" />
                                            <x-input-error for="phone.number" />
                                        </div>
            
                                        <template x-if="form.phones.length > 1">
                                            <button type="button" @click="deletePhone(index)" 
                                                class="p-2 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-full transition-colors">
                                                <x-svgs.trash class="w-5 h-5" />
                                            </button>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <div class="flex justify-end mt-4">
                                <x-button type="button" @click="addPhone">{{ __('messages.add phone') }}</x-button>
                            </div>

                        </div>
        
                        <!-- Addresses Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">{{ __('messages.address') }}</h3>

                            <div class="space-y-4">
                                <template x-for="(address,index) in form.addresses" :key="index">
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg transition-all duration-200 hover:shadow-md text-end">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <!-- start area searchable select -->
                                                <div 
                                                    x-data="{
                                                        items:areas,
                                                        selectedItemId:address.area_id,
                                                        placeholder: '{{ __('messages.area') }}'
                                                    }"
                                                    x-model="selectedItemId"
                                                    x-modelable="address.area_id"
                                                >
                                                    <x-single-searchable-select />
                                                </div>
                                                <!-- end area searchable select -->

                                                <x-input-error for="address.area_id" />
                                            </div>
        
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <x-input x-model="address.block" placeholder="{{ __('messages.block') }}" class="w-full" />
                                                    <x-input-error for="address.block" />
                                                </div>
                                                <div>
                                                    <x-input x-model="address.street" placeholder="{{ __('messages.street') }}" class="w-full" />
                                                    <x-input-error for="address.street" />
                                                </div>
                                            </div>
        
                                            <div class="grid grid-cols-3 gap-2">
                                                <x-input x-model="address.jadda" placeholder="{{ __('messages.jadda') }}" class="w-full" />
                                                <x-input x-model="address.building" placeholder="{{ __('messages.building') }}" class="w-full" />
                                                <x-input x-model="address.floor" placeholder="{{ __('messages.floor') }}" class="w-full" />
                                            </div>
        
                                            <div class="grid grid-cols-2 gap-2">
                                                <x-input x-model="address.apartment" placeholder="{{ __('messages.apartment') }}" class="w-full" />
                                                <x-input x-model="address.notes" placeholder="{{ __('messages.notes') }}" class="w-full" />
                                            </div>
                                        </div>

                                        <template x-if="form.addresses.length > 1">
                                            <button type="button" @click="deleteAddress(index)" 
                                                class="p-2 mt-4 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-full transition-colors">
                                                <x-svgs.trash class="w-5 h-5" />
                                            </button>
                                        </template>


                                    </div>
                                </template>
                            </div>

                            <div class="flex justify-end mt-4">
                                <x-button type="button" @click="addAddress">{{ __('messages.add address') }}</x-button>
                            </div>

                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                            <x-secondary-button type="button" @click="hideModal" class="px-6">
                                {{ __('messages.cancel') }}
                            </x-secondary-button>
                            <x-button x-bind:disabled="saving" type="submit" class="px-6">
                                {{ __('messages.save') }}
                                <span x-show="saving" class="spinner ms-2"></span>
                            </x-button>
                        </div>

                    </form>
                </div>
            </template>

        </div>
    </div>

    <script>

        function customerFormModal() {
            return {
                dismissible: true,
                selectedCustomer: null,
                showModal: false,
                form: null,
                saving: false,
                gettingData: false,
                areas: @js($globalAreasResource),

                async getCustomerResource(customerId) {
                    let response = await axios.get(`/customers/${customerId}`);
                    return response.data.data;
                },
                
                async openModal(e) {
                    this.resetForm();
                    this.showModal = true;
                    if(e.detail.customer) {
                        // edit mode
                        this.gettingData = true;
                        this.selectedCustomer = await this.getCustomerResource(e.detail.customer.id);
                        this.form.name = this.selectedCustomer.name;
                        this.form.notes = this.selectedCustomer.notes;
                        this.selectedCustomer.phones.forEach(phone => {
                            this.form.phones.push({
                                id: phone.id,
                                type: phone.type || null,
                                number: phone.number || null,
                            });
                        });
                        this.selectedCustomer.addresses.forEach(address => {
                            this.form.addresses.push({
                                id: address.id,
                                area_id: address.area_id || null,
                                block: address.block || null,
                                street: address.street || null,
                                jadda: address.jadda || null,
                                building: address.building || null,
                                floor: address.floor || null,
                                apartment: address.apartment || null,
                                notes: address.notes || null,
                            });
                        });
                        this.gettingData = false;
                    }else{
                        // add mode
                        this.selectedCustomer = null;
                        this.addPhone();
                        this.addAddress();
                    }
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedCustomer = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.form = {
                        name: null,
                        notes: null,
                        phones: [],
                        addresses: [],
                    };
                    this.saving = false;
                    this.gettingData = false;
                },

                addPhone() {
                    this.form.phones.push({
                        id: null,
                        type: 'mobile',
                        number: null,
                    });
                },

                addAddress() {
                    this.form.addresses.push({
                        id: null,
                        area_id: null,
                        block: null,
                        street: null,
                        jadda: null,
                        building: null,
                        floor: null,
                        apartment: null,
                        notes: null,
                    });

                },

                deletePhone(index) {
                    this.form.phones.splice(index, 1);
                },

                deleteAddress(index) {
                    this.form.addresses.splice(index, 1);
                },


                validateForm() {
                    // check if name is not empty
                    if (!this.form.name) {
                        alert('{{ __('messages.name_is_required') }}');
                        return false;
                    }
                    // check if phones is not empty 
                    if (this.form.phones.length === 0) {
                        alert('{{ __('messages.phone_is_required') }}');
                        return false;
                    }
                    // check if addresses is not empty
                    if (this.form.addresses.length === 0) {
                        alert('{{ __('messages.address_is_required') }}');
                        return false;
                    }
                    // check if all phones have a type and number
                    for (let phone of this.form.phones) {
                        if (!phone.type || !phone.number) {
                            alert('{{ __('messages.phone_type_and_number_are_required') }}');
                            return false;
                        }
                    }
                    // check if all phone numbers are unique
                    const phoneNumbers = this.form.phones.map(phone => phone.number);
                    const uniquePhoneNumbers = [...new Set(phoneNumbers)];
                    if (uniquePhoneNumbers.length !== phoneNumbers.length) {
                        alert('{{ __('messages.phone_numbers_must_be_unique') }}');
                        return false;
                    }
                    // check if all phone numbers are 8 digits
                    for (let phone of this.form.phones) {
                        if (phone.number.length !== 8) {
                            alert('{{ __('messages.phone_number_must_be_8_digits') }}');
                            return false;
                        }
                    }
                    // check if all addresses have an area_id and block and street are not empty
                    for (let address of this.form.addresses) {
                        if (!address.area_id || !address.block || !address.street) {
                            alert('{{ __('messages.address_area_id_block_street_are_required') }}');
                            return false;
                        }
                    }
                    // check if all addresses fiels are unique
                    const addressFields = this.form.addresses.map(address => `${address.area_id}-${address.block}-${address.street}-${address.jadda}-${address.building}-${address.apartment}-${address.notes}`);
                    const uniqueAddressFields = [...new Set(addressFields)];
                    if (uniqueAddressFields.length !== addressFields.length) {
                        alert('{{ __('messages.addresses_fields_must_be_unique') }}');
                        return false;
                    }


                    return true;
                },

                save() {

                    
                    if (this.saving) return;
                    
                    if (!this.validateForm()) {
                        return;
                    }

                    this.saving = true;

                    let url = '/customers';
                    let method = 'post';
                    let event = 'customer-created';
                    if(this.selectedCustomer) {
                        url = `/customers/${this.selectedCustomer.id}`;
                        method = 'put';
                        event = 'customer-updated';
                    }

                    axios[method](url, this.form)
                        .then(response => {
                            this.$dispatch(event,{customer: response.data.data});
                            this.hideModal();
                        })
                        .catch(error => {
                            alert(error.response?.data?.error);
                        })
                        .finally(() => {
                            this.saving = false;
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