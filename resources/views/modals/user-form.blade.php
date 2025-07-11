<div 
    x-data="userFormModal"
    x-on:open-user-form-modal.window="openModal"
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
            class="mb-6 bg-white dark:bg-gray-800 rounded-md shadow-xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto"
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

            <template x-if="showModal">
                <div class="p-6">
                    <!-- Header with Professional Styling -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-light text-gray-900 dark:text-white" x-text="selectedUser? '{{ __('messages.edit_user') }}' : '{{ __('messages.add_user') }}'"></h2>
                        <p class="ml-4 text-sm text-gray-600 dark:text-gray-400" x-text="selectedUser?.name"></p>
                    </div>

                    <form @submit.prevent="save" class="space-y-3">

                        <div>
                            <x-label for="name_ar">{{ __('messages.name_ar') }}</x-label>
                            <x-input class="w-full" type="text" x-model="form.name_ar" id="name_ar" dir="rtl" />
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.name_ar" x-text="formErrors.name_ar"></p>
                        </div>
                        <div>
                            <x-label for="name_en">{{ __('messages.name_en') }}</x-label>
                            <x-input class="w-full" type="text" x-model="form.name_en" id="name_en" dir="ltr" />
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.name_en" x-text="formErrors.name_en"></p>
                        </div>
                        @if (auth()->id() == 1)
                        <div>
                            <x-label for="username">{{ __('messages.username') }}</x-label>
                            <x-input class="w-full" type="text" x-model="form.username"
                                id="username"
                                autocomplete="new-username" dir="ltr"/>
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.username" x-text="formErrors.username"></p>
                        </div>
                        <div>
                            <x-label for="password">{{ __('messages.password') }}</x-label>
                            <x-input class="w-full" type="password" x-model="form.password" id="password"
                                autocomplete="new-password" dir="ltr" />
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.password" x-text="formErrors.password"></p>
                        </div>

                        <div>
                            <x-label for="title_id">{{ __('messages.title') }}</x-label>
                            <x-select class="w-full" x-model="form.title_id" id="title_id">
                                <option value="">---</option>
                                <template x-for="title in titles" :key="title.id">
                                    <option :value="title.id" x-text="title.name"></option>
                                </template>
                            </x-select>
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.title_id" x-text="formErrors.title_id"></p>
                        </div>
                        <div>
                            <x-label for="department_id">{{ __('messages.department') }}</x-label>
                            <x-select class="w-full" x-model="form.department_id" id="department_id">
                                <option value="">---</option>
                                <template x-for="department in departments" :key="department.id">
                                    <option :value="department.id" x-text="department.name"></option>
                                </template>
                            </x-select>
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.department_id" x-text="formErrors.department_id"></p>
                        </div>
                        <div>
                            <x-label for="shifts">{{ __('messages.shifts') }}</x-label>
                            <x-select class="w-full" x-model="form.shift_id" id="shift_id">
                                <option value="">---</option>
                                <template x-for="shift in shifts" :key="shift.id">
                                    <option :value="shift.id" x-text="shift.name"></option>
                                </template>
                            </x-select>
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.shift_id" x-text="formErrors.shift_id"></p>
                        </div>
                        <div>
                            <x-label for="roles">{{ __('messages.roles') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:roles,
                                    selectedItemIds:form.roles,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemIds"
                                x-modelable="form.roles"
                            >
                                <x-multipule-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.roles" x-text="formErrors.roles"></p>
                        </div>
                        <div>
                            <x-label for="directPermissions">{{ __('messages.direct_permissions') }}</x-label>
                            <!-- start area searchable select -->
                            <div 
                                x-data="{
                                    items:permissions.map(permission => ({
                                        id: permission.id,
                                        name: permission.desc,
                                    })),
                                    selectedItemIds:form.directPermissions,
                                    placeholder: '{{ __('messages.search') }}'
                                }"
                                x-model="selectedItemIds"
                                x-modelable="form.directPermissions"
                            >
                                <x-multipule-searchable-select />
                            </div>
                            <!-- end area searchable select -->
                            <p class="text-sm text-red-600 dark:text-red-400" x-show="formErrors.directPermissions" x-text="formErrors.directPermissions"></p>
                        </div>
                        <div>
                            <x-label for="active" class="flex items-center">
                                <x-checkbox x-model="form.active" id="active" />
                                <span class="ms-2 ">{{ __('messages.active') }}</span>
                            </x-label>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <x-secondary-button type="button" @click="hideModal">
                                {{ __('messages.cancel') }}
                            </x-secondary-button>
                            <x-button type="submit">
                                <span x-show="loading" class="spinner mr-2"></span>
                                {{ __('messages.save') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </template>

        </div>
    </div>

    <script>

        function userFormModal() {
            return {
                permissions: @js($permissions),
                roles: @js($roles),
                titles: @js($titles),
                departments: @js($departments),
                shifts: @js($shifts),
                dismissible: true,
                selectedUser: null,
                showModal: false,
                form: null,
                loading: false,
                duplicate: false,
                formErrors: {},

                openModal(e) {
                    this.resetForm();
                    this.selectedUser = e.detail.selectedUser || null;
                    this.showModal = true;
                    this.$nextTick(async () => {
                        if(!this.selectedUser) {
                            // create mode
                            const generatedUsername = await this.generateUsername();
                            if(generatedUsername) {
                                this.form.username = generatedUsername;
                                this.form.password = generatedUsername;
                            }
                        }else{
                            // edit mode
                            this.form = {...this.selectedUser};
                            this.form.roles = this.form.roles.map(role => role.id);
                            this.form.directPermissions = this.form.directPermissions.map(permission => permission.id);
                        }

                        if(e.detail.duplicate) {
                            // duplicate mode
                            this.duplicate = true;
                            this.selectedUser = null;
                            this.form.id = null;
                            this.form.name_ar = null;
                            this.form.name_en = null;
                            this.form.username = null;
                            this.form.password = null;

                            const generatedUsername = await this.generateUsername();
                            if(generatedUsername) {
                                this.form.username = generatedUsername;
                                this.form.password = generatedUsername;
                            }
                        }
                    });
                },

                hideModal() {
                    this.showModal = false;
                    this.selectedUser = null;
                    setTimeout(() => this.resetForm(), 300);
                },

                resetForm() {
                    this.formErrors = {};
                    this.duplicate = false;
                    this.form = {
                        id: null,
                        name_ar: null,
                        name_en: null,
                        username: null,
                        password: null,
                        title_id: null,
                        department_id: null,
                        shift_id: null,
                        roles: [],
                        directPermissions: [],
                        active: true,
                    };
                    this.loading = false;
                },

                async generateUsername() {
                    const response = await axios.get(`/users/generate-username`);
                    return response.data.username;
                },


                validateForm() {

                    this.formErrors = {};

                    // check if the name_ar is empty
                    if(!this.form.name_ar) {
                        this.formErrors.name_ar = '{{ __('messages.name_ar_is_required') }}';
                    }

                    // check if the name_en is empty
                    if(!this.form.name_en) {
                        this.formErrors.name_en = '{{ __('messages.name_en_is_required') }}';
                    }
                    
                    // check if the username is empty
                    if(!this.form.username) {
                        this.formErrors.username = '{{ __('messages.username_is_required') }}';
                    }
                    
                    // check if the password is empty
                    if(!this.form.password && !this.selectedUser) {
                        this.formErrors.password = '{{ __('messages.password_is_required') }}';
                    }

                    // check if the roles is empty
                    if(!this.form.roles.length) {
                        this.formErrors.roles = '{{ __('messages.roles_are_required') }}';
                    }

                    // check if the title_id is empty
                    if(!this.form.title_id) {
                        this.formErrors.title_id = '{{ __('messages.title_is_required') }}';
                    }

                    // check if the department_id is empty
                    if(!this.form.department_id) {
                        this.formErrors.department_id = '{{ __('messages.department_is_required') }}';
                    }

                    // check if errors is empty
                    if(Object.keys(this.formErrors).length > 0) {
                        return false;
                    }

                    return true;
                },

                save() {

                    
                    if (this.loading) return;
                    
                    if (!this.validateForm()) {
                        return;
                    }



                    this.loading = true;

                    let url = '/users';
                    let method = 'post';
                    let event = 'user-created';
                    if(this.selectedUser && !this.duplicate) {
                        url = `/users/${this.selectedUser.id}`;
                        method = 'put';
                        event = 'user-updated';
                    }




                    axios[method](url, this.form)
                        .then(response => {
                            this.$dispatch(event,{user: response.data.data});
                            this.hideModal();
                        })
                        .catch(error => {
                            if(error.response?.data?.errors) {
                                this.formErrors = error.response?.data?.errors;
                            }else{
                                console.error('Error saving user:', error);
                                alert(error.response?.data?.error || 'An error occurred while saving the user');
                            }
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },


            }
        }
    </script>
</div>