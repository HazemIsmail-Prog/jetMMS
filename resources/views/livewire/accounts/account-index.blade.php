<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.accounts') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>

        </div>
    </x-slot>

    @livewire('accounts.account-form')

    @can('create_root', App\Models\Account::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showAccountFormModal')">
                {{ __('messages.add_account') }}
            </x-button>
        @endteleport
    @endcan

    <div class=" overflow-x-auto sm:rounded-lg">
        <div class=" space-y-2">
            @foreach ($this->accounts as $account)
                <div
                    class=" px-4 py-1 border dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg flex items-center justify-between">
                    <x-label>{{ $account->name }}</x-label>
                    <div class=" flex items-center gap-2">
                        @can('create', App\Models\Account::class)
                            <x-badgeWithCounter title="{{ __('messages.add') }}"
                                wire:click="$dispatch('showAccountFormModal',{parentAccount:{{ $account }}})">
                                <x-svgs.plus class="h-4 w-4" />
                            </x-badgeWithCounter>
                        @endcan

                        @can('update', $account)
                            <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                wire:click="$dispatch('showAccountFormModal',{account:{{ $account }}})">
                                <x-svgs.edit class="h-4 w-4" />
                            </x-badgeWithCounter>
                        @endcan

                        @can('delete', $account)
                            <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                wire:confirm="{{ __('messages.are_u_sure') }}" wire:click="delete({{ $account }})">
                                <x-svgs.trash class="h-4 w-4" />
                            </x-badgeWithCounter>
                        @endcan
                    </div>
                </div>
                @include('livewire.accounts.sub-accounts')
            @endforeach
        </div>


    </div>


</div>
