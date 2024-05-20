<div x-data="{
    form: @entangle('form'),
    addRow() {
        this.form.details.push({ account_id: null, cost_center_id: null, user_id: null, narration: null, debit: null, credit: null });
    },
    deleteRow(index) {
        {{-- event.target.closest('tr').remove(); --}}
        this.form.details.splice(index, 1);
        $wire.verify();
    }
}">
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal" :dismissible="false">
        <x-slot name="title">
            <div>{{ $modalTitle }}</div>
            <x-section-border />
        </x-slot>

        <x-slot name="content">
            @if ($showModal)
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form wire:submit.prevent="save" wire:loading.class="opacity-50">
                <div class="space-y-3">
                    <div class="grid grid-cols-1 lg:grid-cols-4 items-start gap-3">
                        <div class="col-span-1 lg:col-span-1">
                            <x-label for="manual_id">{{ __('messages.manual_id') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.manual_id" autocomplete="off" type="text"
                                id="manual_id" />
                            <x-input-error for="form.manual_id" />
                        </div>
                        <div class="col-span-1 lg:col-span-1">
                            <x-label for="date">{{ __('messages.date') }}</x-label>
                            <x-input required class="w-full py-0" wire:model="form.date" autocomplete="off" type="date"
                                id="date" />
                            <x-input-error for="form.date" />
                        </div>
                        <div class="col-span-1 lg:col-span-2">
                            <x-label for="notes">{{ __('messages.notes') }}</x-label>
                            <x-input class="w-full py-0" wire:model="form.notes" autocomplete="off" type="text"
                                id="notes" />
                            <x-input-error for="form.notes" />
                        </div>
                    </div>

                    <template x-if="form.details.length">
                        <div>
                            <x-section-border />

                            <x-table class="table-fixed">
                                <x-thead>
                                    <tr>
                                        <x-th class="!w-[300px] !px-0.5">{{ __('messages.account') }}</x-th>
                                        <x-th class="!w-[150px] !px-0.5">{{ __('messages.cost_center') }}</x-th>
                                        <x-th class="!w-[150px] !px-0.5">{{ __('messages.contact') }}</x-th>
                                        <x-th class="!w-[370px] !px-0.5">{{ __('messages.narration') }}</x-th>
                                        <x-th class="!w-[100px] !px-0.5">{{ __('messages.debit') }}</x-th>
                                        <x-th class="!w-[100px] !px-0.5">{{ __('messages.credit') }}</x-th>
                                        <x-th class="!w-[50px] !px-0.5"></x-th>
                                    </tr>
                                </x-thead>
                                <tbody>
                                    <template x-for="(row, index) in form.details" :key="index">
                                        <tr>
                                            <x-td class="!px-0.5">
                                                <x-new-searchable-select :list="$this->accounts"
                                                    x-model="form.details[index].account_id" />
                                            </x-td>
                                            <x-td class="!px-0.5">
                                                <x-new-searchable-select :list="$this->cost_centers"
                                                    x-model="form.details[index].cost_center_id" />
                                            </x-td>
                                            <x-td class="!px-0.5">
                                                <x-new-searchable-select :list="$this->users"
                                                    x-model="form.details[index].user_id" />
                                            </x-td>
                                            <x-td class="!px-0.5">
                                                <x-input class="w-full text-start"
                                                    x-model="form.details[index].narration" autocomplete="off"
                                                    type="text" />
                                            </x-td>
                                            <x-td class="!px-0.5">
                                                <x-input class="w-full" dir="ltr" step="0.001"
                                                    x-model="form.details[index].debit" autocomplete="off" type="number"
                                                    @input="if(form.details[index].debit){ form.details[index].credit = 0 }" />
                                            </x-td>
                                            <x-td class="!px-0.5">
                                                <x-input class="w-full" dir="ltr" step="0.001"
                                                    x-model="form.details[index].credit" autocomplete="off"
                                                    type="number"
                                                    @input="if(form.details[index].credit){ form.details[index].debit = 0 }" />
                                            </x-td>
                                            <x-td class="!px-0.5">
                                                <div class="flex items-center justify-center">
                                                    <x-svgs.trash class="w-4 h-4 text-red-500"
                                                        @click="deleteRow(index)" />
                                                </div>
                                            </x-td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <x-td class="!px-0.5" colspan="4"></x-td>
                                        <x-td dir="ltr" class="!px-0.5 !text-start">
                                            <div
                                                class="border {{ $errors->has('form.total_debit') ? 'border-red-500' : 'border-green-500' }} rounded-lg p-2 text-center">
                                                {{ $form->total_debit }}
                                            </div>
                                        </x-td>
                                        <x-td dir="ltr" class="!px-0.5 !text-start">
                                            <div
                                                class="border {{ $errors->has('form.total_credit') ? 'border-red-500' : 'border-green-500' }} rounded-lg p-2 text-center">
                                                {{ $form->total_credit }}
                                            </div>
                                        </x-td>
                                    </tr>
                                    <tr>
                                        <x-td colspan="4" class="!px-0.5"></x-td>
                                        <x-td dir="ltr" colspan="2" class="!px-0.5 !text-start">
                                            <div
                                                class="border {{ $errors->has('form.balance') ? 'border-red-500' : 'border-green-500' }} rounded-lg p-2 text-center">
                                                {{ $form->balance }}
                                            </div>
                                        </x-td>
                                    </tr>
                                </tfoot>
                            </x-table>
                        </div>
                    </template>

                    <div class="text-center">
                        <x-button type="button" @click="addRow">{{ __('messages.add_line') }}</x-button>
                    </div>
                </div>

                <div class="mt-3">
                    <x-button>{{ __('messages.save') }}
                    </x-button>
                </div>
            </form>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>