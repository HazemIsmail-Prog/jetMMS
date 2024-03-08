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
                            <x-label for="increase_date">{{ __('messages.increase_date') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.increase_date" autocomplete="off"
                                type="date" id="increase_date" />
                            <x-input-error for="form.increase_date" />
                        </div>
                        <div>
                            <x-label for="amount">{{ __('messages.amount') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.amount" autocomplete="off"
                                type="number" id="amount" />
                            <x-input-error for="form.amount" />
                        </div>

                        <div>
                            <x-label for="type">{{ __('messages.type') }}</x-label>
                            <div class="p-3 border dark:border-gray-700 rounded-lg space-y-2 ">
                                @foreach (App\Enums\IncreaseTypeEnum::cases() as $type)
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
