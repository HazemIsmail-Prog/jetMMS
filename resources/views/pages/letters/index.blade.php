<x-app-layout>
    <x-slot name="title">
        {{ __('messages.letters') }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.letters') }}
        </h2>
    </x-slot>


    @include('modals.attachments')
    @include('modals.attachment-form')

    <div
        x-data="lettersComponent"
        x-on:attachments-count-updated.window="updateAttachmentsCount"
    >
        <div class="flex justify-end mb-4">
            <x-select class="w-full max-w-xs" x-model="filters.type">
                <option value="">{{__('messages.all')}}</option>
                <option value="incoming">{{__('messages.incoming')}}</option>
                <option value="outgoing">{{__('messages.outgoing')}}</option>
            </x-select>
            <div class="flex-1"></div>
            @can('create', App\Models\Letter::class)
                <x-button @click="openModal(null)">
                    {{__('messages.add_letter')}}
                </x-button>
            @endcan
        </div>

        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{__('messages.type')}}</x-th>
                    <x-th>{{__('messages.date')}}</x-th>
                    <x-th>{{__('messages.sender')}}</x-th>
                    <x-th>{{__('messages.receiver')}}</x-th>
                    <x-th>{{__('messages.reference')}}</x-th>
                    <x-th>{{__('messages.subject')}}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>

            <tbody>
                <template x-for="letter in letters" :key="letter.id">
                    <x-tr>
                        <x-td x-bind:class="letter.type_color_class" x-text="letter.translated_type"></x-td>
                        <x-td x-text="letter.date"></x-td>
                        <x-td x-text="letter.sender"></x-td>
                        <x-td x-text="letter.receiver"></x-td>
                        <x-td x-text="letter.reference"></x-td>
                        <x-td x-text="letter.subject"></x-td>
                        <x-td>
                            <div class="flex justify-end gap-2">
                                <template x-if="letter.can_view_attachments">
                                    <x-badgeWithCounter title="{{ __('messages.attachments') }}"
                                        @click="$dispatch('open-attachment-index-modal',{model: letter, type: 'Letter'})">
                                        <x-svgs.attachment class="h-4 w-4" />
                                        <span x-show="letter.attachments_count > 0" style="font-size: 0.6rem;" x-text="letter.attachments_count"></span>
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="letter.can_edit">
                                    <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                        @click="openModal(letter)">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </template>
                                <template x-if="letter.can_delete">
                                    <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                        @click="deleteLetter(letter.id)">
                                        <x-svgs.trash class="h-4 w-4 text-red-500" />
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
            <x-button @click="loadMore">
                {{__('messages.load_more')}}
            </x-button>
        </div>

        <!-- Modal -->
        <div x-on:close.stop="hideModal" x-on:keydown.escape.window="hideModal" x-show="showModal"
            class="jetstream-modal fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
            style="display: none;">
            <div x-show="showModal" class="fixed inset-0 transform transition-all" x-on:click="hideModal"
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
                
                {{-- Close Button --}}
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
                <div class="p-4">
                    <h1 class="text-2xl font-bold" x-text="modalTitle"></h1>
                </div>
                <form @submit.prevent="submitForm" class="flex flex-col gap-2 p-4">
                    <div class="flex flex-col gap-1">
                        <x-label for="type">{{__('messages.type')}}</x-label>
                        <x-select id="type" x-model="form.type">
                            <option value="">{{ __('messages.select_type') }}</option>
                            <option value="incoming">{{ __('messages.incoming') }}</option>
                            <option value="outgoing">{{ __('messages.outgoing') }}</option>
                        </x-select>
                        <p x-text="errors.type" class="text-red-500"></p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <x-label for="date">{{__('messages.date')}}</x-label>
                        <x-input id="date" type="date" x-model="form.date" />
                        <p x-text="errors.date" class="text-red-500"></p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <x-label for="sender">{{__('messages.sender')}}</x-label>
                        <x-input id="sender" type="text" x-model="form.sender" />
                        <p x-text="errors.sender" class="text-red-500"></p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <x-label for="receiver">{{__('messages.receiver')}}</x-label>
                        <x-input id="receiver" type="text" x-model="form.receiver" />
                        <p x-text="errors.receiver" class="text-red-500"></p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <x-label for="reference">{{__('messages.reference')}}</x-label>
                        <x-input id="reference" type="text" x-model="form.reference" />
                        <p x-text="errors.reference" class="text-red-500"></p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <x-label for="subject">{{__('messages.subject')}}</x-label>
                        <x-input id="subject" type="text" x-model="form.subject" />
                        <p x-text="errors.subject" class="text-red-500"></p>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 mt-4 border-t border-gray-400">
                        <x-button type="submit">{{__('messages.save')}}</x-button>
                        <x-secondary-button type="button" @click="hideModal">{{__('messages.cancel')}}</x-secondary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <script>
        function lettersComponent() {
            return {
                letters: [],
                showModal: false,
                form: null,
                errors: [],
                modalTitle: '',
                currentPage: 1,
                lastPage: 1,
                filters: {
                    type: '',
                },
                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.form = this.getEmptyForm();
                    this.getLetters();
                    this.$watch('filters.type', () => {
                        this.getLetters(1);
                    });
                },

                getEmptyForm() {
                    return {
                        type: '',
                        date: '',
                        sender: '',
                        receiver: '',
                        reference: '',
                        subject: '',
                    }
                },
                getLetters(page=1) {
                    axios.get('/letters?page=' + page, {params: this.filters})
                        .then(response => {
                            if (page === 1) {
                                this.letters = [];
                                this.letters = response.data.data;
                            } else {
                                this.letters = [...this.letters, ...response.data.data];
                            }
                            this.currentPage = response.data.meta.current_page;
                            this.lastPage = response.data.meta.last_page;
                        })
                        .catch(error => {
                            alert(error.response.data.message);
                        });
                },
                openModal(letter=null) {
                    this.errors = [];
                    this.showModal = true;
                    if (letter) {
                        this.form = {...letter};
                        this.modalTitle = '{{__('messages.edit_letter')}}';
                    } else {
                        this.form = this.getEmptyForm();
                        this.modalTitle = '{{__('messages.add_letter')}}';
                    }
                },
                submitForm() {
                    const method = this.form.id ? 'put' : 'post';
                    const url = this.form.id ? `/letters/${this.form.id}` : '/letters';
                    axios[method](url, this.form)
                    .then(response => {
                        if(this.form.id) {
                            const index = this.letters.findIndex(letter => letter.id === this.form.id);
                            if(index !== -1) {
                                this.letters[index] = response.data.data;
                            }
                            this.hideModal();
                        } else {
                            this.getLetters(1);
                            this.hideModal();
                        }
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
                },
                hideModal() {
                    this.showModal = false;
                    this.form = this.getEmptyForm();
                    this.errors = [];
                },
                deleteLetter(id) {
                    if (confirm('Are you sure you want to delete this letter?')) {
                        axios.delete(`/letters/${id}`)
                            .then(response => {
                                this.getLetters(1);
                            })
                            .catch(error => {
                                alert(error.response.data.message);
                            });
                    }
                },
                loadMore() {
                    if (this.currentPage == this.lastPage) return;                    
                    this.currentPage = (this.currentPage || 1) + 1;
                    this.getLetters(this.currentPage);
                },
                updateAttachmentsCount(e) {
                    if(e.detail.method === 'delete') {
                        const index = this.letters.findIndex(letter => letter.id === e.detail.modelId);
                        if(index !== -1) {
                            this.letters[index].attachments_count--;
                        }
                    }
                    if(e.detail.method === 'create') {
                        const index = this.letters.findIndex(letter => letter.id === e.detail.modelId);
                        if(index !== -1) {
                            this.letters[index].attachments_count++;
                        }
                    }
                }
            }
        }
    </script>
</x-app-layout>
