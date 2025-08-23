<div 
    x-data="orderAppointmentModal"
    x-on:open-order-appointment-modal.window="openModal"
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
    
            <template x-if="selectedOrder">
                <div class="p-4"> 
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('messages.set_appointment') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Order #' + selectedOrder.id"></p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="save" class="mt-6 flex flex-col gap-4">
                        <div class=" space-y-3">
                            <div>
                                <x-label for="reason">{{ __('messages.date') }}</x-label>
                                <x-input required class="w-full" type="date" x-model="date" />
                                <x-input-error for="date" />
                            </div>
                            <div>
                                <x-label for="reason">{{ __('messages.time') }}</x-label>
                                <x-input required class="w-full" type="time" x-model="time" />
                                <x-input-error for="time" />
                            </div>
                        </div>
                        <div class="flex justify-end gap-2">
                            <x-button x-bind:disabled="loading" type="submit">
                                {{ __('messages.set_appointment') }}
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
        function orderAppointmentModal() {
            return {
                dismissible: true,
                selectedOrder: null,
                showModal: false,
                date: '',
                time: '',
                loading: false,
                openModal(e) {
                    this.resetForm();
                    this.selectedOrder = e.detail.order;
                    this.date = this.selectedOrder.estimated_start_date;
                    this.showModal = true;
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedOrder = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.date = '';
                    this.time = '';
                    this.loading = false;
                },

                save() {
                    if (this.loading) return;
                    
                    this.loading = true;

                    // backend
                    axios.put(`/orders/${this.selectedOrder.id}/setAppointment`,{
                        date: this.date,
                        time: this.time,
                    })
                    .then(response => {
                        this.$dispatch('order-appointment-set',{orderId:this.selectedOrder.id});
                        this.hideModal();
                    })
                    .catch(error => {
                        alert(error.response.data.error);
                        // window.location.reload();
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