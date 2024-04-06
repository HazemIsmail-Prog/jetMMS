<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.roles') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>

    @can('create', App\Models\Role::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showRoleFormModal')">
                {{ __('messages.add_role') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->roles->total() }}
        </span>
    @endteleport

    @if ($this->roles->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->roles->links() }}</div>
        @endteleport
    @endif

    @livewire('roles.role-form')

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th>{{ __('messages.permissions') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->roles as $role)
                <x-tr>
                    <x-td>
                        <div>{{ $role->name }}</div>
                    </x-td>
                    <x-td class=" !whitespace-normal">
                        <div class="flex flex-wrap gap-1 ">

                            @foreach ($role->permissions as $permission)
                                <span
                                    class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500 whitespace-nowrap">{{ $permission->description }}</span>
                            @endforeach
                        </div>
                    </x-td>
                    <x-td>
                        <div class=" flex items-center justify-end gap-2">

                            @can('update', $role)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showRoleFormModal',{role:{{ $role }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $role)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $role }})">
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
