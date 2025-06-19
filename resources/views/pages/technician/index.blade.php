<x-technician-layout>

    @include('modals.invoice-form')
    @include('modals.payment-form')

    <div x-data="technicianPage">
        <template x-if="currentOrderForTechnician">
            <div class="flex flex-col h-full gap-2">
                <div class=" px-2 border dark:border-gray-700 dark:text-gray-300 rounded-lg">
                    <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                        <x-svgs.hash class="w-4 h-4 shrink-0" />
                        <p x-text="currentOrderForTechnician.formatted_id"></p>
                    </div>
                    <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                        <x-svgs.user class="w-4 h-4 shrink-0" />
                        <p x-text="currentOrderForTechnician.customer.name"></p>
                    </div>
                    <template x-if="currentOrderForTechnician.status_id != 2">
                        <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                            <x-svgs.phone class="w-4 h-4 shrink-0" />
                            <p x-text="currentOrderForTechnician.phone.number"></p>
                            <a class=" ms-5" target="_blank" x-bind:href="'https://wa.me/+965' + currentOrderForTechnician.phone.number">
                                <x-svgs.whatsapp class="w-4 h-4 shrink-0" />
                            </a>
                        </div>
                    </template>
                    <template x-if="currentOrderForTechnician.status_id != 2">
                        <div class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                            <x-svgs.map-pen class="w-4 h-4 shrink-0" />
                            <p x-text="currentOrderForTechnician.address.full_address"></p>
                            <a class=" ms-5" target="_blank" x-bind:href="currentOrderForTechnician.address.full_address">
                                <x-svgs.map-pen class="w-4 h-4 shrink-0" />
                            </a>
                        </div>
                    </template>
                    <div x-show="currentOrderForTechnician.order_description" class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                        <x-svgs.list class="w-4 h-4 shrink-0" />
                        <p x-text="currentOrderForTechnician.order_description"></p>
                    </div>
                    <div x-show="currentOrderForTechnician.notes" class=" flex items-center gap-2 border-b dark:border-gray-700 py-2">
                        <x-svgs.list class="w-4 h-4 shrink-0" />
                        <p x-text="currentOrderForTechnician.notes"></p>
                    </div>
                    <div class="py-2 flex justify-center">
                        <x-button x-cloak x-show="currentOrderForTechnician?.status_id == 2" @click="setOrderReceived" x-bind:disabled="loading">
                            <span>{{ __('messages.accept') }}</span>
                            <span x-show="loading" class="spinner ms-2"></span>
                            </x-button>
                        <x-button x-cloak x-show="currentOrderForTechnician?.status_id == 3" @click="setOrderArrived" x-bind:disabled="loading">
                            <span>{{ __('messages.arrived') }}</span>
                            <span x-show="loading" class="spinner ms-2"></span>
                        </x-button>
                        <x-button x-cloak x-show="currentOrderForTechnician?.status_id == 7" @click="setOrderCompleted" x-bind:disabled="loading">
                            <span>{{ __('messages.done') }}</span>
                            <span x-show="loading" class="spinner ms-2"></span>
                        </x-button>
                    </div>
                </div>
                <template x-if="currentOrderForTechnician.status_id != 2 && selectedOrder">
                    @include('includes.order-comments-section')
                </template>
                <template x-if="currentOrderForTechnician.view_order_invoices && selectedOrder && currentOrderForTechnician?.status_id == 7">
                    @include('includes.order-invoices-section')
                </template>
            </div>
        </template>
        <template x-if="!currentOrderForTechnician">
            <div class="flex items-center justify-center min-h-full">
                <p class="text-gray-700 dark:text-gray-300">{{ __('messages.no_orders') }}</p>
            </div>
        </template>
    </div>

    <script>
        function technicianPage() {
            return {
                currentOrderForTechnician: null,
                selectedOrder: null,
                loading: false,
                init() {
                    this.getCurrentOrderForTechnician();
                    this.initListeners();
                    this.$watch('currentOrderForTechnician', (newValue, oldValue) => {
                        if(newValue != oldValue) {
                            this.selectedOrder = this.currentOrderForTechnician;
                            if(this.selectedOrder) {
                                this.unsubscribeListeners();
                                this.initCommentsChannel();
                                this.initInvoicesChannel();
                            }
                        }
                    });
                },
                getCurrentOrderForTechnician() {
                    axios.get('/technicianPage/getCurrentOrderForTechnician')
                        .then(response => {
                            this.currentOrderForTechnician = response.data.data;
                        });
                },

                setOrderReceived() {
                    if(!this.currentOrderForTechnician) return;
                    if(!confirm('Are you sure you want to accept this order?')) return;
                    
                    this.loading = true;
                    axios.put('/orders/' + this.currentOrderForTechnician.id + '/setReceived')
                        .then(response => {
                            this.getCurrentOrderForTechnician();
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                setOrderArrived() {
                    if(!this.currentOrderForTechnician) return;
                    if(!confirm('Are you sure you want to arrive this order?')) return;
                    
                    this.loading = true;

                    axios.put('/orders/' + this.currentOrderForTechnician.id + '/setArrived')
                        .then(response => {
                            this.getCurrentOrderForTechnician();
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                setOrderCompleted() {
                    if(!this.currentOrderForTechnician) return;
                    if(!confirm('Are you sure you want to complete this order?')) return;
                    
                    this.loading = true;
                    
                    axios.put('/orders/' + this.currentOrderForTechnician.id + '/setCompleted')
                        .then(response => {
                            this.getCurrentOrderForTechnician();
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                async getInvoiceResource(invoiceId) {
                    let response = await axios.get(`/orders/${this.selectedOrder.id}/invoices/${invoiceId}`);
                    return response.data.data;
                },

                async getOrderResource(orderId) {
                    let response = await axios.get(`/orders/${orderId}`);
                    return response.data.data;
                },

                initListeners() {
                    var technicianChannel = Echo.channel('technicians.' + {{ auth()->user()->id }});
                    technicianChannel.listen('OrderUpdatedEvent', (data) => {
                        this.getCurrentOrderForTechnician();
                    });
                },

                initCommentsChannel() {
                    var commentsChannel = Echo.channel('order-comments.' + this.selectedOrder.id);
                    console.log('commentsChannel created');
                    commentsChannel.listen('OrderCommentCreatedEvent', async (data) => {
                        this.$dispatch('order-comment-created', {comment: data.comment});
                    });
                },

                initInvoicesChannel() {
                    var invoicesChannel = Echo.channel('order-invoices.' + this.selectedOrder.id);
                    console.log('invoicesChannel created');
                    invoicesChannel.listen('OrderInvoiceCreatedEvent', async (data) => {
                        const invoiceResource = await this.getInvoiceResource(data.invoice.id);
                        this.$dispatch('invoice-created', {invoice: invoiceResource});
                    });

                    invoicesChannel.listen('OrderInvoiceUpdatedEvent', async (data) => {
                        const invoiceResource = await this.getInvoiceResource(data.invoice.id);
                        this.$dispatch('invoice-discount-updated', {invoice: invoiceResource});
                        
                    });

                    invoicesChannel.listen('InvoicePaymentsUpdatedEvent', async (data) => {
                        const invoiceResource = await this.getInvoiceResource(data.invoice.id);
                        this.$dispatch('invoice-payments-updated', {invoice: invoiceResource});
                    });

                    invoicesChannel.listen('OrderInvoiceDeletedEvent', async (data) => {
                        const orderResource = await this.getOrderResource(data.order.id);
                        this.$dispatch('invoice-deleted', {order: orderResource});
                    });
                },

                unsubscribeListeners() {
                    Echo.leave('order-comments.' + this.selectedOrder.id);
                    Echo.leave('order-invoices.' + this.selectedOrder.id);
                    console.log('unsubscribed from channels');
                },
            }
        }
    </script>

</x-technician-layout>

