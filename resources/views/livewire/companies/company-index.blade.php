<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.companies') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>

        </div>
    </x-slot>

    @can('create', App\Models\Company::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showCompanyFormModal')">
                {{ __('messages.add_company') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->companies->total() }}
        </span>
    @endteleport

    @if ($this->companies->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->companies->links() }}</div>
        @endteleport
    @endif

    @livewire('companies.company-form')

    @livewire('attachments.attachment-index')
    @livewire('attachments.attachment-form')

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.sn') }}</x-th>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->companies as $company)
                <x-tr>
                    <x-td>
                        <div>{{ $loop->iteration }}</div>
                    </x-td>
                    <x-td>
                        <div>{{ $company->name }}</div>
                    </x-td>
                    <x-td>
                        <div class="flex items-center justify-end gap-2">

                            @can('update', $company)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showCompanyFormModal',{company:{{ $company }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('viewAnyAttachment', $company)
                                <x-badgeWithCounter :counter="$company->attachments_count" title="{{ __('messages.attachments') }}"
                                    wire:click="$dispatch('showAttachmentModal',{model:'Company',id:{{ $company->id }}})">
                                    <x-svgs.attachment class="w-4 h-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $company)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $company }})">
                                    <x-svgs.trash class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan
                        </div>
                    </x-td>
                </x-tr>
            @endforeach
        </tbody>
    </x-table>
</div>
