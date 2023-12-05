<div x-on:click="$dispatch('showChatModal')"
    class=" absolute bottom-10 end-7 {{ auth()->user()->total_unread_messages > 0 ? 'animate-bounce' : '' }}   z-50 ">

    <button type="button"
        class="relative inline-flex items-center p-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 20 16">
            <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z" />
            <path
                d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z" />
        </svg>
        <span class="sr-only">Notifications</span>
        @if (auth()->user()->total_unread_messages > 0)
            <div
                class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -start-2">
                {{ auth()->user()->total_unread_messages }}</div>
        @endif
    </button>

</div>
