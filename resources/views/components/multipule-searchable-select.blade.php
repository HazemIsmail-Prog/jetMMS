<div x-data="searchableSelectComponent" @click.away="open = false">
    <div class="relative">
        <x-input 
            x-model="search" 
            @focus="open = true"
            @keydown.escape.stop="open = false"
            @keydown.tab.stop="open = false"
            @keydown.arrow-up.prevent="navigateUp"
            @keydown.arrow-down.prevent="navigateDown"
            @keydown.enter.prevent="selectHighlighted"
            @input="handleSearch" 
            x-bind:placeholder="open ? placeholder : ''" 
            class="w-full text-start pe-10"
            x-ref="searchInput"
        />
        <div  x-cloak x-show="selectedItemIds.length > 0" class=" flex items-center gap-1 absolute end-1 top-1/2 -translate-y-1/2 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 rounded-md border border-gray-200 dark:border-gray-700 px-2 py-1">
            <p x-text="selectedItemIds.length" class="text-[10px]"></p>
            <x-svgs.close class="w-3 h-3 cursor-pointer" x-show="selectedItemIds.length > 0" x-cloak @click="clearSelection" />
        </div>
        <div 
            tabindex="-1"
            x-show="open"
            x-anchor.offset.5="$refs.searchInput"
            class="absolute z-50 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto hidden-scrollbar"
        >
            <div class="py-1" x-ref="optionsContainer">
                <template x-for="(option, index) in filteredItems" :key="option.id">
                    <div
                        @click="handleSelect(option); $refs.searchInput.focus()"
                        :class="{'bg-indigo-50 dark:bg-indigo-900': highlightedIndex === index}"
                        class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 text-start flex items-center gap-2"
                        :id="'option-' + index"
                    >
                        <input 
                            tabindex="-1"
                            type="checkbox"
                            :checked="selectedItemIds.includes(option.id)"
                            @click.stop
                            @change="handleSelect(option)"
                            class="rounded bg-white dark:bg-gray-800 checked:bg-indigo-600 dark:checked:bg-indigo-500 border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 shadow-sm focus:border-indigo-300 dark:focus:border-indigo-700 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:ring-opacity-50"
                        >
                        <span class="text-sm text-gray-500 dark:text-gray-400" x-text="option.name"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

<script>
    function searchableSelectComponent() {
        return {
            open: false,
            search: '',
            filteredItems: [],
            highlightedIndex: -1,

            init() {
                this.filteredItems = this.items;
                this.$watch('open', () => {
                    this.search = '';
                    this.filteredItems = this.items;
                    this.highlightedIndex = -1;
                });
            },
            
            handleSearch() {
                if (!this.search) {
                    this.filteredItems = this.items;
                }
                this.filteredItems = this.items;
                this.filteredItems = this.filteredItems.filter(option => 
                    option.name.toLowerCase().includes(this.search.toLowerCase())
                );
                this.highlightedIndex = -1;
            },

            navigateUp() {
                if (!this.open) {
                    this.open = true;
                    return;
                }
                this.highlightedIndex = this.highlightedIndex > 0 ? this.highlightedIndex - 1 : this.filteredItems.length - 1;
                this.scrollToHighlighted();
            },

            navigateDown() {
                if (!this.open) {
                    this.open = true;
                    return;
                }
                this.highlightedIndex = this.highlightedIndex < this.filteredItems.length - 1 ? this.highlightedIndex + 1 : 0;
                this.scrollToHighlighted();
            },

            scrollToHighlighted() {
                this.$nextTick(() => {
                    const highlighted = this.$refs.optionsContainer.querySelector(`#option-${this.highlightedIndex}`);
                    if (highlighted) {
                        highlighted.scrollIntoView({ block: 'nearest' });
                    }
                });
            },

            selectHighlighted() {
                if (this.highlightedIndex >= 0 && this.filteredItems[this.highlightedIndex]) {
                    this.handleSelect(this.filteredItems[this.highlightedIndex]);
                }
            },

            handleSelect(option) {
                if(this.selectedItemIds.includes(option.id)) {
                    this.selectedItemIds = this.selectedItemIds.filter(id => id !== option.id);
                } else {
                    this.selectedItemIds.push(option.id);
                }
            },

            clearSelection() {
                this.selectedItemIds = [];
                this.search = '';
                this.open = false;
            }
        }
    }
</script>