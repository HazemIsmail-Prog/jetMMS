@props([
    'invalid' => false,
    'disabled' => false,
    'canClear' => true,
    'list',
])

<div
    wire:key="{{rand().rand()}}"
    x-data="{
        isOpen: false,
        search: '',
        options: {{ json_encode($list) }},
        currentlySelected: {{ $attributes->get('x-model') }},
        focusedOptionId: null,

        init() {
            this.setWidth();
        },

        get title() {
            const selectedOption = this.options.find(option => option.id === this.currentlySelected);
            return selectedOption ? selectedOption.name : '---';
        },

        get filteredOptions() {
            if (!this.search) return this.options;
            const search = this.search.toLowerCase();
            return this.options.filter(option => option.name.toLowerCase().includes(search));
        },

        setWidth() {
            this.$refs.dropdownMenu.style.width = `${this.$refs.button.offsetWidth}px`;
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            this.setWidth();
            this.search = '';
            this.$nextTick(() => this.$refs.search.focus());
        },

        handleKeyDown(event) {
            switch (event.key) {
                case 'ArrowDown':
                    this.focusNextOption();
                    break;
                case 'ArrowUp':
                    this.focusPreviousOption();
                    break;
                case 'Enter':
                    this.selectFocusedOption();
                    break;
                case 'Escape':
                    this.isOpen = false;
                    break;
            }
        },

        focusNextOption() {
            const index = this.filteredOptions.findIndex(option => option.id === this.focusedOptionId) + 1;
            this.focusedOptionId = this.filteredOptions[index % this.filteredOptions.length].id;
            this.scrollOptionIntoView();
        },

        focusPreviousOption() {
            const index = this.filteredOptions.findIndex(option => option.id === this.focusedOptionId) - 1;
            this.focusedOptionId = this.filteredOptions[(index + this.filteredOptions.length) % this.filteredOptions.length].id;
            this.scrollOptionIntoView();
        },

        selectSingleOption(option) {
            if (option) {
                this.currentlySelected = option.id;
                this.$refs.select.value = option.id;
                this.$refs.select.dispatchEvent(new Event('change'));
                this.isOpen = false;
            }
        },

        selectFocusedOption() {
            const focusedOption = this.filteredOptions.find(option => option.id === this.focusedOptionId);
            this.selectSingleOption(focusedOption);
        },

        clearButtonIsVisible() {
            return this.currentlySelected;
        },

        clearSelection() {
            this.currentlySelected = null;
            this.$refs.select.value = null;
            this.$refs.select.dispatchEvent(new Event('change'));
        },

        scrollOptionIntoView() {
            const element = document.getElementById(`option-${this.focusedOptionId}`);
            if (element) element.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        },

    }"
    x-init="init()"
    @resize.window="setWidth"
    @keydown.window.prevent.arrow-up.window.prevent.arrow-down.window.prevent.enter.window.prevent.escape="handleKeyDown"
>

    <select class="hidden" x-ref="select" x-model="{{ $attributes->get('x-model') }}" data-row-id="{{ $attributes->get('data-row-id') }}">
        <option value="">---</option>
        <template x-for="option in options" :key="option.id">
            <option :value="option.id" x-text="option.name"></option>
        </template>
    </select>

    @php
    $classes = $invalid
                ? 'relative w-full border focus:ring-1 focus:outline-none px-3 py-1.5 text-center flex items-center justify-between border-red-500 dark:border-red-500 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm'
                : 'relative w-full border focus:ring-1 focus:outline-none px-3 py-1.5 text-center flex items-center justify-between border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
    @endphp
    <button
        x-ref="button"
        @click="toggleDropdown"
        @keydown.arrow-down.prevent="handleKeyDown"
        @keydown.arrow-up.prevent="handleKeyDown"
        @keydown.enter.prevent="handleKeyDown"
        {{ $attributes->merge(['disabled' => $disabled, 'class' => $classes ]) }}
        type="button"
    >
        <span class="truncate" x-text="title"></span>
        <div class="flex items-center gap-1">
            @if (!$disabled && $canClear)
                <div x-cloak @click="clearSelection" x-show="clearButtonIsVisible">
                    {{-- x-mark icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            @endif
            {{-- chevron-down icon --}}
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 1 4 4 4-4" />
            </svg>
        </div>
    </button>

    <!-- Dropdown menu -->
    <div
        x-cloak
        x-ref="dropdownMenu"
        x-show="isOpen"
        @click.away="isOpen = false"
        class="border border-gray-300 dark:border-gray-700 absolute min-w-[240px] mt-1 z-10 bg-white rounded-lg shadow-xl dark:bg-gray-900"
        tabindex="-1"
    >
        <div class="p-3">
            <label for="input-group-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                    {{-- search icon --}}
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input x-ref="search" x-model="search" type="text"
                    class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="{{ __('messages.search') }}"
                    @keydown.arrow-down.prevent="handleKeyDown"
                    @keydown.arrow-up.prevent="handleKeyDown"
                    @keydown.enter.prevent="handleKeyDown"
                    @keydown.escape.prevent="handleKeyDown"
                >
            </div>
        </div>

        <ul class="max-h-[500px] overflow-x-hidden px-3 pb-3 overflow-y-auto text-gray-700 dark:text-gray-200">
            <template x-for="option in filteredOptions" :key="option.id">
                <li>
                    <div
                        :id="'option-' + option.id"
                        class="flex items-center ps-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600"
                        :class="{
                            'bg-indigo-600 text-gray-100 hover:text-gray-700': option.id == currentlySelected,
                            'bg-gray-200 dark:bg-gray-700': option.id === focusedOptionId
                        }"
                        @mouseenter="focusedOptionId = option.id"
                    >
                        <label
                            @click="selectSingleOption(option)"
                            class="w-full py-2 ms-2 font-medium !whitespace-normal"
                            x-text="option.name"
                        ></label>
                    </div>
                </li>
            </template>
        </ul>
    </div>
</div>
