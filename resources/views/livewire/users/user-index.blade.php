<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.users') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>

    @can('create', App\Models\User::class)
    @teleport('#addNew')
    <x-button wire:click="$dispatch('showUserFormModal')">
        {{ __('messages.add_user') }}
    </x-button>
    @endteleport
    @endcan

    @teleport('#counter')
    <span
        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
        {{ $this->users->total() }}
    </span>
    @endteleport

    @if ($this->users->hasPages())
    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>
    @teleport('#pagination')
    <div class="">{{ $this->users->links() }}</div>
    @endteleport
    @endif

    @livewire('users.user-form')


    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">
        <div>
            <x-label for="name">{{ __('messages.name') }}</x-label>
            <x-input id="name" wire:model.live="filters.name" class="w-full py-0" />
        </div>
        <div>
            <x-label for="username">{{ __('messages.username') }}</x-label>
            <x-input id="username" wire:model.live="filters.username" class="w-full py-0" dir="ltr" />
        </div>
        <div>
            <x-label for="title">{{ __('messages.title') }}</x-label>
            <x-searchable-select class=" !py-[5px]" id="title" :list="$this->titles" wire:model.live="filters.title_id"
                multipule />
        </div>
        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-searchable-select class=" !py-[5px]" id="department" :list="$this->departments"
                wire:model.live="filters.department_id" multipule />
        </div>
        <div>
            <x-label for="roles">{{ __('messages.roles') }}</x-label>
            <x-searchable-select class=" !py-[5px]" id="roles" :list="$this->roles" wire:model.live="filters.role_id"
                multipule />
        </div>
        <div>
            <x-label for="shift">{{ __('messages.shift') }}</x-label>
            <x-searchable-select class=" !py-[5px]" id="shift" :list="$this->shifts" wire:model.live="filters.shift_id"
                multipule />
        </div>
        <div>
            <x-label for="status">{{ __('messages.status') }}</x-label>
            <x-select id="status" wire:model.live="filters.status" class=" w-full py-0">
                <option value="all">---</option>
                <option value="1">{{ __('messages.active') }}</option>
                <option value="0">{{ __('messages.inactive') }}</option>
            </x-select>
        </div>
    </div>

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th>{{ __('messages.username') }}</x-th>
                <x-th>{{ __('messages.title') }}</x-th>
                <x-th>{{ __('messages.department') }}</x-th>
                <x-th>{{ __('messages.roles') }}</x-th>
                <x-th>{{ __('messages.shift') }}</x-th>
                <x-th>{{ __('messages.status') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->users as $user)
            <x-tr wire:key="user-{{$user->id}}">
                <x-td>{{ $user->name }}</x-td>
                <x-td>{{ $user->username }}</x-td>
                <x-td>{{ $user->title->name }}</x-td>
                <x-td>{{ $user->department->name ?? '-' }}</x-td>
                <x-td>
                    @foreach ($user->roles as $role)
                    <span
                        class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">{{
                        $role->name }}</span>
                    @endforeach
                </x-td>
                <x-td>{{ $user->shift->name ?? '-' }}</x-td>
                <x-td>
                    @if ($user->has_active_orders)
                    <div class=" text-red-500">{{__('messages.has_active_orders')}}</div>
                    @else
                    <label wire:change="change_status({{$user->id}})"
                        class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" @if($user->active) checked @endif class="sr-only peer">
                        <div
                            class="w-7 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                    </label>
                    @endif
                </x-td>
                <x-td>
                    <div class="flex items-center justify-end gap-2">

                        @can('update', $user)
                        <x-badgeWithCounter title="{{ __('messages.edit') }}"
                            wire:click="$dispatch('showUserFormModal',{user:{{ $user }}})">
                            <x-svgs.edit class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        @can('create', App\Models\User::class)
                        <x-badgeWithCounter title="{{ __('messages.duplicate') }}"
                            wire:click="$dispatch('showUserFormModal',{copiedUser:{{ $user }}})">
                            <x-svgs.duplicate class="h-4 w-4" />
                        </x-badgeWithCounter>
                        @endcan

                        @can('delete', $user)
                        <x-badgeWithCounter title="{{ __('messages.delete') }}"
                            wire:confirm="{{ __('messages.are_u_sure') }}" wire:click="delete({{ $user }})">
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