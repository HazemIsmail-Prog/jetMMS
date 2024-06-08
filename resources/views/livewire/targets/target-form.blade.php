<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.targets') }}
            </h2>
        </div>
    </x-slot>


    {{-- Filters --}}
    <div class=" mb-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">

        <div>
            <x-label for="department">{{ __('messages.department') }}</x-label>
            <x-searchable-select class=" !py-[5px]" id="department" :list="$this->departments"
                wire:model.live="department_id" multipule />
        </div>
        <div>
            <x-label for="month">{{ __('messages.month') }}</x-label>
            <x-select class="w-full" id="month" wire:model.live="month">
                <option value="">---</option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </x-select>
        </div>
        <div>
            <x-label for="year">{{ __('messages.year') }}</x-label>
            <x-select class="w-full" id="year" wire:model.live="year">
                <option value="">---</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </x-select>
        </div>


    </div>

    <div class="hidden text-center p-20" wire:loading.class.remove="hidden" role="status">
        <svg aria-hidden="true" class="inline w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                fill="currentColor" />
            <path
                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                fill="currentFill" />
        </svg>
        <span class="sr-only">Loading...</span>
    </div>

    <div wire:loading.class="hidden">
        <x-table>
            <x-thead>
                <tr>
                    <x-th>{{ __('messages.name') }}</x-th>
                    <x-th>{{ __('messages.department') }}</x-th>
                    <x-th>{{ __('messages.title') }}</x-th>
                    <x-th>{{ __('messages.invoices_target') }}</x-th>
                    <x-th>{{ __('messages.amount_target') }}</x-th>
                </tr>
            </x-thead>
            <tbody>
                @foreach ($this->technicians as $technician)
                <x-tr>
                    <x-td>{{ $technician->name }}</x-td>
                    <x-td>{{ $technician->department->name }}</x-td>
                    <x-td>{{ $technician->title->name }}</x-td>

                    <x-td>
                        <x-input type="number" min="0" wire:model="targets.{{ $technician->id }}.invoices_target" class="w-36 min-w-full
                            text-center py-0" />
                        <x-input-error for="targets.{{ $technician->id }}.invoices_target" />

                    </x-td>
                    <x-td>
                        <x-input type="number" min="0" step="0.001" wire:model="targets.{{ $technician->id }}.amount_target" class="w-36 min-w-full
                            text-center py-0" />
                        <x-input-error for="targets.{{ $technician->id }}.amount_target" />

                    </x-td>
                </x-tr>
                @endforeach
            </tbody>
        </x-table>
        <div class="flex justify-center mt-2">

            <x-button wire:click="save">{{__('messages.save')}}</x-button>
        </div>
    </div>

</div>