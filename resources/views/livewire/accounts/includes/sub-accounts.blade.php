@foreach ($account->child_accounts as $account)
    <div
        @class([
            'px-4 py-1 border dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg flex items-center justify-between', 
            'ms-20' => $account->level == 1,
            'ms-40' => $account->level == 2,
            'ms-60' => $account->level == 3,
            ])
        >
        <x-label>{{ $account->name }}</x-label>
        <div class=" flex items-center gap-2">
            @if ($account->level < 3)
                <x-badgeWithCounter title="{{ __('messages.add') }}"
                    wire:click="$dispatch('showAccountFormModal',{parentAccount:{{ $account }}})">
                    <x-svgs.plus class="h-4 w-4" />
                </x-badgeWithCounter>
            @endif
            <x-badgeWithCounter title="{{ __('messages.edit') }}"
                wire:click="$dispatch('showAccountFormModal',{account:{{ $account }}})">
                <x-svgs.edit class="h-4 w-4" />
            </x-badgeWithCounter>
            <x-badgeWithCounter title="{{ __('messages.delete') }}" wire:confirm="{{ __('messages.are_u_sure') }}"
                wire:click="delete({{ $account }})">
                <x-svgs.trash class="h-4 w-4" />
            </x-badgeWithCounter>
        </div>
    </div>
    @include('livewire.accounts.includes.sub-accounts')
@endforeach
