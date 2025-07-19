<x-app-layout>
    <x-slot name="title">
        {{ $department->name }}
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $department->name }}
            </h2>
            <div id="counter"></div>
        </div>

    </x-slot>    

    <div
        x-data="dispatchingComponent"
        class="overflow-scroll lg:overflow-hidden hidden-scrollbar"
        @order-holded.window="setOrderOnHold"
        @order-canceled.window="setOrderOnCancel"
        @order-pending.window="setOrderOnPending"
        @technician-selected.window="setTechnician"
        @reorder-orders-in-same-box.window="reorderOrdersInSameBox"
        @set-as-first-or-next-order.window="setAsFirstOrNextOrder"
    >
        <template x-teleport="#counter">
            <div class="flex items-center gap-2">
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300" x-text="unCompletedOrders.length"></span>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-700 dark:text-green-300" x-text="completedOrders.length"></span>
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-700 dark:text-red-300" x-text="cancelledOrders.length"></span>
            </div>
        </template>

        <!-- notifications -->
        <template x-if="notifications.length > 0">
            <div x-ref="notificationsContainer" class="absolute top-0 bottom-0 right-0 p-2 flex flex-col gap-2 w-[400px] pointer-events-none z-50 hidden-scrollbar overflow-y-auto h-screen">
                <div class="flex-1"></div>
                <template x-for="notification in notifications" :key="notification.id">
                    <div 
                        @click="notification.onClick(); removeNotification(notification.id)"
                        class="cursor-pointer duration-300 flex flex-col bg-white dark:bg-gray-800 shadow-xl py-1 px-4 rounded-lg w-full shadow-md border-l-4 border-blue-500 transform transition-all duration-500 ease-out pointer-events-auto"
                        x-transition
                        x-show="notification.show"
                    >
                        <div class="flex items-center justify-between gap-2 pb-1">
                            <div class="flex flex-col items-start gap-1">
                                <span class="font-semibold text-gray-800 dark:text-gray-200" x-text="notification.title"></span>
                                <span class="text-xs text-gray-800 dark:text-gray-200" x-text="notification.related_id"></span>
                            </div>
                            <button 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl font-bold" 
                                @click.stop="removeNotification(notification.id)"
                            >×</button>
                        </div>

                        <!-- <x-section-border /> -->
                        
                        <div class="flex items-start justify-between gap-2 border-t border-gray-200 dark:border-gray-700 pt-2">
                            <div>
                                <span class="text-xs text-gray-600 dark:text-gray-400" x-text="notification.sender"></span>
                                <p class="text-gray-700 dark:text-gray-300 mb-1" x-text="notification.body"></p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="text-xs text-gray-500" x-text="notification.date"></span>
                                <span class="text-xs text-gray-500" x-text="notification.time"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <div class="flex gap-1 h-[calc(100vh-110px)] lg:h-[calc(100vh-174px)]">

            <!-- unassigned orders -->
            <div class="w-64 shrink-0 space-y-1 overflow-hidden z-20">
                <div class="flex justify-between items-center p-4 h-10 rounded-md bg-gray-300 dark:bg-gray-700">
                    <h2 class="flex-1 text-sm font-semibold uppercase truncate text-gray-700 dark:text-slate-400">{{__('messages.unassigned')}}</h2>
                    <span x-show="pendingOrders.length > 0" class="bg-gray-100 text-gray-800 border border-gray-300 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500" x-text="pendingOrders.length"></span>
                </div>
                <div
                    x-bind:id="`pendingOrders`"
                    x-sort.ghost
                    x-sort:group="orders"
                    x-sort:config="{
                        onEnd: handleOnEnd,
                        delayOnTouchOnly: true,
                        delay: 500
                    }"
                    class="pointer-events-auto flex flex-col gap-2 border border-gray-400 dark:border-gray-700 p-2 rounded-md overflow-y-auto hidden-scrollbar h-[calc(100vh-154px)] lg:h-[calc(100vh-218px)]"
                >
                    <template x-for="order in pendingOrders" :key="`pending-order-${order.id}-${order.status_id}-${order.index}`">
                        @include('includes.order-card')
                    </template>
                </div>
            </div>

            <!-- on hold orders -->
            <div class="w-64 shrink-0 space-y-1 overflow-hidden z-20">
                <div class="flex justify-between items-center p-4 h-10 rounded-md bg-gray-300 dark:bg-gray-700">
                    <h2 class="flex-1 text-sm font-semibold uppercase truncate text-gray-700 dark:text-slate-400">{{__('messages.on_hold')}}</h2>
                    <span x-show="onHoldOrders.length > 0" class="bg-gray-100 text-gray-800 border border-gray-300 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500" x-text="onHoldOrders.length"></span>
                </div>
                <div
                    x-bind:id="`onHoldOrders`"
                    x-sort.ghost
                    x-sort:group="orders"
                    x-sort:config="{
                        onEnd: handleOnEnd,
                        delayOnTouchOnly: true,
                        delay: 500
                    }"
                    class="pointer-events-auto flex flex-col gap-2 border border-gray-400 dark:border-gray-700 p-2 rounded-md overflow-y-auto hidden-scrollbar h-[calc(100vh-154px)] lg:h-[calc(100vh-218px)]"
                >
                    <template x-for="order in onHoldOrders" :key="`onhold-order-${order.id}-${order.status_id}-${order.index}`">
                        @include('includes.order-card')
                    </template>
                </div>
            </div>

            <!-- titles -->
            <div class="flex gap-1 lg:overflow-x-auto hidden-scrollbar z-10">
                <template x-for="title in titles" :key="title.id">
                    <template x-if="techniciansByTitle(title.id).length > 0">
                        <div class="shrink-0 space-y-1 overflow-hidden">
                            <div class="flex justify-between items-center p-4 h-10 rounded-md bg-gray-300 dark:bg-gray-700">
                                <h2 class="flex-1 text-sm font-semibold uppercase truncate text-gray-700 dark:text-slate-400" x-text="title.name_ar"></h2>
                            </div>
                            <!-- technicians -->
                            <div class="flex-1 flex gap-1 overflow-x-auto hidden-scrollbar z-10">
                                <template x-for="technician in techniciansByTitle(title.id)" :key="technician.id">
                                    <div class="w-64 shrink-0 space-y-1 overflow-hidden">
                                        <div class="flex justify-between items-center p-4 h-10 rounded-md bg-gray-300 dark:bg-gray-700">
                                            <h2 class="flex-1 text-sm font-semibold uppercase truncate text-gray-700 dark:text-slate-400" x-text="technician.name"></h2>
                                            <div class="flex items-center gap-2">
                                                <span 
                                                    @click="$dispatch('open-todays-completed-orders-for-technician-modal', {technician: technician})"
                                                    x-show="getCompletedOrdersForTechnician(technician.id).length > 0" 
                                                    class="cursor-pointer bg-green-100 text-green-800 border border-green-300 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-700 dark:text-green-300 dark:border-green-500" 
                                                    x-text="getCompletedOrdersForTechnician(technician.id).length"
                                                ></span>
                                                <span x-show="techniciansOrders(technician.id).length > 0" class="bg-gray-100 text-gray-800 border border-gray-300 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500" x-text="techniciansOrders(technician.id).length"></span>
                                            </div>
                                        </div>
                
                                        <div 
                                            x-bind:id="`technicianBox-${technician.id}`" 
                                            x-sort.ghost
                                            x-sort:group="orders"
                                            x-sort:config="{
                                                onEnd: handleOnEnd,
                                                draggable: '.draggable',
                                                delayOnTouchOnly: true,
                                                delay: 500
                                            }"
                                            class="pointer-events-auto flex flex-col gap-2 border border-gray-400 dark:border-gray-700 p-2 rounded-md overflow-y-auto hidden-scrollbar h-[calc(100vh-198px)] lg:h-[calc(100vh-262px)]"
                                        >
                                            <template x-for="order in techniciansOrders(technician.id)" :key="`technician-${technician.id}-order-${order.id}-${order.status_id}-${order.index}`">
                                                @include('includes.order-card')
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </template>
            </div>

        </div>
        
    </div>


    <!-- todays completed orders for technician modal -->
    @include('modals.todays-completed-orders-for-technician')

    <!-- single order modal -->
    @include('modals.single-order')
    <!-- invoice form modal -->
    @include('modals.invoice-form')
    <!-- discount modal -->
    @include('modals.discount-form')
    <!-- payment modal -->
    @include('modals.payment-form')
    <!-- cancel reason modal -->
    @include('modals.cancel-reason-form')

    @include('modals.attachments')
    @include('modals.attachment-form')





    <script>

        function handleOnEnd(evt) {
            // get upper and lower siblings with class order-card of the moved order
            var upperSibling = evt.item.previousElementSibling?.firstElementChild;
            var lowerSibling = evt.item.nextElementSibling?.firstElementChild;
            var movedOrderId =  evt.item.firstElementChild.id.split(' - ')[1];
            var upperSiblingId =  upperSibling ? upperSibling.id.split(' - ')[1] : null;
            var lowerSiblingId = lowerSibling ? lowerSibling.id.split(' - ')[1] : null;
            var targetId = evt.to.id;
            if (targetId.includes('technicianBox')) {
                targetId = targetId.split('-')[1];
            }
            var sourceId = evt.from.id;
            if (sourceId.includes('technicianBox')) {
                sourceId = sourceId.split('-')[1];
            }
            var oldIndex = evt.oldIndex;
            var newIndex = evt.newIndex;

            if (targetId === sourceId && oldIndex === newIndex) {
                return;
            }

            if (targetId == sourceId && oldIndex != newIndex) {
                window.dispatchEvent(new CustomEvent('reorder-orders-in-same-box', {detail: {orderId: movedOrderId,upperSiblingId: upperSiblingId,lowerSiblingId: lowerSiblingId}}));
            }

            if (targetId != sourceId) {
                switch (targetId) {
                    case 'pendingOrders':
                        window.dispatchEvent(new CustomEvent('order-pending', {detail: {orderId: movedOrderId,upperSiblingId: upperSiblingId,lowerSiblingId: lowerSiblingId}}));
                        break;
                    case 'onHoldOrders':
                        window.dispatchEvent(new CustomEvent('order-holded', {detail: {orderId: movedOrderId,upperSiblingId: upperSiblingId,lowerSiblingId: lowerSiblingId}}));
                        break;
                    default:
                        window.dispatchEvent(new CustomEvent('technician-selected', {detail: {orderId: movedOrderId, upperSiblingId: upperSiblingId, lowerSiblingId: lowerSiblingId, newTechnicianId: targetId}}));
                        break;
                }
            }                                              
        }

        function dispatchingComponent() {
            return {

                // data
                department: @json($department),
                statuses: @json($globalStatusesResource),
                titles: @json($titles),
                selectedDepartmentId: @json($department->id),
                technicians: @json($technicians),
                orders: @json($orders),
                notifications: [],
                isLoading: false,

                // initiators
                init() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    this.initListeners();
                },

                // getters

                get unCompletedOrders() {
                    return this.orders.filter(order => order.status_id !== 6 && order.status_id !== 4);
                },

                get cancelledOrders() {
                    return this.orders.filter(order => order.status_id === 6);
                },

                get completedOrders() {
                    return this.orders.filter(order => order.status_id === 4);
                },

                get sortedOrders() {
                    return this.unCompletedOrders.sort((a, b) => a.index - b.index);
                },

                get pendingOrders() {
                    return this.sortedOrders.filter(order => order.status_id === 1);
                },

                get onHoldOrders() {
                    return this.sortedOrders.filter(order => order.status_id === 5);
                },

                getStatusColorById(statusId) {
                    return this.statuses.find(status => status.id === statusId).color;
                },

                // computed

                getCompletedOrdersForTechnician(technicianId) {
                    return this.orders.filter(order => order.technician_id === technicianId && order.status_id === 4);
                },

                techniciansByTitle(titleId) {
                    return this.technicians.filter(technician => technician.title_id === titleId);
                },

                techniciansOrders(technicianId) {
                    return this.sortedOrders.filter(order => order.technician_id === technicianId);
                },

                // methods
                getNewIndex(orderId, upperSiblingId, lowerSiblingId) {
                    let newIndex = 10;
                    if (upperSiblingId && lowerSiblingId) {
                        upperSiblingIndex = parseFloat(this.orders.find(order => order.id == upperSiblingId).index);
                        lowerSiblingIndex = parseFloat(this.orders.find(order => order.id == lowerSiblingId).index);
                        newIndex = (upperSiblingIndex + lowerSiblingIndex) / 2;
                    } else if (upperSiblingId) {
                        upperSiblingIndex = parseFloat(this.orders.find(order => order.id == upperSiblingId).index);
                        newIndex = upperSiblingIndex + 10;
                    } else if (lowerSiblingId) {
                        lowerSiblingIndex = parseFloat(this.orders.find(order => order.id == lowerSiblingId).index);
                        newIndex = lowerSiblingIndex - 10;
                    } else {
                        // get last order index to handle hold button and change technician dropdown
                        newIndex = this.orders[this.orders.length - 1].index + 10;
                    }
                    return newIndex;
                },

                reorderOrdersInSameBox(e) {
                    // Don't try to modify this function, it's working fine
                    const index = this.orders.findIndex(order => order.id == e.detail.orderId);
                    const temp = this.orders[index];
                    temp.index = this.getNewIndex(e.detail.orderId, e.detail.upperSiblingId, e.detail.lowerSiblingId);
                    this.orders.splice(index, 1);
                    this.$nextTick(() => {
                        this.orders.push(temp);
                    });
                    
                    // backend
                    axios.put(`/orders/${temp.id}/changeIndex`, temp)
                        .then(response => {
                            console.log('success');
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                            window.location.reload();
                        });

                },

                setOrderOnPending(e) {
                    const index = this.orders.findIndex(order => order.id == e.detail.orderId);
                    this.orders[index].status_id = 1;
                    this.orders[index].technician_id = null;
                    this.orders[index].is_draggable = true;
                    this.orders[index].in_progress = false;
                    this.orders[index].order_color = this.statuses.find(status => status.id === 1).color;
                    this.orders[index].index = this.getNewIndex(e.detail.orderId, e.detail.upperSiblingId, e.detail.lowerSiblingId);

                    // backend
                    axios.put(`/orders/${this.orders[index].id}/setPending`, this.orders[index])
                        .then(response => {
                            console.log('success');
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                            window.location.reload();
                        });
                },

                setOrderOnHold(e) {
                    if(!confirm('{{ __('messages.are_you_sure_you_want_to_hold_this_order') }}')) {
                        // this code is to revert the order to its original position when the user clicks cancel
                        const index = this.orders.findIndex(order => order.id == e.detail.orderId);
                        const order = this.orders[index];
                        this.orders = this.orders.filter(order => order.id != e.detail.orderId);
                        this.$nextTick(() => {
                            this.orders.push(order);
                        });
                        return;
                    }

                    const index = this.orders.findIndex(order => order.id == e.detail.orderId);
                    this.orders[index].status_id = 5;
                    this.orders[index].technician_id = null;
                    this.orders[index].is_draggable = true;
                    this.orders[index].in_progress = false;
                    this.orders[index].order_color = this.statuses.find(status => status.id === 5).color;
                    if(e.detail.mode === 'fromHoldButton') {
                        // this will run if user clicks the hold button
                        // get min index of hold orders and subtract 10 from it
                        const minIndex = this.orders.filter(order => order.status_id === 5).reduce((min, order) => Math.min(min, order.index), 0);
                        this.orders[index].index = minIndex - 10;
                    }else{
                        // this will run if user drags the order to the hold box
                        this.orders[index].index = this.getNewIndex(e.detail.orderId, e.detail.upperSiblingId, e.detail.lowerSiblingId);
                    }

                    // backend
                    axios.put(`/orders/${this.orders[index].id}/setHold`, this.orders[index])
                        .then(response => {
                            console.log('success');
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                            window.location.reload();
                        });
                },

                setOrderOnCancel(e) {
                    const index = this.orders.findIndex(order => order.id == e.detail.orderId);
                    this.orders[index].status_id = 6;
                    this.orders[index].technician_id = null;
                    // this.orders.splice(index, 1);
                    // backend exist in cancel-reason-modal
                },

                setTechnician(e) {
                    const newTechnicianId = parseInt(e.detail.newTechnicianId);
                    const index = this.orders.findIndex(order => order.id == e.detail.orderId);
                    this.orders[index].status_id = 2;
                    this.orders[index].technician_id = newTechnicianId;
                    this.orders[index].is_draggable = true;
                    this.orders[index].order_color = this.statuses.find(status => status.id === 2).color;
                    if(e.detail.mode === 'fromDropdown') {
                        // this will run if user changes the technician from the dropdown
                        // get max index of the technician
                        const maxIndex = this.orders.filter(order => order.technician_id === newTechnicianId).reduce((max, order) => Math.max(max, order.index), 0);
                        this.orders[index].index = maxIndex + 10;
                    }else{
                        // this will run if user drags the order to the technician box
                        this.orders[index].index = this.getNewIndex(e.detail.orderId, e.detail.upperSiblingId, e.detail.lowerSiblingId);
                    }

                    // backend
                    axios.put(`/orders/${this.orders[index].id}/changeTechnician`, this.orders[index])
                        .then(response => {
                            console.log('success');
                        })
                        .catch(error => {
                            alert(error.response.data.error);
                            window.location.reload();
                        });
                },

                setAsFirstOrNextOrder(e) {

                    // get the index of the order
                    const index = this.orders.findIndex(order => order.id == e.detail.orderId);
                    const selectedOrder = this.orders[index];

                    // check if the selected order has a technician
                    if(!selectedOrder.technician_id) return;

                    // exclude any completed orders and store the result in filteredOrders
                    const filteredOrders = this.techniciansOrders(selectedOrder.technician_id).filter(order => order.status_id !== 4);

                    // get the first order of the technician
                    const firstOrder = filteredOrders.sort((a, b) => a.index - b.index)[0];

                    // check if there is first order
                    if(firstOrder) {
                        // check if it is received or arrived
                        if(firstOrder.status_id === 3 || firstOrder.status_id === 7) {
                            // get the second order
                            const secondOrder = filteredOrders.sort((a, b) => a.index - b.index)[1];
                            // check if there is second order
                            if(secondOrder) {
                                // check if the selected order is the second order
                                if(selectedOrder.id === secondOrder.id) {
                                    return;
                                }
                                // set the index to be between the first and second order
                                this.orders[index].index = (firstOrder.index + secondOrder.index) / 2;
                            }
                        }else{
                            // check if the selected order is the first order
                            if(selectedOrder.id === firstOrder.id) {
                                return;
                            }
                            // set the index to be before the first order
                            this.orders[index].index = firstOrder.index - 10;
                        }
                    }

                    // Update backend
                    axios.put(`/orders/${this.orders[index].id}/changeIndex`, this.orders[index])
                        .then(response => {
                            console.log('success');
                        });
                },

                openOrderModal(order) {
                    this.$dispatch('order-selected', {order: order});
                },

                removeNotification(id) {
                    this.notifications = this.notifications.filter(notification => notification.id !== id);
                },

                async getOrderResource(orderId) {
                    let response = await axios.get(`/orders/${orderId}`);
                    return response.data.data;
                },

                async getCommentResource(comment) {
                    let response = await axios.get(`/orders/${comment.order_id}/comments/${comment.id}`);
                    return response.data.data;
                },

                // listeners
                initListeners() {

                    var channel = Echo.channel('departments.' + this.selectedDepartmentId);

                    // OrderCreatedEvent
                    channel.listen('OrderCreatedEvent', async (data) => {
                        const orderResource = await this.getOrderResource(data.order.id);
                        // check if the order is already in the orders array
                        if(this.orders.find(order => order.id == orderResource.id)) {
                            return;
                        }
                        this.orders.push(orderResource);
                    });

                    // OrderUpdatedEvent
                    channel.listen('OrderUpdatedEvent', async (data) => {
                        // first get the order resource
                        const orderResource = await this.getOrderResource(data.order.id);
                        // then check if the order is visible on the screen
                        const index = this.orders.findIndex(order => order.id == orderResource.id);
                        if(index != -1) {
                            // that means the order is visible on the screen
                            // if data.order.department_id is not the same as the selected department id, then remove the order from the orders array
                            if(orderResource.department_id !== this.selectedDepartmentId) {
                                // that means that the updated order is moved to a different department
                                // so we need to remove the order from the orders array
                                this.orders.splice(index, 1);
                            }else{
                                // that means the updated order has been updated without changing the department
                                // so we need to update the order on the screen
                                this.orders[index] = orderResource;
                            }

                            // dispatch the updated order to all listeners components
                            this.$dispatch('order-updated', {order: orderResource});

                        }else{
                            // if all previous conditions are false, that means the order is not visible on the screen
                            // this case means that the updated order moved to this department
                            // check if the updated order is in the selected department
                            if(orderResource.department_id == this.selectedDepartmentId) {
                                // that means that the updated order is in the selected department
                                // so we need to add the order to the orders array
                                this.orders.push(orderResource);
                            }
                        }
                    });

                    // OrderCommentCreatedEvent
                    channel.listen('OrderCommentCreatedEvent', async (data) => {
                        const commentResource = await this.getCommentResource(data.comment);
                        const notification = {
                            id: 'comment-' + commentResource.id,
                            title: '{{ __('messages.new_comment_received') }}',
                            related_id: commentResource.order_id,
                            sender: commentResource.user.name,
                            body: commentResource.comment,
                            date: commentResource.date,
                            time: commentResource.time,
                            show: false,
                            onClick: () => {
                                const order = this.orders.find(order => order.id == commentResource.order_id);
                                this.openOrderModal(order);
                            }
                        };
                        this.notifications.push(notification);
                        this.$nextTick(() => {
                            this.notifications.find(n => n.id == notification.id).show = true;
                            // Wait for Vue to update the DOM after showing notification
                            setTimeout(() => {
                                this.$refs.notificationsContainer.scrollTo({
                                    top: this.$refs.notificationsContainer.scrollHeight,
                                    behavior: 'smooth'
                                });
                            }, 100); // Small delay to ensure notification is rendered
                        });
                    });
                },

            };
        }

    </script>
</x-app-layout>
