<div>
    <x-slot name="header">
        <div class=" flex items-center justify-between">

            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.shift_target_statement') }}
            </h2>

        </div>
    </x-slot>

    {{-- Filters --}}
    <div class=" grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 no-print">

        <div class=" col-span-1 md:col-span-2 xl:col-span-4">
            <x-label for="start_date">{{ __('messages.date') }}</x-label>
            <x-input readonly disabled type="date" class="w-full" id="start_date" wire:model.live="start_date" />
            <x-input type="date" class="w-full" id="end_date" wire:model.live="end_date" />
        </div>
    </div>

    <div class="hidden text-center p-20"  wire:loading.class.remove="hidden" role="status">
        <svg aria-hidden="true" class="inline w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
        <span class="sr-only">Loading...</span>
    </div>

    <div wire:loading.class="hidden">

        @foreach ($this->departments as $department)
    
            <div class="my-5">
                <h2 class="mb-2 font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $department->name }}
                </h2>
    
                <x-table>
    
                    <x-thead>
    
                        <tr>
                            <x-borderd-th>{{ __('messages.technician')}}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.completed_orders') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.required_orders_target') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.orders_shortage') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.service_total') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.required_services_amount_target') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.services_amount_shortage') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.done_percentage') }}</x-borderd-th>
                            <x-borderd-th class="!w-1/12">{{ __('messages.shortage_percentage') }}</x-borderd-th>
                        </tr>
    
                    </x-thead>
    
                    <tbody>
    
                        @foreach ($this->shifts as $shift)
    
                            @foreach ($department->technicians->where('shift_id',$shift->id) as $technician)
    
                                <x-tr>
                                    @php
                                        $data = $technician;
                                        $servicesAmount = $data->invoice_details_services_amount_sum;
                                        $discountAmount = $data->discount_amount_sum;
                                        $servicesAfterDiscount = $servicesAmount - $discountAmount;
                                        $invoice_details_parts_amount_sum = $data->invoice_details_parts_amount_sum;
                                        $invoice_part_details_amount_sum = $data->invoice_part_details_amount_sum;
                                        $partsAmount = $invoice_details_parts_amount_sum + $invoice_part_details_amount_sum;
                                        $deliveryAmount = $data->delivery_amount_sum;
                                        $totalIncome = $servicesAfterDiscount + $partsAmount + $deliveryAmount;
                                        $completedOrdersCount = $data->completed_orders_count;
                                        $requiredOrdersTarget = $data->invoices_target_sum;
                                        $ordersShortage = $requiredOrdersTarget - $completedOrdersCount;
                                        $requiredServicesAmountTarget = $data->amount_target_sum;
                                        $servicesAmountShortage = $requiredServicesAmountTarget - $totalIncome;
                                        $donePercentage = $requiredServicesAmountTarget > 0 ? round($servicesAfterDiscount / $requiredServicesAmountTarget * 100 ,2) : 0;
                                        $shortagePercentage = 100 - $donePercentage;
                                    @endphp
    
                                    <x-borderd-td>{{ $technician->name }}</x-borderd-td>
                                    <x-borderd-td>{{ $completedOrdersCount == 0 ? '-' : $completedOrdersCount  }}</x-borderd-td>
                                    <x-borderd-td>{{ $requiredOrdersTarget }}</x-borderd-td>
                                    <x-borderd-td>{{ $ordersShortage == 0 ? '-' : $ordersShortage }}</x-borderd-td>
                                    <x-borderd-td>{{ $servicesAfterDiscount == 0 ? '-' : number_format($servicesAfterDiscount,3) }}</x-borderd-td>
                                    <x-borderd-td>{{ number_format($requiredServicesAmountTarget,3) }}</x-borderd-td>
                                    <x-borderd-td>{{ $servicesAmountShortage == 0 ? '-' : number_format($servicesAmountShortage,3) }}</x-borderd-td>
                                    <x-borderd-td>{{ number_format($donePercentage,2) }} %</x-borderd-td>
                                    <x-borderd-td>{{ number_format($shortagePercentage,2) }} %</x-borderd-td>  
                                </x-tr>
    
                            @endforeach
    
                            @if ($department->technicians->whereNotIn('shift_id',$shift->id)->count() > 0 &&
                            $department->technicians->whereIn('shift_id',$shift->id)->count() > 0)
    
                                <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">
    
                                    @php
                                        $data = $department->technicians->where('shift_id',$shift->id);
                                        $servicesAmount = $data->sum('invoice_details_services_amount_sum');
                                        $discountAmount = $data->sum('discount_amount_sum');
                                        $servicesAfterDiscount = $servicesAmount - $discountAmount;
                                        $invoice_details_parts_amount_sum = $data->sum('invoice_details_parts_amount_sum');
                                        $invoice_part_details_amount_sum = $data->sum('invoice_part_details_amount_sum');
                                        $partsAmount = $invoice_details_parts_amount_sum + $invoice_part_details_amount_sum;
                                        $deliveryAmount = $data->sum('delivery_amount_sum');
                                        $totalIncome = $servicesAfterDiscount + $partsAmount + $deliveryAmount;
                                        $completedOrdersCount = $data->sum('completed_orders_count');
                                        $requiredOrdersTarget = $data->sum('invoices_target_sum');
                                        $ordersShortage = $requiredOrdersTarget - $completedOrdersCount;
                                        $requiredServicesAmountTarget = $data->sum('amount_target_sum');
                                        $servicesAmountShortage = $requiredServicesAmountTarget - $totalIncome;
                                        $donePercentage = $requiredServicesAmountTarget > 0 ? round($servicesAfterDiscount / $requiredServicesAmountTarget * 100 ,2) : 0;
                                        $shortagePercentage = 100 - $donePercentage;
                                    @endphp
    
                                    <x-borderd-th>{{ $shift->name }}</x-borderd-th>
                                    <x-borderd-th>{{ $completedOrdersCount == 0 ? '-' : $completedOrdersCount  }}</x-borderd-th>
                                    <x-borderd-th>{{ $requiredOrdersTarget }}</x-borderd-th>
                                    <x-borderd-th>{{ $ordersShortage == 0 ? '-' : $ordersShortage }}</x-borderd-th>
                                    <x-borderd-th>{{ $servicesAfterDiscount == 0 ? '-' : number_format($servicesAfterDiscount,3) }}</x-borderd-th>
                                    <x-borderd-th>{{ number_format($requiredServicesAmountTarget,3) }}</x-borderd-th>
                                    <x-borderd-th>{{ $servicesAmountShortage == 0 ? '-' : number_format($servicesAmountShortage,3) }}</x-borderd-th>
                                    <x-borderd-th>{{ number_format($donePercentage,2) }} %</x-borderd-th>
                                    <x-borderd-th>{{ number_format($shortagePercentage,2) }} %</x-borderd-th>     
                                </tr>
    
                            @endif
    
                        @endforeach

                        {{-- Null Shifts --}}

                        @foreach ($department->technicians->whereNull('shift_id') as $technician)
    
                        <x-tr>
                            @php
                                $data = $technician;
                                $servicesAmount = $data->invoice_details_services_amount_sum;
                                $discountAmount = $data->discount_amount_sum;
                                $servicesAfterDiscount = $servicesAmount - $discountAmount;
                                $invoice_details_parts_amount_sum = $data->invoice_details_parts_amount_sum;
                                $invoice_part_details_amount_sum = $data->invoice_part_details_amount_sum;
                                $partsAmount = $invoice_details_parts_amount_sum + $invoice_part_details_amount_sum;
                                $deliveryAmount = $data->delivery_amount_sum;
                                $totalIncome = $servicesAfterDiscount + $partsAmount + $deliveryAmount;
                                $completedOrdersCount = $data->completed_orders_count;
                                $requiredOrdersTarget = $data->invoices_target_sum;
                                $ordersShortage = $requiredOrdersTarget - $completedOrdersCount;
                                $requiredServicesAmountTarget = $data->amount_target_sum;
                                $servicesAmountShortage = $requiredServicesAmountTarget - $totalIncome;
                                $donePercentage = $requiredServicesAmountTarget > 0 ? round($servicesAfterDiscount / $requiredServicesAmountTarget * 100 ,2) : 0;
                                $shortagePercentage = 100 - $donePercentage;
                            @endphp

                            <x-borderd-td>{{ $technician->name }}</x-borderd-td>
                            <x-borderd-td>{{ $completedOrdersCount == 0 ? '-' : $completedOrdersCount  }}</x-borderd-td>
                            <x-borderd-td>{{ $requiredOrdersTarget }}</x-borderd-td>
                            <x-borderd-td>{{ $ordersShortage == 0 ? '-' : $ordersShortage }}</x-borderd-td>
                            <x-borderd-td>{{ $servicesAfterDiscount == 0 ? '-' : number_format($servicesAfterDiscount,3) }}</x-borderd-td>
                            <x-borderd-td>{{ number_format($requiredServicesAmountTarget,3) }}</x-borderd-td>
                            <x-borderd-td>{{ $servicesAmountShortage == 0 ? '-' : number_format($servicesAmountShortage,3) }}</x-borderd-td>
                            <x-borderd-td>{{ number_format($donePercentage,2) }} %</x-borderd-td>
                            <x-borderd-td>{{ number_format($shortagePercentage,2) }} %</x-borderd-td>  
                        </x-tr>

                    @endforeach

                    @if ($department->technicians->whereNull('shift_id')->count() > 0)

                        <tr class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-600 dark:text-gray-400">

                            @php
                                $data = $department->technicians->whereNull('shift_id');
                                $servicesAmount = $data->sum('invoice_details_services_amount_sum');
                                $discountAmount = $data->sum('discount_amount_sum');
                                $servicesAfterDiscount = $servicesAmount - $discountAmount;
                                $invoice_details_parts_amount_sum = $data->sum('invoice_details_parts_amount_sum');
                                $invoice_part_details_amount_sum = $data->sum('invoice_part_details_amount_sum');
                                $partsAmount = $invoice_details_parts_amount_sum + $invoice_part_details_amount_sum;
                                $deliveryAmount = $data->sum('delivery_amount_sum');
                                $totalIncome = $servicesAfterDiscount + $partsAmount + $deliveryAmount;
                                $completedOrdersCount = $data->sum('completed_orders_count');
                                $requiredOrdersTarget = $data->sum('invoices_target_sum');
                                $ordersShortage = $requiredOrdersTarget - $completedOrdersCount;
                                $requiredServicesAmountTarget = $data->sum('amount_target_sum');
                                $servicesAmountShortage = $requiredServicesAmountTarget - $totalIncome;
                                $donePercentage = $requiredServicesAmountTarget > 0 ? round($servicesAfterDiscount / $requiredServicesAmountTarget * 100 ,2) : 0;
                                $shortagePercentage = 100 - $donePercentage;
                            @endphp

                            <x-borderd-th>{{ __('messages.undefined_shift') }}</x-borderd-th>
                            <x-borderd-th>{{ $completedOrdersCount == 0 ? '-' : $completedOrdersCount  }}</x-borderd-th>
                            <x-borderd-th>{{ $requiredOrdersTarget }}</x-borderd-th>
                            <x-borderd-th>{{ $ordersShortage == 0 ? '-' : $ordersShortage }}</x-borderd-th>
                            <x-borderd-th>{{ $servicesAfterDiscount == 0 ? '-' : number_format($servicesAfterDiscount,3) }}</x-borderd-th>
                            <x-borderd-th>{{ number_format($requiredServicesAmountTarget,3) }}</x-borderd-th>
                            <x-borderd-th>{{ $servicesAmountShortage == 0 ? '-' : number_format($servicesAmountShortage,3) }}</x-borderd-th>
                            <x-borderd-th>{{ number_format($donePercentage,2) }} %</x-borderd-th>
                            <x-borderd-th>{{ number_format($shortagePercentage,2) }} %</x-borderd-th>     
                        </tr>

                    @endif
    
                        <tr class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
    
                            @php
                                $data = $department->technicians;
                                $servicesAmount = $data->sum('invoice_details_services_amount_sum');
                                $discountAmount = $data->sum('discount_amount_sum');
                                $servicesAfterDiscount = $servicesAmount - $discountAmount;
                                $invoice_details_parts_amount_sum = $data->sum('invoice_details_parts_amount_sum');
                                $invoice_part_details_amount_sum = $data->sum('invoice_part_details_amount_sum');
                                $partsAmount = $invoice_details_parts_amount_sum + $invoice_part_details_amount_sum;
                                $deliveryAmount = $data->sum('delivery_amount_sum');
                                $totalIncome = $servicesAfterDiscount + $partsAmount + $deliveryAmount;
                                $completedOrdersCount = $data->sum('completed_orders_count');
                                $requiredOrdersTarget = $data->sum('invoices_target_sum');
                                $ordersShortage = $requiredOrdersTarget - $completedOrdersCount;
                                $requiredServicesAmountTarget = $data->sum('amount_target_sum');
                                $servicesAmountShortage = $requiredServicesAmountTarget - $totalIncome;
                                $donePercentage = $requiredServicesAmountTarget > 0 ? round($servicesAfterDiscount / $requiredServicesAmountTarget * 100 ,2) : 0;
                                $shortagePercentage = 100 - $donePercentage;
                            @endphp
    
                            <x-borderd-th>{{ __('messages.total') }}</x-borderd-th>
                            <x-borderd-th>{{ $completedOrdersCount == 0 ? '-' : $completedOrdersCount  }}</x-borderd-th>
                            <x-borderd-th>{{ $requiredOrdersTarget }}</x-borderd-th>
                            <x-borderd-th>{{ $ordersShortage == 0 ? '-' : $ordersShortage }}</x-borderd-th>
                            <x-borderd-th>{{ $servicesAfterDiscount == 0 ? '-' : number_format($servicesAfterDiscount,3) }}</x-borderd-th>
                            <x-borderd-th>{{ number_format($requiredServicesAmountTarget,3) }}</x-borderd-th>
                            <x-borderd-th>{{ $servicesAmountShortage == 0 ? '-' : number_format($servicesAmountShortage,3) }}</x-borderd-th>
                            <x-borderd-th>{{ number_format($donePercentage,2) }} %</x-borderd-th>
                            <x-borderd-th>{{ number_format($shortagePercentage,2) }} %</x-borderd-th>      
                        </tr>
    
                    </tbody>
    
                </x-table>
    
            </div>
    
        @endforeach

    </div>


</div>