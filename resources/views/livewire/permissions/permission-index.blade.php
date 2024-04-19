<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.permissions') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>

    @can('create', App\Models\Permission::class)
        @teleport('#addNew')
            <x-button wire:click="$dispatch('showPermissionFormModal')">
                {{ __('messages.add_permission') }}
            </x-button>
        @endteleport
    @endcan

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->permissions->total() }}
        </span>
    @endteleport

    @if ($this->permissions->hasPages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->permissions->links() }}</div>
        @endteleport
    @endif

    @livewire('permissions.permission-form')

    <x-table>
        <x-thead>
            <tr>
                <x-th>{{ __('messages.name') }}</x-th>
                <x-th>{{ __('messages.section') }}</x-th>
                <x-th>{{ __('messages.description') }}</x-th>
                <x-th></x-th>
            </tr>
        </x-thead>
        <tbody>
            @foreach ($this->permissions as $permission)
                <x-tr>
                    <x-td>{{ $permission->name }}</x-td>
                    <x-td>
                      <div>{{ $permission->section_name_ar }}</div>
                      <div>{{ $permission->section_name_en }}</div>
                    </x-td>
                    <x-td>{{ $permission->description }}</x-td>
                    <x-td>
                        <div class=" flex items-center justify-end gap-2">

                            @can('update', $permission)
                                <x-badgeWithCounter title="{{ __('messages.edit') }}"
                                    wire:click="$dispatch('showPermissionFormModal',{permission:{{ $permission }}})">
                                    <x-svgs.edit class="h-4 w-4" />
                                </x-badgeWithCounter>
                            @endcan

                            @can('delete', $permission)
                                <x-badgeWithCounter title="{{ __('messages.delete') }}"
                                    wire:confirm="{{ __('messages.are_u_sure') }}"
                                    wire:click="delete({{ $permission }})">
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
