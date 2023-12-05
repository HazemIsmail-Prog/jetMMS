<div>
    <x-dialog-modal maxWidth="4xl" wire:model.live="showModal">
        {{-- <x-slot name="title"> --}}
            {{-- <div>{{ $modalTitle }}</div> --}}
        {{-- </x-slot> --}}

        <x-slot name="content">
            <div class="h-[calc(100vh-150px)] flex gap-4">
                {{-- Users --}}
                <div class="flex flex-col w-72 border border-gray-200 dark:border-gray-700 rounded-lg p-4 gap-4">
                    <x-input wire:model.live="search" class="w-full" placeholder="{{ __('messages.search') }}" />
                    <div
                        class=" flex flex-col overflow-auto hidden-scrollbar divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($this->users->sortBy('name') as $user)
                            <div wire:click="selectUser({{ $user->id }})"
                                class="flex items-center justify-between p-4 cursor-pointer select-none {{ @$selectedUser->id == $user->id ? 'bg-blue-600 text-white dark:bg-gray-900' : '' }}">
                                <div>{{ $user->name }}</div>
                                @if ($user->unread_messages > 0)
                                    
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    {{ $user->unread_messages }}
                                </span>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Messages --}}
                <div class="flex flex-col flex-1 border border-gray-200 dark:border-gray-700 rounded-lg">
                    @if ($selectedUser)
                        <div
                            class=" font-extrabold text-lg p-5 border-b border-gray-200 dark:border-gray-700 h-fit w-full">
                            {{ $selectedUser->name }}
                        </div>
                        <div id="messages" class=" flex-1 overflow-scroll hidden-scrollbar p-4 space-y-4">
                            @foreach ($this->selectedMessages as $message)
                                <div
                                    class="flex gap-3 items-end chat-message cursor-pointer {{ auth()->id() == $message->sender_user_id ? '' : 'flex-row-reverse' }}">


                                    <img src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                                        alt="My profile" class="w-6 h-6 rounded-full">


                                    <div
                                        class="  flex-1  {{ auth()->id() == $message->sender_user_id ? 'text-start' : 'text-end' }}  text-xs">
                                        <div
                                            class="px-4 py-2 max-w-xs rounded-lg inline-block {{ auth()->id() == $message->sender_user_id ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}  ">
                                            {{ $message->message }}
           
                                        </div>
                                    </div>

                                    <div class=" self-end flex flex-col {{ $message->read && $message->sender_user_id == auth()->id() ? 'text-blue-600' : '' }}" style="font-size: 0.7rem;">
                                        <div>{{ $message->created_at->diffforHumans() }}</div>
                                    </div>


                                </div>
                            @endforeach
                        </div>
                        <div
                            class=" p-3 border-t border-gray-200 dark:border-gray-700 h-fit w-full flex gap-2 items-center">
                            <x-input id="message" wire:keydown.enter="send" wire:model="message" class="w-full text-start"
                                placeholder="{{ __('messages.write_your_message') }}" />
                            <x-button wire:click="send">{{ __('messages.send') }}</x-button>
                        </div>
                    @endif
                </div>
            </div>
        </x-slot>

        {{-- <x-slot name="footer"> --}}
            {{-- <x-secondary-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                {{ __('messages.back') }}
            </x-secondary-button> --}}
        {{-- </x-slot> --}}
    </x-dialog-modal>
</div>
