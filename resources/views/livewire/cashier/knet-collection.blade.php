<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.knet_collection') }}
                <span id="counter"></span>
            </h2>
            <span id="addNew"></span>
        </div>
    </x-slot>

    @teleport('#counter')
        <span
            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
            {{ $this->payments->total() }}
        </span>
    @endteleport

    <x-slot name="footer">
        <span id="pagination"></span>
    </x-slot>
    @teleport('#pagination')
        <div class=" flex items-center justify-between gap-2">
            <x-select wire:model.live="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="500">500</option>
            </x-select>
            @if ($this->payments->hasPages())
                <div class=" flex-1">{{ $this->payments->links() }}</div>
            @endif
        </div>
    @endteleport

    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">

        <div>
            <x-label for="status">{{ __('messages.status') }}</x-label>
            <x-select class="w-full" wire:model.live="filters.status">
                <option value="1">{{ __('messages.collected') }}</option>
                <option value="0">{{ __('messages.uncollected') }}</option>
            </x-select>
        </div>

        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-searchable-select id="department" class="!py-[5px]" :list="$this->departments"
                wire:model.live="filters.department_id" multipule />
        </div>

        <div>
            <x-label for="technician">{{ __('messages.technician') }}</x-label>
            <x-searchable-select id="technician" class="!py-[5px]" :list="$this->technicians"
                wire:model.live="filters.technician_id" multipule />
        </div>

        <div>
            <x-label for="start_created_at">{{ __('messages.created_at') }}</x-label>
            <x-input class="w-full text-center py-0" type="date" id="start_created_at"
                wire:model.live="filters.start_created_at" />
            <x-input class="w-full text-center py-0" type="date" id="end_created_at"
                wire:model.live="filters.end_created_at" />

        </div>

        @can('mass_collect', App\Models\Payment::class)
            @if (
                $this->payments->count() > 0 &&
                    $filters['start_created_at'] &&
                    $filters['end_created_at'] &&
                    $filters['status'] == '0')
                <x-button class=" self-end justify-center" wire:confirm="{{ __('messages.are_u_sure') }}"
                    wire:click="mass_collect" wire:loading.attr="disabled">

                    <span wire:loading wire:target="mass_collect"
                        class=" hidden animate-pulse">{{ __('messages.collecting') }}</span>
                    <span wire:loading.remove wire:target="mass_collect">{{ __('messages.mass_collect') }}</span>
                </x-button>
            @endif
        @endcan



    </div>

    @if ($this->payments->count() > 0)
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.date') }}</x-th>
                    <x-th>{{ __('messages.invoice_number') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.technician') }}</x-th>
                    <x-th>{{ __('messages.receiver') }}</x-th>
                    {{-- <x-th>{{ __('messages.knet_ref_number') }}</x-th> --}}
                    <x-th>{{ __('messages.amount') }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->payments as $payment)
                    <x-tr wire:key="payment-{{ $payment->id }}-{{ rand() }}">
                        @can('change_date', $payment)
                            <x-td x-data="{ editing: false, date: {{ @json_encode($payment->created_at->format('Y-m-d H:i')) }} }">
                                <div x-show="!editing" class="flex items-center gap-2">
                                    <div>{!! $payment->formated_created_at !!}</div>
                                    <x-badgeWithCounter x-show="!editing" x-on:click="editing = true"
                                        title="{{ __('messages.edit') }}">
                                        <x-svgs.edit class="h-4 w-4" />
                                    </x-badgeWithCounter>
                                </div>
                                <form x-show="editing" class="flex items-center gap-2"
                                    wire:submit.prevent="changeDate($refs.date.value,{{ $payment }})">
                                    <x-input required x-ref="date" x-model="date" type="datetime-local" />
                                    <x-button x-on:click="editing = false">{{ __('messages.save') }}</x-button>
                                    <x-secondary-button x-on:click="editing = false"
                                        type="button">{{ __('messages.cancel') }}
                                    </x-secondary-button>
                                </form>
                            </x-td>
                        @else
                            <x-td>
                                <div>{!! $payment->formated_created_at !!}</div>
                            </x-td>
                        @endcan
                        <x-td> <a target="_blank" class="btn"
                                href="{{ route('invoice.detailed_pdf', encrypt($payment->invoice->id)) }}">{{ $payment->invoice->formated_id }}</a>
                        </x-td>
                        <x-td>{{ $payment->invoice->order->department->name }}</x-td>
                        <x-td>
                            <span class=" cursor-pointer"
                                wire:click="technicianClicked({{ $payment->invoice->order->technician_id }})">{{ $payment->invoice->order->technician->name }}</span>
                        </x-td>
                        <x-td>{{ $payment->user->name }}</x-td>
                        {{-- <x-td>{{ $payment->knet_ref_number }}</x-td> --}}
                        <x-td>{{ $payment->formated_amount }}</x-td>
                        <x-td class=" text-end">
                            @if ($payment->is_collected)
                                @can('uncollect', $payment)
                                    <x-danger-button wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="uncollect_payment({{ $payment }})">{{ __('messages.uncollect') }}</x-danger-button>
                                @endcan
                            @else
                                @can('collect', $payment)
                                    <x-button wire:confirm="{{ __('messages.are_u_sure') }}"
                                        wire:click="collect_payment({{ $payment }})">{{ __('messages.collect') }}</x-button>
                                @endcan
                            @endif
                        </x-td>
                    </x-tr>
                @endforeach
            </tbody>
            <x-tfoot>
                <tr>
                    <x-th></x-th>
                    <x-th></x-th>
                    <x-th></x-th>
                    <x-th></x-th>
                    {{-- <x-th></x-th> --}}
                    <x-th>{{ __('messages.total') }}</x-th>
                    <x-th>{{ number_format($this->payments->sum('amount'), 3) }}</x-th>
                    <x-th></x-th>
                </tr>
            </x-tfoot>
        </x-table>
    @else
        <h2
            class="font-semibold text-xl flex gap-3 items-center justify-center text-green-600 dark:text-green-500 leading-tight">
            {{ __('messages.no_uncollected_payments') }}
        </h2>
    @endif
</div>
