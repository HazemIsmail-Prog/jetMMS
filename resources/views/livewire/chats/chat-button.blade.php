<div>
    <button x-on:click="$dispatch('showChatModal')" type="button"
        class="relative align-middle rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
        <x-svgs.comment class="  w-6 h-6" />
        @if ($this->total_unread_messages > 0)
        <div
            class="absolute inline-flex items-center justify-center w-5 h-5 text-[0.7rem] font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -start-2">
            {{ $this->total_unread_messages }}</div>
        @endif
    </button>
</div>