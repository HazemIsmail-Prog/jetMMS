<div 
    x-data="orderCancelReasonModal"
    x-on:open-order-cancel-reason-modal.window="openModal"
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
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('messages.cancel_order') }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Order #' + selectedOrder.id"></p>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="save" class="mt-6 flex flex-col gap-4">
                        <div class=" space-y-3">
                            <p class="font-medium">{{ __('messages.please_provide_reason_for_cancel') }}</p>
                            <div>
                                <x-label for="reason">{{ __('messages.cancel_reason') }}</x-label>
                                <x-select id="reason" class="w-full text-start" x-model="reason" x-on:change="otherReason = ''">
                                    <option value="">---</option>
                                    <option value="تأخير">تأخير</option>
                                    <option value="اختلاف بالسعر">اختلاف بالسعر</option>
                                    <option value="لا يرد">لا يرد</option>
                                    <option value="خدمة غير متوفرة">خدمة غير متوفرة</option>
                                    <option value="نازل بالخطأ">نازل بالخطأ</option>
                                    <option value="من قبل العميل">من قبل العميل</option>
                                    <option value="متابعة عمل">متابعة عمل</option>
                                    <option value="معاينة غير مدفوعة">معاينة غير مدفوعة</option>
                                    <option value="اسباب أخرى">اسباب أخرى</option>
                                </x-select>
                                <x-input-error for="reason" />
                            </div>
                            <div x-show="reason == 'اسباب أخرى'">
                                <x-input class="w-full" x-bind:required="reason == 'اسباب أخرى'" type="text" x-model="otherReason" id="otherReason" dir="rtl" />
                                <x-input-error for="otherReason" />
                            </div>
                        </div>
                        <div class="flex justify-end gap-2">
                            <x-button x-bind:disabled="loading" type="submit">
                                {{ __('messages.confirm_cancel') }}
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
        function orderCancelReasonModal() {
            return {
                dismissible: true,
                selectedOrder: null,
                showModal: false,
                reason: '',
                loading: false,
                otherReason: '',
                openModal(e) {
                    this.resetForm();
                    this.selectedOrder = e.detail.order;
                    this.showModal = true;
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedOrder = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.reason = '';
                    this.otherReason = '';
                    this.loading = false;
                },

                save() {
                    if (this.loading) return;
                    
                    if (!this.validateForm()) {
                        return;
                    }

                    const reason = this.reason == 'اسباب أخرى' ? `${this.reason} - ${this.otherReason}` : this.reason;

                    this.loading = true;

                    // backend
                    axios.put(`/orders/${this.selectedOrder.id}/setCancelled`,{
                        reason: reason,
                    })
                    .then(response => {
                        this.$dispatch('order-canceled',{orderId:this.selectedOrder.id});
                        this.hideModal();
                    })
                    .catch(error => {
                        alert(error.response.data.error);
                        window.location.reload();
                    });
                },

                validateForm() {
                    if (this.reason == '') {
                        alert('Reason is required');
                        return false;
                    }

                    if (this.reason == 'اسباب أخرى' && this.otherReason == '') {
                        alert('Other reason is required');
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