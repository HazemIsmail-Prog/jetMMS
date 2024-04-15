<div>
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal" :dismissible="false">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)

                {{-- @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div>{{$error}}</div>
                    @endforeach
                @endif --}}

                <form wire:submit.prevent="save" wire:loading.class="opacity-50">

                    <div class=" space-y-3">
                        <div class=" grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 items-start gap-3">
                            <div class=" flex-1">
                                <x-label for="manual_id">{{ __('messages.manual_id') }}</x-label>
                                <x-input class="w-full py-0" wire:model="form.manual_id" autocomplete="off"
                                    type="text" id="manual_id" />
                                <x-input-error for="form.manual_id" />
                            </div>
                            <div class=" flex-1">
                                <x-label for="date">{{ __('messages.date') }}</x-label>
                                <x-input required class="w-full py-0" wire:model="form.date" autocomplete="off"
                                    type="date" id="date" />
                                <x-input-error for="form.date" />
                            </div>
                            <div class=" flex-1">
                                <x-label for="notes">{{ __('messages.notes') }}</x-label>
                                <x-input class="w-full py-0" wire:model="form.notes" autocomplete="off" type="text"
                                    id="notes" />
                                <x-input-error for="form.notes" />
                            </div>
                            @if (!$form->id)
                                <div class=" flex-1">
                                    <x-label for="copy_from">{{ __('messages.copy_from') }}</x-label>
                                    <div class=" flex gap-1">
                                        <x-input class=" flex-1 w-full py-0" wire:model="copy_from" autocomplete="off"
                                            type="number" min="1" id="copy_from" />
                                        <x-button wire:click="copy" type="button">{{ __('messages.copy') }}</x-button>
                                    </div>
                                    <x-input-error for="copy_from" />
                                </div>
                            @endif
                        </div>

                        @if ($form->details)

                            <x-section-border />

                            <x-table class=" table-fixed">
                                <x-thead>
                                    <tr>
                                        <x-th class=" !w-[300px] !px-0.5">{{ __('messages.account') }}</x-th>
                                        <x-th class=" !w-[150px] !px-0.5">{{ __('messages.cost_center') }}</x-th>
                                        <x-th class=" !w-[150px] !px-0.5">{{ __('messages.contact') }}</x-th>
                                        <x-th class=" !w-[370px] !px-0.5">{{ __('messages.narration') }}</x-th>
                                        <x-th class=" !w-[100px] !px-0.5">{{ __('messages.debit') }}</x-th>
                                        <x-th class=" !w-[100px] !px-0.5">{{ __('messages.credit') }}</x-th>
                                        <x-th class=" !w-[50px] !px-0.5"></x-th>
                                    </tr>
                                </x-thead>
                                <tbody x-data="{ form: @entangle('form') }">
                                    @foreach ($form->details as $index => $row)
                                        <x-tr>
                                            <x-td class=" !px-0.5">
                                                <x-searchable-select 
                                                    :invalid="$errors->has('form.details.' . $index . '.account_id')" 
                                                    :list="$this->accounts" 
                                                    wire:model="form.details.{{ $index }}.account_id" 
                                                />
                                            </x-td>
                                            <x-td class=" !px-0.5">
                                                <x-searchable-select :list="$this->cost_centers" wire:model="form.details.{{ $index }}.cost_center_id" />
                                            </x-td>
                                            <x-td class=" !px-0.5">
                                                <x-searchable-select :list="$this->users" wire:model="form.details.{{ $index }}.user_id" />
                                            </x-td>
                                            <x-td class=" !px-0.5">
                                                <x-input class="w-full text-start"
                                                    x-model="form.details[{{ $index }}].narration"
                                                    autocomplete="off" type="text" />
                                            </x-td>
                                            <x-td class=" !px-0.5">
                                                <x-input 
                                                    @class([
                                                        'w-full py-0 text-start',
                                                        'border-red-500 dark:border-red-500' => $errors->has('form.details.' . $index . '.debit'),
                                                    ]) 
                                                    dir="ltr"
                                                    step="0.001"
                                                    x-model="form.details[{{ $index }}].debit"
                                                    autocomplete="off" 
                                                    type="number"
                                                    @input.debounce.1000ms="if(form.details[{{ $index }}].debit){ form.details[{{ $index }}].credit = 0 ; $dispatch('debit') }"
                                                />
                                            </x-td>
                                            <x-td class=" !px-0.5">
                                                <x-input 
                                                    @class([
                                                        'w-full py-0 text-start',
                                                        'border-red-500 dark:border-red-500' => $errors->has('form.details.' . $index . '.credit'),
                                                    ])
                                                    dir="ltr"
                                                    step="0.001"
                                                    x-model="form.details[{{ $index }}].credit"
                                                    autocomplete="off" 
                                                    type="number"
                                                    @input.debounce.1000ms="if(form.details[{{ $index }}].credit){ form.details[{{ $index }}].debit = 0 ; $dispatch('credit') }"
                                                />
                                            </x-td>
                                            <x-td class=" !px-0.5">
                                                <div class="flex items-center justify-center">
                                                    <x-svgs.trash class="w-4 h-4 text-red-500" wire:click="deleteRow({{ $index }})" />
                                                </div>
                                            </x-td>
                                        </x-tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <x-td class=" !px-0.5" colspan="4"></x-td>

                                        <x-td dir="ltr" class=" !px-0.5 !text-start">
                                            <div class=" border {{ $errors->has('form.total_debit') ? 'border-red-500' : 'border-green-500' }} rounded-lg p-2 text-center">
                                                {{ $form->total_debit }}
                                            </div>
                                        </x-td>
                                        <x-td dir="ltr" class=" !px-0.5 !text-start">
                                            <div class=" border {{ $errors->has('form.total_credit') ? 'border-red-500' : 'border-green-500' }} rounded-lg p-2 text-center">
                                                {{ $form->total_credit }}
                                            </div>
                                        </x-td>

                                    </tr>
                                    <tr>
                                        <x-td colspan="4" class=" !px-0.5"></x-td>
                                        <x-td dir="ltr" colspan="2" class=" !px-0.5 !text-start">
                                            <div class=" border {{ $errors->has('form.balance') ? 'border-red-500' : 'border-green-500' }} rounded-lg p-2 text-center">
                                                {{ $form->balance }}
                                            </div>
                                        </x-td>
                                    </tr>
                                </tfoot>
                            </x-table>    
                            
                        @endif

                        <div class=" text-center">
                            <x-button type=button wire:click="addRow">{{ __('messages.add_line') }}</x-button>
                        </div>
                        
                    </div>

                    <div class="mt-3">
                        <x-button wire:model.live="form" wire:dirty.class="border-yellow">{{ __('messages.save') }}</x-button>
                    </div>

                </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>
