<x-app-layout>
    <x-slot name="title">
        {{ __('messages.users') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.users') }}
                <span id="counter"></span>
            </h2>
            <div id="addNew"></div>
        </div>
    </x-slot>

    @include('modals.user-form')

    <div
        x-data="usersComponent()"
        x-on:user-created.window="addNewUser"
        x-on:user-updated.window="updateUser"
    >
        @if (auth()->user()->hasPermission('users_create'))
            <template x-teleport="#addNew">
                <x-button @click="$dispatch('open-user-form-modal')">
                    {{__('messages.add_user')}}
                </x-button>
            </template>
        @endif

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
                <x-label for="name">{{ __('messages.name') }}</x-label>
                <x-input id="name" x-model.debounce="filters.name" class="w-full text-start py-0" />
            </div>
            <div>
                <x-label for="filters.username">{{ __('messages.username') }}</x-label>
                <x-input type="text" id="filters.username" x-model.debounce="filters.username" class="w-full py-0" dir="ltr" />
            </div>

            <div>
                <x-label for="filters.title_ids">{{ __('messages.title') }}</x-label>
                <!-- start area searchable select -->
                <div 
                    x-data="{
                        items:titles,
                        selectedItemIds:filters.title_ids,
                        placeholder: '{{ __('messages.search') }}'
                    }"
                    x-model="selectedItemIds"
                    x-modelable="filters.title_ids"
                >
                    <x-multipule-searchable-select />
                </div>
                <!-- end area searchable select -->
            </div>

            <div>
                <x-label for="filters.department_ids">{{ __('messages.department') }}</x-label>
                <!-- start area searchable select -->
                <div 
                    x-data="{
                        items:departments,
                        selectedItemIds:filters.department_ids,
                        placeholder: '{{ __('messages.search') }}'
                    }"
                    x-model="selectedItemIds"
                    x-modelable="filters.department_ids"
                >
                    <x-multipule-searchable-select />
                </div>
                <!-- end area searchable select -->
            </div>

            <div>
                <x-label for="filters.permission_ids">{{ __('messages.permissions') }}</x-label>
                <!-- start area searchable select -->
                <div 
                    x-data="{
                        items:permissions.map(permission => ({
                            id: permission.id,
                            name: permission.desc,
                        })),
                        selectedItemIds:filters.permission_ids,
                        placeholder: '{{ __('messages.search') }}'
                    }"
                    x-model="selectedItemIds"
                    x-modelable="filters.permission_ids"
                >
                    <x-multipule-searchable-select />
                </div>
                <!-- end area searchable select -->
            </div>
            
            <div>
                <x-label for="filters.status">{{ __('messages.status') }}</x-label>
                <x-select id="filters.status" x-model="filters.status" class="w-full py-0">
                    <option value="">{{ __('messages.all') }}</option>
                    <option value="1">{{ __('messages.active') }}</option>
                    <option value="0">{{ __('messages.inactive') }}</option>
                </x-select>
            </div>
        </div>

        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.name') }}</x-th>
                    <x-th>{{ __('messages.username') }}</x-th>
                    <x-th>{{ __('messages.title') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    @if (auth()->id() == 1)
                        <x-th>{{ __('messages.permissions') }}</x-th>
                    @endif
                    <x-th>{{ __('messages.status') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="user in users" :key="user.id">
                    <x-tr>
                        <x-td x-text="user.name"></x-td>
                        <x-td x-text="user.username"></x-td>
                        <x-td x-text="user.title.name"></x-td>
                        <x-td x-text="user.department.name"></x-td>
                        @if (auth()->id() == 1)
                            <x-td x-data="{
                                expanded: false,
                                get userPermissions() {
                                    return user.permissions;
                                },
                                togglePermission(permission) {
                                    this.permissions.push(permission);
                                }
                            }">
                                <button class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300"
                                    @click="expanded = !expanded">
                                    <span x-text="userPermissions.length"></span>
                                </button>
                                <template x-if="expanded">
                                    <div class="flex flex-col gap-2 mt-2">
                                    <template x-for="permission in userPermissions" :key="permission">
                                        <span 
                                            class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300"
                                            x-text="getPermissionDescByName(permission)"
                                        >
                                        </span>
                                        </template>
                                    </div>
                                </template>
                            </x-td>
                        @endif
                        <x-td>
                            <label
                                class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" :checked="user.active" @change="changeStatus(user.id, $event.target.checked)" class="sr-only peer">
                                <div
                                    class="w-7 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </x-td>
                        <x-td>
                            <div class="flex justify-end gap-2">
                                <template x-if="user.can_edit">
                                    <x-badgeWithCounter class="px-2" title="{{ __('messages.edit') }}"
                                        @click="$dispatch('open-user-form-modal', {selectedUser: user})">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="user.can_duplicate">
                                    <x-badgeWithCounter class="px-2" title="{{ __('messages.duplicate') }}"
                                        @click="$dispatch('open-user-form-modal', {selectedUser: user, duplicate: true})">
                                        <x-svgs.duplicate class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>

                                <template x-if="user.can_delete">
                                    <x-badgeWithCounter
                                        class="px-2 border-red-500 dark:border-red-500 text-red-500 dark:text-red-500 hover:bg-red-500 hover:text-white"
                                        title="{{ __('messages.delete') }}"
                                        @click="deleteUser(user.id)">
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
        function usersComponent() {
            return {
                permissions: @js($permissions),
                titles: @js($titles),
                departments: @js($departments),
                loading: false,
                users: [],
                showModal: false,
                currentPage: 1,
                lastPage: 1,
                totalRecords: 0,
                filters: {
                    name: '',
                    username: '',
                    permission_ids: [],
                    title_ids: [],
                    department_ids: [],
                    status: '',
                },

                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.$watch('filters', () => {
                        this.getUsers(1);
                    });
                },


                getPermissionDescByName(name) {
                    if(name) {
                        return this.permissions.find(permission => permission.name === name).desc;
                    }
                    return '';
                },


                getUsers(page=1) {
                    this.loading = true;
                    axios.get('/users?page=' + page, {params: this.filters})
                        .then(response => {
                            if (page === 1) {
                                this.users = [];
                                this.users = response.data.data;
                            } else {
                                this.users = [...this.users, ...response.data.data];
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
                    this.getUsers(this.currentPage);
                },

                addNewUser(e) {
                    this.getUsers(1);
                },

                async updateUser(e) {
                    const index = this.users.findIndex(user => user.id === e.detail.user.id);
                    if(index !== -1) {
                        this.users[index] = await this.getUserResource(e.detail.user.id);
                    }
                },

                async getUserResource(userId) {
                    const response = await axios.get(`/users/${userId}`);
                    return response.data.data;
                },

                checkFilters() {
                    return Object.values(this.filters).every(value => value === '');
                },

                deleteUser(id) {
                    if(!confirm('{{ __('messages.delete_user_confirmation') }}')) {
                        return;
                    }
                    this.users = this.users.filter(user => user.id !== id);
                    this.totalRecords--;

                    axios.delete(`/users/${id}`)
                        .then(response => {
                            console.log('success');
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                            this.getUsers(1);
                        });
                },

                changeStatus(userId, status) {
                    if(!confirm('{{ __('messages.change_status_confirmation') }}')) {
                        return;
                    }
                    axios.put(`/users/${userId}/change-status`, {status: status})
                        .then(response => {
                            console.log('success');
                            this.getUsers(1);
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                            this.getUsers(1);
                        });
                },
            }
        }
    </script>
</x-app-layout>
