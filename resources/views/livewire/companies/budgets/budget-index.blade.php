<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.company_budgets') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>


    @can('create', App\Models\CompanyBudget::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showCompanyBudgetFormModal')">
                {{ __('messages.add_company_budget') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->budgets->total() }}
    </span>
    @endteleport

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>
    @teleport('#pagination')
    <div class=" flex items-center justify-between gap-2">
        <x-select wire:model.live="perPage">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="500">500</option>
        </x-select>
        @if ($this->budgets->hasPages())
        <div class=" flex-1">{{ $this->budgets->links() }}</div>
        @endif
    </div>
    @endteleport

    @livewire('companies.budgets.budget-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">

        <div>
            <x-label for="year">{{ __('messages.year') }}</x-label>
            <x-input type="number" class="w-full" wire:model.live="filters.year"/>
        </div>

        <div>
            <x-label for="company">{{ __('messages.company') }}</x-label>
            <x-searchable-select id="company" class="!py-[5px]" :list="$this->companies"
                wire:model.live="filters.company_id" multipule />
        </div>

    </div>

    @if ($this->budgets->count() > 0)
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.company') }}</x-th>
                    <x-th>{{ __('messages.year') }}</x-th>
                    <x-th>{{ __('messages.description') }}</x-th>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->budgets as $budget)
                <x-tr>
                    <x-td>{{ $budget->company->name }}</x-td>
                    <x-td>{{ $budget->year }}</x-td>
                    <x-td>{{ $budget->description }}</x-td>
                    <x-td>{{ $budget->notes }}</x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $budget)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showCompanyBudgetFormModal',{companyBudget:{{ $budget }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAnyAttachment', $budget)
                                <x-badgeWithCounter :counter="$budget->attachments_count" title="{{ __('messages.attachments') }}"
                                    wire:click="$dispatch('showAttachmentModal',{model:'CompanyBudget',id:{{ $budget->id }}})">
                                    <x-svgs.attachment class="w-4 h-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $budget)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $budget }})">
                                    <x-svgs.trash class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan
                        </div>
                    </x-td>
                </x-tr>
                @endforeach
            </tbody>
        </x-table>
    @else
        <h2
            class="font-semibold text-xl flex gap-3 items-center justify-center text-green-600 dark:text-green-500 leading-tight">
            {{ __('messages.no_records_found') }}
        </h2>
    @endif
