<div>
    <x-dialog-modal maxWidth="lg" wire:model.live="showModal">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($order)
                <form wire:submit.prevent="save" class=" space-y-5">
                    <p class="font-medium">{{ $modalDescription }}</p>
                    <div>
                        <x-input id="reason" wire:model="reason" class="w-full text-start" type="text" />
                        <x-input-error for="reason" />
                    </div>
                    <x-button>{{ __('messages.confirm_' . $action) }}</x-button>
                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
