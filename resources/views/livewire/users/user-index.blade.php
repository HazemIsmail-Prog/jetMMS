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
    @teleport('#addNew')
        <x-button wire:click="$dispatch('showUserFormModal')">
            {{ __('messages.add_user') }}
        </x-button>
    @endteleport

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
            <x-select id="title" wire:model.live="filters.title_id" class=" w-full py-0">
                <option value="">---</option>
                @foreach ($titles as $title)
                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-select id="department" wire:model.live="filters.department_id" class=" w-full py-0">
                <option value="">---</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-label for="roles">{{ __('messages.roles') }}</x-label>
            <x-select id="roles" wire:model.live="filters.role_id" class=" w-full py-0">
                <option value="">---</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </x-select>
        </div>
        <div>
            <x-label for="shift">{{ __('messages.shift') }}</x-label>
            <x-select id="shift" wire:model.live="filters.shift_id" class=" w-full py-0">
                <option value="">---</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                @endforeach
            </x-select>
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

    <div class=" overflow-x-auto sm:rounded-lg">
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
                    <x-tr>
                        <x-td>{{ $user->name }}</x-td>
                        <x-td>{{ $user->username }}</x-td>
                        <x-td>{{ $user->title->name }}</x-td>
                        <x-td>{{ $user->department->name ?? '-' }}</x-td>
                        <x-td>
                            @foreach ($user->roles as $role)
                                <span
                                    class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">{{ $role->name }}</span>
                            @endforeach
                        </x-td>
                        <x-td>{{ $user->shift->name ?? '-' }}</x-td>
                        <x-td>
                            <livewire:users.status-switcher :$user :key="'switcher-' . $user->id . '-' . rand()">
                        </x-td>
                        <x-td>
                            <div class="flex items-center justify-end gap-2">

                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showUserFormModal',{user:{{ $user }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>


                                <a href="{{ route('user.form', ['user' => $user, 'is_duplicate' => 'true']) }}"
                                    class="flex items-center gap-1 border dark:border-gray-700 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <x-svgs.duplicate class="w-4 h-4" />
                                </a>
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
        </x-table>
    </div>
</div>
