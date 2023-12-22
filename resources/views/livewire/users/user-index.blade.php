<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.users') }}
                <span id="counter"></span>
            </h2>
            <x-anchor class="no-print" href="{{ route('user.form') }}">{{ __('messages.add_user') }}</x-anchor>
        </div>
    </x-slot>
    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->users->total() }}
        </span>
    @endteleport

    @if ($this->users->hasMorePages())
        <x-slot name="footer">
            <span id="pagination"></span>
        </x-slot>
        @teleport('#pagination')
            <div class="">{{ $this->users->links() }}</div>
        @endteleport
    @endif



    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th>
                        <x-input placeholder="{{ __('messages.name') }}" wire:model.live="filters.name"
                            class="w-full text-start py-0" />
                    </th>
                    <th class=" text-center">
                        <x-input placeholder="{{ __('messages.username') }}" wire:model.live="filters.username"
                            class="w-full py-0" dir="ltr" />
                    </th>
                    <th>
                        <x-select wire:model.live="filters.title_id" class=" w-full py-0">
                            <option value="">{{ __('messages.title') }}</option>
                            @foreach ($titles as $title)
                                <option value="{{ $title->id }}">{{ $title->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th>
                        <x-select wire:model.live="filters.department_id" class=" w-full py-0">
                            <option value="">{{ __('messages.department') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th>
                        <x-select wire:model.live="filters.role_id" class=" w-full py-0">
                            <option value="">{{ __('messages.roles') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th>
                        <x-select wire:model.live="filters.shift_id" class=" w-full py-0">
                            <option value="">{{ __('messages.shift') }}</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </x-select>
                    </th>
                    <th>
                        <x-select wire:model.live="filters.status" class=" w-full py-0">
                            <option value="all">{{ __('messages.status') }}</option>
                            <option value="1">{{ __('messages.active') }}</option>
                            <option value="0">{{ __('messages.inactive') }}</option>
                        </x-select>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->users as $user)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $user->name }}
                        </th>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $user->username }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $user->title->name }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $user->department->name ?? '-' }}
                        </td>
                        <td class="px-6 py-1 whitespace-nowrap">
                            @foreach ($user->roles as $role)
                                <span
                                    class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap">
                            {{ $user->shift->name ?? '-' }}
                        </td>
                        <td class="px-6 py-1 text-center whitespace-nowrap ">
                            <livewire:users.status-switcher :$user :key="'switcher-' . $user->id . '-' . now()">
                        </td>
                        <td class="px-6 py-1 text-end whitespace-nowrap flex items-center gap-2 no-print">
                            <a wire:navigate href="{{ route('user.form', $user) }}"
                                class="flex items-center gap-1 border dark:border-gray-700 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                <x-svgs.edit class="w-4 h-4" />
                            </a>


                            <a wire:navigate
                                href="{{ route('user.form', ['user' => $user, 'is_duplicate' => 'true']) }}"
                                class="flex items-center gap-1 border dark:border-gray-700 rounded-lg p-1 justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                <x-svgs.duplicate class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>





</div>
