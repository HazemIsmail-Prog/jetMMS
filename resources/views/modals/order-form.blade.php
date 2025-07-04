<div 
    x-data="orderFormModal"
    x-on:open-order-form-modal.window="openModal"
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
            class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-3xl sm:mx-auto"
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

            <template x-if="getingData">
                <div class="p-6">
                    <div class="flex justify-center items-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent"></div>
                    </div>
                </div>
            </template>
    
            <template x-if="selectedCustomer && !getingData">
                <div class="p-6"> 
                    <div class="mb-6 border-b dark:border-gray-700 pb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="selectedOrder ? '{{ __('messages.edit_order') }}' : '{{ __('messages.add_order') }}'"></h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedCustomer?.name"></p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="save" class="space-y-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <x-label for="phone" :value="__('messages.phone')" />
                                <x-select id="phone" class="w-full" x-model="form.phone_id">
                                    <option value="">---</option>
                                    <template x-for="phone in selectedCustomer?.phones" :key="phone.id">
                                        <option :value="phone.id" :selected="form.phone_id == phone.id" x-text="phone.number"></option>
                                    </template>
                                </x-select>
                            </div>

                            <div class="flex flex-col">
                                <x-label for="address" :value="__('messages.address')" />
                                <x-select id="address" class="w-full" x-model="form.address_id">
                                    <option value="">---</option>
                                    <template x-for="address in selectedCustomer?.addresses" :key="address.id">
                                        <option :value="address.id" :selected="form.address_id == address.id" x-text="address.full_address"></option>
                                    </template>
                                </x-select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <x-label for="department" :value="__('messages.department')" />
                                <template x-if="selectedOrder?.status_id !== 1">
                                    <p class="text-sm text-gray-600 dark:text-gray-400" x-text="selectedOrder?.department.name"></p>
                                </template>
                                <template x-if="!selectedOrder || selectedOrder?.status_id === 1">
                                    <!-- start area searchable select -->
                                    <div 
                                        x-data="{
                                            items:departments,
                                            selectedItemId:form.department_id,
                                            placeholder: ''
                                        }"
                                        x-model="selectedItemId"
                                        x-modelable="form.department_id"
                                        x-effect="handleDepartmentChange"
                                    >
                                        <x-single-searchable-select />
                                    </div>
                                    <!-- end area searchable select -->
                                </template>
                                <template x-if="inProgressOrders?.length > 0">
                                    <p class="text-sm text-yellow-800 dark:text-yellow-300" x-text="`{{ __('messages.Duplicate Order', ['department' => '${getDepartmentName(form.department_id)}']) }}`"></p>
                                </template>
                            </div>

                            <div class="flex flex-col">
                                <x-label for="estimated_start_date" :value="__('messages.estimated_start_date')" />
                                <x-input id="estimated_start_date" class="w-full" x-model="form.estimated_start_date" type="date" />
                            </div>

                            @if(auth()->user()->hasPermission('dispatching_menu'))
                                <template x-if="!selectedOrder && form.department_id">
                                    <div class="flex flex-col">
                                        <x-label for="technician" :value="__('messages.technician')" />
                                        <!-- start area searchable select -->
                                        <div 
                                            x-data="{
                                                get items() { return technicians.filter(technician => technician.department_id == form.department_id && technician.active) },
                                                selectedItemId:form.technician_id,
                                                placeholder: '---'
                                            }"
                                            x-model="selectedItemId"
                                            x-modelable="form.technician_id"
                                        >
                                            <x-single-searchable-select />
                                        </div>
                                        <!-- end area searchable select -->
                                    </div>
                                </template>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <x-label for="notes" :value="__('messages.notes')" />
                                <textarea name="notes" id="notes" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" x-model="form.notes" rows="3"></textarea>
                            </div>

                            <div class="flex flex-col">
                                <x-label for="order_description" :value="__('messages.order_description')" />
                                <textarea name="order_description" id="order_description" class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" x-model="form.order_description" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <x-label for="tag" :value="__('messages.orderTag')" />
                                <x-input id="tag" class="w-full" x-model="form.tag" />
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                            <x-button x-bind:disabled="saving" type="submit">
                                {{ __('messages.save') }}
                                <span x-show="saving" class="spinner ms-2"></span>
                            </x-button>
                            <x-secondary-button type="button" @click="hideModal">{{ __('messages.cancel') }}</x-secondary-button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>

    <script>
        function orderFormModal() {
            return {
                departments: @js($departments),
                technicians: [],
                dismissible: true,
                selectedCustomer: null,
                selectedOrder: null,
                showModal: false,
                form: null,
                saving: false,
                getingData: false,
                inProgressOrders: [],

                async getOrderRequest(id) {
                    const response = await axios.get(`/orders/${id}`);
                    return response.data.data;
                },

                async getCustomerRequest(id) {
                    const response = await axios.get(`/customers/${id}`);
                    return response.data.data;
                },

                async getAvailableTechnicians(department_id) {
                    const response = await axios.get(`/customers/getAvailableTechnicians/${department_id}`);
                    return response.data.data;
                },

                async openModal(e) {
                    this.showModal = true;
                    if(e.detail.order) {
                        // edit mode
                        this.getingData = true;
                        this.selectedCustomer = await this.getCustomerRequest(e.detail.customer.id);
                        this.selectedOrder = await this.getOrderRequest(e.detail.order.id);
                        this.form = this.selectedOrder;
                        this.getingData = false;
                    }else{
                        // create mode
                        this.resetForm();
                        this.selectedCustomer = await this.getCustomerRequest(e.detail.customer.id);
                        this.selectedOrder = null;
                        this.$nextTick(() => {
                            this.form.phone_id = this.selectedCustomer.phones.length < 2 ? this.selectedCustomer.phones[0].id : null;
                            this.form.address_id = this.selectedCustomer.addresses.length < 2 ? this.selectedCustomer.addresses[0].id : null;
                        });
                    }


                },

                hideModal() {
                    this.showModal = false;
                    this.selectedCustomer = null;
                    this.selectedOrder = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.form = {
                        phone_id: null,
                        address_id: null,
                        department_id: null,
                        technician_id: null,
                        estimated_start_date: new Date().toISOString().split('T')[0],
                        notes: null,
                        order_description: null,
                        tag: null,
                    };
                    this.saving = false;
                    this.inProgressOrders = [];
                },

                validateForm() {
                    if (!this.form.phone_id || !this.form.address_id || !this.form.department_id) {
                        alert('Please select a phone, address, and department');
                        return false;
                    }
                    if (!this.form.estimated_start_date) {
                        alert('Please select an estimated start date');
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

                    const url = this.selectedOrder ? `/orders/${this.selectedOrder.id}` : `/orders/${this.selectedCustomer.id}`;
                    const method = this.selectedOrder ? 'put' : 'post';
                    const eventName = this.selectedOrder ? 'order-updated' : 'order-created';

                    axios[method](url, this.form)
                        .then(response => {
                            this.$dispatch(eventName,{order: response.data.data});
                            this.hideModal();
                        })
                        .catch(error => {
                            console.error('Error updating order:', error);
                            alert(error.response?.data?.error || 'An error occurred while updating the order');
                        })
                        .finally(() => {
                            this.saving = false;
                        });
                },

                getDepartmentName(id) {
                    return this.departments.find(department => department.id == id)?.name || '';
                },

                async handleDepartmentChange() {
                    // Reset technician selection and inProgressOrders once department changes
                    this.form.technician_id = null;
                    this.inProgressOrders = [];

                    if(!this.form.department_id) return;
                    this.technicians = await this.getAvailableTechnicians(this.form.department_id);
                    axios.get(`/customers/${this.selectedCustomer.id}/getDepartmentInProgressOrders/${this.form.department_id}`)
                    .then(response => {
                            this.inProgressOrders = response.data.in_progress_orders;
                            // if selected order is in inProgressOrders, then remove it
                            if(this.selectedOrder && this.inProgressOrders.find(order => order.id == this.selectedOrder.id)) {
                                this.inProgressOrders = this.inProgressOrders.filter(order => order.id != this.selectedOrder.id);
                            }
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