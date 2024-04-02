<div>
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true"></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="
        flex 
        flex-col 
        absolute 
        hidden-scrollbar
        z-40 
        {{ app()->getLocale() == 'ar' ? 'dark:border-l' : 'dark:border-r' }} 
        dark:border-gray-700 
        start-0 
        top-0 
        lg:static 
        lg:start-auto 
        lg:top-auto 
        lg:translate-x-0 
        lg:overflow-y-auto 
        h-screen 
        overflow-y-auto 
        no-scrollbar 
        w-64 
        shrink-0 
        bg-gray-800 
        p-3 
        transition-all 
        duration-200 
        ease-in-out"
        :class="sidebarOpen ? 'translate-x-0' : '{{ app()->getLocale() == 'ar' ? 'translate-x-64' : '-translate-x-64' }}'"
        @click.outside="sidebarOpen = false" @keydown.escape.window="sidebarOpen = false">

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pe-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-slate-500 hover:text-slate-400" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <x-application-mark class="w-6" />
        </div>

        <!-- Links -->
        <!-- Pages group -->
        <ul class="space-y-1">

            <!-- Dashboard -->
            @can('dashboard_menu', App\Models\DummyModel::class)
                <x-sidebar-item icon="chart-pie" route="dashboard" :title="__('messages.dashboard')" />
            @endcan

            {{-- Operations --}}
            @can('operations_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.operations') }}</h3>
            @endcan

            @can('viewAny', App\Models\Customer::class)
                <x-sidebar-item icon="users" route="customer.index" :title="__('messages.customers')" />
            @endcan

            @can('viewAny', App\Models\Order::class)
                <x-sidebar-item icon="list-bullet" route="order.index" :title="__('messages.orders')" />
            @endcan

            @can('viewAny', App\Models\Marketing::class)
                <x-sidebar-item icon="arrow-trending-up" route="marketing.index" :title="__('messages.marketing')" />
            @endcan

            @can('viewAny', App\Models\Rating::class)
                <x-sidebar-item icon="star" route="rating.index" :title="__('messages.ratings')" />
            @endcan

            @can('viewAny', App\Models\Invoice::class)
                <x-sidebar-item icon="circle-stack" route="invoice.index" :title="__('messages.invoices')" />
            @endcan

            @can('canDispatch', App\Models\DummyModel::class)

                <x-sidebar-dropdown title="{{ __('messages.dispatching') }}" icon="truck">
                    @foreach ($this->departments as $department)
                        <x-nested-sidebar-item route="dispatch-panel.index" :param="$department->id" :title="$department->name" />
                    @endforeach
                </x-sidebar-dropdown>












                {{-- <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if (in_array(Route::current()->getName(), ['dispatch-panel.index'])) {{ 'bg-slate-900' }} @endif"
                    x-data="{ open: {{ in_array(Route::current()->getName(), ['dispatch-panel.index']) ? 1 : 0 }} }">
                    <a class="block text-slate-400 hover:text-white truncate transition duration-150 @if (in_array(Route::current()->getName(), ['dispatch-panel.index'])) {{ 'hover:text-slate-200' }} @endif"
                        href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined">airport_shuttle</span>
                                <span class="text-sm font-medium ms-3 duration-200">{{ __('messages.dispatching') }}</span>
                            </div>
                            <x-svgs.chevron />
                        </div>
                    </a>
                    <ul class="ps-9 mt-1 @if (!in_array(Route::current()->getName(), ['dispatch-panel.index'])) {{ 'hidden' }} @endif"
                        :class="open ? '!block' : 'hidden'">
                        @foreach ($this->departments as $department)
                            <x-nested-sidebar-item route="dispatch-panel.index" :param="$department->id" :title="$department->name" />
                        @endforeach

                    </ul>
                </li> --}}
            @endcan

            @can('operations_reports')
                <li class="px-3 py-2 rounded-lg mb-0.5 last:mb-0 @if (in_array(Route::current()->getName(), ['roles.index', 'permissions.index', 'users.index'])) {{ 'bg-slate-900' }} @endif"
                    x-data="{ open: {{ in_array(Route::current()->getName(), ['roles.index', 'permissions.index', 'users.index']) ? 1 : 0 }} }">
                    <a class="block text-slate-400 hover:text-white truncate transition duration-150 @if (in_array(Route::current()->getName(), ['roles.index', 'permissions.index', 'users.index'])) {{ 'hover:text-slate-200' }} @endif"
                        href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <x-svgs.settings />
                                <span class="text-sm font-medium ms-3 duration-200">{{ __('messages.reports') }}</span>
                            </div>
                            <x-svgs.chevron />
                        </div>
                    </a>
                    <ul class="ps-9 mt-1 @if (!in_array(Route::current()->getName(), ['roles.index', 'permissions.index', 'users.index'])) {{ 'hidden' }} @endif"
                        :class="open ? '!block' : 'hidden'">
                        <x-nested-sidebar-item route="dashboard" :title="__('messages.account_statement')" />
                        <x-nested-sidebar-item route="dashboard" :title="__('messages.balance_sheet')" />
                        <x-nested-sidebar-item route="dashboard" :title="__('messages.trial_balance')" />
                        <x-nested-sidebar-item route="dashboard" :title="__('messages.profit_loss')" />
                    </ul>
                </li>
            @endcan


            {{-- Cashier --}}
            @can('cashier_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.cashier') }}</h3>
            @endcan

            @can('cash_collection_menu', App\Models\DummyModel::class)
                <x-sidebar-item icon="banknotes" route="cash_collection" :title="__('messages.cash_collection')" />
            @endcan

            @can('knet_collection_menu', App\Models\DummyModel::class)
                <x-sidebar-item icon="credit-card" route="knet_collection" :title="__('messages.knet_collection')" />
            @endcan

            @can('viewAny', App\Models\PartInvoice::class)
                <x-sidebar-item icon="document-text" route="part_invoice" :title="__('messages.part_invoices')" />
            @endcan


            {{-- Accounting --}}
            @can('accounting_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.accounting') }}</h3>
            @endcan

            @can('viewAny', App\Models\Account::class)
                <x-sidebar-item icon="bars-3-bottom-left" route="account.index" :title="__('messages.accounts')" />
            @endcan

            @can('viewAny', App\Models\Voucher::class)
                <x-sidebar-item icon="document-text" route="voucher.index" :title="__('messages.journal_vouchers')" />
            @endcan

            @can('accounting_reports', App\Models\DummyModel::class)
                <x-sidebar-dropdown title="{{ __('messages.reports') }}" icon="chart-bar">
                    @can('account_statement_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="account_statement" :title="__('messages.account_statement')" />
                    @endcan
                    @can('balance_sheet_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="balance_sheet" :title="__('messages.balance_sheet')" />
                    @endcan
                    @can('trial_balance_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="trial_balance" :title="__('messages.trial_balance')" />
                    @endcan
                    @can('profit_loss_report', App\Models\DummyModel::class)
                        <x-nested-sidebar-item route="profit_loss" :title="__('messages.profit_loss')" />
                    @endcan
                </x-sidebar-dropdown>
            @endcan


            {{-- HR --}}

            @can('hr_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.hr') }}</h3>
            @endcan

            @can('viewAny', App\Models\Employee::class)
                <x-sidebar-item icon="users" route="employee.index" :title="__('messages.employees')" />
            @endcan

            @can('hr_reports')
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
            @endcan


            {{-- Assets --}}
            @can('assets_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.assets') }}</h3>
            @endcan


            @can('viewAny', App\Models\Car::class)
                <x-sidebar-item icon="truck" route="car.index" :title="__('messages.cars')" />
            @endcan

            @can('assets_reports')
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
                {{--  --}}
            @endcan


            {{-- Admin --}}
            @can('admin_title', App\Models\DummyModel::class)
                <h3 class=" py-3 text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.admin') }}</h3>
            @endcan

            @can('viewAny', App\Models\Supplier::class)
                <x-sidebar-item icon="chart-pie" route="supplier.index" :title="__('messages.suppliers')" />
            @endcan

            @can('viewAny', App\Models\CostCenter::class)
                <x-sidebar-item icon="chart-pie" route="cost_center.index" :title="__('messages.cost_centers')" />
            @endcan

            @can('viewAny', App\Models\Role::class)
                <x-sidebar-item icon="chart-pie" route="role.index" :title="__('messages.roles')" />
            @endcan

            @can('viewAny', App\Models\User::class)
                <x-sidebar-item icon="users" route="user.index" :title="__('messages.users')" />
            @endcan

            @can('viewAny', App\Models\Title::class)
                <x-sidebar-item icon="chart-pie" route="title.index" :title="__('messages.titles')" />
            @endcan

            @can('viewAny', App\Models\Status::class)
                <x-sidebar-item icon="chart-pie" route="status.index" :title="__('messages.statuses')" />
            @endcan

            @can('viewAny', App\Models\Department::class)
                <x-sidebar-item icon="chart-pie" route="department.index" :title="__('messages.departments')" />
            @endcan

            @can('viewAny', App\Models\Company::class)
                <x-sidebar-item icon="chart-pie" route="company.index" :title="__('messages.companies')" />
            @endcan

            @can('viewAny', App\Models\Shift::class)
                <x-sidebar-item icon="chart-pie" route="shift.index" :title="__('messages.shifts')" />
            @endcan

            @can('viewAny', App\Models\Area::class)
                <x-sidebar-item icon="chart-pie" route="area.index" :title="__('messages.areas')" />
            @endcan

            @can('viewAny', App\Models\Service::class)
                <x-sidebar-item icon="chart-pie" route="service.index" :title="__('messages.services')" />
            @endcan

            @can('viewAny', App\Models\Setting::class)
                <x-sidebar-item icon="settings" route="settings.form" :title="__('messages.settings')" />
            @endcan

        </ul>
    </div>
</div>


@script
    {{-- this script to keep sidebar scroll position on change pages --}}
    <script>
        let sidebar = document.getElementById("sidebar");
        let top = localStorage.getItem("sidebar-scroll");
        if (top !== null) {
            sidebar.scrollTop = parseInt(top, 10);
        }

        window.addEventListener("beforeunload", () => {
            localStorage.setItem("sidebar-scroll", sidebar.scrollTop);
        });
    </script>
@endscript
