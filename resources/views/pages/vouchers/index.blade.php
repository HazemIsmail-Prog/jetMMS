<x-app-layout>
    <x-slot name="title">
        {{ __('messages.journal_vouchers') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.journal_vouchers') }}
                <span id="counter"></span>
            </h2>
            <div id="addNew"></div>
        </div>
    </x-slot>

    @include('modals.voucher-form')
    @include('modals.attachments')
    @include('modals.attachment-form')

    <div
        x-data="vouchersComponent()"
        x-on:voucher-created.window="addNewVoucher"
        x-on:voucher-updated.window="updateVoucher"
        x-on:attachments-count-updated.window="updateAttachmentsCount"
    >
        @can('create', App\Models\Voucher::class)
            <template x-teleport="#addNew">
                <x-button @click="$dispatch('open-voucher-form-modal')">
                    {{__('messages.add_journal_voucher')}}
                </x-button>
            </template>
        @endcan

        <template x-teleport="#counter">
            <span 
                class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300"
                x-text="totalRecords"
            >
            </span>
        </template>

        <!-- Filters -->
        <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
            <div>
                <x-label for="search">{{ __('messages.search') }}</x-label>
                <x-input id="search" x-model.debounce="filters.search" class="w-full text-start py-0" />
            </div>

            <div>
                <x-label for="start_date">{{ __('messages.date') }}</x-label>
                <x-input id="start_date" class="w-36 min-w-full text-center py-0" type="date"
                    x-model="filters.start_date" />
                <x-input id="end_date" class="w-36 min-w-full text-center py-0" type="date"
                    x-model="filters.end_date" />
            </div>
        </div>

        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.voucher_number') }}</x-th>
                    <x-th>{{ __('messages.manual_id') }}</x-th>
                    <x-th>{{ __('messages.date') }}</x-th>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-th>{{ __('messages.amount') }}</x-th>
                    <x-th>{{ __('messages.creator') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="voucher in vouchers" :key="voucher.id">
                    <x-tr>
                        <x-td>
                        <div class="flex gap-2 items-center">
                            <span x-text="voucher.id"></span>
                            <template x-if="voucher.can_edit">
                                <x-badgeWithCounter 
                                    title="{{ __('messages.edit') }}"
                                    @click="$dispatch('open-voucher-form-modal', {voucher: voucher})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            </template>
                            
                        </div>
                    </x-td>
                        <x-td x-text="voucher.manual_id"></x-td>
                        <x-td x-text="voucher.formatted_date"></x-td>
                        <x-td class="!whitespace-normal" x-text="voucher.notes"></x-td>
                        <x-td x-text="formatNumber(voucher.amount)"></x-td>
                        <x-td x-text="voucher.creator.name"></x-td>
                        <x-td>
                            <div class="flex justify-end gap-2">
                                <template x-if="voucher.can_list_attachments">
                                    <x-badgeWithCounter
                                        title="{{ __('messages.view_voucher_attachment') }}"
                                        @click="$dispatch('open-attachment-index-modal', {model: voucher, type: 'Voucher'})"
                                    >
                                        <x-svgs.attachment class="h-4 w-4" />
                                        <span x-show="voucher.attachments_count > 0" style="font-size: 0.6rem;" x-text="voucher.attachments_count"></span>
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="voucher.can_create">
                                    <x-badgeWithCounter 
                                        title="{{ __('messages.duplicate') }}"
                                        @click="$dispatch('open-voucher-form-modal', {voucher: voucher, duplicate: true})">
                                        <x-svgs.duplicate class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="voucher.can_delete">
                                    <x-badgeWithCounter
                                        class="border-red-500 dark:border-red-500 text-red-500 dark:text-red-500 hover:bg-red-500 hover:text-white"
                                        title="{{ __('messages.delete') }}"
                                        @click="deleteVoucher(voucher.id)">
                                        <x-svgs.trash class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>
                            </div>
                        </x-td>
                    </x-tr>
                </template>
            </tbody>

        </x-table>

        <!-- load more letters -->
        <div class="flex justify-center mt-4" x-show="currentPage < lastPage">
            <x-button @click="loadMore">{{__('messages.load_more')}}</x-button>
        </div>
    </div>


    <script>
        function vouchersComponent() {
            return {
                loading: false,
                vouchers: [],
                showModal: false,
                currentPage: 1,
                lastPage: 1,
                totalRecords: 0,
                filters: {
                    search: '',
                    start_date: '',
                    end_date: '',
                },
                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.getVouchers(1);    
                    this.$watch('filters', () => {
                        this.getVouchers(1);
                    });
                },

                formatNumber(number) {
                    if(number > 0) {
                        return number.toLocaleString('en-US', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
                    }
                    return '-';
                },


                getVouchers(page=1) {
                    this.loading = true;
                    axios.get('/vouchers?page=' + page, {params: this.filters})
                        .then(response => {
                            if (page === 1) {
                                this.vouchers = [];
                                this.vouchers = response.data.data;
                            } else {
                                this.vouchers = [...this.vouchers, ...response.data.data];
                            }
                            this.currentPage = response.data.meta.current_page;
                            this.lastPage = response.data.meta.last_page;
                            this.totalRecords = response.data.meta.total;
                        })

                        .catch(error => {
                            alert(error.response.data.message);
                        })                        
                        .finally(() => {
                            this.loading = false;
                        });
                },

                loadMore() {
                    if (this.currentPage == this.lastPage || this.loading) return;                    
                    this.currentPage = (this.currentPage || 1) + 1;
                    this.getVouchers(this.currentPage);
                },

                addNewVoucher(e) {
                    this.vouchers.unshift(e.detail.voucher);
                },

                updateVoucher(e) {
                    const index = this.vouchers.findIndex(voucher => voucher.id === e.detail.voucher.id);
                    if(index !== -1) {
                        this.vouchers[index] = e.detail.voucher;
                    }
                },

                deleteVoucher(id) {
                    if(!confirm('{{ __('messages.delete_voucher_confirmation') }}')) {
                        return;
                    }
                    this.vouchers = this.vouchers.filter(voucher => voucher.id !== id);
                    this.totalRecords--;

                    axios.delete(`/test/deleteVoucher/${id}`)
                        .then(response => {
                            console.log('success');
                        })
                        .catch(error => {
                            alert(error.response.data.message);
                            this.getVouchers(1);
                        });
                },

                updateAttachmentsCount(e) {
                    if(e.detail.method === 'delete') {
                        const index = this.vouchers.findIndex(voucher => voucher.id === e.detail.modelId);
                        if(index !== -1) {
                            this.vouchers[index].attachments_count--;
                        }
                    }

                    if(e.detail.method === 'create') {
                        const index = this.vouchers.findIndex(voucher => voucher.id === e.detail.modelId);
                        if(index !== -1) {
                            this.vouchers[index].attachments_count++;
                        }
                    }
                },
            }
        }
    </script>
</x-app-layout>
