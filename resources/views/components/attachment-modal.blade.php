<!-- Attachment Index Modal -->
<div 
    x-data="attachmentsComponent" 
    x-show="showModal"
    x-on:open-attachment-index-modal.window="openModal($event.detail)" 
    x-on:attachments-updated.window="getAttachments"
    x-on:close.stop="hideModal" 
    x-on:keydown.escape.window="hideModal" 
    class="jetstream-modal fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;"
>
    <div 
        x-show="showModal" 
        x-on:click="hideModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 transform transition-all" 
    >
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    <div 
        x-show="showModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-xl sm:mx-auto"
    >
        
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
            <!-- title and add attachment button -->
            <div class=" flex items-center justify-between">
            <div class="text-lg font-bold">{{__('messages.attachments')}}</div>
            <template x-if="selectedModel?.can_create_attachment">
                <x-button type="button" @click="$dispatch('open-attachment-form-modal',{modelId: selectedModelId, type: selectedModelType, attachment: null})">{{ __('messages.add_attachment') }}</x-button>                    
            </template>
            </div>
            <x-section-border />
            <!-- attachments table -->
             <template x-if="attachments.length > 0">
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __('messages.description') }}</x-th>
                            <x-th>{{ __('messages.expirationDate') }}</x-th>
                            <x-th></x-th>
                        </tr>
                    </x-thead>
                    <tbody>
                        <template x-for="attachment in attachments" :key="attachment.id">
                            <x-tr>
                                <x-td class=" !whitespace-normal" x-text="attachment.translated_description"></x-td>
                                <x-td x-text="attachment.expirationDate"></x-td>
                                <x-td>
                                    <div class=" flex items-center justify-end gap-2">
                                        <a target="__blank" :href="attachment.full_path">
                                            <x-badgeWithCounter title="{{ __('messages.view') }}">
                                                <x-svgs.view class="w-4 h-4" />
                                            </x-badgeWithCounter>
                                        </a>
                                        <template x-if="selectedModel.can_update_attachment">
                                            <x-badgeWithCounter
                                                title="{{ __('messages.edit') }}"
                                                @click="$dispatch('open-attachment-form-modal',{modelId: selectedModelId, type: selectedModelType, attachment: attachment})">
                                                <x-svgs.edit class="w-4 h-4" />
                                            </x-badgeWithCounter>
                                        </template>
                                        <template x-if="selectedModel.can_delete_attachment">
                                            <x-badgeWithCounter wire:confirm="{{ __('messages.are_u_sure') }}"
                                                title="{{ __('messages.delete') }}"
                                                @click="deleteAttachment(attachment.id)">
                                                <x-svgs.trash class="w-4 h-4" />
                                            </x-badgeWithCounter>
                                        </template>
                                    </div>
                                </x-td>
                            </x-tr>
                        </template>
                    </tbody>
                </x-table>
            </template>

            <template x-if="attachments.length === 0">
                <div class="text-center text-gray-500">{{ __('messages.no_attachments') }}</div>
            </template>
        </div>
    </div>
</div>
<script>
    function attachmentsComponent() {
    return {
        showModal: false,
        selectedModel: null,
        selectedModelId: null,
        selectedModelType: null,
        attachments: [],
        getAttachments() {
            axios.get(`/attachments`, {params: {model_id: this.selectedModelId, model_type: this.selectedModelType}})
                .then(response => {
                    this.attachments = response.data.data;
                })
                .catch(error => {
                    alert(error.response.data.message);
                });
        },
        openModal(detail) {
            this.selectedModel = detail.model;
            this.selectedModelId = detail.model.id;
            this.selectedModelType = detail.type;
            this.getAttachments();
            this.showModal = true;
        },
        hideModal() {
            this.attachments = [];
            this.selectedModel = null;
            this.selectedModelId = null;
            this.selectedModelType = null;
            this.showModal = false;
        },
        deleteAttachment(id) {
            if (confirm('{{ __('messages.are_u_sure') }}')) {
                axios.delete(`/attachments/${id}`)
                    .then(response => {
                        this.getAttachments();
                        this.$dispatch('attachments-updated', this.selectedModelId);
                    })
                    .catch(error => {
                        alert(error.response.data.message);
                    });
            }
        }
    }
}

</script>