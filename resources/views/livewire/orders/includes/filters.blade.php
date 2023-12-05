<div x-data="{ search_form: true }" class="mb-3">
    <x-button @click="search_form = ! search_form">
        <x-svgs.edit />
    </x-button>

    <div x-show="search_form" x-collapse.duration.100ms style="display: none"
        class=" border dark:border-gray-700 p-4 mt-3 rounded-lg">

        <div class=" flex gap-2">

            <div class=" flex-1">
                <x-label for="customer_name">{{ __('messages.customer_name') }}</x-label>
                <x-input class="w-full" id="customer_name" wire:model.live="filters.customer_name" />
            </div>
            <div class=" flex-1">
                <x-label for="customer_phone">{{ __('messages.customer_phone') }}</x-label>
                <x-input class="w-full" id="customer_phone" wire:model.live="filters.customer_phone" dir="ltr" />
            </div>
            <div class=" flex-1">
                <x-label for="areas">{{ __('messages.area') }}</x-label>
                <x-select class="w-full select2" id="areas" wire:model.live="filters.areas">
                    <option value="">---</option>
                    @foreach ($areas->sortBy->name as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class=" flex-1">
                <x-label for="block">{{ __('messages.block') }}</x-label>
                <x-input class="w-full" id="block" wire:model.live="filters.block" dir="ltr" />
            </div>
            <div class=" flex-1">
                <x-label for="street">{{ __('messages.street') }}</x-label>
                <x-input class="w-full" id="street" wire:model.live="filters.street" dir="ltr" />
            </div>

        </div>

        <div class=" flex gap-2 mt-4">
            <div class=" flex-1">
                <x-label for="order_number">{{ __('messages.order_number') }}</x-label>
                <x-input class="w-full" id="order_number" wire:model.live="filters.order_number" dir="ltr" />
            </div>
            <div class=" flex-1">
                <x-label for="creators">{{ __('messages.creator') }}</x-label>
                <x-select class="w-full select2" id="creators" wire:ignore wire:model.live="filters.creators">
                    <option value="">---</option>
                    @foreach ($creators as $creator)
                        <option value="{{ $creator->id }}">{{ $creator->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class=" flex-1">
                <x-label for="statuses">{{ __('messages.status') }}</x-label>
                <x-select class="w-full select2" id="statuses" wire:ignore wire:model.live="filters.statuses">
                    <option value="">---</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class=" flex-1">
                <x-label for="technicians">{{ __('messages.technician') }}</x-label>
                <x-select class="w-full select2" id="technicians" wire:ignore wire:model.live="filters.technicians">
                    <option value="">---</option>
                    @foreach ($technicians as $technician)
                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class=" flex-1">
                <x-label for="departments">{{ __('messages.department') }}</x-label>
                <x-select class="w-full select2" id="departments" wire:ignore wire:model.live="filters.departments">
                    <option value="">---</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>
            <div class=" flex-1">
                <x-label for="tags">{{ __('messages.orderTag') }}</x-label>
                <x-select class="w-full select2" id="tags" wire:ignore wire:model.live="filters.tags">
                    <option value="">---</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag }}">{{ $tag }}
                        </option>
                    @endforeach
                </x-select>
            </div>
            <div class=" flex-1">
                <x-label for="start_created_at">{{ __('messages.created_at') }}</x-label>
                <x-input type="date" class="w-full" id="start_created_at"
                    wire:model.live="filters.start_created_at" />
                <x-input type="date" class="w-full" id="end_created_at" wire:model.live="filters.end_created_at" />
            </div>
            <div class=" flex-1">
                <x-label for="start_completed_at">{{ __('messages.completed_at') }}</x-label>
                <x-input type="date" class="w-full" id="start_completed_at"
                    wire:model.live="filters.start_completed_at" />
                <x-input type="date" class="w-full" id="end_completed_at"
                    wire:model.live="filters.end_completed_at" />
            </div>

        </div>

        <div>
            <x-button>{{ __('messages.export_to_excel') }}</x-button>
        </div>

    </div>
</div>
