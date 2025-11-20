<x-app-layout>
    <x-slot name="title">
        {{ __('messages.other_income_categories') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.other_income_categories') }}
            </h2>
            @if(auth()->user()->hasPermission('other_income_categories_create'))
                <x-button @click="$dispatch('open-create-modal')">
                    {{__('messages.add_other_income_category')}}
                </x-button>
            @endif
        </div>
    </x-slot>

    @include('modals.other-income-category-form')

    <div
        x-data="otherIncomeCategoriesComponent"
        x-on:other-income-category-created.window="getOtherIncomeCategories(1)"
        x-on:other-income-category-updated.window="getOtherIncomeCategories(1)"
        x-on:open-create-modal.window="openModal(null)"
    >

    <div class="flex justify-end mb-4">


        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{__('messages.name')}}</x-th>
                    <x-th>{{__('messages.income_account_id')}}</x-th>
                    <x-th>{{__('messages.expense_account_id')}}</x-th>
                    <x-th>{{__('messages.cash_account_id')}}</x-th>
                    <x-th>{{__('messages.knet_account_id')}}</x-th>
                    <x-th>{{__('messages.bank_charges_account_id')}}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="otherIncomeCategory in otherIncomeCategories" :key="otherIncomeCategory.id">
                    <x-tr>
                        <x-td x-text="otherIncomeCategory.name"></x-td>
                        <x-td x-text="getAccountNameById(otherIncomeCategory.income_account_id)"></x-td>
                        <x-td x-text="getAccountNameById(otherIncomeCategory.expense_account_id)"></x-td>
                        <x-td x-text="getAccountNameById(otherIncomeCategory.cash_account_id)"></x-td>
                        <x-td x-text="getAccountNameById(otherIncomeCategory.knet_account_id)"></x-td>
                        <x-td x-text="getAccountNameById(otherIncomeCategory.bank_charges_account_id)"></x-td>
                        <x-td>
                            <div class="flex justify-end gap-2">
                                <template x-if="otherIncomeCategory.can_edit">
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        @click="openModal(otherIncomeCategory)">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="otherIncomeCategory.can_delete">
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        @click="deleteOtherIncomeCategory(otherIncomeCategory.id)">
                                        <x-svgs.trash class="h-4 w-4 text-red-500" />
                                    </x-badgeWithCounter>
                                </template>
                            </div>
                        </x-td>
                    </x-tr>
                </template>
            </tbody>

        </x-table>

        <!-- load more -->
        <div class="flex justify-center mt-4" x-show="currentPage < lastPage">
            <x-button @click="loadMore">
                {{__('messages.load_more')}}
            </x-button>
        </div>
    </div>


    <script>
        function otherIncomeCategoriesComponent() {
            return {
                accounts: @js($accounts),
                otherIncomeCategories: [],
                currentPage: 1,
                lastPage: 1,
                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.getOtherIncomeCategories();
                },

                getAccountNameById(id) {
                    return this.accounts.find(account => account.id === id)?.name;
                },

                getOtherIncomeCategories(page=1) {
                    axios.get('/other-income-categories?page=' + page)
                        .then(response => {
                            if (page === 1) {
                                this.otherIncomeCategories = [];
                                this.otherIncomeCategories = response.data.data;
                            } else {
                                this.otherIncomeCategories = [...this.otherIncomeCategories, ...response.data.data];
                            }
                            this.currentPage = response.data.meta.current_page;
                            this.lastPage = response.data.meta.last_page;
                        })
                        .catch(error => {
                            alert(error.response.data.message);
                        });
                },
                openModal(otherIncomeCategory=null) {
                    this.$dispatch('open-other-income-category-form-modal', {selectedOtherIncomeCategory: otherIncomeCategory});
                },

                deleteOtherIncomeCategory(id) {
                    if (confirm('Are you sure you want to delete this other income category?')) {
                        axios.delete(`/other-income-categories/${id}`)
                            .then(response => {
                                this.getOtherIncomeCategories(1);
                            })
                            .catch(error => {
                                alert(error.response.data.message);
                            });
                    }
                },
                loadMore() {
                    if (this.currentPage == this.lastPage) return;                    
                    this.currentPage = (this.currentPage || 1) + 1;
                    this.getOtherIncomeCategories(this.currentPage);
                },
            }
        }
    </script>
</x-app-layout>
