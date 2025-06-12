<!-- Attachment Form Modal -->
<div 
    x-data="attachmentFormComponent" 
    x-on:open-attachment-form-modal.window="openModal($event.detail)" 
    x-on:close.stop="hideModal" 
    x-on:keydown.escape.window="hideModal" 
    x-show="showModal"
    class="jetstream-modal fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;"
>
    <div 
        x-show="showModal" 
        class="fixed inset-0 transform transition-all" 
        x-on:click="hideModal"
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    <div 
        x-show="showModal"
        class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-xl sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
        x-transition:leave="ease-in duration-200"
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
        <!-- Modal Content -->
        <div class="p-4">
            <div class="text-lg font-bold" x-text="form.id ? '{{ __('messages.edit_attachment') }}' : '{{ __('messages.add_attachment') }}'"></div>
            <x-section-border />

            <form @submit.prevent="submitForm">

                <div class=" space-y-3">

                    <div>
                        <x-label for="description_ar">{{ __('messages.description_ar') }}</x-label>
                        <x-input class="w-full" type="text" x-model="form.description_ar"
                            id="description_ar" dir="rtl" />
                        <p class="text-red-500" x-text="errors?.description_ar"></p>  
                    </div>

                    <div>
                        <x-label for="description_en">{{ __('messages.description_en') }}</x-label>
                        <x-input class="w-full" type="text" x-model="form.description_en"
                            id="description_en" dir="ltr" />
                        <p class="text-red-500" x-text="errors?.description_en"></p>
                    </div>

                    <div>
                        <label for="form.file"
                        :class="{
                            'flex flex-col items-center justify-center w-full border-2 border-dashed rounded-lg cursor-pointer border-gray-300 bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600': !form.file,
                            'flex flex-col items-center justify-center w-full border-2 border-dashed rounded-lg cursor-pointer border-green-300 bg-green-50 dark:hover:bg-bray-800 dark:bg-green-700 hover:bg-green-100 dark:border-green-600 dark:hover:border-green-500 dark:hover:bg-green-600': form.file
                        }">
                            <div class="flex flex-col items-center justify-center w-full p-1">
                                <p :class="{
                                    'text-sm text-gray-500 dark:text-gray-400 font-semibold': !form.file,
                                    'text-sm text-green-500 dark:text-green-400 font-semibold': form.file
                                }" x-text="form.file ? '{{ __('messages.upload_done') }}' : '{{ __('messages.select_file') }}'">
                                </p>
                            </div>
                            <input id="form.file" type="file" x-ref="fileInput" class="hidden" @change="handleFileChange" />
                        </label>
                        <p class="text-red-500" x-text="errors?.file"></p>
                    </div>



                    <div>
                        <x-label for="expirationDate">{{ __('messages.expirationDate') }}</x-label>
                        <x-input class="w-full" type="date" x-model="form.expirationDate"
                            id="expirationDate" />
                        <p class="text-red-500" x-text="errors?.expirationDate"></p>
                    </div>

                    <div class="flex flex-none gap-2 items-center">
                        <x-checkbox x-model="form.alertable" id="alertable" />
                        <x-label for="alertable">{{ __('messages.alertable') }}</x-label>
                        <p class="text-red-500" x-text="errors?.alertable"></p>
                    </div>

                    <template x-if="form.alertable">
                        <div>
                            <x-label for="alertBefore">{{ __('messages.alertBefore') }}</x-label>
                            <x-input class="w-full" type="date" type="number" min="1" dir="ltr"
                                x-model="form.alertBefore" id="alertBefore" />
                            <p class="text-red-500" x-text="errors?.alertBefore"></p>
                        </div>
                    </template>

                    <!-- <template x-if="form.file"> -->
                        <div class="mt-3">
                            <x-button type="submit" x-bind:disabled="submitting" x-text="submitting ? '{{ __('messages.saving') }}' : '{{ __('messages.save') }}'"></x-button>
                        </div>
                    <!-- </template> -->    
                </div>
            </form>
        </div>
    </div>
</div>

<script>
            function attachmentFormComponent() {
            return {
                showModal: false,
                selectedModelId: null,
                selectedModelType: null,
                form: null,
                errors: null,
                submitting: false,
                getEmptyForm() {
                    return {
                        description_ar: '',
                        description_en: '',
                        file: null,
                        expirationDate: '',
                        alertable: false,
                        alertBefore: '',
                    }
                },
                init() {
                    this.form = this.getEmptyForm();
                },
                openModal(details) {
                    this.errors = null;
                    this.$watch('form.alertable', (oldValue, newValue) => {
                        if (newValue) {
                            this.form.alertBefore = '';
                        }
                    });
                    this.selectedModelId = details.modelId;
                    this.selectedModelType = details.type;
                    if (details.attachment) {
                        this.form = {...details.attachment};
                    } else {
                        this.form = this.getEmptyForm();
                    }
                    this.showModal = true;
                },
                hideModal() {
                    this.errors = null;
                    this.$refs.fileInput.value = null;
                    this.form = this.getEmptyForm();

                    this.selectedModelId = null;
                    this.selectedModelType = null;
                    this.showModal = false;
                },
                handleFileChange(event) {
                    this.form.file = event.target.files[0];
                },
                submitForm() {
                    this.submitting = true;
                    formdata = new FormData();
                    formdata.append('description_ar', this.form.description_ar);
                    formdata.append('description_en', this.form.description_en);
                    formdata.append('file', this.form.file);
                    formdata.append('expirationDate', this.form.expirationDate??'');
                    formdata.append('alertable', this.form.alertable ? 1 : 0);
                    formdata.append('alertBefore', this.form.alertBefore);
                    formdata.append('attachable_id', this.selectedModelId);
                    formdata.append('attachable_type', this.selectedModelType);

                    const url = this.form.id ? `/attachments/${this.form.id}` : '/attachments';
                    const method = this.form.id ? 'put' : 'post';
                    
                    // Always use FormData for consistency
                    if (method === 'put') {
                        formdata.append('_method', 'PUT');
                    }
                    axios.post(url, formdata, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        this.$dispatch('attachments-updated', this.selectedModelId);
                        if(method === 'post') {
                            this.$dispatch('attachments-count-updated', {modelId: this.selectedModelId, method: 'create'});
                        }
                        this.hideModal();
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    })
                    .finally(() => {
                        this.submitting = false;
                    });
                }
            }
        }
</script>