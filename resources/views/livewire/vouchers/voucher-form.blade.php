<div>
    @if ($showModal)
        <x-dialog-modal maxWidth="7xl" wire:model.live="showModal">
            <x-slot name="title">
                <div>{{ $modalTitle }}</div>
                <x-section-border />
            </x-slot>

            <x-slot name="content">
                <form wire:submit="save">

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
                            <div class=" overflow-x-auto">
                                <div class="min-w-max">
                                    <table class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="min-w-[250px]">{{ __('messages.account') }}</th>
                                                <th class="min-w-[130px]">{{ __('messages.cost_center') }}</th>
                                                <th class="min-w-[130px]">{{ __('messages.contact') }}</th>
                                                <th class="min-w-[320px]">{{ __('messages.narration') }}</th>
                                                <th class="min-w-[50px]">{{ __('messages.debit') }}</th>
                                                <th class="min-w-[50px]">{{ __('messages.credit') }}</th>
                                                <th class="min-w-[10px] "></th>
                                            </tr>
                                        </thead>
                                        <tbody x-data="{ form: @entangle('form') }">
                                            @foreach ($form->details as $index => $row)
                                                <tr>
                                                    <td class=" align-top">
                                                        <x-searchable-select position="relative"
                                                            :list="$this->accounts"
                                                            model="form.details.{{ $index }}.account_id" />
    
                                                        {{-- <x-select @class([
                                                            'w-full',
                                                            'border-red-500' => $errors->has('form.details.' . $index . '.account_id'),
                                                        ])
                                                            wire:model="form.details.{{ $index }}.account_id">
                                                            <option value="">---</option>
                                                            @foreach ($this->accounts->sortBy('name') as $account)
                                                                <option value="{{ $account->id }}">{{ $account->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select> --}}
                                                    </td>
                                                    <td class=" align-top">
                                                        <x-searchable-select position="relative"
                                                            :list="$this->cost_centers"
                                                            model="form.details.{{ $index }}.cost_center_id" />
                                                    </td>
                                                    <td class=" align-top">
                                                        <x-searchable-select position="relative"
                                                            :list="$this->users"
                                                            model="form.details.{{ $index }}.user_id" />
                                                        {{-- <x-select @class([
                                                            'w-full',
                                                            'border-red-500' => $errors->has('form.details.' . $index . '.user_id'),
                                                        ])
                                                            wire:model="form.details.{{ $index }}.user_id">
                                                            <option value="">---</option>
                                                            @foreach ($this->users->sortBy('name') as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select> --}}
                                                    </td>
                                                    <td class=" align-top">
                                                        <x-input class="w-full py-0"
                                                            x-model="form.details[{{ $index }}].narration"
                                                            autocomplete="off" type="text" />
                                                    </td>
                                                    <td class=" align-top">
                                                        <x-input @class([
                                                            'w-full py-0',
                                                            'border-red-500' => $errors->has('form.details.' . $index . '.debit'),
                                                        ]) step="0.001"
                                                            x-model="form.details[{{ $index }}].debit"
                                                            autocomplete="off" type="number" @input="$dispatch('debit')"
                                                            x-on:input="form.details[{{ $index }}].credit = 0" />
    
                                                    </td>
                                                    <td class=" align-top">
                                                        <x-input @class([
                                                            'w-full py-0',
                                                            'border-red-500' => $errors->has('form.details.' . $index . '.credit'),
                                                        ]) step="0.001"
                                                            x-model="form.details[{{ $index }}].credit"
                                                            autocomplete="off" type="number" @input="$dispatch('credit')"
                                                            x-on:input="form.details[{{ $index }}].debit = 0" />
    
                                                    </td>
                                                    <td class=" align-top">
                                                        <div class="h-[35px] flex items-center">
    
                                                            <x-svgs.trash class="w-4 h-4 text-red-500"
                                                                wire:click="deleteRow({{ $index }})" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class=" text-center">{{ $total_debit }}</td>
                                                <td class=" text-center">{{ $total_credit }}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class=" text-center">{{ $balance }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endif
                        <div class=" text-center">

                            <x-button type=button wire:click="addRow">{{ __('messages.add_line') }}</x-button>
                        </div>


                    </div>

                    @if ($balance == 0 && $total_debit != 0 && $total_credit != 0)
                        <div class="mt-3">
                            <x-button>{{ __('messages.save') }}</x-button>
                        </div>
                    @endif

                </form>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
