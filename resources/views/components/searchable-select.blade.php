@props(['list', 'model', 'live' => false, 'position' => 'absolute'])

<div class="w-full" wire:ignore x-init="getInitData" x-data="{
    isOpen: false,
    title: '---',
    search: null,
    selectedOption: @entangle($model),
    options: {{ @json_encode($list) }},
    live: {{ @json_encode($live) }},
    model: {{ @json_encode($model) }},

    getInitData: function() {
        this.options.forEach(option => {
            if (option.id == this.selectedOption) {
                this.title = option.name;
            }
        });
    },

    filteredOptions: function() {
        if (!this.search)
            return this.options;
        if (!this.search.trim()) return this.options;
        return this.options.filter(option => {
            return option.name.toLowerCase().includes(this.search.toLowerCase());
        });
    },

    handleButtonClick: function() {
        this.isOpen = !this.isOpen;
        this.search = null;
        setTimeout(() => $refs.search.focus(), 50);
        let parentWidth = $root.clientWidth + 'px';
        $refs.dropDownBox.style.width = parentWidth;
    },

    selectOption: function(option) {
        if (option) {
            this.selectedOption = option.id;
            this.title = option.name;
        } else {
            this.selectedOption = '';
            this.title = '---';
        }
        this.isOpen = false;
        if (this.live) {
            $wire.set(this.model, this.selectedOption);
        }
    }

}">
    <button @click="handleButtonClick"
        {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-between w-full truncate  px-2 py-2 text-center border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none']) }}>
        <span x-text="title"></span>
        <template x-if="selectedOption">
            <x-svgs.close  @click="selectOption(null)" class="w-4 h-4 z-10" />
        </template>
    </button>

    <div x-cloak x-ref="dropDownBox" x-show="isOpen" @click.away="isOpen = false"
        class=" {{ $position }} mt-1 border bg-white border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-md">

        <x-input x-ref="search" class="w-full cursor-default" placeholder="{{ __('messages.search') }}" type="text"
            x-model="search" @click="isOpen = true" @keydown.escape="isOpen = false" />
        <ul
            class="max-h-60 overflow-y-auto hidden-scrollbar text-sm z-10  divide-y divide-gray-100 dark:divide-gray-700  dark:text-gray-400 ">
            <template x-for="(option, index) in filteredOptions" :key="option.id">
                <li @click="selectOption(option)"
                    :class="{ 'bg-indigo-600 text-gray-100': option.id == selectedOption }"
                    class="cursor-pointer px-4 py-2 hover:bg-indigo-600 hover:text-gray-100" x-text="option.name">
                </li>
            </template>
        </ul>
    </div>
</div>
