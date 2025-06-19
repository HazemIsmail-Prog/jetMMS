<div 
    x-data="orderCommentsComponent"
    x-on:order-comment-created.window="addCommentToComments"
>
    <div class=" border dark:border-gray-700 rounded-lg p-3">
        <div class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('messages.order_comments') }}
        </div>
        <x-section-border />
            <div class="flex-1 justify-between flex flex-col">
                <div x-ref="messages"
                    class="hidden-scrollbar h-72 flex gap-3 border rounded-lg border-gray-200 dark:border-gray-700 flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                    <template x-for="comment in comments" :key="comment.id">
                        <div
                            class="flex gap-3 items-end !mt-0"
                            x-bind:class="comment.is_sender ? 'flex-row-reverse' : ''"
                        >

                            <img src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                                alt="My profile" class="w-6 h-6 rounded-full">

                            <div
                                class="flex-1  text-xs"
                                x-bind:class="comment.is_sender ? 'text-end' : 'text-start'"
                            >
                                <span 
                                    class=" px-4 py-2 max-w-xs rounded-lg inline-block" 
                                    x-bind:class="comment.is_sender ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'" 
                                    x-text="comment.comment"
                                ></span>
                            </div>

                            <div class=" self-end flex flex-col" style="font-size: 0.7rem;">
                                <template x-if="!comment.is_sender">
                                    <div class=" font-extrabold" x-text="comment.user.name"></div>
                                </template>
                                <div dir="ltr" class="dark:text-gray-400" x-text="comment.formated_created_at"></div>
                            </div>


                        </div>
                    </template>
                </div>
                <form @submit.prevent="save" class=" h-full">
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex items-center gap-3">
                            <x-input 
                                x-model="comment" 
                                id="comment" 
                                type="text" 
                                placeholder="{{ __('messages.write_your_message') }}"
                                class="text-start flex-1"
                            />
                            <button 
                                x-bind:disabled="sending" 
                                type="submit"
                                class="inline-flex items-center p-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                            >
                                {{ __('messages.send') }}
                                <span x-show="sending" class="spinner ms-2"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
    </div>
    <script>
        function orderCommentsComponent() {
            return {

                comments: [],
                comment: '',
                sending: false,

                init() {
                    this.getComments();
                },

                getComments() {
                    axios.get('/orders/' + this.selectedOrder.id + '/comments')
                        .then(response => {
                            this.comments = response.data.data;
                        })
                        .then(() => {
                            this.$nextTick(() => {
                                this.$refs.messages.scrollTo({
                                    top: this.$refs.messages.scrollHeight,
                                });
                            });
                        });
                },

                save() {
                    if (this.comment.trim() == '') {
                        return;
                    }
                    const commentToSend = this.comment;
                    this.comment = '';
                    this.sending = true;
                    axios.post('/orders/' + this.selectedOrder.id + '/comments', {
                        comment: commentToSend
                    }).then(response => {
                        this.comments.push(response.data.data);
                        this.$nextTick(() => {
                            this.$refs.messages.scrollTo({
                                top: this.$refs.messages.scrollHeight,
                                behavior: 'smooth'
                            });
                        });
                        this.sending = false;
                    });
                },

                async getCommentResource(comment) {
                    let response = await axios.get(`/orders/${comment.order_id}/comments/${comment.id}`);
                    return response.data.data;
                },                    

                async addCommentToComments(e) {
                    const commentResource = await this.getCommentResource(e.detail.comment);
                    // if comment contains target, then add it to the comments
                    this.comments.push(commentResource);
                    this.$nextTick(() => {
                        this.$refs.messages.scrollTo({
                            top: this.$refs.messages.scrollHeight,
                            behavior: 'smooth'
                        });
                    });
                },
            }
        }
    </script>

    <style>
        .spinner {
            border: 2px solid #f3f3f3;
            border-radius: 50%;
            border-top: 2px solid #3498db;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</div>
