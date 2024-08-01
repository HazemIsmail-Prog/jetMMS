<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.company_contracts') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>


    @can('create', App\Models\CompanyContract::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showCompanyContractFormModal')">
                {{ __('messages.add_company_contract') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->contracts->total() }}
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
        @if ($this->contracts->hasPages())
        <div class=" flex-1">{{ $this->contracts->links() }}</div>
        @endif
    </div>
    @endteleport

    @livewire('companies.contracts.contract-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">

        <div>
            <x-label for="client_name">{{ __('messages.client_name') }}</x-label>
            <x-input class="w-full" wire:model.live="filters.client_name"/>
        </div>

        <div>
            <x-label for="company">{{ __('messages.company') }}</x-label>
            <x-searchable-select id="company" class="!py-[5px]" :list="$this->companies"
                wire:model.live="filters.company_id" multipule />
        </div>

    </div>

    @if ($this->contracts->count() > 0)
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.company') }}</x-th>
                    <x-th>{{ __('messages.client_name') }}</x-th>
                    <x-th>{{ __('messages.date') }}</x-th>
                    <x-th>{{ __('messages.description') }}</x-th>
                    <x-th>{{ __('messages.notes') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->contracts as $contract)
                <x-tr>
                    <x-td>{{ $contract->company->name }}</x-td>
                    <x-td>{{ $contract->client_name }}</x-td>
                    <x-td>
                        <div>{{ $contract->initiation_date }}</div>
                        <div>{{ $contract->expiration_date }}</div>
                    </x-td>
                    <x-td>{{ $contract->description }}</x-td>
                    <x-td>{{ $contract->notes }}</x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $contract)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showCompanyContractFormModal',{companyContract:{{ $contract }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAnyAttachment', $contract)
                                <x-badgeWithCounter :counter="$contract->attachments_count" title="{{ __('messages.attachments') }}"
                                    wire:click="$dispatch('showAttachmentModal',{model:'CompanyContract',id:{{ $contract->id }}})">
                                    <x-svgs.attachment class="w-4 h-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $contract)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $contract }})">
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
