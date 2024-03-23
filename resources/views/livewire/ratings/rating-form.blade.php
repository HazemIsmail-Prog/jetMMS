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

                        <div class=" flex items-center justify-center gap-2">

                            @for ($i = 1; $i <= 5; $i++)
                                <x-svgs.star wire:click="$set('rating',{{ $i }})"
                                    class="h-12 w-12 cursor-pointer text-yellow-400 {{ $rating >= $i ? 'fill-yellow-200' : 'fill-none' }}" />
                            @endfor
                        </div>
                        <div>
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input class="w-full py-0" wire:model="notes" autocomplete="off" type="text"
                                id="notes" />
                            <x-input-error for="notes" />
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
