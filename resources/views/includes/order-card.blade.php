<div 
    :class="order.is_draggable ? 'draggable' : ''"
>

    <div
        x-data="orderCardComponent"
        class="border rounded-lg"
        :style="order.is_future ? 'border-color: #000000; background: #000000' : `border-color: ${getStatusColorById(order.status_id)}; background: ${getStatusColorById(order.status_id)}`"
        x-sort:item="order.id"
        x-bind:id="`order - ${order.id}`"
    >
        <div class="p-2 text-white">
            <div class="flex justify-between items-center">
                <div class="text-md font-semibold truncate" x-text="order.customer.name"></div>
                <div class="text-xs" x-text="order.phone.number"></div>
            </div>
            <h4 class="mt-2 text-xs" x-text="order.address.full_address"></h4>
        </div>
    
        <div
            class="mt-2 bg-white p-2 rounded-t-lg dark:bg-gray-800  text-gray-950 dark:text-gray-200"
        >
            <div x-text="order.creator.name" class="items-center bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700"></div>
            <div  x-text="order.formatted_id" class="items-center mt-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700"></div>
            <div class="text-end mt-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700" x-text="order.formatted_created_at" dir="ltr"></div>
            <!-- <div class="text-end my-1 bg-gray-100 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700" x-text="order.index" dir="ltr"></div> -->
            <!-- template if order is not in progress -->
            <template x-if="!order.in_progress">
                <div class="flex items-center mt-1 bg-gray-100 text-xs font-medium p-0 rounded dark:bg-gray-700">
                    <x-select
                        @change="setTechnician(order.id, $event.target.value)"
                        class="technician_select !h-auto text-start border-none w-full focus:ring-0 bg-gray-100 text-xs font-medium p-0 rounded dark:bg-gray-700 ">
                        <option x-show="order.status_id != 2" value="">---</option>
                        <template x-for="technician in technicians" :key="technician.id">
                            <option :selected="technician.id == order.technician_id" x-show="technician.id != order.technician_id" :value="technician.id" x-text="technician.name"></option>
                        </template>
                    </x-select>
                </div>
            </template>

            <div x-show="order.order_description" class="mt-1 bg-gray-100 text-xs px-2.5 rounded dark:bg-gray-700 text-start" style="white-space: pre-line; text-align: start;">
                <span x-html="order.order_description" class="block -my-[0.65rem]"></span>
            </div>

            <div x-show="order.notes" class="mt-1 bg-gray-100 text-xs px-2.5 rounded dark:bg-gray-700 text-start" style="white-space: pre-line; text-align: start;">
                <span x-html="order.notes" class="block -my-[0.65rem]"></span>
            </div>
    
        </div>
    
        <div
            class="flex items-center gap-2 justify-between w-full mt-px text-xs font-medium bg-white dark:bg-gray-800 p-2 rounded-b-lg text-gray-950 dark:text-gray-200"
        >
            <div class=" flex gap-2 items-center">
                <x-badgeWithCounter @click="openOrderModal(order)">
                    <x-svgs.list class="h-4 w-4" />
                </x-badgeWithCounter>
                <template x-if="isFirstOrNextOrderButtonVisible()">
                    <x-badgeWithCounter @click="setAsFirstOrNextOrder(order.id)">
                        <x-svgs.arrow-up class="h-4 w-4" />
                    </x-badgeWithCounter>
                </template>
    
                <x-badgeWithCounter
                    x-show="order.unread_comments_count > 0"
                    class="bg-red-400 border-red-400 dark:border-red-400 text-white hover:bg-red-400 hover:border-red-400 hover:dark:border-red-400 hover:text-white"
                >
                    <x-svgs.comment class="h-4 w-4" />
                </x-badgeWithCounter>
            </div>


            <template x-if="order.appointment">
                <div class="flex-1 flex justify-between items-center bg-amber-400 text-amber-900 rounded-lg py-0.5 px-2 gap-2">
                    <div class="flex flex-col items-center flex-1">
                        <div dir="ltr" x-text="order.formatted_appointment_time"></div>
                        <div class="text-[9px]" x-text="order.formatted_appointment_date"></div>
                    </div>
                    <x-svgs.close @click="deleteAppointment(order)" class="h-3 w-3 cursor-pointer" />
                </div>
            </template>

            <div class=" flex gap-2 items-center">
                <template x-if="!order.appointment">
                    <x-badgeWithCounter 
                        @click="handleSetAppointment(order)" 
                        title="{{__('messages.set_appointment')}}" 
                    >
                        <x-svgs.calendar class="h-4 w-4" />
                    </x-badgeWithCounter>
                </template>
                <template x-if="order.can_hold_order && order.status_id != 5">
                    <x-badgeWithCounter 
                        @click="setOrderOnHold(order.id)" 
                        title="{{__('messages.on_hold')}}" 
                    >
                        <x-svgs.clock class="h-4 w-4" />
                    </x-badgeWithCounter>
                </template>
                <template x-if="order.can_cancel_order">
                    <x-badgeWithCounter 
                        @click="handleOrderCancel(order)" 
                        title="{{__('messages.cancel')}}" 
                    >
                        <x-svgs.trash class="h-4 w-4" />
                    </x-badgeWithCounter>
                </template>
            </div>
    
        </div>
    
    </div>
    
    <script>
        function orderCardComponent() {
            
            return {

                handleSetAppointment(order) {
                    this.$dispatch('open-order-appointment-modal',{order:order});
                },
                setOrderOnHold(id) {
                    this.$dispatch('order-holded',{orderId:id,mode:'fromHoldButton'});
                },
                setTechnician(id, technicianId) {
                    this.$dispatch('technician-selected',{orderId:id, newTechnicianId:technicianId,mode:'fromDropdown'});
                },
                handleOrderCancel(order) {
                    this.$dispatch('open-order-cancel-reason-modal',{order:order});
                },
                setAsFirstOrNextOrder(id) {
                    if(!confirm('{{ __('messages.this_will_set_this_order_as_first_order') }}')) {
                        return;
                    }
                    this.$dispatch('set-as-first-or-next-order',{orderId:id});
                },

                isFirstOrNextOrderButtonVisible() {
                    // wanna hide this button if the order status is not 2
                    // or if it is already the first
                    // or if it is the second order and the first order is received or arrived
                    if(this.order.status_id !== 2) return false;
                    const technicianOrders = this.techniciansOrders(this.order.technician_id);
                    const filteredOrders = technicianOrders.filter(order => order.status_id !== 4);
                    const firstOrder = filteredOrders.sort((a, b) => a.index - b.index)[0];
                    if (firstOrder.id === this.order.id) return false;
                    if (firstOrder.status_id === 3 || firstOrder.status_id === 7) {
                        const secondOrder = filteredOrders.sort((a, b) => a.index - b.index)[1];
                        if (!secondOrder) return true;
                        return secondOrder.id !== this.order.id;
                    }
                    return true;
                },

                deleteAppointment(order) {
                    if(!confirm('{{ __('messages.this_will_delete_the_appointment') }}')) {
                        return;
                    }
                    order.appointment = null;
                    axios.put(`/orders/${order.id}/deleteAppointment`)
                    .then(response => {
                        console.log('Success');
                    })
                    .catch(error => {
                        alert(error.response.data.error);
                    });
                }
            }
        }
    </script>
</div>
