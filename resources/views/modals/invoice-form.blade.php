<div 
    x-data="orderInvoiceFormModal"
    x-on:open-order-invoice-form-modal.window="openModal"
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
            class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-xl sm:mx-auto"
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
    
            <template x-if="selectedOrder">
                <div class="p-4"> 
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('messages.create_invoice') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Order #' + selectedOrder.id"></p>
                    </div>

                    <!-- Search -->
                    <x-input x-model="search" class="w-full mb-4" placeholder="{{ __('messages.search') }}" />

                    <!-- Services and Parts Container -->
                    <div class="mt-2 p-3 border dark:border-gray-700 rounded-lg flex flex-col md:flex-row gap-4">
                        <!-- Services Container -->
                        <div class="flex-1">
                            <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.services') }}</h3>
                            <ul class="w-full overflow-y-auto h-72 hidden-scrollbar text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <template x-for="service in filteredServices" :key="service.id">
                                    <li class="w-full border-b border-gray-200 rounded-t-lg dark:border-gray-600">
                                        <div class="flex items-center ps-3">
                                            <input 
                                                type="checkbox"
                                                x-bind:id="`service-${service.id}`" 
                                                x-bind:checked="selected_services.some(s => s.id === service.id)"
                                                x-bind:value="service.id"
                                                x-on:change="toggleService(service, $event.target.checked)"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label x-bind:for="`service-${service.id}`"
                                                class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer"
                                                x-text="service.name"></label>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="save" class="mt-6">
                        <!-- Services Form Section -->
                        <div class="p-4 border dark:border-gray-700 rounded-lg">
                            <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.services') }}</h3>
                            <div class="divide-y dark:divide-gray-700">
                                <template x-for="service in selected_services" :key="service.id">
                                    <div class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <div class="w-2/6 text-sm" x-text="service.name"></div>
                                        <div class="w-3/6 space-y-2">
                                            <x-input 
                                                step="0.01" 
                                                type="number"
                                                dir="ltr" 
                                                required 
                                                class="w-full" 
                                                x-model="service.quantity"
                                                placeholder="{{ __('messages.quantity') }}" />
                                            <x-input 
                                                step="0.001" 
                                                type="number"
                                                dir="ltr"
                                                required 
                                                class="w-full"
                                                x-bind:min="service.min_price" 
                                                x-bind:max="service.max_price" 
                                                x-model="service.price"
                                                x-bind:placeholder="`${service.min_price} - ${service.max_price}`" />
                                            <div class="text-center font-medium text-green-600 dark:text-green-400" x-text="`${(service.quantity * service.price).toFixed(3)} KD`"></div>
                                        </div>
                                        <div class="w-1/6 flex justify-end">
                                            <button type="button" x-on:click="deleteService(service.id)" class="text-red-600 hover:text-red-800 transition">
                                                <x-svgs.trash class="w-5 h-5" />
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="selected_services.length === 0">
                                    <div class="flex items-center justify-center font-medium text-gray-500 p-4">
                                        {{ __('messages.no_services_selected') }}
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Parts Form Section -->
                        <div class="p-4 border dark:border-gray-700 rounded-lg mt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('messages.parts') }}</h3>
                                <x-button type="button" x-on:click="addPartRow" class="text-sm">
                                    <span class="mr-2">+</span>{{ __('messages.add_part') }}
                                </x-button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="part in parts" :key="part.id">
                                    <div class="flex items-start gap-3 border dark:border-gray-700 rounded-lg p-3">
                                        <div class="flex-1">
                                            <x-input 
                                                required 
                                                class="w-full mb-2" 
                                                type="text"
                                                x-model="part.name"
                                                placeholder="{{ __('messages.name') }}" />
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <x-label for="quantity" class="text-sm">{{ __('messages.quantity') }}</x-label>
                                                    <x-input
                                                        required
                                                        class="w-full"
                                                        type="number"
                                                        x-model="part.quantity"
                                                        dir="ltr"
                                                        placeholder="{{ __('messages.quantity') }}" />
                                                </div>
                                                <div>
                                                    <x-label for="price" class="text-sm">{{ __('messages.amount') }}</x-label>
                                                    <x-input
                                                        required
                                                        class="w-full"
                                                        type="number"
                                                        x-model="part.price"
                                                        dir="ltr"
                                                        step="0.001"
                                                        placeholder="{{ __('messages.amount') }}" />
                                                </div>
                                            </div>
                                            <div class="text-right mt-2 font-medium text-green-600 dark:text-green-400" x-text="`${(part.quantity * part.price).toFixed(3)} KD`"></div>
                                        </div>
                                        <!-- <div class="w-32">
                                            <x-select 
                                                required 
                                                class="w-full"
                                                x-model="part.type">
                                                <option value="">{{ __('messages.part_source') }}</option>
                                                <option value="internal">{{ __('messages.internal') }}</option>
                                                <option value="external">{{ __('messages.external') }}</option>
                                            </x-select>
                                        </div> -->
                                        <button type="button" class="p-2 hover:text-red-800 transition" x-on:click="deletePartRow(part.id)">
                                            <x-svgs.trash class="w-5 h-5 text-red-600" />
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <template x-if="parts.length === 0">
                                <div class="flex items-center justify-center font-medium text-gray-500 p-4">
                                    {{ __('messages.no_parts_selected') }}
                                </div>
                            </template>
                        </div>

                        <!-- Delivery Form Section -->
                        <div class="p-4 border dark:border-gray-700 rounded-lg mt-6">
                            <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">{{ __('messages.delivery') }}</h3>
                            <x-input 
                                class="w-full" 
                                x-model="delivery" 
                                type="number" 
                                step="0.001" 
                                min="0"
                                dir="ltr" />
                        </div>

                        <!-- Total Amount -->
                        <div class="p-4 border dark:border-gray-700 rounded-lg mt-6 text-center bg-gray-50 dark:bg-gray-700">
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.total_amount') }}</div>
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400" x-text="`${totalAmount.toFixed(3)} KD`"></div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4 mt-6">
                            <x-secondary-button type="button" x-on:click="hideModal">
                                {{ __('messages.cancel') }}
                            </x-secondary-button>
                            <x-button 
                                type="submit"
                                x-bind:disabled="loading"
                                x-bind:class="{'opacity-75 cursor-not-allowed': loading}">
                                <template x-if="loading">
                                    <div class="mr-2 spinner"></div>
                                </template>
                                {{ __('messages.save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>

    <script>
        function orderInvoiceFormModal() {
            return {
                dismissible: true,
                selectedOrder: null,
                showModal: false,
                search: '',
                services: [],
                selected_services: [],
                parts: [],
                delivery: 0,
                loading: false,

                get filteredServices() {
                    return this.services.filter(service => 
                        service.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                get totalAmount() {
                    const servicesTotal = this.selected_services.reduce((acc, service) => 
                        acc + (service.quantity * service.price), 0
                    );
                    const partsTotal = this.parts.reduce((acc, part) => 
                        acc + (part.quantity * part.price), 0
                    );
                    return servicesTotal + partsTotal + Number(this.delivery || 0);
                },

                getDepartmentServices() {
                    axios.get(`/orders/${this.selectedOrder.id}/getDepartmentServices`)
                        .then(response => {
                            this.services = response.data.data;
                        })
                        .catch(error => {
                            console.error('Error fetching services:', error);
                        });
                },

                openModal(e) {
                    this.selectedOrder = e.detail.order;
                    this.resetForm();
                    this.getDepartmentServices();
                    this.showModal = true;
                },

                hideModal() {
                    this.showModal = false;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.search = '';
                    this.selected_services = [];
                    this.parts = [];
                    this.delivery = 0;
                    this.loading = false;
                },

                save() {
                    if (this.loading) return;
                    
                    if (!this.validateForm()) {
                        return;
                    }

                    this.loading = true;

                    const formData = {
                        order_id: this.selectedOrder.id,
                        services: this.selected_services,
                        parts: this.parts,
                        delivery: this.delivery
                    };

                    axios.post(`/orders/${this.selectedOrder.id}/invoices`, formData)
                        .then(response => {
                            this.$dispatch('invoice-created');
                            this.hideModal();
                        })
                        .catch(error => {
                            alert(error.response?.data?.error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                validateForm() {
                    // Check if no services or parts are selected
                    if (this.selected_services.length === 0 && this.parts.length === 0) {
                        alert('Please add at least one service or part');
                        return false;
                    }

                    // Check if any selected services have invalid quantities or prices
                    if (this.selected_services.some(service => service.quantity <= 0)) {
                        alert('All services must have valid quantities and prices');
                        return false;
                    }

                    // Check if any parts have invalid quantities, prices or missing names
                    if (this.parts.some(part => part.quantity <= 0 || part.price <= 0 || !part.name.trim())) {
                        alert('All parts must have valid names, quantities and prices');
                        return false;
                    }

                    return true;
                },

                addPartRow() {
                    this.parts.push({
                        id: Date.now(),
                        name: '',
                        quantity: 0,
                        price: 0,
                        type: ''
                    });
                },

                deletePartRow(id) {
                    this.parts = this.parts.filter(part => part.id !== id);
                },

                toggleService(service, checked) {
                    if (checked) {
                        this.selected_services.push({
                            ...service,
                            quantity: 0,
                            price: service.min_price == service.max_price ? service.min_price : 0
                        });
                    } else {
                        this.selected_services = this.selected_services.filter(s => s.id !== service.id);
                    }
                },

                deleteService(id) {
                    this.selected_services = this.selected_services.filter(s => s.id !== id);
                }
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