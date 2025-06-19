<div 
    x-data="orderModalComponent"
    x-on:order-selected.window="openModal"
    x-on:order-updated.window="handleOrderUpdated"
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
            class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-[90%] sm:mx-auto"
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
                <div class=" grid grid-cols-1 md:grid-cols-2 gap-3 p-4">
                        @include('includes.order-details-section')

                    <template x-if="selectedOrder.view_order_comments">
                        @include('includes.order-comments-section')
                    </template>

                    <template x-if="selectedOrder.view_order_progress">
                        @include('includes.order-statuses-section')
                    </template>

                    <template x-if="selectedOrder.view_order_invoices">
                        <template x-if="selectedOrder.status_id == 4 || selectedOrder.status_id == 7">
                            @include('includes.order-invoices-section')
                        </template>
                    </template>
                </div>
            </template>
        </div>
    </div>
    <script>
        function orderModalComponent() {
            return {
                dismissible: true,
                showModal: false,
                selectedOrder: null,
                statuses: @json($globalStatusesResource),
                technicians: @json($technicians),
                openModal(event) {
                    this.showModal = true;
                    this.selectedOrder = event.detail.order;
                    this.initListeners();
                },

                hideModal() {
                    this.showModal = false;
                    this.unsubscribeListeners();
                    this.selectedOrder = null;
                },

                getStatusNameById(statusId) {
                    return this.statuses.find(status => status.id === statusId).name;
                },

                getStatusColorById(statusId) {
                    return this.statuses.find(status => status.id === statusId).color;
                },

                getTechnicianNameById(technicianId) {
                    return this.technicians.find(technician => technician.id === technicianId).name;
                },

                handleOrderUpdated(event) {
                    if(!this.selectedOrder) {
                        return;
                    }
                    // this will refresh order details section
                    if (this.selectedOrder.id == event.detail.order.id) {
                        this.selectedOrder = event.detail.order;
                    }
                },

                async getCommentResource(commentId) {
                    let response = await axios.get(`/orders/${this.selectedOrder.id}/comments/${commentId}`);
                    return response.data.data;
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

                    // invoices
                    var invoicesChannel = Echo.channel('order-invoices.' + this.selectedOrder.id);
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

                    // comments
                    var commentsChannel = Echo.channel('order-comments.' + this.selectedOrder.id);
                    commentsChannel.listen('OrderCommentCreatedEvent', async (data) => {
                        const commentResource = await this.getCommentResource(data.comment.id);
                        this.$dispatch('order-comment-created', {comment: commentResource});
                    });

                },

                unsubscribeListeners() {
                    if(this.selectedOrder) {
                        Echo.leave('order-invoices.' + this.selectedOrder.id);
                        Echo.leave('order-comments.' + this.selectedOrder.id);
                    }
                }
            }
        }
    </script>
</div>