<div>
    <x-dialog-modal maxWidth="md" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
                <form wire:submit="save">

                    <div class=" space-y-3">

                        <div>
                            <x-label for="start_date">{{ __('messages.start_date') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.start_date" autocomplete="off"
                                type="date" id="start_date" />
                            <x-input-error for="form.start_date" />
                        </div>

                        <div>
                            <x-label for="end_date">{{ __('messages.end_date') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.end_date" autocomplete="off"
                                type="date" id="end_date" />
                            <x-input-error for="form.end_date" />
                        </div>

                        <div>
                            <x-label for="type">{{ __('messages.type') }}</x-label>
                            <div class="p-3 border dark:border-gray-700 rounded-lg space-y-2 ">
                                @foreach (App\Enums\LeaveTypeEnum::cases() as $type)
                                    <div class="flex items-center">
                                        <input wire:model="form.type" id="type-{{ $type->value }}" type="radio"
                                            value="{{ $type->value }}"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="type-{{ $type->value }}"
                                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $type->title() }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error for="form.type" />
                        </div>

                        <div>
                            <x-label for="status">{{ __('messages.status') }}</x-label>
                            <div class="p-3 border dark:border-gray-700 rounded-lg space-y-2 ">
                                @foreach (App\Enums\LeaveStatusEnum::cases() as $status)
                                    <div class="flex items-center">
                                        <input wire:model="form.status" id="status-{{ $status->value }}" type="radio"
                                            value="{{ $status->value }}"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="status-{{ $status->value }}"
                                            class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $status->title() }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error for="form.status" />
                        </div>

                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.notes" autocomplete="off" type="text"
                                id="notes" />
                            <x-input-error for="form.notes" />
                        </div>

                    </div>

                    <div class="mt-3">
                        <x-button>{{ __('messages.save') }}</x-button>
                    </div>

                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
