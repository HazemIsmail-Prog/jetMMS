<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="2xl" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border />
            </x-slot>


            <x-slot name="content">

                @if ($order)
                    <div class="border rounded-lg overflow-hidden dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                        {{ __('messages.status') }}</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                        {{ __('messages.technician') }}</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                        {{ __('messages.date') }}/{{ __('messages.time') }}</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                        {{ __('messages.creator') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($this->statuses as $status)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                            style="color: {{ $status->status->color }}">
                                            {{ $status->status->name }}</td>
                                        <td
                                            class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            {{ @$status->technician->name ?? '-' }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            <div>{{ $status->created_at->format('d-m-Y') }}</div>
                                            <div class=" text-xs">{{ $status->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                            {{ @$status->creator->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
