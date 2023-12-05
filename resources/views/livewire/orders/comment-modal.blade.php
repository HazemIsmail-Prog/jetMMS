<div>
    <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
        </x-slot>

        <x-slot name="content">
            @if ($order)
                <form wire:submit="save" class=" h-full">
                    <div class="flex-1 justify-between flex flex-col">
                        <div id="messages"
                            class="hidden-scrollbar flex border rounded-lg border-gray-200 dark:border-gray-700 flex-col h-[calc(100vh-300px)] space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                            @foreach ($this->comments as $comment)
                                {{-- @if (auth()->id() == $comment->user_id) --}}
                                <div
                                    class="flex gap-3 items-end chat-message cursor-pointer {{ auth()->id() == $comment->user_id ? '' : 'flex-row-reverse' }}">


                                    <img src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                                        alt="My profile" class="w-6 h-6 rounded-full">


                                    <div
                                        class="flex-1  {{ auth()->id() == $comment->user_id ? 'text-start' : 'text-end' }}  text-xs">
                                        <span
                                            class=" px-4 py-2 max-w-xs rounded-lg inline-block {{ auth()->id() == $comment->user_id ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}  ">
                                            {{ $comment->comment }}
                                        </span>
                                    </div>

                                    <div class=" self-end flex flex-col" style="font-size: 0.7rem;">
                                        @if (auth()->id() != $comment->user_id)
                                            <div class=" font-extrabold">{{ $comment->user->name }}</div>
                                        @endif
                                        <div>{{ $comment->created_at->diffforHumans() }}</div>
                                    </div>


                                </div>
                            @endforeach
                        </div>
                        <div class="border-t-1 pt-4 ">
                            <div class="relative flex">
                                <input wire:model="comment" type="text"
                                    placeholder="{{ __('messages.write_your_message') }}"
                                    class="w-full text-gray-900 dark:text-gray-100 pe-16 bg-transparent rounded-md py-3">
                                <div class="absolute end-0 items-center inset-y-0 hidden sm:flex">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-lg px-4 py-3 transition duration-500 ease-in-out focus:outline-none">
                                        <span class="font-bold">{{ __('messages.send') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </x-slot>

        <x-slot name="footer">
            {{-- <x-secondary-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                {{ __('messages.back') }}
            </x-secondary-button> --}}
        </x-slot>
    </x-dialog-modal>
</div>
