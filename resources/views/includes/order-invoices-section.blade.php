<div 
    x-data="orderInvoicesComponent"
    x-on:invoice-created.window="getInvoices"
    x-on:invoice-deleted.window="getInvoices"
>
    <div class=" border dark:border-gray-700 rounded-lg p-3">
        <div class=" flex items-center justify-between">
            <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('messages.invoices') }}
            </div>
            @can('create',App\Models\Invoice::class)
                <x-button x-on:click="openInvoiceFormModal" type="button">{{ __('messages.create_invoice') }}</x-button>
            @endcan
        </div>
        <x-section-border />
        <template x-if="invoices?.length > 0">
            <div class=" flex flex-col gap-3">
                <template x-for="invoice in invoices" :key="invoice.id">
                    @include('includes.invoice-card')
                </template>
            </div>
        </template>
        <template x-if="invoices?.length == 0">
            <div class="flex items-center justify-center font-bold text-red-600 p-2">
                {{ __('messages.no_invoices_found') }}
            </div>
        </template>
    </div>
    <script>
        function orderInvoicesComponent() {
            return {
                invoices: [],
                init() {
                    this.getInvoices();
                },
                getInvoices() {
                    axios.get('/orders/' + this.selectedOrder.id + '/invoices')
                    .then(response => {
                        this.invoices = response.data.data;
                        });
                },
                openInvoiceFormModal() {
                    this.$dispatch('open-order-invoice-form-modal', {order: this.selectedOrder});
                },
            }
        }
    </script>
</div>
