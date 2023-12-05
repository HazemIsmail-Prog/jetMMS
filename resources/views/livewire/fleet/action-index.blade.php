<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.car_actions') }} - {{ $car->code }}
                <span
                    class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $this->actions->count() }}</span>
            </h2>
        </div>
    </x-slot>

    <div class=" overflow-x-auto sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.action_type') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.driver') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.date') }} / {{ __('messages.time') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.kilos') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        {{ __('messages.fuel') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('messages.notes') }}
                    </th>
                    <th scope="col" class="px-6 py-3 no-print">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->actions as $action)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td
                            class="px-6 py-4 {{ $action->type == 'unassign' ? 'text-red-600 dark:text-red-500' : 'text-green-600 dark:text-green-500' }}">
                            {{ __('messages.' . $action->type) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $action->driver->name }}
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $action->date->format('d-m-Y') }}</div>
                            <div class=" text-xs">{{ $action->time->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $action->kilos }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ $action->fuel }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $action->notes }}
                        </td>

                        <td class="px-6 py-4 text-right flex items-center gap-2 no-print">
                            <a wire:navigate href="{{ route('car.action.report',$action) }}" class="font-medium text-gray-600 dark:text-gray-500">
                                <span class="material-symbols-outlined">
                                    print
                                </span>
                            </a>

                            <a wire:navigate href="#" class="font-medium text-blue-600 dark:text-blue-500">
                                <span class="material-symbols-outlined">
                                    edit_square
                                </span>
                            </a>

                            <a wire:navigate href="#" class="font-medium text-red-600 dark:text-red-500">
                                <span class="material-symbols-outlined">
                                    delete
                                </span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $this->actions->links() }}</div>




</div>
