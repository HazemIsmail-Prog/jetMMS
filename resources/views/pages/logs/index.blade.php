<x-app-layout>
    <x-slot name="title">
        {{ __('messages.logs') }}
    </x-slot>

    <x-slot name="header">
        <div dir="ltr" class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                Activity Logs
                <div id="counter"></div>
            </h2>
        </div>
    </x-slot>


    <div dir="ltr" x-data="logsComponent()" class="p-4">

        <template x-teleport="#counter">
            <span x-text="filteredLogs.length"></span>
        </template>

        <!-- filters -->
        <div class="grid grid-cols-7 gap-2 items-center text-sm text-gray-500 dark:text-gray-400 py-2 px-4 bg-gray-50 dark:bg-gray-700/50 rounded-md mb-2">
            <div>Timestamp</div>
            <x-select class="text-start" x-model="filters.user">
                <option value="">Users</option>
                <template x-for="user in uniqueUsers" :key="user">
                    <option x-bind:value="user" x-text="user"></option>
                </template>
            </x-select>
            <x-select class="text-start" x-model="filters.model">
                <option value="">Models</option>
                <template x-for="model in uniqueModels" :key="model">
                    <option x-bind:value="model" x-text="model"></option>
                </template>
            </x-select>
            <x-input class="text-start" x-model="filters.search" placeholder="ID" />
            <x-select class="text-start" x-model="filters.action">
                <option value="">Actions</option>
                <template x-for="action in uniqueActions" :key="action">
                    <option x-bind:value="action" x-text="action"></option>
                </template>
            </x-select>
            <div>Message</div>
            <div class="flex justify-end">
                <x-button @click="clearFilters">Clear Filters</x-button>
            </div>
        </div>

        <!-- logs -->
        <template x-for="(log, index) in filteredLogs" :key="index">
            <div class="grid grid-cols-7 gap-2 items-center text-sm text-gray-500 dark:text-gray-400 border rounded-md border-gray-200 dark:border-gray-700 py-2 px-4 mb-2">
                <div x-text="log.timestamp"></div>
                <div x-text="log.user_name"></div>
                <div x-text="log.model"></div>
                <div x-text="log.id"></div>
                <div x-text="log.action"></div>
                <div x-text="log.message"></div>
                <div class="flex justify-end">
                    <button @click="toggleChanges(index)" class="p-2 border border-gray-200 dark:border-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="selectedLog === index ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
                <!-- Changes Panel -->
                <template x-if="selectedLog === index">
                    <div class="mt-2 col-span-7 flex items-stretch gap-4">
                        <div class="flex-1 bg-gray-100 dark:bg-gray-700/50 rounded-md p-4">
                            <h3 class="font-bold mb-2">Old Data:</h3>
                            <pre x-html="highlightChanges(filteredLogs[selectedLog].old_data, filteredLogs[selectedLog].new_data)"></pre>
                        </div>
                        <div class="self-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                            </svg>
                        </div>
                        <div class="flex-1 bg-gray-100 dark:bg-gray-700/50 rounded-md p-4">
                            <h3 class="font-bold mb-2">New Data:</h3>
                            <pre x-html="highlightChanges(filteredLogs[selectedLog].new_data, filteredLogs[selectedLog].old_data)"></pre>
                        </div>
                    </div>
                </template>
            </div>
        </template>


    </div>

    <script>
        function logsComponent() {
            return {
                logs: @json($logs),
                selectedLog: null,
                filters: {
                    search: '',
                    model: '',
                    user: '',
                    action: '',
                },

                toggleChanges(index) {
                    this.selectedLog = this.selectedLog === index ? null : index;
                },

                get uniqueModels() {
                    return [...new Set(this.filteredLogs.map(log => log.model))];
                },

                get uniqueUsers() {
                    return [...new Set(this.filteredLogs.map(log => log.user_name))];
                },

                get uniqueActions() {
                    return [...new Set(this.filteredLogs.map(log => log.action))];
                },

                get filteredLogs() {
                    return this.logs
                        .sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp))
                        .filter(log => {
                            return (this.filters.model ? log.model.toLowerCase() == this.filters.model.toLowerCase() : true) &&
                                (this.filters.user ? log.user_name.toLowerCase() == this.filters.user.toLowerCase() : true) &&
                                (this.filters.action ? log.action.toLowerCase() == this.filters.action.toLowerCase() : true ) &&
                                (this.filters.search ? log.id.toString() === this.filters.search.toString() : true);
                        });
                },

                stringifyData(data) {
                    return JSON.stringify(data, null, 2);
                },

                highlightChanges(currentData, compareData) {
                    if (!currentData || !compareData) {
                        return this.stringifyData(currentData);
                    }

                    let currentStr = JSON.stringify(currentData, null, 2);
                    let compareStr = JSON.stringify(compareData, null, 2);

                    // Split into lines
                    let currentLines = currentStr.split('\n');
                    let compareLines = compareStr.split('\n');

                    // Process each line
                    let result = currentLines.map((line, index) => {
                        if (index >= compareLines.length) {
                            return `<span class="bg-green-100 dark:bg-green-900">${line}</span>`;
                        }

                        if (line !== compareLines[index]) {
                            return `<span class="bg-yellow-100 dark:bg-yellow-900">${line}</span>`;
                        }

                        return line;
                    });

                    return result.join('\n');
                },

                clearFilters() {
                    this.filters = {
                        search: '',
                        model: '',
                        user: '',
                        action: '',
                    };
                }
            }
        }
    </script>


</x-app-layout>
